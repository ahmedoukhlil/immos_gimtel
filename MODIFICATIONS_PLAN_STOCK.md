# Modifications apportées au Plan de Gestion de Stock

## ✅ Changements effectués

### 1. 🏪 Ajout de la table `stock_magasins`

**Nouvelle table** placée en premier dans la liste des tables à créer :

```sql
CREATE TABLE stock_magasins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    magasin VARCHAR(255) NOT NULL COMMENT 'Nom du magasin',
    localisation VARCHAR(255) NOT NULL COMMENT 'Localisation du magasin',
    observations TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

**Objectif** : Gérer plusieurs magasins de stockage avec leur localisation

**Exemples de données** :
- Magasin : "Magasin Central", Localisation : "Bâtiment A, Rez-de-chaussée"
- Magasin : "Magasin Annexe", Localisation : "Bâtiment B, 1er étage"
- Magasin : "Entrepôt Principal", Localisation : "Zone Industrielle"

### 2. 🔗 Liaison des produits aux magasins

**Modification de la table `stock_produits`** :
- ✅ Ajout du champ `magasin_id` (FK → stock_magasins.id)
- ✅ Le champ `stockage` devient l'emplacement précis **dans** le magasin
  - Avant : `stockage` = "Magasin Central - Étagère A3"
  - Après : 
    - `magasin_id` = 1 (Magasin Central)
    - `stockage` = "Étagère A3"

**Avantages** :
- 📊 Statistiques par magasin
- 🔍 Filtrage rapide des produits par magasin
- 📦 Meilleure organisation du stock
- 📈 Tableau de bord avec répartition par magasin

### 3. 👥 Utilisation du RBAC avec la table `users` existante

**Avant** : Création de permissions séparées

**Après** : Utilisation de la table `users` existante avec le champ `role`

#### Structure de la table users (existante)
```php
- idUser (PK)
- users (nom d'utilisateur)
- mdp (mot de passe)
- role ('admin' ou 'agent')
```

#### Permissions détaillées par rôle

**👨‍💼 Admin (role = 'admin')**
- ✅ **Magasins** : CRUD complet
- ✅ **Catégories** : CRUD complet
- ✅ **Fournisseurs** : CRUD complet
- ✅ **Demandeurs** : CRUD complet
- ✅ **Produits** : CRUD complet
- ✅ **Entrées** : Créer, voir toutes
- ✅ **Sorties** : Créer, voir toutes
- ✅ **Dashboard** : Vue complète
- ✅ **Utilisateurs** : Gestion complète

**👤 Agent (role = 'agent')**
- 👁️ **Magasins** : Lecture seule
- 👁️ **Catégories** : Lecture seule
- 👁️ **Fournisseurs** : Lecture seule
- 👁️ **Demandeurs** : Lecture seule
- 👁️ **Produits** : Lecture seule (consulter stock)
- 👁️ **Entrées** : Lecture seule
- ✅ **Sorties** : Créer et voir ses propres sorties
- 👁️ **Dashboard** : Vue limitée

#### Implémentation dans le modèle User

```php
// app/Models/User.php

/**
 * Vérifie si l'utilisateur est admin
 */
public function isAdmin(): bool
{
    return $this->role === 'admin';
}

/**
 * Vérifie si l'utilisateur est agent
 */
public function isAgent(): bool
{
    return $this->role === 'agent';
}

/**
 * Vérifie si l'utilisateur peut gérer le stock (CRUD références)
 */
public function canManageStock(): bool
{
    return $this->isAdmin();
}

/**
 * Vérifie si l'utilisateur peut créer des sorties
 */
public function canCreateSortie(): bool
{
    return $this->isAdmin() || $this->isAgent();
}

/**
 * Vérifie si l'utilisateur peut créer des entrées
 */
public function canCreateEntree(): bool
{
    return $this->isAdmin();
}
```

#### Protection des composants Livewire

```php
// Composants réservés aux Admins
public function mount()
{
    if (!auth()->user()->isAdmin()) {
        abort(403, 'Accès non autorisé');
    }
}

// Composants accessibles aux Agents et Admins
public function mount()
{
    if (!auth()->user()->canCreateSortie()) {
        abort(403, 'Accès non autorisé');
    }
}
```

### 4. 📊 Nouveaux composants et routes

**Ajout des composants Magasins** :
1. `Stock/Magasins/ListeMagasins`
2. `Stock/Magasins/FormMagasin`

**Nouvelles routes** :
```php
// Magasins (Admin uniquement)
Route::get('/stock/magasins', ListeMagasins::class)
    ->name('stock.magasins.index')
    ->middleware('auth');
    
Route::get('/stock/magasins/create', FormMagasin::class)
    ->name('stock.magasins.create')
    ->middleware('auth');
    
Route::get('/stock/magasins/{id}/edit', FormMagasin::class)
    ->name('stock.magasins.edit')
    ->middleware('auth');
```

### 5. 🗺️ Navigation mise à jour

```
Dashboard
├── Immobilisations
├── Inventaire
├── 📦 Stock (nouveau)
│   ├── Dashboard Stock
│   ├── Produits
│   ├── Entrées
│   ├── Sorties
│   └── Paramètres (Admin uniquement) ← MIS À JOUR
│       ├── Magasins ← NOUVEAU
│       ├── Catégories
│       ├── Fournisseurs
│       └── Demandeurs
├── Localisations
└── Utilisateurs (Admin uniquement) ← PRÉCISION AJOUTÉE
```

### 6. 📈 Index base de données

**Nouveaux index ajoutés** :
```sql
-- Index pour la relation produit -> magasin
CREATE INDEX idx_produit_magasin ON stock_produits(magasin_id);

-- Index pour traçabilité (who created?)
CREATE INDEX idx_entree_created_by ON stock_entrees(created_by);
CREATE INDEX idx_sortie_created_by ON stock_sorties(created_by);
```

### 7. 🔄 Ordre d'implémentation mis à jour

**Phase 2 - Références** : Ajout de Magasins en premier
- ✅ CRUD Magasins (nouveau)
- ✅ CRUD Catégories
- ✅ CRUD Fournisseurs
- ✅ CRUD Demandeurs

**Phase 6 - Permissions RBAC** (nouvelle phase)
- ✅ Ajouter méthodes helpers dans modèle User
- ✅ Protéger les routes selon les rôles
- ✅ Adapter les vues selon les permissions

## 📊 Nouvelles fonctionnalités

### Dashboard enrichi
- 📊 Statistiques **par magasin**
- 📈 Graphiques : stock par magasin
- 🏪 Vue d'ensemble de tous les magasins
- 🔴 Alertes de stock par magasin

### Filtres améliorés
- 🏪 Filtrer produits par magasin
- 📦 Voir stock disponible dans chaque magasin
- 📤 Filtrer sorties par magasin
- 📥 Filtrer entrées par magasin

### Traçabilité renforcée
- 👤 Toutes les entrées/sorties liées à un utilisateur (`created_by`)
- 🕒 Historique des actions par utilisateur
- 📊 Rapport d'activité par agent

## 🎯 Résumé des avantages

### Gestion multi-magasins
- ✅ Organisation claire avec plusieurs lieux de stockage
- ✅ Suivi précis de la localisation physique des produits
- ✅ Statistiques et alertes par magasin

### RBAC avec users existants
- ✅ Pas de doublon d'utilisateurs
- ✅ Gestion centralisée des accès
- ✅ Permissions granulaires par rôle
- ✅ Facilite l'administration

### Traçabilité complète
- ✅ Qui a créé chaque entrée/sortie
- ✅ Audit trail complet
- ✅ Responsabilisation des agents

## 📋 Checklist de validation

- [x] Table `stock_magasins` ajoutée au plan
- [x] Champ `magasin_id` ajouté à `stock_produits`
- [x] Section RBAC détaillée avec méthodes helpers
- [x] Composants Magasins ajoutés
- [x] Routes Magasins ajoutées
- [x] Navigation mise à jour
- [x] Index base de données mis à jour
- [x] Ordre d'implémentation ajusté
- [x] Documentation des permissions complète

## 🚀 Prochaine étape

Créer les migrations et modèles pour démarrer l'implémentation !
