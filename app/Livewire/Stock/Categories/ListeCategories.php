<?php

namespace App\Livewire\Stock\Categories;

use App\Models\StockCategorie;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class ListeCategories extends Component
{
    use WithPagination;

    public $search = '';
    public $confirmingDeletion = false;
    public $categorieToDelete = null;

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
        $this->categorieToDelete = $id;
        $this->confirmingDeletion = true;
    }

    public function cancelDelete()
    {
        $this->confirmingDeletion = false;
        $this->categorieToDelete = null;
    }

    public function delete()
    {
        $categorie = StockCategorie::find($this->categorieToDelete);

        if ($categorie) {
            if ($categorie->produits()->count() > 0) {
                session()->flash('error', 'Impossible de supprimer cette catégorie car elle contient des produits.');
                $this->cancelDelete();
                return;
            }

            $categorie->delete();
            session()->flash('success', 'Catégorie supprimée avec succès.');
        }

        $this->cancelDelete();
    }

    public function render()
    {
        $categories = StockCategorie::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('libelle', 'like', '%' . $this->search . '%')
                      ->orWhere('observations', 'like', '%' . $this->search . '%');
                });
            })
            ->withCount('produits')
            ->orderBy('libelle')
            ->paginate(15);

        return view('livewire.stock.categories.liste-categories', [
            'categories' => $categories,
        ]);
    }
}
