<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Billet SATAS - {{ $booking->reference }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .ticket { border: 2px solid #1e3a8a; padding: 20px; max-width: 600px; margin: 0 auto; }
        .header { background: #1e3a8a; color: white; padding: 15px; margin: -20px -20px 20px; }
        .reference { font-size: 24px; font-weight: bold; }
        .route { display: flex; justify-content: space-between; margin: 20px 0; }
        .city { font-size: 20px; font-weight: bold; }
        .time { font-size: 28px; font-weight: bold; color: #1e3a8a; }
        .details { background: #f3f4f6; padding: 15px; margin: 20px 0; }
        .detail-row { display: flex; justify-content: space-between; margin: 10px 0; }
        .label { color: #6b7280; }
        .value { font-weight: bold; }
        .footer { margin-top: 20px; padding-top: 20px; border-top: 2px dashed #d1d5db; text-align: center; color: #6b7280; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <div style="text-align: center;">
                <div style="font-size: 32px; font-weight: bold;">SATAS</div>
                <div style="margin-top: 10px;">BILLET DE VOYAGE</div>
            </div>
            <div style="margin-top: 15px;">
                <div class="reference">{{ $booking->reference }}</div>
            </div>
        </div>

        <div class="route">
            <div style="text-align: center; width: 40%;">
                <div class="time">{{ \Carbon\Carbon::parse($booking->segment->programme->heure_depart)->format('H:i') }}</div>
                <div class="city">{{ $booking->segment->depart->gare->ville->name }}</div>
                <div style="color: #6b7280; margin-top: 5px;">{{ $booking->segment->depart->gare->name }}</div>
            </div>
            <div style="text-align: center; width: 20%; padding-top: 20px;">
                <div style="color: #6b7280;">{{ \Carbon\Carbon::parse($booking->segment->programme->jour_depart)->format('d/m/Y') }}</div>
                <div style="margin: 10px 0;">→</div>
            </div>
            <div style="text-align: center; width: 40%;">
                <div class="time">{{ \Carbon\Carbon::parse($booking->segment->programme->heure_arrivee)->format('H:i') }}</div>
                <div class="city">{{ $booking->segment->arrivee->gare->ville->name }}</div>
                <div style="color: #6b7280; margin-top: 5px;">{{ $booking->segment->arrivee->gare->name }}</div>
            </div>
        </div>

        <div class="details">
            <div class="detail-row">
                <span class="label">Passager:</span>
                <span class="value">{{ $booking->user->name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Siège:</span>
                <span class="value" style="color: #dc2626; font-size: 18px;">{{ $booking->siege_numero }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Type de Bus:</span>
                <span class="value">{{ ucfirst($booking->segment->bus->type) }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Immatriculation:</span>
                <span class="value">{{ $booking->segment->bus->immatriculation }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Prix Total:</span>
                <span class="value">{{ number_format($booking->total_price, 2) }} MAD</span>
            </div>
            @if($booking->snack_box || $booking->insurance)
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #d1d5db;">
                @if($booking->snack_box)
                <div>✓ Snack-box inclus</div>
                @endif
                @if($booking->insurance)
                <div>✓ Assurance voyage incluse</div>
                @endif
            </div>
            @endif
        </div>

        <div class="footer">
            <p><strong>Merci de voyager avec SATAS</strong></p>
            <p style="font-size: 12px;">Veuillez présenter ce billet à l'embarquement</p>
            <p style="font-size: 12px;">Réservé le {{ \Carbon\Carbon::parse($booking->date_reservation)->format('d/m/Y à H:i') }}</p>
        </div>
    </div>
</body>
</html>
