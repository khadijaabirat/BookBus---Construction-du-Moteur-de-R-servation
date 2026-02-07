<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de la recherche</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #10b981; /* Vert pour le prix/validation */
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --text-color: #1f2937;
            --text-light: #6b7280;
            --card-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            padding: 40px 20px;
            color: var(--text-color);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        /* En-tête de la page */
        .page-header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .page-header h2 {
            font-size: 2rem;
            font-weight: 700;
        }

        /* Style des cartes de trajet */
        .trip-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column; /* Mobile first */
            gap: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Petite barre décorative à gauche */
        .trip-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 6px;
            background: var(--primary-color);
        }

        .trip-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        @media (min-width: 768px) {
            .trip-card {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        /* Info Trajet (Gauche) */
        .trip-info {
            flex: 1;
        }

        .route {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .route i {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .details {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            color: var(--text-light);
            font-size: 0.95rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .detail-item i {
            color: var(--primary-color);
        }

        /* Action & Prix (Droite) */
        .trip-action {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 10px;
            min-width: 180px;
        }

        .price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .price span {
            font-size: 0.9rem;
            font-weight: 400;
            color: var(--text-light);
        }

        button.btn-book {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Poppins', sans-serif;
        }

        button.btn-book:hover {
            background-color: var(--primary-hover);
            transform: scale(1.02);
        }

        /* État Vide (Pas de trajets) */
        .empty-state {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .empty-state i {
            font-size: 4rem;
            color: #d1d5db;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 1.1rem;
            color: var(--text-color);
            margin-bottom: 25px;
        }

        .btn-back {
            display: inline-block;
            text-decoration: none;
            color: var(--primary-color);
            font-weight: 600;
            padding: 10px 20px;
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: var(--primary-color);
            color: white;
        }

        .bus-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* 4 krasa f s-ster */
    gap: 10px;
    padding: 20px;
    background: #f9fafb;
    border-radius: 15px;
    margin-top: 15px;
    max-height: 300px;
    overflow-y: auto; /* Ila kano krasa bzaf */
}

.seat-item {
    position: relative;
    text-align: center;
}

.seat-item input {
    display: none; /* n-khbiw l-checkbox l-3adiya */
}

.seat-item label {
    display: block;
    padding: 10px;
    background: #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.2s;
}

.seat-item input:checked + label {
    background: var(--secondary-color);
    color: white;
}

.seat-item.occupied label {
    background: #fca5a5; /* loun l-krasa l-m3mmrin */
    cursor: not-allowed;
    color: #991b1b;
}

.aisle {
    grid-column: 3; /* couloir f l-west */
    width: 20px;
}
    </style>
</head>
<body>

    <div class="container">
        
        <div class="page-header">
            <h2><i class="fa-solid fa-route"></i> Trajets disponibles</h2>
        </div>

        @if($trips->isEmpty())
            <div class="empty-state">
                <i class="fa-regular fa-face-frown-open"></i>
                <p>Désolé, aucun trajet n'est disponible pour cet itinéraire pour le moment.</p>
                <a href="{{ route('search.index') }}" class="btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Retour à la recherche
                </a>
            </div>
        @else
           @foreach($trips as $trip)
    <div class="trip-card">
        <div class="trip-info">
            <div class="route">
                {{ $trip->depart->gare->ville->name }} 
                <i class="fa-solid fa-arrow-right"></i> 
                {{ $trip->arrivee->gare->ville->name }}
            </div>
            
            <div class="details">
                <div class="detail-item">
                    <i class="fa-solid fa-bus"></i>
                    {{ $trip->bus->matricule }}
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-clock"></i>
                    {{ \Carbon\Carbon::parse($trip->programme->heure_depart)->format('H:i') }}
                </div>
                <div class="detail-item">
                    <i class="fa-solid fa-bolt"></i>
                    {{ $trip->distance_km }} KM
                </div>
            </div>
        </div>

        <div class="trip-action">
            <div class="price">{{ $trip->tarif }} <span>MAD</span></div>
        </div>

        <form action="{{ route('reservation.store', $trip->id) }}" method="POST" style="width: 100%; border-top: 1px solid #eee; padding-top: 15px;">
            @csrf
            <p style="font-size: 0.9rem; font-weight: 600; margin-bottom: 10px;">
                <i class="fa-solid fa-couch"></i> Sélectionnez vos places :
            </p>

            <div class="bus-grid">
                @php
                    $reservedSeats = $trip->reservations->pluck('siege_numero')->toArray();
                    $totalSeats = $trip->bus->capacite; 
                @endphp

                @for($i = 1; $i <= $totalSeats; $i++)
                    @php $isTaken = in_array($i, $reservedSeats); @endphp
                    
                    <div class="seat-item {{ $isTaken ? 'occupied' : '' }}">
                        <input type="checkbox" name="seats[]" value="{{ $i }}" 
                               id="seat-{{ $trip->id }}-{{ $i }}" 
                               {{ $isTaken ? 'disabled' : '' }}>
                        <label for="seat-{{ $trip->id }}-{{ $i }}">{{ $i }}</label>
                    </div>
                @endfor
            </div>

            <button type="submit" class="btn-book" style="margin-top: 15px;">
                Réserver les places sélectionnées
            </button>
        </form>
    </div>
@endforeach
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ route('search.index') }}" style="color: rgba(255,255,255,0.8); text-decoration: none; font-size: 0.9rem;">
                    <i class="fa-solid fa-arrow-left"></i> Faire une autre recherche
                </a>
            </div>

        @endif

    </div>

</body>
</html>