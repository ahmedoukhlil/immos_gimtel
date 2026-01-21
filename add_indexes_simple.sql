-- Version simplifiée - À exécuter directement dans MySQL
-- Remplacez 'bdimmos' par le nom de votre base de données si différent

USE bdimmos;

-- Index sur affectation.idLocalisation
CREATE INDEX IF NOT EXISTS affectation_idlocalisation_index ON affectation (idLocalisation);

-- Index sur emplacement.idLocalisation
CREATE INDEX IF NOT EXISTS emplacement_idlocalisation_index ON emplacement (idLocalisation);

-- Index sur emplacement.idAffectation
CREATE INDEX IF NOT EXISTS emplacement_idaffectation_index ON emplacement (idAffectation);

-- Index composite sur emplacement (idLocalisation, idAffectation)
CREATE INDEX IF NOT EXISTS emplacement_localisation_affectation_index ON emplacement (idLocalisation, idAffectation);
