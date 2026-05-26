@extends('layouts.app')

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Steps Indicator --}}
        <div class="mb-8">
            <div class="flex items-center justify-center max-w-2xl mx-auto">
                @foreach([1=>'Sièges & Options', 2=>'Passager', 3=>'Paiement', 4=>'Confirmation'] as $n => $label)
                <div class="flex items-center {{ $n < 4 ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300
                            {{ $n === 1 ? 'bg-red-600 border-red-600 text-white' : 'bg-white border-gray-300 text-gray-400' }}"
                            id="step-circle-{{ $n }}">{{ $n }}</div>
                        <span class="text-xs mt-1 font-medium whitespace-nowrap {{ $n === 1 ? 'text-red-600' : 'text-gray-400' }}"
                            id="step-label-{{ $n }}">{{ $label }}</span>
                    </div>
                    @if($n < 4)
                    <div class="flex-1 h-0.5 bg-gray-200 mx-2 mb-4" id="step-line-{{ $n }}"></div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <form action="{{ route('reservation.store', $segment->id) }}" method="POST" id="booking-form" enctype="multipart/form-data">
                    @csrf

                    {{-- STEP 1 --}}
                    <div id="step-1">
                        <div class="card p-8 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold">1</span>
                                Sélectionnez vos sièges
                            </h2>
                            <div class="bg-gray-100 p-6 rounded-2xl flex flex-col md:flex-row gap-8 items-center justify-center">
                                <div class="flex flex-col gap-3 text-sm text-gray-600">
                                    <div class="flex items-center gap-2"><div class="w-6 h-6 rounded bg-[#E8EDF3] border border-gray-300"></div> Disponible</div>
                                    <div class="flex items-center gap-2"><div class="w-6 h-6 rounded bg-red-600"></div> Sélectionné</div>
                                    <div class="flex items-center gap-2"><div class="w-6 h-6 rounded bg-red-100"></div> Occupé</div>
                                </div>
                                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                                    <div class="w-full flex justify-end mb-4">
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                            <i class="fa-solid fa-steering-wheel"></i>
                                        </div>
                                    </div>
                                    <div class="seat-grid">
                                        @for($i = 1; $i <= $segment->bus->capacite; $i++)
                                            @php $isTaken = in_array($i, $reservedSeats); @endphp
                                            <div class="relative">
                                                <input type="checkbox" name="seats[]" value="{{ $i }}" id="seat-{{ $i }}"
                                                    class="peer hidden seat-checkbox" {{ $isTaken ? 'disabled' : '' }}>
                                                <label for="seat-{{ $i }}" class="seat {{ $isTaken ? 'seat-taken' : 'peer-checked:seat-selected' }}">
                                                    {{ $i }}
                                                </label>
                                            </div>
                                            @if($i % 4 == 2)
                                                <div class="seat-aisle"></div>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card p-8 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold">2</span>
                                Options de voyage
                            </h2>
                            <div class="space-y-4">
                                <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="snack_box" value="1" class="w-5 h-5 mt-1 text-red-600 rounded option-checkbox" data-price="15">
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <span class="font-bold text-gray-900">Snack-box SATAS</span>
                                            <span class="font-bold text-red-600">+15 MAD</span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Sandwich, eau et dessert inclus.</p>
                                    </div>
                                </label>
                                <label class="flex items-start gap-4 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="insurance" value="1" class="w-5 h-5 mt-1 text-red-600 rounded option-checkbox" data-price="20">
                                    <div class="flex-1">
                                        <div class="flex justify-between">
                                            <span class="font-bold text-gray-900">Assurance Annulation</span>
                                            <span class="font-bold text-red-600">+20 MAD / siège</span>
                                        </div>
                                        <p class="text-sm text-gray-500 mt-1">Remboursement 80% garanti même moins de 24h avant départ.</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" onclick="goToStep(2)" id="btn-step1"
                                class="btn-primary px-8 py-3 opacity-50 cursor-not-allowed" disabled>
                                Suivant : Passager <i class="fa-solid fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    {{-- STEP 2 --}}
                    <div id="step-2" class="hidden">
                        <div class="card p-8 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold">2</span>
                                Informations Passager
                            </h2>
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6 text-sm text-blue-700">
                                <i class="fa-solid fa-circle-info mr-2"></i>
                                Informations pré-remplies depuis votre compte.
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                                    <input type="text" value="{{ auth()->user()->name }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm bg-gray-50" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="text" value="{{ auth()->user()->email }}" class="w-full border border-gray-200 rounded-lg px-4 py-3 text-sm bg-gray-50" readonly>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Code Promo (optionnel)</label>
                                <div class="flex gap-2">
                                    <input type="text" name="promo_code" id="promo_code_input"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm uppercase"
                                        placeholder="Ex: SATAS10 ou VACANCES2026">
                                    <button type="button" onclick="verifyPromo()"
                                        class="bg-gray-800 hover:bg-gray-900 text-white px-4 py-3 rounded-lg text-sm font-medium whitespace-nowrap">
                                        Appliquer
                                    </button>
                                </div>
                                <div id="promo-result" class="mt-2 hidden"></div>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(1)" class="btn-outline px-8 py-3">
                                <i class="fa-solid fa-arrow-left mr-2"></i> Retour
                            </button>
                            <button type="button" onclick="goToStep(3)" class="btn-primary px-8 py-3">
                                Suivant : Paiement <i class="fa-solid fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    {{-- STEP 3 --}}
                    <div id="step-3" class="hidden">
                        <div class="card p-8 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-sm font-bold">3</span>
                                Paiement
                            </h2>
                            <div class="mb-6">
                                <label class="block text-sm font-semibold text-gray-700 mb-3">Mode de paiement</label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label>
                                        <input type="radio" name="payment_method" value="card" class="hidden payment-radio" checked>
                                        <div onclick="selectPayment('card')" id="pay-opt-card"
                                            class="border-2 border-red-500 bg-red-50 rounded-xl p-4 text-center cursor-pointer transition-all">
                                            <i class="fa-solid fa-credit-card text-2xl text-red-600 mb-2 block"></i>
                                            <span class="text-xs font-semibold text-gray-700">Carte</span>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="payment_method" value="cash" class="hidden payment-radio">
                                        <div onclick="selectPayment('cash')" id="pay-opt-cash"
                                            class="border-2 border-gray-200 rounded-xl p-4 text-center cursor-pointer transition-all">
                                            <i class="fa-solid fa-money-bill text-2xl text-gray-400 mb-2 block"></i>
                                            <span class="text-xs font-semibold text-gray-700">Espèces</span>
                                        </div>
                                    </label>
                                    <label>
                                        <input type="radio" name="payment_method" value="virement" class="hidden payment-radio">
                                        <div onclick="selectPayment('virement')" id="pay-opt-virement"
                                            class="border-2 border-gray-200 rounded-xl p-4 text-center cursor-pointer transition-all">
                                            <i class="fa-solid fa-building-columns text-2xl text-gray-400 mb-2 block"></i>
                                            <span class="text-xs font-semibold text-gray-700">Virement</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div id="pay-card">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Numéro de carte</label>
                                    <div class="relative">
                                        <input type="text" placeholder="1234 5678 9012 3456" maxlength="19"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm pr-12"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'').replace(/(\d{4})(?=\d)/g,'$1 ')">
                                        <i class="fa-brands fa-cc-visa absolute right-4 top-1/2 -translate-y-1/2 text-blue-700 text-2xl"></i>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Date d'expiration</label>
                                        <input type="text" placeholder="MM/AA" maxlength="5"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'').replace(/(\d{2})(\d)/,'$1/$2')">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                        <input type="text" placeholder="123" maxlength="3"
                                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom sur la carte</label>
                                    <input type="text" placeholder="NOM PRÉNOM" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm uppercase">
                                </div>
                            </div>

                            <div id="pay-cash" class="hidden">
                                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-800 mb-4">
                                    <i class="fa-solid fa-circle-info mr-2"></i>
                                    Le paiement en espèces s'effectue à la gare SATAS. Uploadez votre reçu après paiement.
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Justificatif de paiement <span class="text-gray-400">(optionnel)</span></label>
                                    <input type="file" name="payment_proof" accept="image/*,.pdf"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm">
                                    <p class="text-xs text-gray-400 mt-1">Photo du reçu ou bon de caisse. Max 2MB.</p>
                                </div>
                            </div>

                            <div id="pay-virement" class="hidden">
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800 mb-4">
                                    <i class="fa-solid fa-circle-info mr-2"></i>
                                    RIB: <strong>007 780 0001234567890123 45</strong><br>
                                    Banque: Attijariwafa Bank — SATAS Bus Maroc
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Justificatif de virement <span class="text-red-500">*</span></label>
                                    <input type="file" name="payment_proof" accept="image/*,.pdf"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm">
                                    <p class="text-xs text-gray-400 mt-1">Capture d'écran ou PDF du virement bancaire. Max 2MB.</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(2)" class="btn-outline px-8 py-3">
                                <i class="fa-solid fa-arrow-left mr-2"></i> Retour
                            </button>
                            <button type="button" onclick="goToStep(4)" class="btn-primary px-8 py-3">
                                Suivant : Confirmation <i class="fa-solid fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    {{-- STEP 4 --}}
                    <div id="step-4" class="hidden">
                        <div class="card p-8 mb-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </span>
                                Récapitulatif
                            </h2>
                            <div class="bg-gray-50 rounded-xl p-6 space-y-3 text-sm mb-6">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Trajet</span>
                                    <span class="font-semibold">{{ $segment->depart->gare->ville->name }} → {{ $segment->arrivee->gare->ville->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Date</span>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($segment->programme->jour_depart)->format('d/m/Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Départ</span>
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($segment->programme->heure_depart)->format('H:i') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Bus</span>
                                    <span class="font-semibold">{{ ucfirst($segment->bus->type) }} — {{ $segment->bus->matricule }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Siège(s)</span>
                                    <span class="font-semibold text-red-600" id="recap-seats">—</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Options</span>
                                    <span class="font-semibold" id="recap-options">Aucune</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Paiement</span>
                                    <span class="font-semibold" id="recap-payment">Carte bancaire</span>
                                </div>
                                <div class="border-t border-gray-200 pt-3 flex justify-between">
                                    <span class="font-bold text-gray-800">Total</span>
                                    <span class="font-black text-red-600 text-lg" id="recap-total">0.00 MAD</span>
                                </div>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-sm text-green-700">
                                <i class="fa-solid fa-shield-halved mr-2"></i>
                                En confirmant, votre réservation sera définitivement enregistrée.
                            </div>
                        </div>
                        <div class="flex justify-between">
                            <button type="button" onclick="goToStep(3)" class="btn-outline px-8 py-3">
                                <i class="fa-solid fa-arrow-left mr-2"></i> Retour
                            </button>
                            <button type="submit" class="btn-primary px-8 py-3 shadow-lg">
                                <i class="fa-solid fa-lock mr-2"></i> Confirmer et Payer
                            </button>
                        </div>
                    </div>

                </form>
            </div>

            {{-- Summary --}}
            <div class="lg:col-span-1">
                <div class="card p-6 sticky top-24 border-t-4 border-red-600">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Résumé</h3>
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                        <div class="text-center">
                            <div class="text-xl font-black">{{ \Carbon\Carbon::parse($segment->programme->heure_depart)->format('H:i') }}</div>
                            <div class="text-xs text-gray-500 uppercase font-semibold">{{ $segment->depart->gare->ville->name }}</div>
                        </div>
                        <i class="fa-solid fa-arrow-right text-gray-300 text-xl"></i>
                        <div class="text-center">
                            <div class="text-xl font-black">{{ \Carbon\Carbon::parse($segment->programme->heure_arrivee)->format('H:i') }}</div>
                            <div class="text-xs text-gray-500 uppercase font-semibold">{{ $segment->arrivee->gare->ville->name }}</div>
                        </div>
                    </div>

                    @php $basePrice = $segment->bus->type === 'premium' ? $segment->tarif * 1.2 : $segment->tarif; @endphp

                    <div class="space-y-2 text-sm mb-4">
                        <div class="flex justify-between text-gray-600">
                            <span>{{ \Carbon\Carbon::parse($segment->programme->jour_depart)->format('d/m/Y') }}</span>
                            <span class="font-medium">{{ ucfirst($segment->bus->type) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Prix / siège</span>
                            <span class="font-semibold">{{ number_format($basePrice, 2) }} MAD</span>
                        </div>
                        <div class="flex justify-between text-gray-600" id="summary-seats-row" style="display:none">
                            <span>Sièges (<span id="summary-count">0</span>x)</span>
                            <span id="summary-base">0.00 MAD</span>
                        </div>
                        <div class="flex justify-between text-gray-600" id="summary-options-row" style="display:none">
                            <span>Options</span>
                            <span id="summary-options">+0.00 MAD</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-gray-700">Total</span>
                            <span class="text-2xl font-black text-red-600" id="grand-total">0.00 MAD</span>
                        </div>
                    </div>

                    <p class="text-xs text-center text-gray-400 mt-4">
                        <i class="fa-solid fa-lock mr-1"></i> Paiement 100% sécurisé
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const basePrice = {{ $basePrice }};

function goToStep(step) {
    if (step > 1) {
        const selected = document.querySelectorAll('.seat-checkbox:checked').length;
        if (selected === 0) {
            alert('Veuillez sélectionner au moins un siège.');
            return;
        }
    }

    [1,2,3,4].forEach(n => document.getElementById('step-' + n).classList.add('hidden'));
    document.getElementById('step-' + step).classList.remove('hidden');

    [1,2,3,4].forEach(n => {
        const circle = document.getElementById('step-circle-' + n);
        const label  = document.getElementById('step-label-' + n);
        const line   = document.getElementById('step-line-' + n);

        if (n < step) {
            circle.className = 'w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 bg-green-500 border-green-500 text-white';
            circle.innerHTML = '<i class="fa-solid fa-check text-xs"></i>';
            label.className  = 'text-xs mt-1 font-medium text-green-600 whitespace-nowrap';
            if (line) line.className = 'flex-1 h-0.5 bg-green-400 mx-2 mb-4';
        } else if (n === step) {
            circle.className = 'w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 bg-red-600 border-red-600 text-white';
            circle.innerHTML = n;
            label.className  = 'text-xs mt-1 font-medium text-red-600 whitespace-nowrap';
        } else {
            circle.className = 'w-10 h-10 rounded-full flex items-center justify-center text-sm font-bold border-2 bg-white border-gray-300 text-gray-400';
            circle.innerHTML = n;
            label.className  = 'text-xs mt-1 font-medium text-gray-400 whitespace-nowrap';
            if (line) line.className = 'flex-1 h-0.5 bg-gray-200 mx-2 mb-4';
        }
    });

    if (step === 4) updateRecap();
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function selectPayment(val) {
    document.querySelectorAll('.payment-radio').forEach(r => r.checked = r.value === val);
    ['card','cash','virement'].forEach(v => {
        const opt    = document.getElementById('pay-opt-' + v);
        const detail = document.getElementById('pay-' + v);
        if (v === val) {
            opt.className = 'border-2 border-red-500 bg-red-50 rounded-xl p-4 text-center cursor-pointer transition-all';
            opt.querySelector('i').classList.replace('text-gray-400','text-red-600');
            detail.classList.remove('hidden');
        } else {
            opt.className = 'border-2 border-gray-200 rounded-xl p-4 text-center cursor-pointer transition-all';
            if (opt.querySelector('i').classList.contains('text-red-600'))
                opt.querySelector('i').classList.replace('text-red-600','text-gray-400');
            detail.classList.add('hidden');
        }
    });
}

function updateSummary() {
    const count   = document.querySelectorAll('.seat-checkbox:checked').length;
    let options   = 0;

    document.querySelectorAll('.option-checkbox').forEach(cb => {
        if (cb.checked)
            options += cb.name === 'snack_box' ? parseInt(cb.dataset.price) : parseInt(cb.dataset.price) * count;
    });

    const base     = count * basePrice;
    const subtotal = base + options;
    const discount = (subtotal * promoDiscount) / 100;
    const total    = subtotal - discount;

    document.getElementById('grand-total').textContent = total.toFixed(2) + ' MAD';

    const seatsRow = document.getElementById('summary-seats-row');
    seatsRow.style.display = count > 0 ? 'flex' : 'none';
    document.getElementById('summary-count').textContent = count;
    document.getElementById('summary-base').textContent  = base.toFixed(2) + ' MAD';

    const optRow = document.getElementById('summary-options-row');
    optRow.style.display = options > 0 ? 'flex' : 'none';
    document.getElementById('summary-options').textContent = '+' + options.toFixed(2) + ' MAD';

    const btn = document.getElementById('btn-step1');
    if (count > 0) {
        btn.removeAttribute('disabled');
        btn.classList.remove('opacity-50','cursor-not-allowed');
    } else {
        btn.setAttribute('disabled','disabled');
        btn.classList.add('opacity-50','cursor-not-allowed');
    }
}

function updateRecap() {
    const seats = [...document.querySelectorAll('.seat-checkbox:checked')].map(c => c.value);
    document.getElementById('recap-seats').textContent = seats.join(', ');

    const opts = [];
    document.querySelectorAll('.option-checkbox:checked').forEach(cb => {
        opts.push(cb.name === 'snack_box' ? 'Snack-box (+15 MAD)' : 'Assurance (+20 MAD/siège)');
    });
    if (promoDiscount > 0) opts.push('Promo -' + promoDiscount + '%');
    document.getElementById('recap-options').textContent = opts.length ? opts.join(', ') : 'Aucune';

    const payVal = document.querySelector('.payment-radio:checked')?.value || 'card';
    document.getElementById('recap-payment').textContent = {card:'Carte bancaire', cash:'Espèces', virement:'Virement'}[payVal];
    document.getElementById('recap-total').textContent   = document.getElementById('grand-total').textContent;
}

let promoDiscount = 0;

function verifyPromo() {
    const code = document.getElementById('promo_code_input').value.trim().toUpperCase();
    const resultEl = document.getElementById('promo-result');

    if (!code) return;

    fetch('{{ route("promo.verify") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code })
    })
    .then(r => r.json())
    .then(data => {
        resultEl.classList.remove('hidden');
        if (data.valid) {
            promoDiscount = data.discount_percent;
            resultEl.innerHTML = `<div class="flex items-center gap-2 text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2 text-sm">
                <i class="fa-solid fa-check-circle"></i>
                Code valide ! Réduction de <strong>${data.discount_percent}%</strong> appliquée.
            </div>`;
            updateSummary();
        } else {
            promoDiscount = 0;
            resultEl.innerHTML = `<div class="flex items-center gap-2 text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2 text-sm">
                <i class="fa-solid fa-times-circle"></i>
                ${data.message}
            </div>`;
            updateSummary();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.seat-checkbox').forEach(cb => cb.addEventListener('change', updateSummary));
    document.querySelectorAll('.option-checkbox').forEach(cb => cb.addEventListener('change', updateSummary));
    updateSummary();
});
</script>
@endsection
