<?php

namespace App\Livewire\Stock\Fournisseurs;

use App\Models\StockFournisseur;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app')]
class FormFournisseur extends Component
{
    public $fournisseur = null;
    public $id = null;
    public $libelle = '';
    public $observations = '';

    public function mount($id = null)
    {
        $user = auth()->user();
        if (!$user || !$user->canManageStock()) {
            abort(403, 'Accès non autorisé.');
        }

        if ($id) {
            $this->id = $id;
            $this->fournisseur = StockFournisseur::findOrFail($id);
            $this->libelle = $this->fournisseur->libelle;
            $this->observations = $this->fournisseur->observations ?? '';
        }
    }

    protected function rules()
    {
        return [
            'libelle' => 'required|string|max:255',
            'observations' => 'nullable|string',
        ];
    }

    protected function messages()
    {
        return [
            'libelle.required' => 'Le nom du fournisseur est obligatoire.',
            'libelle.max' => 'Le nom du fournisseur ne peut pas dépasser 255 caractères.',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->fournisseur) {
            $this->fournisseur->update($validated);
            session()->flash('success', 'Fournisseur modifié avec succès.');
        } else {
            StockFournisseur::create($validated);
            session()->flash('success', 'Fournisseur créé avec succès.');
        }

        return redirect()->route('stock.fournisseurs.index');
    }

    public function cancel()
    {
        return redirect()->route('stock.fournisseurs.index');
    }

    public function render()
    {
        return view('livewire.stock.fournisseurs.form-fournisseur');
    }
}
