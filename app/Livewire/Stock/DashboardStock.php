<?php

namespace App\Livewire\Stock;

use App\Models\StockProduit;
use App\Models\StockMagasin;
use App\Models\StockCategorie;
use App\Models\StockEntree;
use App\Models\StockSortie;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class DashboardStock extends Component
{
    public $totalProduits = 0;
    public $produitsEnAlerte = 0;
    public $totalMagasins = 0;
    public $entreesduMois = 0;
    public $sortiesDuMois = 0;
    public $produitsAlerteDetails = [];
    public $stockParMagasin = [];
    public $stockParCategorie = [];
    public $derniersMovements = [];

    public function mount()
    {
        $this->loadStatistics();
    }

    public function refresh()
    {
        $this->loadStatistics();
    }

    private function loadStatistics()
    {
        // Statistiques globales
        $this->totalProduits = StockProduit::count();
        $this->produitsEnAlerte = StockProduit::whereColumn('stock_actuel', '<=', 'seuil_alerte')->count();
        $this->totalMagasins = StockMagasin::count();

        // Mouvements du mois en cours
        $debutMois = now()->startOfMonth();
        $finMois = now()->endOfMonth();

        $this->entreesduMois = StockEntree::whereBetween('date_entree', [$debutMois, $finMois])->sum('quantite');
        $this->sortiesDuMois = StockSortie::whereBetween('date_sortie', [$debutMois, $finMois])->sum('quantite');

        // Produits en alerte (top 10)
        $this->produitsAlerteDetails = StockProduit::with(['categorie', 'magasin'])
            ->whereColumn('stock_actuel', '<=', 'seuil_alerte')
            ->orderBy('stock_actuel')
            ->limit(10)
            ->get()
            ->map(function ($produit) {
                return [
                    'id' => $produit->id,
                    'libelle' => $produit->libelle,
                    'categorie' => $produit->categorie->libelle ?? '-',
                    'magasin' => $produit->magasin->magasin ?? '-',
                    'stock_actuel' => $produit->stock_actuel,
                    'seuil_alerte' => $produit->seuil_alerte,
                    'statut' => $produit->statut_stock,
                ];
            })
            ->toArray();

        // Stock par magasin
        $this->stockParMagasin = StockMagasin::withCount('produits')
            ->get()
            ->map(function ($magasin) {
                $produitsEnAlerte = $magasin->produits()
                    ->whereColumn('stock_actuel', '<=', 'seuil_alerte')
                    ->count();

                return [
                    'magasin' => $magasin->magasin,
                    'localisation' => $magasin->localisation,
                    'nombre_produits' => $magasin->produits_count,
                    'produits_en_alerte' => $produitsEnAlerte,
                ];
            })
            ->toArray();

        // Stock par catégorie
        $this->stockParCategorie = StockCategorie::withCount('produits')
            ->get()
            ->map(function ($categorie) {
                $stockTotal = $categorie->produits()->sum('stock_actuel');

                return [
                    'categorie' => $categorie->libelle,
                    'nombre_produits' => $categorie->produits_count,
                    'stock_total' => $stockTotal,
                ];
            })
            ->toArray();

        // Derniers mouvements (10 derniers)
        $dernieresEntrees = StockEntree::with(['produit', 'fournisseur', 'createur'])
            ->orderBy('date_entree', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($entree) {
                return [
                    'type' => 'entree',
                    'date' => $entree->date_entree,
                    'produit' => $entree->produit->libelle ?? 'N/A',
                    'tiers' => $entree->fournisseur->libelle ?? 'N/A',
                    'quantite' => $entree->quantite,
                    'createur' => $entree->nom_createur,
                ];
            });

        $dernieresSorties = StockSortie::with(['produit', 'demandeur', 'createur'])
            ->orderBy('date_sortie', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($sortie) {
                return [
                    'type' => 'sortie',
                    'date' => $sortie->date_sortie,
                    'produit' => $sortie->produit->libelle ?? 'N/A',
                    'tiers' => $sortie->demandeur->nom ?? 'N/A',
                    'quantite' => $sortie->quantite,
                    'createur' => $sortie->nom_createur,
                ];
            });

        $this->derniersMovements = $dernieresEntrees->concat($dernieresSorties)
            ->sortByDesc('date')
            ->take(10)
            ->values()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.stock.dashboard-stock');
    }
}
