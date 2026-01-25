<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Nouvelle entrée de stock</h1>
            <p class="text-gray-500 mt-1">Enregistrez un approvisionnement</p>
        </div>

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="save">
            <div class="bg-white rounded-lg shadow p-6 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="date_entree" class="block text-sm font-medium text-gray-700 mb-1">
                            Date d'entrée <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="date_entree" wire:model="date_entree" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('date_entree') border-red-500 @enderror">
                        @error('date_entree') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="reference_commande" class="block text-sm font-medium text-gray-700 mb-1">Référence commande</label>
                        <input type="text" id="reference_commande" wire:model="reference_commande" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="BC-2026-001">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Produit <span class="text-red-500">*</span>
                    </label>
                    <livewire:components.searchable-select
                        wire:model.live="produit_id"
                        :options="$this->produitOptions"
                        placeholder="Sélectionner un produit"
                        search-placeholder="Rechercher un produit..."
                        no-results-text="Aucun produit trouvé"
                        :key="'produit-select'"
                    />
                    @error('produit_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Fournisseur <span class="text-red-500">*</span>
                    </label>
                    <livewire:components.searchable-select
                        wire:model.live="fournisseur_id"
                        :options="$this->fournisseurOptions"
                        placeholder="Sélectionner un fournisseur"
                        search-placeholder="Rechercher un fournisseur..."
                        no-results-text="Aucun fournisseur trouvé"
                        :key="'fournisseur-select'"
                    />
                    @error('fournisseur_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="quantite" class="block text-sm font-medium text-gray-700 mb-1">
                        Quantité <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="quantite" wire:model="quantite" min="1"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg @error('quantite') border-red-500 @enderror">
                    @error('quantite') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="observations" class="block text-sm font-medium text-gray-700 mb-1">Observations</label>
                    <textarea id="observations" wire:model="observations" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Notes..."></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t">
                    <button type="button" wire:click="cancel" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Annuler</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Enregistrer l'entrée
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
