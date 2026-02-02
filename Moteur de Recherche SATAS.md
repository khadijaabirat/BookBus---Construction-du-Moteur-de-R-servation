1️⃣ Utilisation de whereHas() pour filtrer via des relations
Explication :

whereHas() permet de filtrer des modèles en fonction d’une condition sur une relation, pour ne récupérer que les éléments ayant une relation spécifique.

Exemple SATAS :

Trouver tous les trajets qui passent par Settat comme étape intermédiaire.

Si un trajet passe Casa→Settat→Marrakech → il sera inclus.

Si le trajet est Casa→Marrakech direct → il ne sera inclus que si on filtre sur Casa ou Marrakech.

Implémentation :
$trips = Trip::whereHas('stops.city', function ($query) {
    $query->where('name', 'Settat');
})->get();

Tests :

Ville avec trajets → résultats corrects

Ville sans trajets → collection vide

Ville inexistante → collection vide

2️⃣ Différence entre with() et load()
Explication :

with() charge les relations au moment de la requête initiale, tandis que load() charge les relations après avoir récupéré le modèle.

Exemple SATAS :

Afficher la liste des trajets avec la ville de départ, la ville d’arrivée et la compagnie de transport.

Implémentation :
// Avec with() pour liste
$trips = Trip::with(['departureCity', 'arrivalCity', 'company'])->get();

// Avec load() pour détail
$trip = Trip::find($id);
$trip->load(['stops.city']);

Tests :

Vérifier le nombre de requêtes via Laravel Debugbar

Sans with() → N+1 queries

Avec with() → requêtes optimisées

3️⃣ Scopes pour les requêtes réutilisables
Explication :

Les scopes permettent de réutiliser des filtres complexes sur plusieurs requêtes Eloquent.

Exemple SATAS :

Récupérer uniquement les trajets actifs et avec des places disponibles.

Implémentation :
// Trip.php
public function scopeActive($query) {
    return $query->where('is_active', true);
}

public function scopeBookable($query) {
    return $query->where('available_seats', '>', 0);
}

// Utilisation
$trips = Trip::active()->bookable()->get();

Tests :

Trajet inactif → exclu

Trajet sans places → exclu

Trajet actif avec places → inclus

4️⃣ Problème N+1 et comment l’éviter
Explication :

Le problème N+1 survient lorsque Laravel effectue une requête principale puis une requête supplémentaire pour chaque élément lié, ce qui ralentit l’application.

Exemple SATAS :

Afficher 50 trajets avec leurs villes → sans optimisation → 51 requêtes (1 + 50).

Implémentation :
$trips = Trip::with(['departureCity', 'arrivalCity'])->get();

Tests :

Vérifier le nombre de requêtes via Debugbar

Tester avec 10, 50, 100 trajets → nombre de requêtes constant

5️⃣ Importance des indexes en base de données
Explication :

Les indexes accélèrent les requêtes WHERE, ORDER BY et JOIN en évitant le scan complet des tables.

Exemple SATAS :

Recherche fréquente par departure_city_id, arrival_city_id et departure_time.

Implémentation :
$table->index('departure_city_id');
$table->index('arrival_city_id');
$table->index('departure_time');

Tests :

Comparer temps de réponse avant/après index

Vérifier plan d’exécution SQL

6️⃣ Quand utiliser le cache vs requêtes fraîches
Explication :

Le cache est utile pour des données peu changeantes et très consultées, tandis que les requêtes fraîches sont nécessaires pour les données qui changent souvent.

Exemple SATAS :

Cache → Top 10 des trajets les plus réservés

Requêtes fraîches → Disponibilité des places en temps réel

Implémentation :
$popularTrips = Cache::remember('popular_trips', 3600, function () {
    return Trip::orderBy('reservations_count', 'desc')
        ->take(10)
        ->get();
});

Tests :

Première requête → SQL exécuté

Requêtes suivantes → cache utilisé

Après 1h → cache régénéré

7️⃣ Règles de validation custom
Explication :

Permettent de créer des règles spécifiques au projet, non couvertes par Laravel standard.

Exemple SATAS :

Nombre de places demandé ≤ places disponibles pour un trajet.

Implémentation :
$request->validate([
    'seats' => ['required', 'integer', function ($attr, $value, $fail) use ($trip) {
        if ($value > $trip->available_seats) {
            $fail('Nombre de places insuffisant.');
        }
    }]
]);

Tests :

Valide → réservation acceptée

Trop de places → message d’erreur

Valeur non numérique → erreur Laravel standard

8️⃣ Messages d’erreur personnalisés
Explication :

Permettent de fournir des messages clairs et compréhensibles pour l’utilisateur.

Exemple SATAS :

Si email vide → message “L’adresse email est obligatoire”.

Implémentation :
$request->validate(
    ['email' => 'required|email'],
    ['email.required' => 'L’adresse email est obligatoire']
);

Tests :

Champ vide → message personnalisé

Email invalide → message standard ou personnalisé

9️⃣ Validation asynchrone (JavaScript)
Explication :

Permet de valider des données côté serveur sans recharger la page, souvent via AJAX.

Exemple SATAS :

Vérifier en temps réel :

Disponibilité des places

Validité d’un code promo

Implémentation :
fetch('/check-seats', {
    method: 'POST',
    body: JSON.stringify({ trip_id: tripId, seats: seats }),
    headers: { 'Content-Type': 'application/json' }
})
.then(res => res.json())
.then(data => {
    if(!data.valid){
        alert(data.message);
    }
});

// Laravel Controller
public function checkSeats(Request $request) {
    $trip = Trip::find($request->trip_id);
    if($request->seats > $trip->available_seats){
        return response()->json(['valid' => false, 'message' => 'Nombre de places insuffisant.']);
    }
    return response()->json(['valid' => true]);
}

Tests :

Places disponibles → OK

Places insuffisantes → message d’erreur immédiat

Code promo invalide → message immédiat