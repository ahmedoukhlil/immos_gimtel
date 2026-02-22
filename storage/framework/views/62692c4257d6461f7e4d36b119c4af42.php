<div>
    <?php
        $isAdmin = auth()->user()->isAdmin();
    ?>

    
    <div class="mb-6">
        <nav class="flex mb-4" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <a href="<?php echo e(route('biens.index')); ?>" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">Immobilisations</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2"><?php echo e($bien->NumOrdre); ?></span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                    <?php echo e($bien->code_formate ?? 'NumOrdre: ' . $bien->NumOrdre); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->categorie): ?>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <?php echo e($bien->categorie->Categorie); ?>

                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    <?php echo e($bien->designation ? $bien->designation->designation : 'N/A'); ?>

                </p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2">
                <a href="<?php echo e(route('biens.index')); ?>"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Retour
                </a>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAdmin && !$editing): ?>
                    <button wire:click="startEditing"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Modifier
                    </button>
                    <a href="<?php echo e(route('biens.edit', $bien)); ?>"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        Formulaire complet
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editing): ?>
                    <button wire:click="saveDetails"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Enregistrer
                    </button>
                    <button wire:click="cancelEditing"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <button 
                    id="btn-print-etiquette-<?php echo e($bien->NumOrdre); ?>"
                    data-bien-id="<?php echo e($bien->NumOrdre); ?>"
                    data-code-value="<?php echo e($bien->NumOrdre); ?>"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Imprimer
                </button>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAdmin && !$editing): ?>
                    <button 
                        wire:click="supprimer"
                        wire:confirm="Supprimer ce bien ? Cette action est irréversible."
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Supprimer
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editing): ?>
        <div class="mb-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3 flex items-center gap-2">
            <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            <span class="text-sm text-yellow-800 font-medium">Mode modification actif</span>
            <span class="text-xs text-yellow-600">- Modifiez les champs ci-dessous puis cliquez sur "Enregistrer"</span>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        
        <div class="lg:col-span-3 space-y-6">
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations générales</h2>
                
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            <?php echo e($bien->designation ? $bien->designation->designation : 'N/A'); ?>

                        </h3>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->categorie): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <?php echo e($bien->categorie->Categorie); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->etat): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <?php echo e($bien->etat->Etat); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->natureJuridique): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                <?php echo e($bien->natureJuridique->NatJur); ?>

                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        
                        <div>
                            <p class="text-sm text-gray-500">Année d'acquisition</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editing): ?>
                                <input type="number" wire:model="editDateAcquisition" min="1900" max="<?php echo e(now()->year + 1); ?>" placeholder="<?php echo e(now()->year); ?>"
                                    class="mt-1 block w-full px-3 py-1.5 border border-yellow-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-yellow-50">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editDateAcquisition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                <p class="text-sm font-medium text-gray-900">
                                    <?php if($bien->DateAcquisition && $bien->DateAcquisition > 1970): ?>
                                        <?php echo e($bien->DateAcquisition); ?>

                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->age && $this->age > 0): ?>
                                            <span class="text-gray-500">(<?php echo e($this->age); ?> an<?php echo e($this->age > 1 ? 's' : ''); ?>)</span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">Non renseignée</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Numéro d'ordre</p>
                            <p class="text-2xl font-bold text-indigo-600"><?php echo e($bien->NumOrdre); ?></p>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                        <div>
                            <p class="text-sm text-gray-500">Valeur d'acquisition (MRU)</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editing): ?>
                                <input type="number" wire:model="editValeurAcquisition" min="0" step="0.01" placeholder="Ex: 150000"
                                    class="mt-1 block w-full px-3 py-1.5 border border-yellow-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-yellow-50">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editValeurAcquisition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                <p class="text-sm font-medium text-gray-900">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->valeur_acquisition): ?>
                                        <?php echo e(number_format($bien->valeur_acquisition, 2, ',', ' ')); ?> MRU
                                    <?php else: ?>
                                        <span class="text-gray-400">Non renseignée</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date de mise en service</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editing): ?>
                                <input type="date" wire:model="editDateMiseEnService"
                                    class="mt-1 block w-full px-3 py-1.5 border border-yellow-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-yellow-50">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editDateMiseEnService'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php else: ?>
                                <p class="text-sm font-medium text-gray-900">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->date_mise_en_service): ?>
                                        <?php echo e($bien->date_mise_en_service->format('d/m/Y')); ?>

                                    <?php else: ?>
                                        <span class="text-gray-400">Non renseignée</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500 mb-2">Code d'immobilisation</p>
                        <div class="flex items-center gap-2">
                            <code class="px-3 py-2 bg-gray-100 rounded-lg text-sm font-mono"><?php echo e($bien->code_formate ?? 'N/A'); ?></code>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->code_formate): ?>
                                <button 
                                    onclick="navigator.clipboard.writeText('<?php echo e($bien->code_formate); ?>'); alert('Code copié !');"
                                    class="p-2 text-gray-500 hover:text-gray-700 transition-colors"
                                    title="Copier">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Emplacement</h2>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->emplacement): ?>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->emplacement->localisation): ?>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Localisation</p>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($bien->emplacement->localisation->Localisation); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->emplacement->localisation->CodeLocalisation): ?>
                                    <p class="text-xs text-gray-500 mt-1">Code: <?php echo e($bien->emplacement->localisation->CodeLocalisation); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->emplacement->affectation): ?>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Affectation</p>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($bien->emplacement->affectation->Affectation); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->emplacement->affectation->CodeAffectation): ?>
                                    <p class="text-xs text-gray-500 mt-1">Code: <?php echo e($bien->emplacement->affectation->CodeAffectation); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Emplacement</p>
                            <p class="text-sm font-medium text-gray-900"><?php echo e($bien->emplacement->Emplacement); ?></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->emplacement->CodeEmplacement): ?>
                                <p class="text-xs text-gray-500 mt-1">Code: <?php echo e($bien->emplacement->CodeEmplacement); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-gray-500 italic">Aucun emplacement assigné</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Observations</h2>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editing): ?>
                    <textarea wire:model="editObservations" rows="4" placeholder="Saisir des observations..."
                        class="block w-full px-3 py-2 border border-yellow-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-yellow-50"></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editObservations'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-xs text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php elseif($bien->Observations): ?>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap"><?php echo e($bien->Observations); ?></p>
                <?php else: ?>
                    <p class="text-sm text-gray-500 italic">Aucune observation</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div class="lg:col-span-2 space-y-6">
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Code-barres Code 128</h2>
                
                <div class="text-center mb-4">
                    <div id="barcode-container-<?php echo e($bien->NumOrdre); ?>"
                        class="w-full mx-auto cursor-pointer hover:opacity-80 transition-opacity bg-white p-2 rounded border border-gray-200"
                        onclick="document.getElementById('barcode-modal').classList.remove('hidden')"
                        title="Cliquez pour agrandir">
                        <svg id="barcode-svg-<?php echo e($bien->NumOrdre); ?>" width="100%" height="40" style="max-width: 100%; display: block;"></svg>
                    </div>
                    <p class="text-xs text-gray-500 mt-1.5">Code 128 - 89mm x 36mm</p>
                    <div class="mt-2 space-y-1">
                        <p class="text-xs text-gray-700 font-mono font-semibold"><?php echo e($bien->NumOrdre); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->code_formate): ?>
                            <p class="text-xs text-gray-600 font-mono"><?php echo e($bien->code_formate); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->designation): ?>
                            <p class="text-xs text-gray-700 font-medium"><?php echo e($bien->designation->designation); ?></p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <button 
                        id="btn-print-label-<?php echo e($bien->NumOrdre); ?>"
                        data-bien-id="<?php echo e($bien->NumOrdre); ?>"
                        data-code-value="<?php echo e($bien->NumOrdre); ?>"
                        class="w-full px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                        Imprimer étiquette
                    </button>
                </div>
            </div>

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informations complémentaires</h2>
                
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->natureJuridique): ?>
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs text-gray-500">Nature Juridique</p>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($bien->natureJuridique->NatJur); ?></p>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->natureJuridique->CodeNatJur): ?>
                                <span class="text-xs text-gray-400 font-mono"><?php echo e($bien->natureJuridique->CodeNatJur); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->sourceFinancement): ?>
                        <div class="flex justify-between items-start pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-xs text-gray-500">Source de Financement</p>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($bien->sourceFinancement->SourceFin); ?></p>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->sourceFinancement->CodeSourceFin): ?>
                                <span class="text-xs text-gray-400 font-mono"><?php echo e($bien->sourceFinancement->CodeSourceFin); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->categorie && $bien->categorie->duree_amortissement): ?>
                        <div class="flex justify-between items-start pt-3 border-t border-gray-100">
                            <div>
                                <p class="text-xs text-gray-500">Type CGI</p>
                                <p class="text-sm font-medium text-gray-900"><?php echo e($bien->categorie->type_cgi ?? '-'); ?></p>
                            </div>
                            <span class="text-xs text-gray-400"><?php echo e($bien->categorie->duree_amortissement); ?> ans / <?php echo e($bien->categorie->taux_amortissement); ?>%</span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->age): ?>
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500">Âge</p>
                            <p class="text-sm font-medium text-gray-900"><?php echo e($this->age); ?> an<?php echo e($this->age > 1 ? 's' : ''); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Actions rapides</h2>
                
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->emplacement): ?>
                        <a href="<?php echo e(route('biens.index', ['filterEmplacement' => $bien->idEmplacement])); ?>"
                            class="block w-full text-left px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Biens de cet emplacement
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->categorie): ?>
                        <a href="<?php echo e(route('biens.index', ['filterCategorie' => $bien->idCategorie])); ?>"
                            class="block w-full text-left px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Biens de cette catégorie
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->designation): ?>
                        <a href="<?php echo e(route('biens.index', ['filterDesignation' => $bien->idDesignation])); ?>"
                            class="block w-full text-left px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            Biens de cette désignation
                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Amortissement
        </h2>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->resumeAmortissement): ?>
            <?php $resume = $this->resumeAmortissement; ?>

            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-xs text-blue-600 font-medium">Valeur d'acquisition</p>
                    <p class="text-lg font-bold text-blue-900"><?php echo e(number_format($resume['valeur_acquisition'], 2, ',', ' ')); ?></p>
                    <p class="text-xs text-blue-500">MRU</p>
                </div>
                <div class="bg-amber-50 rounded-lg p-4">
                    <p class="text-xs text-amber-600 font-medium">Dotation annuelle</p>
                    <p class="text-lg font-bold text-amber-900"><?php echo e(number_format($resume['dotation_annuelle'], 2, ',', ' ')); ?></p>
                    <p class="text-xs text-amber-500">MRU/an (<?php echo e($resume['taux']); ?>%)</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-xs text-green-600 font-medium">VNC actuelle</p>
                    <p class="text-lg font-bold text-green-900"><?php echo e(number_format($resume['vnc'], 2, ',', ' ')); ?></p>
                    <p class="text-xs text-green-500">MRU</p>
                </div>
                <div class="<?php echo e($resume['est_totalement_amorti'] ? 'bg-red-50' : 'bg-purple-50'); ?> rounded-lg p-4">
                    <p class="text-xs <?php echo e($resume['est_totalement_amorti'] ? 'text-red-600' : 'text-purple-600'); ?> font-medium">Statut</p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($resume['est_totalement_amorti']): ?>
                        <p class="text-lg font-bold text-red-900">Totalement amorti</p>
                    <?php else: ?>
                        <p class="text-lg font-bold text-purple-900"><?php echo e($resume['annees_restantes']); ?> an<?php echo e($resume['annees_restantes'] > 1 ? 's' : ''); ?></p>
                        <p class="text-xs text-purple-500">restant<?php echo e($resume['annees_restantes'] > 1 ? 's' : ''); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            
            <div class="mb-6">
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Progression : <?php echo e($resume['pourcentage_amorti']); ?>%</span>
                    <span><?php echo e($resume['date_debut']); ?> - <?php echo e($resume['date_fin']); ?></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="h-3 rounded-full transition-all duration-500 <?php echo e($resume['est_totalement_amorti'] ? 'bg-red-500' : 'bg-indigo-600'); ?>" style="width: <?php echo e(min($resume['pourcentage_amorti'], 100)); ?>%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span><?php echo e($resume['type_cgi']); ?> - <?php echo e($resume['duree']); ?> ans</span>
                    <span>Cumul : <?php echo e(number_format($resume['amortissement_cumule'], 2, ',', ' ')); ?> MRU</span>
                </div>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->tableauAmortissement): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-medium text-gray-500">Exercice</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Valeur amortissable</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Taux</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Dotation</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">Amort. cumulé</th>
                                <th class="px-4 py-3 text-right font-medium text-gray-500">VNC</th>
                                <th class="px-4 py-3 text-center font-medium text-gray-500">Note</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->tableauAmortissement['lignes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ligne): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($ligne['exercice'] == now()->year ? 'bg-indigo-50 font-semibold' : ''); ?>">
                                    <td class="px-4 py-2 text-gray-900"><?php echo e($ligne['exercice']); ?></td>
                                    <td class="px-4 py-2 text-right text-gray-700"><?php echo e(number_format($ligne['valeur_amortissable'], 2, ',', ' ')); ?></td>
                                    <td class="px-4 py-2 text-right text-gray-700"><?php echo e($ligne['taux']); ?>%</td>
                                    <td class="px-4 py-2 text-right text-gray-900 font-medium"><?php echo e(number_format($ligne['dotation'], 2, ',', ' ')); ?></td>
                                    <td class="px-4 py-2 text-right text-gray-700"><?php echo e(number_format($ligne['cumul'], 2, ',', ' ')); ?></td>
                                    <td class="px-4 py-2 text-right text-gray-900 font-medium"><?php echo e(number_format($ligne['vnc'], 2, ',', ' ')); ?></td>
                                    <td class="px-4 py-2 text-center text-xs text-gray-500"><?php echo e($ligne['prorata'] ?? ''); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php else: ?>
            <div class="bg-gray-50 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-gray-500 font-medium">Amortissement non disponible</p>
                <p class="text-xs text-gray-400 mt-1"><?php echo e($this->raisonNonAmortissable); ?></p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isAdmin && !$editing && (!$bien->valeur_acquisition || !$bien->date_mise_en_service)): ?>
                    <button wire:click="startEditing" class="mt-3 inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-100 rounded-lg hover:bg-indigo-200 transition-colors">
                        Renseigner les informations financières
                    </button>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div id="barcode-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" onclick="this.classList.add('hidden')">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black bg-opacity-50" onclick="document.getElementById('barcode-modal').classList.add('hidden')"></div>
            <div class="relative bg-white rounded-lg p-8 max-w-2xl" onclick="event.stopPropagation()">
                <button 
                    onclick="document.getElementById('barcode-modal').classList.add('hidden')"
                    class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <div class="w-full flex items-center justify-center bg-white p-6 rounded-lg border border-gray-200">
                    <div id="barcode-modal-placeholder-<?php echo e($bien->NumOrdre); ?>" style="min-height: 120px; display: flex; align-items: center; justify-content: center; width: 100%; max-width: 600px;">
                        <svg id="barcode-svg-modal-<?php echo e($bien->NumOrdre); ?>" width="100%" height="140" style="max-width: 100%; display: block;"></svg>
                    </div>
                </div>
                <div class="text-center mt-4 space-y-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->code_formate): ?>
                        <p class="text-xs font-mono text-gray-600"><?php echo e($bien->code_formate); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bien->designation): ?>
                        <p class="text-xs font-medium text-gray-700"><?php echo e($bien->designation->designation); ?></p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('success')): ?>
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            x-transition
            class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(session()->has('error')): ?>
        <div 
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 5000)"
            x-transition
            class="fixed bottom-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($bien) && $bien->NumOrdre): ?>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <script>
        const BIEN_ID = <?php echo e($bien->NumOrdre); ?>;
        const CODE_VALUE = <?php echo e($bien->NumOrdre); ?>;
        window.CODE_FORMATE = <?php echo json_encode($bien->code_formate ?? '', 15, 512) ?>;
        window.DESIGNATION = <?php echo json_encode($bien->designation->designation ?? '', 15, 512) ?>;

        function generateBarcode(bienId, codeValue) {
            if (!codeValue || String(codeValue).trim() === '' || typeof JsBarcode === 'undefined') return false;
            
            const code = String(codeValue).trim();
            
            const svgMain = document.getElementById('barcode-svg-' + bienId);
            if (svgMain) {
                try {
                    JsBarcode(svgMain, code, { format: "CODE128", width: 1.5, height: 35, displayValue: false, background: "#ffffff", lineColor: "#000000", margin: 4 });
                } catch (e) { return false; }
            }
            
            const svgModal = document.getElementById('barcode-svg-modal-' + bienId);
            if (svgModal) {
                try {
                    JsBarcode(svgModal, code, { format: "CODE128", width: 3, height: 100, displayValue: false, background: "#ffffff", lineColor: "#000000", margin: 15 });
                } catch (e) {}
            }
            
            return true;
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            const btnPrintLabel = document.getElementById('btn-print-label-' + BIEN_ID);
            if (btnPrintLabel) {
                btnPrintLabel.addEventListener('click', function() {
                    if (typeof window.imprimerEtiquette === 'function') window.imprimerEtiquette(BIEN_ID, CODE_VALUE);
                });
            }
            
            const btnPrintEtiquette = document.getElementById('btn-print-etiquette-' + BIEN_ID);
            if (btnPrintEtiquette) {
                btnPrintEtiquette.addEventListener('click', function() {
                    if (typeof window.imprimerEtiquette === 'function') window.imprimerEtiquette(BIEN_ID, CODE_VALUE);
                });
            }
        });
        
        window.addEventListener('load', function() {
            setTimeout(function() {
                if (typeof JsBarcode !== 'undefined') generateBarcode(BIEN_ID, CODE_VALUE);
            }, 300);
        });

        window.imprimerEtiquette = async function(bienId, codeValue) {
            try {
                if (typeof JsBarcode === 'undefined' || typeof window.jspdf === 'undefined') {
                    alert('Erreur: bibliothèques non chargées. Veuillez recharger la page.');
                    return;
                }

                const { jsPDF } = window.jspdf;
                const codeStr = String(codeValue).trim();
                if (!codeStr) throw new Error('Code vide');
                
                const labelWidthMm = 89, labelHeightMm = 36;
                
                const tempCanvas = document.createElement('canvas');
                tempCanvas.style.position = 'absolute';
                tempCanvas.style.left = '-9999px';
                document.body.appendChild(tempCanvas);
                
                JsBarcode(tempCanvas, codeStr, { format: "CODE128", width: 2, height: 50, displayValue: false, background: "#ffffff", lineColor: "#000000", margin: 0 });
                
                const mmToPx = 3.779527559;
                const pdfCanvas = document.createElement('canvas');
                pdfCanvas.width = labelWidthMm * mmToPx;
                pdfCanvas.height = labelHeightMm * mmToPx;
                const pdfCtx = pdfCanvas.getContext('2d');
                pdfCtx.fillStyle = '#ffffff';
                pdfCtx.fillRect(0, 0, pdfCanvas.width, pdfCanvas.height);
                
                const barcodeWidthMm = Math.min(labelWidthMm - 10, (tempCanvas.width / mmToPx));
                const barcodeHeightMm = (tempCanvas.height / mmToPx);
                const barcodeX = (labelWidthMm - barcodeWidthMm) / 2;
                const barcodeY = (labelHeightMm - barcodeHeightMm - 6) / 2;
                
                pdfCtx.drawImage(tempCanvas, barcodeX * mmToPx, barcodeY * mmToPx, barcodeWidthMm * mmToPx, barcodeHeightMm * mmToPx);
                document.body.removeChild(tempCanvas);
                
                const pdf = new jsPDF({ orientation: 'landscape', unit: 'mm', format: [labelHeightMm, labelWidthMm] });
                const imgData = pdfCanvas.toDataURL('image/png', 1.0);
                pdf.addImage(imgData, 'PNG', 0, 0, labelWidthMm, labelHeightMm);
                
                let currentY = labelHeightMm - 8;
                const codeFormate = window.CODE_FORMATE || '';
                if (codeFormate.trim()) {
                    pdf.setFontSize(7);
                    pdf.setFont('courier', 'normal');
                    pdf.text(codeFormate, labelWidthMm / 2, currentY, { align: 'center' });
                    currentY += 5;
                }
                
                const designation = window.DESIGNATION || '';
                if (designation.trim()) {
                    pdf.setFontSize(6);
                    pdf.setFont('helvetica', 'normal');
                    const lines = pdf.splitTextToSize(designation, labelWidthMm - 4);
                    lines.forEach(line => {
                        if (currentY < labelHeightMm - 1) {
                            pdf.text(line, labelWidthMm / 2, currentY, { align: 'center' });
                            currentY += 2.5;
                        }
                    });
                }
                
                const pdfBlob = pdf.output('blob');
                const pdfUrl = URL.createObjectURL(pdfBlob);
                const printWindow = window.open(pdfUrl, '_blank');
                
                if (printWindow) {
                    printWindow.onload = function() {
                        setTimeout(() => { printWindow.print(); setTimeout(() => URL.revokeObjectURL(pdfUrl), 1000); }, 250);
                    };
                } else {
                    pdf.save('etiquette_' + codeValue + '.pdf');
                }
            } catch (error) {
                alert('Erreur: ' + error.message);
            }
        };
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
</div>
<?php /**PATH C:\xampp\htdocs\immos_gimtel\resources\views/livewire/biens/detail-bien.blade.php ENDPATH**/ ?>