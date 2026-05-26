<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Reservation;
use App\Models\PromoCode;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class ReservationController extends Controller
{
    public function index()
    {
        $bookings = Reservation::with(['segment.depart.gare.ville', 'segment.arrivee.gare.ville', 'segment.programme'])
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = Reservation::with(['segment.depart.gare.ville', 'segment.arrivee.gare.ville', 'segment.programme', 'segment.bus'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);
            
        return view('bookings.confirmation', ['reservations' => collect([$booking])]);
    }

    public function create($segment_id)
    {
        $segment = Segment::with(['depart.gare.ville', 'arrivee.gare.ville', 'programme.route', 'bus'])
            ->findOrFail($segment_id);

        $reservedSeats = Reservation::where('segment_id', $segment_id)
            ->where('statut', '!=', 'Annulé')
            ->pluck('siege_numero')
            ->toArray();

        return view('bookings.create', compact('segment', 'reservedSeats'));
    }

    public function store(Request $request, $segment_id)
    {
        $request->validate([
            'seats' => 'required|array|min:1',
            'seats.*' => 'integer|min:1',
            'snack_box' => 'nullable|boolean',
            'insurance' => 'nullable|boolean',
            'promo_code' => 'nullable|string',
            'payment_method' => 'required|in:card,cash,virement',
            'payment_proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $segment = Segment::with('bus')->findOrFail($segment_id);
        
         $isPremium = $segment->bus->type === 'premium';
        $basePricePerSeat = $isPremium ? $segment->tarif * 1.2 : $segment->tarif;
        
        $snackBoxPrice = $request->boolean('snack_box') ? 15 : 0;
        $insurancePrice = $request->boolean('insurance') ? 20 : 0;
        $extrasPerSeat = $snackBoxPrice + $insurancePrice;
        
        $totalSeats = count($request->seats);
        $totalBasePrice = $basePricePerSeat * $totalSeats;
        $totalExtrasPrice = $extrasPerSeat * $totalSeats;
        $grandTotal = $totalBasePrice + $totalExtrasPrice;

        // Apply Promo Code
        $appliedPromo = null;
        if ($request->filled('promo_code')) {
            $promo = PromoCode::where('code', strtoupper($request->promo_code))
                ->where('is_active', true)
                ->where('valid_from', '<=', now())
                ->where('valid_until', '>=', now())
                ->whereColumn('used_count', '<', 'max_uses')
                ->first();
                
            if ($promo) {
                $discountAmount = ($grandTotal * $promo->discount_percent) / 100;
                $grandTotal -= $discountAmount;
                $appliedPromo = $promo->code;
                
                $promo->increment('used_count');
            }
        }

        $newReservationIds = [];
        $paymentMethod = $request->input('payment_method', 'card');
        $statut = in_array($paymentMethod, ['cash', 'virement']) ? 'En attente' : 'Confirmé';

        // Handle payment proof upload
        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payment_proofs', 'public');
        }

        foreach ($request->seats as $seatNumber) {
            // Check availability
            $isTaken = Reservation::where('segment_id', $segment_id)
                                ->where('statut', '!=', 'Annulé')
                                ->where('siege_numero', $seatNumber)
                                ->exists();

            if ($isTaken) {
                return back()->with('error', "Le siège $seatNumber a déjà été réservé entre temps.");
            }

            // Create Reservation
            $res = Reservation::create([
                'reference' => 'SATAS-' . strtoupper(Str::random(6)),
                'date_reservation' => now(),
                'statut' => $statut,
                'siege_numero' => $seatNumber,
                'user_id' => auth()->id(),
                'segment_id' => $segment_id,
                'snack_box' => $request->boolean('snack_box'),
                'insurance' => $request->boolean('insurance'),
                'promo_code' => $appliedPromo,
                'base_price' => $basePricePerSeat,
                'extras_price' => $extrasPerSeat,
                'total_price' => $grandTotal / $totalSeats, // Distribute evenly
                'payment_method' => $paymentMethod,
                'payment_proof'  => $proofPath,
            ]);
            
            $newReservationIds[] = $res->id;
        }

        return redirect()->route('reservation.success', ['ids' => implode(',', $newReservationIds)])
                         ->with('success', 'Réservation effectuée avec succès !');
    }

    public function success(Request $request)
    {
        if (!$request->has('ids')) {
            return redirect()->route('bookings.index');
        }

        $ids = explode(',', $request->query('ids'));
        
        $reservations = Reservation::with([
            'segment.depart.gare.ville', 
            'segment.arrivee.gare.ville', 
            'segment.programme',
            'segment.bus'
        ])->where('user_id', auth()->id())
          ->whereIn('id', $ids)
          ->get();

        if ($reservations->isEmpty()) {
            return redirect()->route('search.index');
        }

        return view('bookings.confirmation', compact('reservations'));
    }

    public function cancel(Request $request, $id)
    {
        $reservation = Reservation::where('user_id', auth()->id())->findOrFail($id);
        
        if ($reservation->statut === 'Annulé') {
            return back()->with('error', 'Cette réservation est déjà annulée.');
        }

        $jourDepart = $reservation->segment->programme->jour_depart;
        $heureDepart = $reservation->segment->programme->heure_depart;
        
        if (strpos($jourDepart, ' ') !== false) {
            $departureTime = \Carbon\Carbon::parse($jourDepart);
        } else {
            $departureTime = \Carbon\Carbon::parse($jourDepart . ' ' . $heureDepart);
        }
        
        if (now()->isAfter($departureTime)) {
            return back()->with('error', 'Impossible d\'annuler un trajet passé.');
        }

        $hoursUntilDeparture = now()->diffInHours($departureTime);
        $refundPercentage = 0;

        if ($reservation->insurance) {
            $refundPercentage = 80;
        } elseif ($hoursUntilDeparture > 24) {
            $refundPercentage = 100; // Full refund without insurance if > 24h (custom logic)
        } else {
            $refundPercentage = 50; // 50% refund if < 24h
        }

        $refundAmount = ($reservation->total_price * $refundPercentage) / 100;

        $reservation->update([
            'statut' => 'Annulé',
            'cancelled_at' => now(),
            'refund_amount' => $refundAmount
        ]);

        return back()->with('success', "Réservation annulée. Vous serez remboursé de $refundAmount MAD ($refundPercentage%).");
    }

    public function verifyPromo(Request $request)
    {
        $promo = PromoCode::where('code', strtoupper($request->code))
            ->where('is_active', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now())
            ->whereColumn('used_count', '<', 'max_uses')
            ->first();

        if ($promo) {
            return response()->json(['valid' => true, 'discount_percent' => $promo->discount_percent]);
        }

        return response()->json(['valid' => false, 'message' => 'Code invalide ou expiré.']);
    }

    public function downloadTicket($id)
    {
        $booking = Reservation::with(['segment.depart.gare.ville', 'segment.arrivee.gare.ville', 'segment.programme', 'segment.bus', 'user'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $pdf = Pdf::loadView('bookings.ticket-pdf', compact('booking'));
        return $pdf->download('ticket-' . $booking->reference . '.pdf');
    }
}