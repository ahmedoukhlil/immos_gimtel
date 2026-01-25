-- =====================================================
-- STRUCTURES DES TABLES - PRÊTES À EXÉCUTER DANS MYSQL
-- =====================================================
-- Date: 2026-01-24
-- Description: Structures complètes pour historique_transferts et tables de stock
-- =====================================================

-- =====================================================
-- 1. TABLE: historique_transferts
-- =====================================================
CREATE TABLE IF NOT EXISTS `historique_transferts` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    
    -- Immobilisation transférée
    `NumOrdre` INT NOT NULL,
    
    -- Ancien emplacement (sauvegardé pour historique)
    `ancien_idEmplacement` INT NULL,
    `ancien_emplacement_libelle` VARCHAR(255) NULL,
    `ancien_affectation_libelle` VARCHAR(255) NULL,
    `ancien_localisation_libelle` VARCHAR(255) NULL,
    
    -- Nouveau emplacement
    `nouveau_idEmplacement` INT NOT NULL,
    `nouveau_emplacement_libelle` VARCHAR(255) NOT NULL,
    `nouveau_affectation_libelle` VARCHAR(255) NOT NULL,
    `nouveau_localisation_libelle` VARCHAR(255) NOT NULL,
    
    -- Utilisateur et date
    `transfert_par` INT NOT NULL COMMENT 'ID utilisateur (users.idUser)',
    `date_transfert` DATETIME NOT NULL,
    
    -- Raison du transfert (optionnel)
    `raison` TEXT NULL,
    
    -- ID de groupe pour regrouper les transferts effectués ensemble
    `groupe_transfert_id` VARCHAR(50) NULL COMMENT 'Format: TRF-YYYYMMDDHHMMSS-XXXXXX',
    
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    
    -- Index pour améliorer les performances
    INDEX `idx_numordre` (`NumOrdre`),
    INDEX `idx_date_transfert` (`date_transfert`),
    INDEX `idx_groupe_transfert` (`groupe_transfert_id`),
    INDEX `idx_transfert_par` (`transfert_par`),
    
    -- Clés étrangères
    CONSTRAINT `fk_historique_numordre` 
        FOREIGN KEY (`NumOrdre`) 
        REFERENCES `gesimmo`(`NumOrdre`) 
        ON DELETE CASCADE 
        ON UPDATE CASCADE,
    
    CONSTRAINT `fk_historique_ancien_emplacement` 
        FOREIGN KEY (`ancien_idEmplacement`) 
        REFERENCES `emplacement`(`idEmplacement`) 
        ON DELETE SET NULL 
        ON UPDATE CASCADE,
    
    CONSTRAINT `fk_historique_nouveau_emplacement` 
        FOREIGN KEY (`nouveau_idEmplacement`) 
        REFERENCES `emplacement`(`idEmplacement`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    
    CONSTRAINT `fk_historique_transfert_par` 
        FOREIGN KEY (`transfert_par`) 
        REFERENCES `users`(`idUser`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2. TABLES DE STOCK (22/01/2026)
-- =====================================================

-- =====================================================
-- 2.1 TABLE: stock_magasins
-- =====================================================
CREATE TABLE IF NOT EXISTS `stock_magasins` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `magasin` VARCHAR(255) NOT NULL COMMENT 'Nom du magasin',
    `localisation` VARCHAR(255) NOT NULL COMMENT 'Localisation du magasin',
    `observations` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2.2 TABLE: stock_categories
-- =====================================================
CREATE TABLE IF NOT EXISTS `stock_categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `libelle` VARCHAR(255) NOT NULL,
    `observations` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2.3 TABLE: stock_fournisseurs
-- =====================================================
CREATE TABLE IF NOT EXISTS `stock_fournisseurs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `libelle` VARCHAR(255) NOT NULL,
    `observations` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2.4 TABLE: stock_demandeurs
-- =====================================================
CREATE TABLE IF NOT EXISTS `stock_demandeurs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nom` VARCHAR(255) NOT NULL,
    `poste_service` VARCHAR(255) NOT NULL COMMENT 'Poste/Service/Direction',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2.5 TABLE: stock_produits
-- =====================================================
CREATE TABLE IF NOT EXISTS `stock_produits` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `libelle` VARCHAR(255) NOT NULL,
    `categorie_id` BIGINT UNSIGNED NOT NULL,
    `magasin_id` BIGINT UNSIGNED NOT NULL,
    `stock_initial` INT NOT NULL DEFAULT 0,
    `stock_actuel` INT NOT NULL DEFAULT 0,
    `seuil_alerte` INT NOT NULL DEFAULT 10,
    `descriptif` TEXT NULL,
    `stockage` VARCHAR(255) NULL COMMENT 'Emplacement précis dans le magasin',
    `observations` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    
    -- Index pour optimiser les requêtes
    INDEX `idx_produit_categorie` (`categorie_id`),
    INDEX `idx_produit_magasin` (`magasin_id`),
    
    -- Clés étrangères
    CONSTRAINT `fk_produit_categorie` 
        FOREIGN KEY (`categorie_id`) 
        REFERENCES `stock_categories`(`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    
    CONSTRAINT `fk_produit_magasin` 
        FOREIGN KEY (`magasin_id`) 
        REFERENCES `stock_magasins`(`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2.6 TABLE: stock_entrees
-- =====================================================
CREATE TABLE IF NOT EXISTS `stock_entrees` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `date_entree` DATE NOT NULL,
    `reference_commande` VARCHAR(255) NULL,
    `produit_id` BIGINT UNSIGNED NOT NULL,
    `fournisseur_id` BIGINT UNSIGNED NOT NULL,
    `quantite` INT NOT NULL,
    `observations` TEXT NULL,
    `created_by` INT NOT NULL COMMENT 'ID utilisateur qui a créé l\'entrée (users.idUser)',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    
    -- Index pour optimiser les requêtes
    INDEX `idx_entree_produit` (`produit_id`),
    INDEX `idx_entree_fournisseur` (`fournisseur_id`),
    INDEX `idx_entree_created_by` (`created_by`),
    INDEX `idx_entree_date` (`date_entree`),
    
    -- Clés étrangères
    CONSTRAINT `fk_entree_produit` 
        FOREIGN KEY (`produit_id`) 
        REFERENCES `stock_produits`(`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    
    CONSTRAINT `fk_entree_fournisseur` 
        FOREIGN KEY (`fournisseur_id`) 
        REFERENCES `stock_fournisseurs`(`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    
    CONSTRAINT `fk_entree_created_by` 
        FOREIGN KEY (`created_by`) 
        REFERENCES `users`(`idUser`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 2.7 TABLE: stock_sorties
-- =====================================================
CREATE TABLE IF NOT EXISTS `stock_sorties` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `date_sortie` DATE NOT NULL,
    `produit_id` BIGINT UNSIGNED NOT NULL,
    `demandeur_id` BIGINT UNSIGNED NOT NULL,
    `quantite` INT NOT NULL,
    `observations` TEXT NULL,
    `created_by` INT NOT NULL COMMENT 'ID utilisateur qui a créé la sortie (users.idUser)',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    
    PRIMARY KEY (`id`),
    
    -- Index pour optimiser les requêtes
    INDEX `idx_sortie_produit` (`produit_id`),
    INDEX `idx_sortie_demandeur` (`demandeur_id`),
    INDEX `idx_sortie_created_by` (`created_by`),
    INDEX `idx_sortie_date` (`date_sortie`),
    
    -- Clés étrangères
    CONSTRAINT `fk_sortie_produit` 
        FOREIGN KEY (`produit_id`) 
        REFERENCES `stock_produits`(`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    
    CONSTRAINT `fk_sortie_demandeur` 
        FOREIGN KEY (`demandeur_id`) 
        REFERENCES `stock_demandeurs`(`id`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE,
    
    CONSTRAINT `fk_sortie_created_by` 
        FOREIGN KEY (`created_by`) 
        REFERENCES `users`(`idUser`) 
        ON DELETE RESTRICT 
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- FIN DES STRUCTURES
-- =====================================================
-- 
-- NOTES IMPORTANTES:
-- 1. Toutes les tables utilisent InnoDB pour supporter les clés étrangères
-- 2. Le charset est utf8mb4 pour supporter les caractères Unicode complets
-- 3. Les clés étrangères utilisent ON DELETE RESTRICT pour protéger l'intégrité
-- 4. La table historique_transferts utilise ON DELETE CASCADE pour NumOrdre
--    car si une immobilisation est supprimée, son historique doit l'être aussi
-- 5. Les colonnes created_by référencent users.idUser (INT, pas BIGINT)
-- =====================================================
