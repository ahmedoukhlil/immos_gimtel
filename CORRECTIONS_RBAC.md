# 🔧 Corrections RBAC - Problèmes et Solutions

## 🐛 Problèmes identifiés

### 1. **Appels non sécurisés à `auth()->user()`**
**Problème** : Les composants appelaient `auth()->user()->canManageStock()` sans vérifier si l'utilisateur est null.

**Erreur possible** :
```
Call to a member function canManageStock() on null
```

**Solution** : Vérifier que l'utilisateur existe avant d'appeler les méthodes RBAC.

### 2. **Vérifications manquantes dans les vues**
**Problème** : Les vues utilisent `auth()->user()->canManageStock()` sans vérification de null.

**Solution** : Ajouter des vérifications ou utiliser `auth()->check()`.

## ✅ Corrections appliquées

### Composants Livewire corrigés (14 composants)

1. ✅ `Stock/Magasins/ListeMagasins.php`
2. ✅ `Stock/Magasins/FormMagasin.php`
3. ✅ `Stock/Categories/ListeCategories.php`
4. ✅ `Stock/Categories/FormCategorie.php`
5. ✅ `Stock/Fournisseurs/ListeFournisseurs.php`
6. ✅ `Stock/Fournisseurs/FormFournisseur.php`
7. ✅ `Stock/Demandeurs/ListeDemandeurs.php`
8. ✅ `Stock/Demandeurs/FormDemandeur.php`
9. ✅ `Stock/Produits/ListeProduits.php`
10. ✅ `Stock/Produits/FormProduit.php`
11. ✅ `Stock/Entrees/ListeEntrees.php`
12. ✅ `Stock/Entrees/FormEntree.php`
13. ✅ `Stock/Sorties/ListeSorties.php`
14. ✅ `Stock/Sorties/FormSortie.php`

### Pattern de correction appliqué

**Avant** :
```php
if (!auth()->user()->canManageStock()) {
    abort(403, 'Accès non autorisé.');
}
```

**Après** :
```php
$user = auth()->user();
if (!$user || !$user->canManageStock()) {
    abort(403, 'Accès non autorisé.');
}
```

## 🔍 Vérifications à faire

### 1. Vérifier que la colonne `role` existe dans la table `users`

```sql
SHOW COLUMNS FROM users LIKE 'role';
```

Si elle n'existe pas, l'ajouter :
```sql
ALTER TABLE users ADD COLUMN role VARCHAR(20) DEFAULT 'agent';
UPDATE users SET role = 'admin' WHERE idUser = 1; -- Mettre le premier utilisateur en admin
```

### 2. Vérifier que les utilisateurs ont un rôle défini

```sql
SELECT idUser, users, role FROM users;
```

Tous les utilisateurs doivent avoir `role = 'admin'` ou `role = 'agent'`.

### 3. Tester les permissions

#### Test Admin
1. Se connecter en tant qu'admin
2. Accéder à `/stock/magasins` → ✅ Doit fonctionner
3. Accéder à `/stock/entrees` → ✅ Doit fonctionner
4. Accéder à `/stock/sorties` → ✅ Doit fonctionner

#### Test Agent
1. Se connecter en tant qu'agent
2. Accéder à `/stock/magasins` → ❌ Doit retourner 403
3. Accéder à `/stock/entrees` → ❌ Doit retourner 403
4. Accéder à `/stock/sorties` → ✅ Doit fonctionner
5. Créer une sortie → ✅ Doit fonctionner
6. Voir les sorties → ✅ Doit voir seulement ses propres sorties

## 📝 Middlewares en place

### Middleware `admin`
- **Fichier** : `app/Http/Middleware/IsAdmin.php`
- **Vérifie** : `$user->role === 'admin'`
- **Utilisé pour** : Routes admin uniquement (magasins, catégories, fournisseurs, demandeurs, entrées)

### Middleware `inventory`
- **Fichier** : `app/Http/Middleware/CanManageInventaire.php`
- **Vérifie** : `in_array($user->role, ['admin', 'agent'])`
- **Utilisé pour** : Routes accessibles à admin + agent (dashboard, produits, sorties)

## 🎯 Méthodes RBAC dans le modèle User

Toutes les méthodes vérifient d'abord `isAdmin()` ou `isAgent()` :

```php
public function canManageStock(): bool
{
    return $this->isAdmin(); // Seul admin
}

public function canCreateEntree(): bool
{
    return $this->isAdmin(); // Seul admin
}

public function canCreateSortie(): bool
{
    return $this->isAdmin() || $this->isAgent(); // Admin + Agent
}

public function canViewAllMovements(): bool
{
    return $this->isAdmin(); // Seul admin
}
```

## ⚠️ Points d'attention

1. **Vérifier la colonne `role`** : Assurez-vous qu'elle existe et contient 'admin' ou 'agent'
2. **Vérifier les utilisateurs** : Tous doivent avoir un rôle défini
3. **Tester avec différents rôles** : Admin et Agent doivent avoir des accès différents
4. **Vérifier les middlewares** : Ils doivent être enregistrés dans `bootstrap/app.php`

## 🚀 Prochaines étapes

1. ✅ Corrections appliquées dans tous les composants
2. ⏳ Vérifier la structure de la table `users`
3. ⏳ Vérifier que les utilisateurs ont des rôles
4. ⏳ Tester avec un utilisateur admin
5. ⏳ Tester avec un utilisateur agent

## 📞 Si le problème persiste

1. Vérifier les logs Laravel : `storage/logs/laravel.log`
2. Vérifier que la session fonctionne
3. Vérifier que l'authentification fonctionne
4. Vérifier que `auth()->user()` retourne bien un utilisateur
