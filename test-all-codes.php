<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Localisation;
use App\Models\Bien;
use App\Models\Inventaire;
use App\Models\InventaireLocalisation;

echo "========================================\n";
echo "  TEST DE TOUS LES CODES EN BASE\n";
echo "========================================\n\n";

// Localisations
echo "=== LOCALISATIONS ===\n";
$localisations = Localisation::where('actif', true)->get();

if ($localisations->isEmpty()) {
    echo "❌ AUCUNE LOCALISATION ACTIVE EN BASE\n";
} else {
    echo "Total : {$localisations->count()}\n\n";
    
    foreach ($localisations as $loc) {
        // Format attendu dans le QR
        $qrJson = json_encode([
            'type' => 'localisation',
            'id' => $loc->id,
            'code' => $loc->code
        ], JSON_UNESCAPED_SLASHES);
        
        echo "📍 {$loc->code}\n";
        echo "   ID: {$loc->id}\n";
        echo "   Désignation: {$loc->designation}\n";
        echo "   QR JSON: {$qrJson}\n";
        echo "   Test PWA: scannerManager.onScanSuccess('{$qrJson}', null);\n";
        echo "\n";
    }
}

// Biens
echo "\n=== BIENS (premiers 10) ===\n";
$biens = Bien::limit(10)->get();

if ($biens->isEmpty()) {
    echo "❌ AUCUN BIEN EN BASE\n";
} else {
    echo "Total en base : " . Bien::count() . "\n\n";
    
    foreach ($biens as $bien) {
        $qrJson = json_encode([
            'type' => 'bien',
            'id' => $bien->id,
            'code' => $bien->code_inventaire
        ], JSON_UNESCAPED_SLASHES);
        
        echo "📦 {$bien->code_inventaire}\n";
        echo "   ID: {$bien->id}\n";
        echo "   Désignation: {$bien->designation}\n";
        if ($bien->localisation) {
            echo "   Localisation: {$bien->localisation->code}\n";
        }
        echo "   QR JSON: {$qrJson}\n";
        echo "   Test PWA: scannerManager.onScanSuccess('{$qrJson}', null);\n";
        echo "\n";
    }
}

// Inventaire actif
echo "\n=== INVENTAIRE ACTIF ===\n";
$inventaire = Inventaire::whereIn('statut', ['en_cours', 'en_preparation'])
    ->orderBy('created_at', 'desc')
    ->first();

if (!$inventaire) {
    echo "❌ AUCUN INVENTAIRE EN COURS !\n";
} else {
    echo "✅ Inventaire actif trouvé\n";
    echo "   ID: {$inventaire->id}\n";
    echo "   Année: {$inventaire->annee}\n";
    echo "   Statut: {$inventaire->statut}\n";
    echo "   Date début: {$inventaire->date_debut}\n";
    
    // Assignations
    echo "\n   === ASSIGNATIONS ===\n";
    $assignations = InventaireLocalisation::where('inventaire_id', $inventaire->id)
        ->with(['localisation'])
        ->get();
    
    if ($assignations->isEmpty()) {
        echo "   ❌ AUCUNE LOCALISATION ASSIGNÉE\n";
    } else {
        echo "   Total : {$assignations->count()}\n\n";
        foreach ($assignations->take(5) as $assign) {
            $userName = $assign->user_id ? \App\Models\User::find($assign->user_id)->name ?? 'ID: ' . $assign->user_id : 'Non assigné';
            echo sprintf(
                "   📍 %s → %s (%d/%d biens) [%s]\n",
                $assign->localisation->code,
                $userName,
                $assign->nombre_biens_scannes,
                $assign->nombre_biens_attendus,
                $assign->statut
            );
        }
    }
}

echo "\n========================================\n";
echo "  FIN DU TEST\n";
echo "========================================\n";

