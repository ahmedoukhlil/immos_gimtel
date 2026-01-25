# 🔄 Vue de Transfert d'Immobilisation

## ✅ Vue créée

Une interface complète pour transférer des immobilisations vers un nouvel emplacement (localisation, affectation, emplacement).

## 📍 Accès

**URL** : `/biens/transfert`  
**Route** : `biens.transfert`  
**Accès** : Admin + Agent (via `canManageInventaire()`)

## 🎯 Fonctionnalités

### 1. **Sélection de l'immobilisation**
- Recherche avec composant `SearchableSelect`
- Affichage : Désignation (Ordre: XXX) - Emplacement [Localisation]
- Chargement automatique de l'emplacement actuel

### 2. **Affichage de l'emplacement actuel**
- Carte bleue avec informations complètes :
  - Localisation actuelle (avec code)
  - Affectation actuelle (avec code)
  - Emplacement actuel (avec code)

### 3. **Sélection du nouvel emplacement (hiérarchique)**
- **Localisation** → **Affectation** → **Emplacement**
- Filtrage automatique :
  - Affectation dépend de la Localisation
  - Emplacement dépend de l'Affectation
- Validation en temps réel
- Messages d'aide si sélection incorrecte

### 4. **Validation et sécurité**
- ✅ Vérifie que l'immobilisation existe
- ✅ Vérifie que le nouvel emplacement est différent
- ✅ Vérifie la cohérence hiérarchique (localisation → affectation → emplacement)
- ✅ Avertissement si même emplacement sélectionné

### 5. **Transfert**
- Mise à jour de `idEmplacement` dans la table `gesimmo`
- Message de confirmation avec détails (ancien → nouveau)
- Réinitialisation du formulaire après succès

## 🎨 Interface utilisateur

### Structure de la vue

```
┌─────────────────────────────────────────┐
│  🔄 Transfert d'immobilisation          │
├─────────────────────────────────────────┤
│  1. Sélection immobilisation            │
│     [Recherche...]                      │
├─────────────────────────────────────────┤
│  2. Emplacement actuel (carte bleue)    │
│     📍 Localisation: ...                 │
│     🏢 Affectation: ...                 │
│     🏠 Emplacement: ...                 │
├─────────────────────────────────────────┤
│  ➡️ Nouvel emplacement                  │
├─────────────────────────────────────────┤
│  3. Localisation [Select]               │
│  4. Affectation [Select] (dépendant)    │
│  5. Emplacement [Select] (dépendant)    │
├─────────────────────────────────────────┤
│  [Annuler] [Effectuer le transfert]     │
└─────────────────────────────────────────┘
```

### Couleurs et styles
- **Carte actuelle** : Bleu (`bg-blue-50`)
- **Séparateur** : Flèche "➡️ Nouvel emplacement"
- **Avertissement** : Jaune si même emplacement
- **Bouton transfert** : Indigo avec icône de flèche

## 🔧 Composant Livewire

### Fichier
`app/Livewire/Biens/TransfertBien.php`

### Propriétés
- `bienId` : ID de l'immobilisation sélectionnée
- `bienSelectionne` : Objet Gesimmo chargé
- `idLocalisation` : Nouvelle localisation
- `idAffectation` : Nouvelle affectation
- `idEmplacement` : Nouvel emplacement
- `emplacementActuel` : Emplacement actuel de l'immobilisation
- `localisationActuelle` : Localisation actuelle
- `affectationActuelle` : Affectation actuelle

### Méthodes principales
- `updatedBienId()` : Charge l'immobilisation et son emplacement actuel
- `updatedIdLocalisation()` : Réinitialise affectation/emplacement
- `updatedIdAffectation()` : Réinitialise emplacement
- `transferer()` : Effectue le transfert avec validations
- `getBienOptionsProperty()` : Options pour rechercher les immobilisations
- `getLocalisationOptionsProperty()` : Options localisations
- `getAffectationOptionsProperty()` : Options affectations (filtrées)
- `getEmplacementOptionsProperty()` : Options emplacements (filtrés)

## 🛣️ Route ajoutée

```php
Route::get('/transfert', \App\Livewire\Biens\TransfertBien::class)->name('transfert');
```

## 🧭 Navigation

Un lien "🔄 Transfert Immobilisation" a été ajouté dans le menu Immobilisations, juste après "Ajouter Immobilisation".

## 🧪 Workflow de transfert

### Exemple : Transférer une chaise

1. **Accéder à la vue** : `/biens/transfert`
2. **Sélectionner l'immobilisation** :
   - Rechercher "Chaise"
   - Sélectionner "Chaise (Ordre: 1001) - Bureau A [Bâtiment 1]"
3. **Voir l'emplacement actuel** :
   - Localisation : Bâtiment 1
   - Affectation : Bureau A
   - Emplacement : Bureau A
4. **Sélectionner le nouvel emplacement** :
   - Localisation : Bâtiment 2
   - Affectation : Bureau B (filtré automatiquement)
   - Emplacement : Bureau B (filtré automatiquement)
5. **Valider** : Cliquer "Effectuer le transfert"
6. ✅ **Résultat** : Message "Immobilisation transférée avec succès de 'Bureau A' vers 'Bureau B'."

## 🔒 Validations

### Côté serveur
- ✅ Immobilisation existe
- ✅ Localisation existe
- ✅ Affectation existe et appartient à la localisation
- ✅ Emplacement existe et appartient à l'affectation
- ✅ Nouvel emplacement différent de l'actuel

### Côté client
- ✅ Champs obligatoires marqués
- ✅ Messages d'erreur en temps réel
- ✅ Désactivation des champs dépendants
- ✅ Avertissement si même emplacement

## 📊 Base de données

### Table modifiée
- **`gesimmo`** : Colonne `idEmplacement` mise à jour

### Relations utilisées
- `Gesimmo` → `Emplacement` (via `idEmplacement`)
- `Emplacement` → `Affectation` (via `idAffectation`)
- `Affectation` → `LocalisationImmo` (via `idLocalisation`)

## 🎯 Cas d'usage

### Cas 1 : Déplacement d'un bureau
```
Chaise du Bureau A → Bureau B
```

### Cas 2 : Réorganisation
```
Table du Bâtiment 1 → Bâtiment 2
```

### Cas 3 : Correction d'erreur
```
Immobilisation mal affectée → Bon emplacement
```

## 🚀 Améliorations futures possibles

1. **Historique des transferts** : Logger qui a transféré quoi et quand
2. **Transfert en masse** : Transférer plusieurs immobilisations à la fois
3. **Notifications** : Notifier les responsables des emplacements
4. **Rapport de transfert** : Générer un PDF avec les détails
5. **Validation par responsable** : Workflow d'approbation

## 📝 Fichiers créés/modifiés

### Créés
- ✅ `app/Livewire/Biens/TransfertBien.php`
- ✅ `resources/views/livewire/biens/transfert-bien.blade.php`

### Modifiés
- ✅ `routes/web.php` - Ajout route `biens.transfert`
- ✅ `resources/views/components/layouts/app.blade.php` - Ajout lien menu

## 🎉 Résultat

Une interface complète et intuitive pour transférer des immobilisations vers un nouvel emplacement avec validation hiérarchique et sécurité renforcée ! 🚀
