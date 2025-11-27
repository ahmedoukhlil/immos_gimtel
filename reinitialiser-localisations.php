<?php

/**
 * Script pour réinitialiser toutes les localisations d'un inventaire
 * 
 * Usage: php reinitialiser-localisations.php [inventaire_id]
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Inventaire;
use App\Models\InventaireLocalisation;

echo "========================================\n";
echo "  RÉINITIALISATION DES LOCALISATIONS\n";
echo "========================================\n\n";

// Récupérer l'ID de l'inventaire (argument ou 2 par défaut)
$inventaireId = $argv[1] ?? 2;

echo "Inventaire ID: {$inventaireId}\n\n";

// Vérifier que l'inventaire existe
$inventaire = Inventaire::find($inventaireId);
if (!$inventaire) {
    echo "❌ ERREUR: Inventaire ID {$inventaireId} introuvable\n";
    exit(1);
}

echo "Inventaire trouvé: {$inventaire->annee} (Statut: {$inventaire->statut})\n\n";

// Récupérer toutes les localisations de cet inventaire
$localisations = InventaireLocalisation::where('inventaire_id', $inventaireId)
    ->with('localisation')
    ->get();

if ($localisations->isEmpty()) {
    echo "⚠️  Aucune localisation trouvée pour cet inventaire\n";
    exit(0);
}

echo "Localisations trouvées: {$localisations->count()}\n\n";

// Afficher l'état actuel
echo "État AVANT réinitialisation:\n";
echo str_repeat('-', 60) . "\n";
foreach ($localisations as $loc) {
    echo sprintf(
        "  %-20s | Statut: %-12s | Scannés: %3d/%3d\n",
        $loc->localisation->code,
        $loc->statut,
        $loc->nombre_biens_scannes,
        $loc->nombre_biens_attendus
    );
}
echo "\n";

// Demander confirmation
echo "Voulez-vous réinitialiser toutes ces localisations ? (o/n): ";
$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim($line) !== 'o' && trim($line) !== 'O' && trim($line) !== 'oui') {
    echo "\n❌ Opération annulée\n";
    exit(0);
}
fclose($handle);

// Réinitialiser
echo "\nRéinitialisation en cours...\n";
$count = 0;

foreach ($localisations as $loc) {
    $loc->update([
        'statut' => 'en_attente',
        'nombre_biens_scannes' => 0,
        'date_debut_scan' => null,
        'date_fin_scan' => null,
    ]);
    
    $count++;
    echo "  ✓ {$loc->localisation->code} réinitialisée\n";
}

echo "\n✅ {$count} localisation(s) réinitialisée(s)\n\n";

// Afficher l'état final
echo "État APRÈS réinitialisation:\n";
echo str_repeat('-', 60) . "\n";
$localisations->refresh();
foreach ($localisations as $loc) {
    echo sprintf(
        "  %-20s | Statut: %-12s | Scannés: %3d/%3d\n",
        $loc->localisation->code,
        $loc->statut,
        $loc->nombre_biens_scannes,
        $loc->nombre_biens_attendus
    );
}

echo "\n========================================\n";
echo "  ✅ RÉINITIALISATION TERMINÉE\n";
echo "========================================\n";

