<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Réservation - SATAS</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; padding: 20px; }
        .ticket { background: #fff; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
        .ticket-header { background: #4f46e5; color: white; padding: 25px; }
        .ticket-body { padding: 30px; }
        .ref-container { margin: 15px 0; }
        .ref { font-size: 1rem; font-weight: 700; color: #4f46e5; border: 2px dashed #4f46e5; padding: 8px 15px; display: inline-block; margin: 5px; border-radius: 5px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px solid #f8f8f8; padding-bottom: 8px; }
        .label { color: #6b7280; font-size: 0.85rem; }
        .value { font-weight: 600; font-size: 0.95rem; }
        .qr-mock { background: #f9fafb; height: 120px; width: 120px; margin: 25px auto; display: flex; align-items: center; justify-content: center; border-radius: 15px; border: 1px solid #eee; }
        .btn-home { display: inline-block; margin-top: 20px; color: #4f46e5; text-decoration: none; font-weight: 600; transition: 0.3s; }
        .btn-home:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="ticket-header">
            <i class="fa-solid fa-circle-check" style="font-size: 3.5rem; margin-bottom: 10px;"></i>
            <h2 style="margin: 0;">Réservation Confirmée</h2>
        </div>

        <div class="ticket-body">
            <p class="label">Référence(s) de votre ticket :</p>
            <div class="ref-container">
                @foreach($reservations as $res)
                    <div class="ref">{{ $res->reference }}</div>
                @endforeach
            </div>
            
            <div class="info-row" style="margin-top: 20px;">
                <span class="label"><i class="fa-solid fa-route"></i> Trajet :</span>
                <span class="value">
                    {{ $reservations->first()->segment->depart->gare->ville->name }} 
                    ➔ 
                    {{ $reservations->first()->segment->arrivee->gare->ville->name }}
                </span>
            </div>

            <div class="info-row">
                <span class="label"><i class="fa-solid fa-calendar"></i> Date :</span>
                <span class="value">{{ $reservations->first()->segment->programme->jour_depart }}</span>
            </div>

            <div class="info-row">
                <span class="label"><i class="fa-solid fa-clock"></i> Heure :</span>
                <span class="value">{{ \Carbon\Carbon::parse($reservations->first()->segment->programme->heure_depart)->format('H:i') }}</span>
            </div>

            <div class="info-row">
                <span class="label"><i class="fa-solid fa-couch"></i> Siège(s) :</span>
                <span class="value">
                    @foreach($reservations as $res)
                        #{{ $res->siege_numero }}{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                </span>
            </div>

            <div class="info-row" style="border-bottom: none;">
                <span class="label"><i class="fa-solid fa-money-bill-wave"></i> Prix Total :</span>
                <span class="value" style="color: #10b981; font-size: 1.2rem;">
                    {{ $reservations->count() * $reservations->first()->segment->tarif }} MAD
                </span>
            </div>

            <div class="qr-mock">
                <i class="fa-solid fa-qrcode" style="font-size: 5rem; color: #1f2937;"></i>
            </div>
            
            <a href="{{ route('search.index') }}" class="btn-home">
                <i class="fa-solid fa-house"></i> Retour à l'accueil
            </a>
        </div>
    </div>

</body>
</html>