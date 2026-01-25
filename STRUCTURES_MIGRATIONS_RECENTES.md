# 📊 Structures des Migrations Récentes

## 1. `2026_01_24_231835_create_historique_transferts_table.php`
**Date** : 24 janvier 2026  
**Description** : Table pour l'historique des transferts d'immobilisations

### Structure de la table `historique_transferts`

```sql
CREATE TABLE historique_transferts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    
    -- Immobilisation transférée
    NumOrdre INT NOT NULL,
    
    -- Ancien emplacement (sauvegardé pour historique)
    ancien_idEmplacement INT NULL,
    ancien_emplacement_libelle VARCHAR(255) NULL,
    ancien_affectation_libelle VARCHAR(255) NULL,
    ancien_localisation_libelle VARCHAR(255) NULL,
    
    -- Nouveau emplacement
    nouveau_idEmplacement INT NOT NULL,
    nouveau_emplacement_libelle VARCHAR(255) NOT NULL,
    nouveau_affectation_libelle VARCHAR(255) NOT NULL,
    nouveau_localisation_libelle VARCHAR(255) NOT NULL,
    
    -- Utilisateur et date
    transfert_par INT NOT NULL,  -- idUser
    date_transfert DATETIME NOT NULL,
    
    -- Raison du transfert (optionnel)
    raison TEXT NULL,
    
    -- ID de groupe pour regrouper les transferts effectués ensemble
    groupe_transfert_id VARCHAR(50) NULL,  -- Format: TRF-YYYYMMDDHHMMSS-XXXXXX
    
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Index
    INDEX idx_numordre (NumOrdre),
    INDEX idx_date_transfert (date_transfert),
    INDEX idx_groupe_transfert (groupe_transfert_id),
    INDEX idx_transfert_par (transfert_par),
    
    -- Clés étrangères
    FOREIGN KEY (NumOrdre) REFERENCES gesimmo(NumOrdre) ON DELETE CASCADE,
    FOREIGN KEY (ancien_idEmplacement) REFERENCES emplacement(idEmplacement) ON DELETE SET NULL,
    FOREIGN KEY (nouveau_idEmplacement) REFERENCES emplacement(idEmplacement) ON DELETE RESTRICT,
    FOREIGN KEY (transfert_par) REFERENCES users(idUser) ON DELETE RESTRICT
);
```

---

## 2. `2026_01_22_134500_create_stock_tables.php`
**Date** : 22 janvier 2026  
**Description** : Tables pour la gestion de stock de consommables

### 2.1 Table `stock_magasins`

```sql
CREATE TABLE stock_magasins (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    magasin VARCHAR(255) NOT NULL COMMENT 'Nom du magasin',
    localisation VARCHAR(255) NOT NULL COMMENT 'Localisation du magasin',
    observations TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 2.2 Table `stock_categories`

```sql
CREATE TABLE stock_categories (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(255) NOT NULL,
    observations TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 2.3 Table `stock_fournisseurs`

```sql
CREATE TABLE stock_fournisseurs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(255) NOT NULL,
    observations TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 2.4 Table `stock_demandeurs`

```sql
CREATE TABLE stock_demandeurs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    poste_service VARCHAR(255) NOT NULL COMMENT 'Poste/Service/Direction',
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### 2.5 Table `stock_produits`

```sql
CREATE TABLE stock_produits (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    libelle VARCHAR(255) NOT NULL,
    categorie_id BIGINT NOT NULL,
    magasin_id BIGINT NOT NULL,
    stock_initial INT DEFAULT 0,
    stock_actuel INT DEFAULT 0,
    seuil_alerte INT DEFAULT 10,
    descriptif TEXT NULL,
    stockage VARCHAR(255) NULL COMMENT 'Emplacement précis dans le magasin',
    observations TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Index
    INDEX idx_produit_categorie (categorie_id),
    INDEX idx_produit_magasin (magasin_id),
    
    -- Clés étrangères
    FOREIGN KEY (categorie_id) REFERENCES stock_categories(id) ON DELETE RESTRICT,
    FOREIGN KEY (magasin_id) REFERENCES stock_magasins(id) ON DELETE RESTRICT
);
```

### 2.6 Table `stock_entrees`

```sql
CREATE TABLE stock_entrees (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    date_entree DATE NOT NULL,
    reference_commande VARCHAR(255) NULL,
    produit_id BIGINT NOT NULL,
    fournisseur_id BIGINT NOT NULL,
    quantite INT NOT NULL,
    observations TEXT NULL,
    created_by INT NOT NULL COMMENT 'ID utilisateur qui a créé l\'entrée',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Index
    INDEX idx_entree_produit (produit_id),
    INDEX idx_entree_fournisseur (fournisseur_id),
    INDEX idx_entree_created_by (created_by),
    INDEX idx_entree_date (date_entree),
    
    -- Clés étrangères
    FOREIGN KEY (produit_id) REFERENCES stock_produits(id) ON DELETE RESTRICT,
    FOREIGN KEY (fournisseur_id) REFERENCES stock_fournisseurs(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(idUser) ON DELETE RESTRICT
);
```

### 2.7 Table `stock_sorties`

```sql
CREATE TABLE stock_sorties (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    date_sortie DATE NOT NULL,
    produit_id BIGINT NOT NULL,
    demandeur_id BIGINT NOT NULL,
    quantite INT NOT NULL,
    observations TEXT NULL,
    created_by INT NOT NULL COMMENT 'ID utilisateur qui a créé la sortie',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Index
    INDEX idx_sortie_produit (produit_id),
    INDEX idx_sortie_demandeur (demandeur_id),
    INDEX idx_sortie_created_by (created_by),
    INDEX idx_sortie_date (date_sortie),
    
    -- Clés étrangères
    FOREIGN KEY (produit_id) REFERENCES stock_produits(id) ON DELETE RESTRICT,
    FOREIGN KEY (demandeur_id) REFERENCES stock_demandeurs(id) ON DELETE RESTRICT,
    FOREIGN KEY (created_by) REFERENCES users(idUser) ON DELETE RESTRICT
);
```

---

## 3. `2026_01_21_134609_add_indexes_to_affectation_and_emplacement_tables.php`
**Date** : 21 janvier 2026  
**Description** : Ajout d'index pour optimiser les performances des requêtes hiérarchiques

### Modifications

#### Table `affectation`
```sql
-- Index sur idLocalisation
CREATE INDEX affectation_idlocalisation_index ON affectation(idLocalisation);
```

#### Table `emplacement`
```sql
-- Index sur idLocalisation
CREATE INDEX emplacement_idlocalisation_index ON emplacement(idLocalisation);

-- Index sur idAffectation
CREATE INDEX emplacement_idaffectation_index ON emplacement(idAffectation);

-- Index composite pour les requêtes combinées
CREATE INDEX emplacement_localisation_affectation_index 
    ON emplacement(idLocalisation, idAffectation);
```

---

## 4. `2026_01_20_003617_add_localisation_to_affectation_table.php`
**Date** : 20 janvier 2026  
**Description** : Ajout de la colonne idLocalisation à la table affectation

### Modifications

#### Table `affectation`
```sql
-- Ajout de la colonne idLocalisation
ALTER TABLE affectation 
ADD COLUMN idLocalisation INT NULL AFTER CodeAffectation;
```

**Note** : Cette colonne permet de lier directement une affectation à une localisation, améliorant la structure hiérarchique.

---

## 5. `2026_01_20_011458_create_inventaire_localisations_table.php`
**Date** : 20 janvier 2026  
**Description** : Table pour suivre l'avancement des inventaires par localisation

### Structure de la table `inventaire_localisations`

```sql
CREATE TABLE inventaire_localisations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    inventaire_id BIGINT NOT NULL,
    localisation_id INT NOT NULL COMMENT 'Référence à localisation.idLocalisation',
    date_debut_scan DATETIME NULL,
    date_fin_scan DATETIME NULL,
    statut ENUM('en_attente', 'en_cours', 'termine') DEFAULT 'en_attente',
    nombre_biens_attendus INT DEFAULT 0,
    nombre_biens_scannes INT DEFAULT 0,
    user_id INT NULL COMMENT 'Référence à users.idUser',
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    -- Contraintes
    UNIQUE KEY unique_inventaire_localisation (inventaire_id, localisation_id),
    
    -- Index
    INDEX idx_statut (statut),
    INDEX idx_user_id (user_id),
    INDEX idx_localisation_id (localisation_id),
    
    -- Clé étrangère
    FOREIGN KEY (inventaire_id) REFERENCES inventaires(id) ON DELETE CASCADE
);
```

---

## 📋 Résumé des Tables Créées/Modifiées

### Tables créées récemment :
1. ✅ `historique_transferts` - Historique des transferts d'immobilisations
2. ✅ `stock_magasins` - Magasins de stock
3. ✅ `stock_categories` - Catégories de produits
4. ✅ `stock_fournisseurs` - Fournisseurs
5. ✅ `stock_demandeurs` - Demandeurs de sorties
6. ✅ `stock_produits` - Produits en stock
7. ✅ `stock_entrees` - Entrées de stock
8. ✅ `stock_sorties` - Sorties de stock
9. ✅ `inventaire_localisations` - Suivi d'inventaire par localisation

### Tables modifiées récemment :
1. ✅ `affectation` - Ajout de `idLocalisation` et index
2. ✅ `emplacement` - Ajout d'index pour performance

---

## 🔗 Relations Principales

### Historique des transferts
- `historique_transferts.NumOrdre` → `gesimmo.NumOrdre`
- `historique_transferts.transfert_par` → `users.idUser`
- `historique_transferts.ancien_idEmplacement` → `emplacement.idEmplacement`
- `historique_transferts.nouveau_idEmplacement` → `emplacement.idEmplacement`

### Stock
- `stock_produits.categorie_id` → `stock_categories.id`
- `stock_produits.magasin_id` → `stock_magasins.id`
- `stock_entrees.produit_id` → `stock_produits.id`
- `stock_entrees.fournisseur_id` → `stock_fournisseurs.id`
- `stock_entrees.created_by` → `users.idUser`
- `stock_sorties.produit_id` → `stock_produits.id`
- `stock_sorties.demandeur_id` → `stock_demandeurs.id`
- `stock_sorties.created_by` → `users.idUser`

### Inventaires
- `inventaire_localisations.inventaire_id` → `inventaires.id`
- `inventaire_localisations.localisation_id` → `localisation.idLocalisation` (référence sans FK)
- `inventaire_localisations.user_id` → `users.idUser` (référence sans FK)

---

## 📊 Statistiques

- **Total de tables créées** : 9
- **Total de tables modifiées** : 2
- **Total d'index ajoutés** : 15+
- **Total de clés étrangères** : 12+
