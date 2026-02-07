<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de trajets</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4f46e5; /* Indigo moderne */
            --primary-hover: #4338ca;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --text-color: #1f2937;
            --input-bg: #f3f4f6;
            --input-border: #d1d5db;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .container:hover {
            transform: translateY(-5px);
        }

        h1 {
            color: var(--text-color);
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 1.8rem;
        }

        p.subtitle {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 0.9rem;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 1.1rem;
            pointer-events: none; /* Laisse passer le clic */
        }

        select {
            width: 100%;
            padding: 14px 20px 14px 45px; /* Espace pour l'icône */
            border-radius: 12px;
            border: 2px solid transparent;
            background-color: var(--input-bg);
            font-size: 1rem;
            color: var(--text-color);
            appearance: none; /* Cache la flèche par défaut moche */
            outline: none;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            cursor: pointer;
        }

        /* Petite flèche custom pour le select */
        .input-wrapper::after {
            content: '\f078'; /* Code FontAwesome chevron down */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            pointer-events: none;
            font-size: 0.8rem;
        }

        select:focus {
            background-color: #fff;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        button {
            width: 100%;
            padding: 16px;
            background-color: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 15px 25px rgba(79, 70, 229, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        /* Décoration optionnelle : ligne de connexion */
        .connector {
            display: flex;
            justify-content: center;
            margin: -10px 0 10px 0;
            color: #d1d5db;
        }
    </style>
</head>
<body>

    <div class="container">
        <div style="font-size: 3rem; color: var(--primary-color); margin-bottom: 10px;">
            <i class="fa-solid fa-map-location-dot"></i>
        </div>
        
        <h1>Planifiez votre voyage</h1>
        <p class="subtitle">Trouvez le meilleur trajet en quelques clics</p>

       <form action="{{ route('search.trip') }}" method="POST">
    @csrf

    <div class="form-group">
        <label>Ville de départ :</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-location-dot"></i>
            <select name="departure_city" required>
                <option value="" disabled selected>Sélectionnez votre départ</option>
                @foreach($villes as $ville)
                    <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="connector">
        <i class="fa-solid fa-arrows-up-down"></i>
    </div>

    <div class="form-group">
        <label>Ville d'arrivée :</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-map-pin"></i>
            <select name="arrival_city" required>
                <option value="" disabled selected>Où allez-vous ?</option>
                @foreach($villes as $ville)
                    <option value="{{ $ville->id }}">{{ $ville->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <label>Date du voyage :</label>
        <div class="input-wrapper">
            <i class="fa-solid fa-calendar-day"></i>
            <input type="date" name="travel_date" 
                   value="{{ date('Y-m-d') }}" 
                   min="{{ date('Y-m-d') }}"
                   style="width: 100%; padding: 14px 20px 14px 45px; border-radius: 12px; border: 2px solid transparent; background-color: var(--input-bg); font-family: 'Poppins', sans-serif; outline: none; transition: all 0.3s ease;"
                   required>
        </div>
    </div>

    <button type="submit">
        <i class="fa-solid fa-magnifying-glass"></i>
        Trouver mon trajet
    </button>
</form>

    </div>

</body>
</html>