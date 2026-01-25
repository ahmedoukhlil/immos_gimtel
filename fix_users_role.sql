-- Script SQL pour vérifier et corriger la colonne 'role' dans la table users

-- 1. Vérifier si la colonne 'role' existe
SELECT COLUMN_NAME, DATA_TYPE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
  AND TABLE_NAME = 'users' 
  AND COLUMN_NAME = 'role';

-- 2. Si la colonne n'existe pas, l'ajouter
ALTER TABLE users 
ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'agent' 
AFTER mdp;

-- 3. Vérifier les utilisateurs existants
SELECT idUser, users, role FROM users;

-- 4. Mettre le premier utilisateur en admin (à adapter selon vos besoins)
-- UPDATE users SET role = 'admin' WHERE idUser = 1;

-- 5. S'assurer que tous les utilisateurs ont un rôle
UPDATE users SET role = 'agent' WHERE role IS NULL OR role = '';

-- 6. Vérifier le résultat final
SELECT idUser, users, role FROM users;
