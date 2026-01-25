# 📦 Nouveau Rôle : Admin_stock

## ✅ Rôle créé

Un nouveau rôle **`admin_stock`** a été ajouté au système RBAC pour gérer complètement le module Stock sans avoir accès aux autres modules.

## 🎯 Permissions du rôle Admin_stock

### ✅ Accès complet au module Stock
- ✅ Dashboard Stock
- ✅ Gestion des produits (CRUD)
- ✅ Création d'entrées de stock
- ✅ Création de sorties de stock
- ✅ Gestion des magasins
- ✅ Gestion des catégories
- ✅ Gestion des fournisseurs
- ✅ Gestion des demandeurs
- ✅ Voir tous les mouvements de stock

### ❌ Pas d'accès
- ❌ Gestion des immobilisations
- ❌ Gestion des inventaires
- ❌ Gestion des utilisateurs
- ❌ Paramètres généraux

## 📊 Comparaison des rôles

| Fonctionnalité | Admin | Admin_stock | Agent |
|----------------|-------|-------------|-------|
| **Immobilisations** | ✅ Complet | ❌ Aucun | ✅ Inventaire |
| **Stock - Dashboard** | ✅ | ✅ | ✅ |
| **Stock - Produits** | ✅ CRUD | ✅ CRUD | ✅ Lecture |
| **Stock - Entrées** | ✅ | ✅ | ❌ |
| **Stock - Sorties** | ✅ | ✅ | ✅ |
| **Stock - Paramètres** | ✅ | ✅ | ❌ |
| **Utilisateurs** | ✅ | ❌ | ❌ |
| **Voir tous mouvements** | ✅ | ✅ | ❌ (seulement les siens) |

## 🔧 Modifications apportées

### 1. Modèle User (`app/Models/User.php`)
- ✅ Ajout de `isAdminStock()` : Vérifie si l'utilisateur est admin_stock
- ✅ Mise à jour de `canManageStock()` : Admin + Admin_stock
- ✅ Mise à jour de `canCreateEntree()` : Admin + Admin_stock
- ✅ Mise à jour de `canCreateSortie()` : Admin + Admin_stock + Agent
- ✅ Mise à jour de `canViewAllMovements()` : Admin + Admin_stock
- ✅ Ajout de `canAccessStock()` : Admin + Admin_stock + Agent
- ✅ Mise à jour de `getRoleNameAttribute()` : Ajout "Admin Stock"
- ✅ Ajout de `scopeAdminStocks()` : Scope pour filtrer les admin_stock

### 2. Middleware (`app/Http/Middleware/CanManageStock.php`)
- ✅ Nouveau middleware créé pour protéger les routes stock
- ✅ Vérifie `canManageStock()` (Admin + Admin_stock)

### 3. Routes (`routes/web.php`)
- ✅ Routes stock paramètres : Protégées par middleware `stock` (Admin + Admin_stock)
- ✅ Routes stock générales : Protégées par middleware `inventory` (Admin + Admin_stock + Agent)

### 4. Layout (`resources/views/components/layouts/app.blade.php`)
- ✅ Menu Stock visible pour Admin + Admin_stock + Agent
- ✅ Section "Paramètres" visible pour Admin + Admin_stock

### 5. Vue Gestion Rôles (`resources/views/livewire/users/gestion-roles.blade.php`)
- ✅ Ajout du rôle Admin_stock dans les statistiques
- ✅ Ajout du filtre "Admin Stock uniquement"
- ✅ Badge indigo pour Admin_stock
- ✅ Boutons pour changer vers Admin_stock
- ✅ Légende des permissions mise à jour

### 6. Composants Livewire
- ✅ Tous les composants Stock vérifient `canManageStock()` (Admin + Admin_stock)
- ✅ Tous les composants Stock vérifient `canCreateEntree()` (Admin + Admin_stock)
- ✅ Tous les composants Stock vérifient `canViewAllMovements()` (Admin + Admin_stock)

## 🎨 Interface

### Badge Admin_stock
- **Couleur** : Indigo (📦)
- **Texte** : "Admin Stock"
- **Style** : `bg-indigo-100 text-indigo-800 border border-indigo-200`

### Statistiques
- 4 cartes au lieu de 3 :
  - Total utilisateurs
  - Administrateurs
  - **Admin Stock** (nouveau)
  - Agents

## 🧪 Tests recommandés

### Test 1 : Créer un utilisateur Admin_stock
1. Aller sur `/users/roles`
2. Trouver un utilisateur Agent
3. Cliquer "📦 Admin Stock"
4. Confirmer
5. ✅ L'utilisateur devient Admin_stock

### Test 2 : Vérifier les accès Admin_stock
1. Se connecter en tant qu'admin_stock
2. Accéder à `/stock` → ✅ Doit fonctionner
3. Accéder à `/stock/magasins` → ✅ Doit fonctionner
4. Accéder à `/stock/entrees` → ✅ Doit fonctionner
5. Accéder à `/biens` → ❌ Doit retourner 403 ou ne pas être visible
6. Accéder à `/users` → ❌ Doit retourner 403

### Test 3 : Vérifier les permissions
1. Admin_stock peut créer un produit → ✅
2. Admin_stock peut créer une entrée → ✅
3. Admin_stock peut créer une sortie → ✅
4. Admin_stock peut voir tous les mouvements → ✅
5. Admin_stock ne peut pas créer d'immobilisation → ❌

## 📝 Migration SQL (optionnelle)

Si vous voulez convertir des utilisateurs existants en Admin_stock :

```sql
-- Vérifier la colonne role
SHOW COLUMNS FROM users LIKE 'role';

-- Si elle n'existe pas, l'ajouter
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'agent' AFTER mdp;

-- Convertir un utilisateur en Admin_stock
UPDATE users SET role = 'admin_stock' WHERE idUser = X; -- Remplacer X par l'ID
```

## 🎯 Cas d'usage

Le rôle **Admin_stock** est idéal pour :
- ✅ Responsable des stocks qui n'a pas besoin de gérer les immobilisations
- ✅ Personne dédiée uniquement à la gestion des consommables
- ✅ Séparation des responsabilités entre immobilisations et stock

## 🚀 Résultat

Le système RBAC supporte maintenant **3 rôles** :
1. **👑 Admin** : Accès complet à tout
2. **📦 Admin_stock** : Accès complet au stock uniquement
3. **👤 Agent** : Inventaire + Sorties de stock

Tous les composants, routes, middlewares et vues ont été mis à jour pour supporter ce nouveau rôle ! 🎉
