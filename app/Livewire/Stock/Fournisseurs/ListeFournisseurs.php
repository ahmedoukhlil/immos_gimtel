<?php

namespace App\Livewire\Stock\Fournisseurs;

use App\Models\StockFournisseur;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class ListeFournisseurs extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $fournisseurToDelete = null;

    protected $queryString = ['search'];

    public function mount()
    {
        $user = auth()->user();
        if (!$user || !$user->canManageStock()) {
            abort(403, 'Accès non autorisé.');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($id)
    {
        $this->fournisseurToDelete = $id;
        $this->confirmingDeletion = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->fournisseurToDelete = null;
    }

    public function delete()
    {
        $fournisseur = StockFournisseur::find($this->fournisseurToDelete);

        if ($fournisseur) {
            if ($fournisseur->entrees()->count() > 0) {
                session()->flash('error', 'Impossible de supprimer ce fournisseur car il a des entrées de stock associées.');
                $this->cancelDelete();
                return;
            }

            $fournisseur->delete();
            session()->flash('success', 'Fournisseur supprimé avec succès.');
        }

        $this->cancelDelete();
    }

    public function render()
    {
        $fournisseurs = StockFournisseur::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('libelle', 'like', '%' . $this->search . '%')
                      ->orWhere('observations', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount('entrees')
            ->orderBy('libelle')
            ->paginate(15);

        return view('livewire.stock.fournisseurs.liste-fournisseurs', [
            'fournisseurs' => $fournisseurs,
        ]);
    }
}
