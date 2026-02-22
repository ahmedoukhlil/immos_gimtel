<div>
    <div class="space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tableau des Amortissements</h1>
                
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select wire:model.live="filterCategorie" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Toutes les catégories</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($categorie->idCategorie); ?>"><?php echo e($categorie->Categorie); ?> (<?php echo e($categorie->taux_amortissement); ?>%)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Exercice</label>
                    <select wire:model.live="filterExercice" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value=""><?php echo e(now()->year); ?> (en cours)</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->exercices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ex): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($ex); ?>"><?php echo e($ex); ?><?php echo e($ex == now()->year ? ' (en cours)' : ''); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                
                <div class="flex items-end">
                    <button wire:click="resetFilters" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Réinitialiser les filtres
                    </button>
                </div>
            </div>
        </div>

        
        <?php $totaux = $this->totaux; ?>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <p class="text-xs text-gray-500 font-medium">Exercice</p>
                <p class="text-2xl font-bold text-gray-900"><?php echo e($totaux['exercice']); ?></p>
            </div>
            <div class="bg-blue-50 rounded-lg shadow-sm border border-blue-200 p-4">
                <p class="text-xs text-blue-600 font-medium">Total valeur d'acquisition</p>
                <p class="text-xl font-bold text-blue-900"><?php echo e(number_format($totaux['total_valeur'], 2, ',', ' ')); ?></p>
                <p class="text-xs text-blue-500">MRU</p>
            </div>
            <div class="bg-amber-50 rounded-lg shadow-sm border border-amber-200 p-4">
                <p class="text-xs text-amber-600 font-medium">Total dotations (<?php echo e($totaux['exercice']); ?>)</p>
                <p class="text-xl font-bold text-amber-900"><?php echo e(number_format($totaux['total_dotation'], 2, ',', ' ')); ?></p>
                <p class="text-xs text-amber-500">MRU</p>
            </div>
            <div class="bg-green-50 rounded-lg shadow-sm border border-green-200 p-4">
                <p class="text-xs text-green-600 font-medium">Total VNC</p>
                <p class="text-xl font-bold text-green-900"><?php echo e(number_format($totaux['total_vnc'], 2, ',', ' ')); ?></p>
                <p class="text-xs text-green-500">MRU</p>
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th wire:click="sortBy('NumOrdre')" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">
                                N° Ordre
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'NumOrdre'): ?>
                                    <span><?php echo e($sortDirection === 'asc' ? '&#9650;' : '&#9660;'); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Désignation</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                            <th wire:click="sortBy('valeur_acquisition')" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">
                                Valeur acq.
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'valeur_acquisition'): ?>
                                    <span><?php echo e($sortDirection === 'asc' ? '&#9650;' : '&#9660;'); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </th>
                            <th wire:click="sortBy('date_mise_en_service')" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase cursor-pointer hover:text-gray-700">
                                Mise en service
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortField === 'date_mise_en_service'): ?>
                                    <span><?php echo e($sortDirection === 'asc' ? '&#9650;' : '&#9660;'); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Durée</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Taux</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Dotation <?php echo e($exercice); ?></th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cumul amort.</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">VNC</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Détail</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $biens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bien): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-sm font-medium text-indigo-600"><?php echo e($bien->NumOrdre); ?></td>
                                <td class="px-4 py-3 text-sm text-gray-900"><?php echo e($bien->designation->designation ?? 'N/A'); ?></td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo e($bien->categorie->Categorie ?? 'N/A'); ?>

                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-right font-medium text-gray-900"><?php echo e(number_format($bien->valeur_acquisition, 2, ',', ' ')); ?></td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700"><?php echo e($bien->date_mise_en_service ? $bien->date_mise_en_service->format('d/m/Y') : '-'); ?></td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700"><?php echo e($bien->categorie->duree_amortissement ?? '-'); ?> ans</td>
                                <td class="px-4 py-3 text-sm text-center text-gray-700"><?php echo e($bien->categorie->taux_amortissement ?? '-'); ?>%</td>
                                <td class="px-4 py-3 text-sm text-right font-medium <?php echo e($bien->dotation_exercice > 0 ? 'text-amber-700' : 'text-gray-400'); ?>">
                                    <?php echo e(number_format($bien->dotation_exercice, 2, ',', ' ')); ?>

                                </td>
                                <td class="px-4 py-3 text-sm text-right text-gray-700"><?php echo e(number_format($bien->cumul_exercice, 2, ',', ' ')); ?></td>
                                <td class="px-4 py-3 text-sm text-right font-medium <?php echo e($bien->vnc_exercice > 0 ? 'text-green-700' : 'text-red-600'); ?>">
                                    <?php echo e(number_format($bien->vnc_exercice, 2, ',', ' ')); ?>

                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="<?php echo e(route('biens.show', $bien)); ?>" class="text-indigo-600 hover:text-indigo-800 transition-colors" title="Voir le détail">
                                        <svg class="w-5 h-5 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="11" class="px-4 py-8 text-center text-sm text-gray-500">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <p class="font-medium">Aucune immobilisation amortissable</p>
                                    <p class="text-xs text-gray-400 mt-1">Les biens doivent avoir une valeur d'acquisition >= 50 000 MRU et une date de mise en service.</p>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($biens->hasPages()): ?>
                <div class="px-4 py-3 border-t border-gray-200">
                    <?php echo e($biens->links()); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\immos_gimtel\resources\views/livewire/biens/liste-amortissements.blade.php ENDPATH**/ ?>