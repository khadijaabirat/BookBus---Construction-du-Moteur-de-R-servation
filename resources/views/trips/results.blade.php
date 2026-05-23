@extends('layouts.app')

@section('content')
<div class="bg-satas-navy py-6 text-white border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Compact Search Form -->
        <form action="{{ route('search.trip') }}" method="GET" class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1 w-full">
                <select name="departure_city" class="form-select w-full !py-2 !text-sm !text-gray-900" required>
                    @foreach($villes as $v)
                        <option value="{{ $v->id }}" {{ $departureCity->id == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="hidden md:flex items-center justify-center text-white/50 pb-2">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
            </div>

            <div class="flex-1 w-full">
                <select name="arrival_city" class="form-select w-full !py-2 !text-sm !text-gray-900" required>
                    @foreach($villes as $v)
                        <option value="{{ $v->id }}" {{ $arrivalCity->id == $v->id ? 'selected' : '' }}>{{ $v->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 w-full">
                <input type="date" name="travel_date" class="form-input w-full !py-2 !text-sm !text-gray-900" value="{{ $travelDate->format('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
            </div>

            <button type="submit" class="btn-primary !py-2 px-6 w-full md:w-auto shadow-none">
                Modifier
            </button>
        </form>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation mt-0.5"></i>
            <div>
                <p class="font-bold">Oops ! Il y a un problème :</p>
                <ul class="list-disc ml-5 mt-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- Filters Sidebar -->
        <div class="w-full lg:w-1/4">
            <div class="card p-6 sticky top-24">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900">Filtres</h3>
                    <a href="{{ request()->url() }}?departure_city={{ request('departure_city') }}&arrival_city={{ request('arrival_city') }}&travel_date={{ request('travel_date') }}" class="text-xs text-satas-red hover:underline">Réinitialiser</a>
                </div>
                
                <form action="{{ route('search.trip') }}" method="GET" id="filter-form">
                    <input type="hidden" name="departure_city" value="{{ $departureCity->id }}">
                    <input type="hidden" name="arrival_city" value="{{ $arrivalCity->id }}">
                    <input type="hidden" name="travel_date" value="{{ $travelDate->format('Y-m-d') }}">
                    
                    <div class="mb-6">
                        <label class="font-semibold text-sm text-gray-700 block mb-3">Trier par</label>
                        <select name="sort" class="form-select text-sm w-full" onchange="document.getElementById('filter-form').submit()">
                            <option value="time_asc" {{ request('sort') == 'time_asc' ? 'selected' : '' }}>Heure de départ (plus tôt)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Prix (moins cher)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Prix (plus cher)</option>
                        </select>
                    </div>
                    
                    <hr class="border-gray-100 my-4">
                    
                    <div class="mb-6">
                        <label class="font-semibold text-sm text-gray-700 block mb-3">Type de bus</label>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="bus_type[]" value="standard" class="rounded text-satas-red focus:ring-satas-red border-gray-300 w-4 h-4" onchange="document.getElementById('filter-form').submit()" {{ in_array('standard', (array)request('bus_type', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Standard</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="bus_type[]" value="confort" class="rounded text-satas-red focus:ring-satas-red border-gray-300 w-4 h-4" onchange="document.getElementById('filter-form').submit()" {{ in_array('confort', (array)request('bus_type', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Confort</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" name="bus_type[]" value="premium" class="rounded text-satas-red focus:ring-satas-red border-gray-300 w-4 h-4" onchange="document.getElementById('filter-form').submit()" {{ in_array('premium', (array)request('bus_type', [])) ? 'checked' : '' }}>
                                <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Premium (+20%)</span>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Results List -->
        <div class="w-full lg:w-3/4">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                {{ $trips->count() }} trajet(s) disponible(s) 
                <span class="text-base font-normal text-gray-500 block sm:inline sm:ml-2">le {{ $travelDate->isoFormat('dddd D MMMM YYYY') }}</span>
            </h2>

            @if($trips->isEmpty())
                <div class="card p-12 text-center border border-dashed border-gray-300 bg-gray-50">
                    <div class="w-20 h-20 mx-auto bg-gray-200 rounded-full flex items-center justify-center text-gray-400 text-3xl mb-4">
                        <i class="fa-solid fa-bus-slash"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Aucun trajet trouvé</h3>
                    <p class="text-gray-500 mb-6">Nous n'avons pas trouvé de bus SATAS pour cette date et cet itinéraire.</p>
                    <a href="{{ route('home') }}" class="btn-outline inline-flex">Modifier la recherche</a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($trips as $trip)
                        @php
                            $reservedCount = $trip->reservations->where('statut', '!=', 'Annulé')->count();
                            $capacity = $trip->bus->capacite;
                            $availableSeats = $capacity - $reservedCount;
                            
                            $isPremium = $trip->bus->type === 'premium';
                            $finalPrice = $isPremium ? $trip->tarif * 1.2 : $trip->tarif;
                            
                            $departTime = \Carbon\Carbon::parse($trip->programme->heure_depart);
                            $arriveeTime = \Carbon\Carbon::parse($trip->programme->heure_arrivee);
                            // handle overnight trips simply (if arrival < depart)
                            if($arriveeTime < $departTime) {
                                $arriveeTime->addDay();
                            }
                        @endphp
                        
                        <div class="trip-card flex flex-col md:flex-row bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative">
                            
                            @if($trip->bus->type === 'premium')
                                <div class="absolute top-0 left-0 w-1 h-full" style="background: linear-gradient(180deg, var(--satas-gold), #D4A03A);"></div>
                                <div class="absolute top-3 right-3">
                                    <span class="badge-premium text-[10px]"><i class="fa-solid fa-crown mr-1"></i> Premium</span>
                                </div>
                            @elseif($trip->bus->type === 'confort')
                                <div class="absolute top-0 left-0 w-1 h-full bg-blue-500"></div>
                            @else
                                <div class="absolute top-0 left-0 w-1 h-full bg-gray-300"></div>
                            @endif

                            <div class="flex-1 p-6 flex flex-col sm:flex-row sm:items-center gap-6">
                                <!-- Time info -->
                                <div class="flex flex-row sm:flex-col items-center sm:items-start justify-between sm:justify-center min-w-[120px]">
                                    <div class="text-center sm:text-left">
                                        <div class="text-2xl font-bold text-gray-900">{{ $departTime->format('H:i') }}</div>
                                        <div class="text-xs text-gray-500 font-medium">{{ $trip->depart->gare->ville->name }}</div>
                                    </div>
                                    
                                    <div class="flex-1 sm:flex-none flex flex-col items-center justify-center px-4 sm:px-0 sm:py-2 text-gray-300">
                                        <span class="text-[10px] text-gray-500 mb-1">{{ $trip->duree_estimee }}</span>
                                        <div class="w-full sm:w-px h-px sm:h-8 bg-gray-200 relative flex items-center justify-center">
                                            <i class="fa-solid fa-chevron-down sm:block hidden absolute text-[8px] bottom-0 translate-y-1/2 bg-white px-1"></i>
                                            <i class="fa-solid fa-chevron-right sm:hidden absolute text-[8px] right-0 translate-x-1/2 bg-white py-1"></i>
                                        </div>
                                    </div>

                                    <div class="text-center sm:text-left">
                                        <div class="text-2xl font-bold text-gray-900">{{ $arriveeTime->format('H:i') }}</div>
                                        <div class="text-xs text-gray-500 font-medium">{{ $trip->arrivee->gare->ville->name }}</div>
                                    </div>
                                </div>

                                <!-- Middle Info -->
                                <div class="flex-1 flex flex-col justify-center border-t border-gray-100 pt-4 mt-2 sm:border-0 sm:pt-0 sm:mt-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-sm font-semibold text-satas-navy">{{ $trip->programme->route->nom }}</span>
                                        <span class="text-xs text-gray-400">&bull;</span>
                                        <span class="text-xs font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-600"><i class="fa-solid fa-bus-simple mr-1"></i>{{ $trip->bus->matricule }}</span>
                                    </div>
                                    
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        @foreach((array)$trip->bus->amenities as $amenity)
                                            <span class="amenity-badge">
                                                @if($amenity == 'wifi') <i class="fa-solid fa-wifi"></i> 
                                                @elseif($amenity == 'prises') <i class="fa-solid fa-plug"></i>
                                                @elseif($amenity == 'wc') <i class="fa-solid fa-restroom"></i>
                                                @elseif($amenity == 'climatisation') <i class="fa-solid fa-snowflake"></i>
                                                @endif
                                                <span class="capitalize">{{ $amenity }}</span>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action / Price -->
                            <div class="p-6 bg-gray-50 flex flex-row md:flex-col items-center justify-between md:justify-center md:min-w-[180px] border-t md:border-t-0 md:border-l border-gray-100">
                                <div class="text-center mb-0 md:mb-4">
                                    <div class="price-tag">{{ number_format($finalPrice, 2) }} <span class="currency">MAD</span></div>
                                    
                                    @if($availableSeats > 0 && $availableSeats <= 5)
                                        <div class="text-xs font-semibold text-red-500 mt-1"><i class="fa-solid fa-fire text-orange-500"></i> Plus que {{ $availableSeats }} places !</div>
                                    @elseif($availableSeats > 0)
                                        <div class="text-xs text-green-600 mt-1"><i class="fa-solid fa-check"></i> {{ $availableSeats }} places dispo</div>
                                    @else
                                        <div class="text-xs font-bold text-red-600 mt-1">Complet</div>
                                    @endif
                                </div>
                                
                                @if($availableSeats > 0)
                                    <a href="{{ route('reservation.create', $trip->id) }}" class="btn-primary w-auto md:w-full px-8 md:px-4">
                                        Choisir
                                    </a>
                                @else
                                    <button disabled class="btn w-auto md:w-full bg-gray-300 text-gray-500 cursor-not-allowed">
                                        Complet
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
