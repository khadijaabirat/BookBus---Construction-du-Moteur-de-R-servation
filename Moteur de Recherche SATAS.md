1️⃣ Utilisation de whereHas()
Explication :

Permet de filtrer des modèles selon une condition sur une relation, pour récupérer uniquement les éléments ayant une relation spécifique.

Exemple SATAS :

Rechercher tous les trajets qui passent par Settat comme étape intermédiaire.

Trajet Casa→Settat→Marrakech → inclus

Trajet Casa→Marrakech direct → inclus seulement si on filtre Casa ou Marrakech

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

with() charge les relations au moment de la requête initiale, tandis que load() charge les relations après avoir récupéré le modèle, utile pour éviter le problème N+1.

Exemple SATAS :

Afficher une liste de trajets avec :

Ville de départ

Ville d’arrivée

Compagnie de transport

Implémentation :
// Avec with() pour la liste
$trips = Trip::with(['departureCity', 'arrivalCity', 'company'])->get();

// Avec load() pour un détail
$trip = Trip::find($id);
$trip->load(['stops.city']);

Tests :

Vérifier le nombre de requêtes avec Debugbar

Sans with() → N+1 queries

Avec with() → requêtes optimisées

3️⃣ Cache (Cache::remember)
Explication :

Permet de stocker temporairement des données fréquemment utilisées pour éviter de refaire les mêmes requêtes et améliorer la performance.

Exemple SATAS :

Top 10 des trajets les plus réservés → stockés 1h dans le cache pour accélérer l’affichage.

Implémentation :
$popularTrips = Cache::remember('popular_trips', 3600, function () {
return Trip::orderBy('reservations_count', 'desc')
->take(10)
->get();
});

Tests :

Première requête → SQL exécuté et cache créé

Requêtes suivantes → données récupérées depuis cache

Après 1h → cache régénéré automatiquement
4️⃣ Scopes pour les requêtes réutilisables
Explication :

Les scopes permettent de réutiliser des filtres complexes sur plusieurs requêtes Eloquent.

Exemple SATAS :

Récupérer uniquement les trajets actifs et avec des places disponibles.

Implémentation :
// Dans Trip.php
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

5️⃣ Problème N+1 et comment l’éviter
Explication :

Le problème N+1 se produit lorsque Laravel effectue une requête principale + une requête pour chaque élément lié, ralentissant l’application.

Exemple SATAS :

Afficher 50 trajets avec leurs villes → sans optimisation → 51 requêtes (1 + 50).

Implémentation :
$trips = Trip::with(['departureCity', 'arrivalCity'])->get();

Tests :

Vérifier le nombre de requêtes via Debugbar

Tester avec 10, 50, 100 trajets → nombre de requêtes constant

6️⃣ Importance des indexes en base de données
Explication :

Les indexes accélèrent les recherches et tris dans la base de données, surtout pour les colonnes souvent utilisées dans WHERE, JOIN ou ORDER BY.

Exemple SATAS :

Recherche fréquente par departure_city_id, arrival_city_id et departure_time.

Implémentation :
$table->index('departure_city_id');
$table->index('arrival_city_id');
$table->index('departure_time');

Tests :

Comparer temps de réponse avant/après index

Vérifier plan d’exécution SQL

Tester avec un grand volume de données

7️⃣ Quand utiliser le cache vs requêtes fraîches
Explication :

Le cache est utile pour les données peu changeantes et très consultées, alors que les requêtes fraîches sont nécessaires pour les données toujours à jour.

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

Première requête → SQL exécuté et cache créé

Requêtes suivantes → données récupérées depuis cache

Après 1h → cache régénéré automatiquement

8️⃣ Messages d’erreur personnalisés
Explication :

Permettent de fournir des messages clairs et compréhensibles pour l’utilisateur, au lieu des messages génériques Laravel.

Exemple SATAS :

Si email vide → message “L’adresse email est obligatoire”.

Implémentation :
$request->validate(
['email' => 'required|email'],
['email.required' => 'L’adresse email est obligatoire']
);

Tests :

Champ vide → message personnalisé affiché

Email invalide → message standard ou personnalisé

9️⃣ Validation asynchrone (JavaScript / AJAX)
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

Code promo invalide → message d’erreur immédiat
