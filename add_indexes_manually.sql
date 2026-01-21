-- Script SQL pour ajouter les index manuellement sur les tables affectation et emplacement
-- À exécuter dans votre client MySQL (phpMyAdmin, MySQL Workbench, etc.)

USE bdimmos; -- Remplacez par le nom de votre base de données si différent

-- Index sur la table affectation
-- Vérifier si l'index existe avant de le créer
SET @exist := (SELECT COUNT(*) FROM information_schema.STATISTICS 
               WHERE TABLE_SCHEMA = DATABASE() 
               AND TABLE_NAME = 'affectation' 
               AND INDEX_NAME = 'affectation_idlocalisation_index');

SET @sqlstmt := IF(@exist = 0, 
    'CREATE INDEX affectation_idlocalisation_index ON affectation (idLocalisation)',
    'SELECT "Index affectation_idlocalisation_index existe déjà" AS message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Index sur la table emplacement - idLocalisation
SET @exist := (SELECT COUNT(*) FROM information_schema.STATISTICS 
               WHERE TABLE_SCHEMA = DATABASE() 
               AND TABLE_NAME = 'emplacement' 
               AND INDEX_NAME = 'emplacement_idlocalisation_index');

SET @sqlstmt := IF(@exist = 0, 
    'CREATE INDEX emplacement_idlocalisation_index ON emplacement (idLocalisation)',
    'SELECT "Index emplacement_idlocalisation_index existe déjà" AS message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Index sur la table emplacement - idAffectation
SET @exist := (SELECT COUNT(*) FROM information_schema.STATISTICS 
               WHERE TABLE_SCHEMA = DATABASE() 
               AND TABLE_NAME = 'emplacement' 
               AND INDEX_NAME = 'emplacement_idaffectation_index');

SET @sqlstmt := IF(@exist = 0, 
    'CREATE INDEX emplacement_idaffectation_index ON emplacement (idAffectation)',
    'SELECT "Index emplacement_idaffectation_index existe déjà" AS message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Index composite sur la table emplacement (idLocalisation, idAffectation)
SET @exist := (SELECT COUNT(*) FROM information_schema.STATISTICS 
               WHERE TABLE_SCHEMA = DATABASE() 
               AND TABLE_NAME = 'emplacement' 
               AND INDEX_NAME = 'emplacement_localisation_affectation_index');

SET @sqlstmt := IF(@exist = 0, 
    'CREATE INDEX emplacement_localisation_affectation_index ON emplacement (idLocalisation, idAffectation)',
    'SELECT "Index emplacement_localisation_affectation_index existe déjà" AS message');
PREPARE stmt FROM @sqlstmt;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Vérifier les index créés
SELECT 
    TABLE_NAME,
    INDEX_NAME,
    COLUMN_NAME,
    SEQ_IN_INDEX
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = DATABASE()
AND TABLE_NAME IN ('affectation', 'emplacement')
AND INDEX_NAME LIKE '%localisation%' OR INDEX_NAME LIKE '%affectation%'
ORDER BY TABLE_NAME, INDEX_NAME, SEQ_IN_INDEX;
