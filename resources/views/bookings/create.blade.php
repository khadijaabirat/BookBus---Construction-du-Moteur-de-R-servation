@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Steps -->
        <div class="mb-8">
            <div class="step-indicator max-w-3xl mx-auto">
                <div class="step-dot active"><i class="fa-solid fa-couch"></i></div>
                <div class="step-line"></div>
                <div class="step-dot"><i class="fa-solid fa-user"></i></div>
                <div class="step-line"></div>
                <div class="step-dot"><i class="fa-solid fa-credit-card"></i></div>
                <div class="step-line"></div>
                <div class="step-dot"><i class="fa-solid fa-check"></i></div>
            </div>
            <div class="flex justify-between max-w-3xl mx-auto mt-2 text-xs font-medium text-gray-500">
                <span class="text-satas-red">Sièges & Options</span>
                <span>Passager</span>
                <span>Paiement</span>
                <span>Confirmation</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Form -->
            <div class="lg:col-span-2">
                <form action="{{ route('reservation.store', $segment->id) }}" method="POST" id="booking-form">
                    @csrf
                    
                    <!-- Section 1: Seat Selection -->
                    <div class="card p-8 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-sm">1</span>
                            Sélectionnez vos sièges
                        </h2>
                        
                        <div class="bg-gray-100 p-6 rounded-2xl flex flex-col md:flex-row gap-8 items-center justify-center">
                            
                            <!-- Legend -->
                            <div class="flex flex-col gap-3 text-sm text-gray-600 w-full md:w-auto">
                                <div class="flex items-center gap-2"><div class="w-6 h-6 rounded bg-[#E8EDF3] border border-transparent"></div> Disponible</div>
                                <div class="flex items-center gap-2"><div class="w-6 h-6 rounded bg-satas-red border border-satas-red-dark"></div> Sélectionné</div>
                                <div class="flex items-center gap-2"><div class="w-6 h-6 rounded bg-[#FEE2E2]"></div> Occupé</div>
                            </div>
                            
                            <!-- Bus Map -->
                            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm relative">
                                <div class="w-full flex justify-end mb-4">
                                    <div class="w-10 h-10 seat-driver flex items-center justify-center"><i class="fa-solid fa-steering-wheel"></i></div>
                                </div>
                                
                                <div class="seat-grid">
                                    @for($i = 1; $i <= $segment->bus->capacite; $i++)
                                        @php $isTaken = in_array($i, $reservedSeats); @endphp
                                        
                                        <div class="relative">
                                            <input type="checkbox" name="seats[]" value="{{ $i }}" id="seat-{{ $i }}" class="peer hidden seat-checkbox" {{ $isTaken ? 'disabled' : '' }}>
                                            <label for="seat-{{ $i }}" class="seat {{ $isTaken ? 'seat-taken' : 'peer-checked:seat-selected' }}">
                                                {{ $i }}
                                            </label>
                                        </div>
                                        
                                        @if($i % 4 == 2)
                                            <div class="seat-aisle"></div> <!-- Aisle -->
                                        @endif
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Options -->
                    <div class="card p-8 mb-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-sm">2</span>
                            Options de voyage
                        </h2>
                        
                        <div class="space-y-4">
                            <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors group">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="checkbox" name="snack_box" value="1" class="w-5 h-5 text-satas-red rounded border-gray-300 focus:ring-satas-red option-checkbox" data-price="15">
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <span class="font-bold text-gray-900">Snack-box SATAS</span>
                                        <span class="font-bold text-satas-red">+15 MAD</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Une boîte collation comprenant un sandwich, une bouteille d'eau et un dessert.</p>
                                </div>
                            </label>
                            
                            <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors group">
                                <div class="flex-shrink-0 mt-1">
                                    <input type="checkbox" name="insurance" value="1" class="w-5 h-5 text-satas-red rounded border-gray-300 focus:ring-satas-red option-checkbox" data-price="20">
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between">
                                        <span class="font-bold text-gray-900">Assurance Annulation</span>
                                        <span class="font-bold text-satas-red">+20 MAD / s.</span>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">Remboursement garanti à 80% même en cas d'annulation moins de 24h avant le départ.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Right Column: Summary -->
            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-24 border-t-4 border-t-satas-red">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Détails du Trajet</h3>
                    
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="flex flex-col text-center">
                            <span class="text-lg font-bold">{{ \Carbon\Carbon::parse($segment->programme->heure_depart)->format('H:i') }}</span>
                            <span class="text-xs font-semibold text-gray-500 uppercase">{{ $segment->depart->gare->ville->name }}</span>
                        </div>
                        <div class="flex-1 flex items-center justify-center text-gray-300">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                        <div class="flex flex-col text-center">
                            <span class="text-lg font-bold">{{ \Carbon\Carbon::parse($segment->programme->heure_arrivee)->format('H:i') }}</span>
                            <span class="text-xs font-semibold text-gray-500 uppercase">{{ $segment->arrivee->gare->ville->name }}</span>
                        </div>
                    </div>
                    
                    <div class="py-4 border-b border-gray-100 space-y-2 text-sm text-gray-600">
                        <div class="flex justify-between">
                            <span><i class="fa-solid fa-calendar w-5 text-center text-gray-400"></i> Date</span>
                            <span class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($segment->programme->jour_depart)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span><i class="fa-solid fa-bus w-5 text-center text-gray-400"></i> Bus</span>
                            <span class="font-medium text-gray-900">{{ $segment->bus->type }} ({{ $segment->bus->matricule }})</span>
                        </div>
                        <div class="flex justify-between">
                            <span><i class="fa-solid fa-couch w-5 text-center text-gray-400"></i> Sièges sélectionnés</span>
                            <span class="font-bold text-satas-navy" id="selected-seats-count">0</span>
                        </div>
                    </div>
                    
                    @php
                        $basePrice = $segment->bus->type === 'premium' ? $segment->tarif * 1.2 : $segment->tarif;
                    @endphp
                    
                    <div class="py-4 border-b border-gray-100 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Prix unitaire</span>
                            <span>{{ number_format($basePrice, 2) }} MAD</span>
                        </div>
                        <div class="flex justify-between font-medium text-gray-800" id="base-total-row" style="display: none;">
                            <span>Sous-total (<span id="seat-multiplier">0</span>x)</span>
                            <span id="base-total">0.00 MAD</span>
                        </div>
                        <div class="flex justify-between text-gray-600" id="options-total-row" style="display: none;">
                            <span>Options</span>
                            <span id="options-total">+ 0.00 MAD</span>
                        </div>
                    </div>
                    
                    <!-- Promo Code -->
                    <div class="py-4 border-b border-gray-100">
                        <label class="text-xs font-semibold text-gray-500 block mb-2 uppercase">Code Promo</label>
                        <div class="flex gap-2">
                            <input type="text" form="booking-form" name="promo_code" class="form-input !py-2 text-sm flex-1 uppercase" placeholder="EX: SATAS10">
                        </div>
                    </div>
                    
                    <div class="pt-4 mb-6">
                        <div class="flex justify-between items-end">
                            <span class="text-gray-600 font-medium">Total à payer</span>
                            <div class="text-right">
                                <span class="text-3xl font-black text-satas-red block leading-none" id="grand-total">0.00</span>
                                <span class="text-xs font-semibold text-gray-500 uppercase">MAD</span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" form="booking-form" class="btn-primary w-full shadow-glow" id="submit-btn" disabled>
                        Confirmer et Payer <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>
                    
                    <p class="text-xs text-center text-gray-400 mt-4">
                        <i class="fa-solid fa-lock mr-1"></i> Paiement 100% sécurisé
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const basePrice = {{ $basePrice }};
        const checkboxes = document.querySelectorAll('.seat-checkbox');
        const optionCheckboxes = document.querySelectorAll('.option-checkbox');
        
        const countEl = document.getElementById('selected-seats-count');
        const baseTotalRow = document.getElementById('base-total-row');
        const multiplierEl = document.getElementById('seat-multiplier');
        const baseTotalEl = document.getElementById('base-total');
        
        const optionsTotalRow = document.getElementById('options-total-row');
        const optionsTotalEl = document.getElementById('options-total');
        
        const grandTotalEl = document.getElementById('grand-total');
        const submitBtn = document.getElementById('submit-btn');
        
        function updateSummary() {
            // Count seats
            let selectedCount = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) selectedCount++;
            });
            
            // Calculate base total
            const totalBase = selectedCount * basePrice;
            
            // Calculate options
            let totalOptions = 0;
            optionCheckboxes.forEach(cb => {
                if (cb.checked) {
                    // Snack is fixed, insurance is per seat
                    if (cb.name === 'snack_box') {
                        totalOptions += parseInt(cb.dataset.price); // per booking
                    } else if (cb.name === 'insurance') {
                        totalOptions += parseInt(cb.dataset.price) * selectedCount; // per seat
                    }
                }
            });
            
            // Update DOM
            countEl.textContent = selectedCount;
            
            if (selectedCount > 0) {
                baseTotalRow.style.display = 'flex';
                multiplierEl.textContent = selectedCount;
                baseTotalEl.textContent = totalBase.toFixed(2) + ' MAD';
                
                submitBtn.removeAttribute('disabled');
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                baseTotalRow.style.display = 'none';
                submitBtn.setAttribute('disabled', 'disabled');
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            
            if (totalOptions > 0 && selectedCount > 0) {
                optionsTotalRow.style.display = 'flex';
                optionsTotalEl.textContent = '+ ' + totalOptions.toFixed(2) + ' MAD';
            } else {
                optionsTotalRow.style.display = 'none';
            }
            
            const grandTotal = totalBase + (selectedCount > 0 ? totalOptions : 0);
            grandTotalEl.textContent = grandTotal.toFixed(2);
        }
        
        checkboxes.forEach(cb => cb.addEventListener('change', updateSummary));
        optionCheckboxes.forEach(cb => cb.addEventListener('change', updateSummary));
        
        // Initial state
        updateSummary();
    });
</script>
@endsection
