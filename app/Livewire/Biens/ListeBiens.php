<?php

namespace App\Livewire\Biens;

use App\Models\Gesimmo;
use App\Models\LocalisationImmo;
use App\Models\Emplacement;
use App\Models\Affectation;
use App\Models\Designation;
use App\Models\Categorie;
use App\Models\Etat;
use App\Models\NatureJuridique;
use App\Models\SourceFinancement;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ListeBiens extends Component
{
    use WithPagination;

    /**
     * Propriétés publiques pour les filtres et la recherche
     */
    public $search = '';
    public $filterDesignation = '';
    public $filterCategorie = '';
    public $filterLocalisation = '';
    public $filterAffectation = '';
    public $filterEmplacement = '';
    public $filterEtat = '';
    public $filterNatJur = '';
    public $filterSF = '';
    public $filterDateAcquisition = '';
    public $sortField = 'NumOrdre';
    public $sortDirection = 'asc';
    public $perPage = 20;
    public $selectedBiens = [];

    /**
     * Cache interne pour éviter les recalculs
     */
    private $cachedAffectationOptions = null;
    private $cachedAffectationOptionsKey = null;
    private $cachedEmplacementOptions = null;
    private $cachedEmplacementOptionsKey = null;

    /**
     * Initialisation du composant
     */
    public function mount(): void
    {
        // Réinitialiser la pagination si nécessaire
        $this->resetPage();
    }

    /**
     * Propriété calculée : Retourne toutes les désignations avec leurs catégories
     */
    public function getDesignationsProperty()
    {
        return Designation::with('categorie')
            ->orderBy('designation')
            ->get();
    }

    /**
     * Propriété calculée : Retourne toutes les catégories
     */
    public function getCategoriesProperty()
    {
        return Categorie::orderBy('Categorie')->get();
    }

    /**
     * Propriété calculée : Retourne toutes les localisations
     * Utilise le cache pour améliorer les performances
     */
    public function getLocalisationsProperty()
    {
        return cache()->remember('localisations_list', 3600, function () {
            return LocalisationImmo::select('idLocalisation', 'Localisation', 'CodeLocalisation')
                ->orderBy('Localisation')
                ->get();
        });
    }

    /**
     * Propriété calculée : Retourne les affectations filtrées selon la localisation sélectionnée
     * Requête directe ultra-optimisée sans cache pour une réponse instantanée
     */
    public function getAffectationsProperty()
    {
        $query = Affectation::select('idAffectation', 'Affectation', 'CodeAffectation', 'idLocalisation');
        
        // Si une localisation est sélectionnée, filtrer les affectations par idLocalisation
        if (!empty($this->filterLocalisation)) {
            $query->where('idLocalisation', $this->filterLocalisation);
        }
        
        return $query->orderBy('Affectation')->get();
    }

    /**
     * Options pour SearchableSelect : Affectations
     * Filtrées selon la localisation sélectionnée
     * Utilise un cache interne pour éviter les recalculs
     */
    public function getAffectationOptionsProperty()
    {
        $cacheKey = 'affectation_options_' . ($this->filterLocalisation ?? 'all');
        
        // Utiliser le cache interne si la clé n'a pas changé
        if ($this->cachedAffectationOptions !== null && $this->cachedAffectationOptionsKey === $cacheKey) {
            return $this->cachedAffectationOptions;
        }

        $options = [[
            'value' => '',
            'text' => 'Toutes les affectations',
        ]];

        $affectations = $this->affectations
            ->map(function ($affectation) {
                return [
                    'value' => (string)$affectation->idAffectation,
                    'text' => $affectation->Affectation . ($affectation->CodeAffectation ? ' (' . $affectation->CodeAffectation . ')' : ''),
                ];
            })
            ->toArray();

        $result = array_merge($options, $affectations);
        
        // Mettre en cache
        $this->cachedAffectationOptions = $result;
        $this->cachedAffectationOptionsKey = $cacheKey;
        
        return $result;
    }

    /**
     * Propriété calculée : Retourne les emplacements filtrés selon la localisation et/ou l'affectation
     * Requête directe ultra-optimisée sans cache pour une réponse instantanée
     */
    public function getEmplacementsProperty()
    {
        // Sélectionner uniquement les colonnes nécessaires pour l'affichage
        $query = Emplacement::select(
            'idEmplacement',
            'Emplacement',
            'CodeEmplacement',
            'idLocalisation',
            'idAffectation'
        );
        
        // Filtrer par localisation si sélectionnée
        if (!empty($this->filterLocalisation)) {
            $query->where('idLocalisation', $this->filterLocalisation);
        }
        
        // Filtrer par affectation si sélectionnée
        if (!empty($this->filterAffectation)) {
            $query->where('idAffectation', $this->filterAffectation);
        }
        
        $emplacements = $query->orderBy('Emplacement')->get();
        
        // Charger les relations en une seule requête si nécessaire (seulement si on a des résultats)
        if ($emplacements->isNotEmpty()) {
            $localisationIds = $emplacements->pluck('idLocalisation')->unique()->filter();
            $affectationIds = $emplacements->pluck('idAffectation')->unique()->filter();
            
            $localisations = collect();
            $affectations = collect();
            
            if ($localisationIds->isNotEmpty()) {
                $localisations = LocalisationImmo::select('idLocalisation', 'Localisation', 'CodeLocalisation')
                    ->whereIn('idLocalisation', $localisationIds)
                    ->get()
                    ->keyBy('idLocalisation');
            }
            
            if ($affectationIds->isNotEmpty()) {
                $affectations = Affectation::select('idAffectation', 'Affectation', 'CodeAffectation')
                    ->whereIn('idAffectation', $affectationIds)
                    ->get()
                    ->keyBy('idAffectation');
            }
            
            // Ajouter les relations et le nom d'affichage
            return $emplacements->map(function ($emplacement) use ($localisations, $affectations) {
                $emplacement->localisation = $localisations->get($emplacement->idLocalisation);
                $emplacement->affectation = $affectations->get($emplacement->idAffectation);
                $emplacement->display_name = $this->getEmplacementDisplayName($emplacement);
                return $emplacement;
            });
        }
        
        return $emplacements;
    }

    /**
     * Options pour SearchableSelect : Emplacements
     * Filtrés selon la localisation et l'affectation sélectionnées
     * Utilise un cache interne pour éviter les recalculs
     */
    public function getEmplacementOptionsProperty()
    {
        $cacheKey = 'emplacement_options_' . ($this->filterLocalisation ?? 'all') . '_' . ($this->filterAffectation ?? 'all');
        
        // Utiliser le cache interne si la clé n'a pas changé
        if ($this->cachedEmplacementOptions !== null && $this->cachedEmplacementOptionsKey === $cacheKey) {
            return $this->cachedEmplacementOptions;
        }

        $options = [[
            'value' => '',
            'text' => 'Tous les emplacements',
        ]];

        $emplacements = $this->emplacements
            ->map(function ($emplacement) {
                return [
                    'value' => (string)$emplacement->idEmplacement,
                    'text' => $emplacement->display_name ?? $emplacement->Emplacement,
                ];
            })
            ->toArray();

        $result = array_merge($options, $emplacements);
        
        // Mettre en cache
        $this->cachedEmplacementOptions = $result;
        $this->cachedEmplacementOptionsKey = $cacheKey;
        
        return $result;
    }
    
    /**
     * Génère le nom d'affichage d'un emplacement avec ses relations
     */
    private function getEmplacementDisplayName($emplacement): string
    {
        $parts = [];
        
        // Localisation
        if ($emplacement->localisation) {
            $parts[] = $emplacement->localisation->Localisation ?? '';
            if ($emplacement->localisation->CodeLocalisation) {
                $parts[] = '(' . $emplacement->localisation->CodeLocalisation . ')';
            }
        }
        
        // Affectation
        if ($emplacement->affectation) {
            $parts[] = '- ' . ($emplacement->affectation->Affectation ?? '');
        }
        
        // Emplacement
        $parts[] = '- ' . ($emplacement->Emplacement ?? '');
        
        return implode(' ', array_filter($parts));
    }

    /**
     * Propriété calculée : Retourne tous les états
     */
    public function getEtatsProperty()
    {
        return Etat::orderBy('Etat')->get();
    }

    /**
     * Propriété calculée : Retourne toutes les natures juridiques
     */
    public function getNatureJuridiquesProperty()
    {
        return NatureJuridique::orderBy('NatJur')->get();
    }

    /**
     * Propriété calculée : Retourne toutes les sources de financement
     */
    public function getSourceFinancementsProperty()
    {
        return SourceFinancement::orderBy('SourceFin')->get();
    }

    /**
     * Propriété calculée : Vérifie si tous les biens sont sélectionnés
     */
    public function getAllSelectedProperty()
    {
        $allBiensIds = $this->getBiensQuery()->pluck('NumOrdre')->toArray();
        
        if (empty($allBiensIds)) {
            return false;
        }

        return count($this->selectedBiens) === count($allBiensIds) &&
               empty(array_diff($allBiensIds, $this->selectedBiens));
    }

    /**
     * Change le tri de la colonne
     */
    public function sortBy($field): void
    {
        if ($this->sortField === $field) {
            // Inverser la direction si on clique sur la même colonne
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Nouvelle colonne, tri ascendant par défaut
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        // Réinitialiser la pagination lors du changement de tri
        $this->resetPage();
    }

    /**
     * Réinitialise tous les filtres
     */
    public function resetFilters(): void
    {
        $this->search = '';
        $this->filterDesignation = '';
        $this->filterCategorie = '';
        $this->filterLocalisation = '';
        $this->filterAffectation = '';
        $this->filterEmplacement = '';
        $this->filterEtat = '';
        $this->filterNatJur = '';
        $this->filterSF = '';
        $this->filterDateAcquisition = '';
        $this->selectedBiens = [];
        $this->resetPage();
    }

    /**
     * Réinitialise les filtres dépendants quand la localisation change
     */
    public function updatedFilterLocalisation($value): void
    {
        // Réinitialiser l'affectation et l'emplacement si la localisation change
        $this->filterAffectation = '';
        $this->filterEmplacement = '';
        
        // Réinitialiser le cache interne
        $this->cachedAffectationOptions = null;
        $this->cachedAffectationOptionsKey = null;
        $this->cachedEmplacementOptions = null;
        $this->cachedEmplacementOptionsKey = null;
        
        $this->resetPage();
    }

    /**
     * Réinitialise les filtres dépendants quand l'affectation change
     */
    public function updatedFilterAffectation($value): void
    {
        // Réinitialiser l'emplacement si l'affectation change
        $this->filterEmplacement = '';
        
        // Réinitialiser le cache interne
        $this->cachedEmplacementOptions = null;
        $this->cachedEmplacementOptionsKey = null;
        
        $this->resetPage();
    }

    /**
     * Sélectionne ou désélectionne tous les biens (toutes pages)
     */
    public function toggleSelectAll(): void
    {
        // Récupérer tous les IDs des biens correspondant aux filtres (sans pagination)
        $allBiensIds = $this->getBiensQuery()->pluck('NumOrdre')->toArray();
        
        // Vérifier si tous les biens sont déjà sélectionnés
        $allSelected = !empty($allBiensIds) && 
                       count($this->selectedBiens) === count($allBiensIds) &&
                       empty(array_diff($allBiensIds, $this->selectedBiens));

        if ($allSelected) {
            // Tout désélectionner
            $this->selectedBiens = [];
        } else {
            // Tout sélectionner (fusionner avec les biens déjà sélectionnés)
            $this->selectedBiens = array_unique(array_merge($this->selectedBiens, $allBiensIds));
        }
    }

    /**
     * Supprime un bien
     */
    public function deleteBien($bienId): void
    {
        // Vérifier que l'utilisateur est admin
        if (!Auth::user()->isAdmin()) {
            session()->flash('error', 'Vous n\'avez pas les permissions nécessaires pour supprimer un bien.');
            return;
        }

        $bien = Gesimmo::find($bienId);

        if ($bien) {
            $bien->delete();
            session()->flash('success', 'L\'immobilisation a été supprimée avec succès.');
            
            // Retirer de la sélection si présent
            $this->selectedBiens = array_diff($this->selectedBiens, [$bienId]);
        } else {
            session()->flash('error', 'Immobilisation introuvable.');
        }
    }

    /**
     * Exporte les biens sélectionnés
     */
    public function exportSelected()
    {
        if (empty($this->selectedBiens)) {
            session()->flash('warning', 'Veuillez sélectionner au moins un bien à exporter.');
            return;
        }

        // Rediriger vers la route d'export avec les IDs sélectionnés en paramètre de requête
        $ids = implode(',', $this->selectedBiens);
        return redirect()->route('biens.export-excel', ['ids' => $ids]);
    }

    /**
     * Construit la requête de base pour les immobilisations
     */
    protected function getBiensQuery()
    {
        $query = Gesimmo::with([
            'designation',
            'categorie',
            'etat',
            'emplacement.localisation',
            'natureJuridique',
            'sourceFinancement'
        ]);

        // Recherche globale
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('NumOrdre', 'like', '%' . $this->search . '%')
                    ->orWhereHas('designation', function ($q2) {
                        $q2->where('designation', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('emplacement', function ($q2) {
                        $q2->where('Emplacement', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Filtre par désignation
        if (!empty($this->filterDesignation)) {
            $query->where('idDesignation', $this->filterDesignation);
        }

        // Filtre par catégorie
        if (!empty($this->filterCategorie)) {
            $query->where('idCategorie', $this->filterCategorie);
        }

        // Filtre hiérarchique par localisation
        if (!empty($this->filterLocalisation)) {
            $query->whereHas('emplacement', function ($q) {
                $q->where('idLocalisation', $this->filterLocalisation);
            });
        }

        // Filtre hiérarchique par affectation
        if (!empty($this->filterAffectation)) {
            $query->whereHas('emplacement', function ($q) {
                $q->where('idAffectation', $this->filterAffectation);
            });
        }

        // Filtre par emplacement (prioritaire sur les filtres hiérarchiques)
        if (!empty($this->filterEmplacement)) {
            $query->where('idEmplacement', $this->filterEmplacement);
        }

        // Filtre par état
        if (!empty($this->filterEtat)) {
            $query->where('idEtat', $this->filterEtat);
        }

        // Filtre par nature juridique
        if (!empty($this->filterNatJur)) {
            $query->where('idNatJur', $this->filterNatJur);
        }

        // Filtre par source de financement
        if (!empty($this->filterSF)) {
            $query->where('idSF', $this->filterSF);
        }

        // Filtre par année d'acquisition
        if (!empty($this->filterDateAcquisition)) {
            $query->where('DateAcquisition', (int)$this->filterDateAcquisition);
        }

        // Tri
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    /**
     * Rendu du composant
     */
    public function render()
    {
        $biens = $this->getBiensQuery()->paginate($this->perPage);

        return view('livewire.biens.liste-biens', [
            'biens' => $biens,
        ]);
    }
}

