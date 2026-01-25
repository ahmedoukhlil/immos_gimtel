# 🚀 Guide de Démarrage Rapide - Module Stock

## ⚡ Test rapide en 5 minutes

### Étape 1 : Accéder au Dashboard Stock (30s)

```
1. Ouvrir : http://localhost:8000/stock
2. Observer : Dashboard vide (normal, aucune donnée)
3. Vérifier : Menu "Stock" visible dans la sidebar
```

### Étape 2 : Créer un magasin (1 min)

```
1. Cliquer sur le menu "Stock" > "Magasins"
2. Cliquer "Nouveau magasin"
3. Remplir :
   - Magasin : "Magasin Central"
   - Localisation : "Bâtiment A, RDC"
4. Cliquer "Créer le magasin"
5. ✅ Message : "Magasin créé avec succès"
```

### Étape 3 : Créer une catégorie (1 min)

```
1. Menu "Stock" > "Catégories"
2. Cliquer "Nouvelle catégorie"
3. Remplir :
   - Libellé : "Fournitures de bureau"
4. Cliquer "Créer la catégorie"
5. ✅ Message : "Catégorie créée avec succès"
```

### Étape 4 : Créer un fournisseur (1 min)

```
1. Menu "Stock" > "Fournisseurs"
2. Cliquer "Nouveau fournisseur"
3. Remplir :
   - Libellé : "Société ABC"
4. Cliquer "Créer le fournisseur"
5. ✅ Message : "Fournisseur créé avec succès"
```

### Étape 5 : Créer un demandeur (1 min)

```
1. Menu "Stock" > "Demandeurs"
2. Cliquer "Nouveau demandeur"
3. Remplir :
   - Nom : "Mohamed Ahmed"
   - Poste/Service : "Direction IT"
4. Cliquer "Créer le demandeur"
5. ✅ Message : "Demandeur créé avec succès"
```

### Étape 6 : Créer un produit (1 min 30s)

```
1. Menu "Stock" > "Produits"
2. Cliquer "Nouveau produit"
3. Remplir :
   - Libellé : "Ramettes A4"
   - Catégorie : "Fournitures de bureau"
   - Magasin : "Magasin Central"
   - Stockage : "Étagère A1"
   - Stock initial : 100
   - Stock actuel : 100 (automatique)
   - Seuil d'alerte : 20
4. Cliquer "Créer le produit"
5. ✅ Message : "Produit créé avec succès"
6. Observer : Badge 🟢 OK (stock suffisant)
```

### Étape 7 : Faire une entrée de stock (1 min)

```
1. Menu "Stock" > "Entrées"
2. Cliquer "Nouvelle entrée"
3. Remplir :
   - Date : Aujourd'hui (par défaut)
   - Produit : "Ramettes A4 (Stock: 100)"
   - Fournisseur : "Société ABC"
   - Quantité : 50
   - Référence : "BC-2026-001"
4. Cliquer "Enregistrer l'entrée"
5. ✅ Message : "Entrée de stock enregistrée avec succès. Le stock a été mis à jour."
6. Vérifier : Stock = 150 (100 + 50) ⚡ AUTOMATIQUE
```

### Étape 8 : Faire une sortie de stock (1 min)

```
1. Menu "Stock" > "Sorties"
2. Cliquer "Nouvelle sortie"
3. Remplir :
   - Date : Aujourd'hui (par défaut)
   - Produit : "Ramettes A4 (Stock: 150) 🟢"
   - Observer : Carte bleue affiche "Stock disponible: 150"
   - Demandeur : "Mohamed Ahmed - Direction IT"
   - Quantité : 135
4. Cliquer "Enregistrer la sortie"
5. ✅ Message : "Sortie enregistrée. ⚠️ ALERTE : Le stock est maintenant en dessous du seuil d'alerte (15/20)."
6. Vérifier : Stock = 15 (150 - 135) ⚡ AUTOMATIQUE
7. Observer : Badge 🔴 Alerte
```

### Étape 9 : Vérifier le Dashboard (30s)

```
1. Retourner sur : http://localhost:8000/stock
2. Observer :
   - Total produits : 1
   - Alertes stock : 1 (rouge)
   - Entrées ce mois : 50
   - Sorties ce mois : 135
3. Voir : "Produits en alerte" → Ramettes A4 (15/20) 🔴
4. Voir : "Derniers mouvements" → 2 mouvements affichés
5. Voir : "Stock par magasin" → Magasin Central (1 produit, 1 en alerte)
```

---

## 🎯 Test de validation du stock insuffisant

### Test : Tentative de sortie > stock disponible

```
1. Menu "Stock" > "Sorties" > "Nouvelle sortie"
2. Sélectionner : "Ramettes A4 (Stock: 15) 🔴"
3. Observer : Carte rouge "Stock disponible: 15"
4. Mettre : Quantité = 20
5. Cliquer "Enregistrer"
6. ✅ Attendu : Erreur "Stock insuffisant. Stock disponible : 15, demandé : 20"
7. ✅ Stock non modifié : reste à 15
```

---

## 🔐 Test des permissions RBAC

### Test Agent : Pas d'accès aux paramètres

```
1. Se connecter en tant qu'Agent
2. Aller sur : http://localhost:8000/stock/magasins
3. ✅ Attendu : Erreur 403 "Accès non autorisé"
4. Aller sur : http://localhost:8000/stock/sorties/create
5. ✅ Attendu : Formulaire accessible ✅
```

### Test Agent : Voir seulement ses sorties

```
1. Connecté en tant qu'Agent
2. Créer une sortie
3. Aller sur : http://localhost:8000/stock/sorties
4. ✅ Attendu : Voir uniquement ses propres sorties
5. Se reconnecter en Admin
6. Voir : Toutes les sorties (de tous les utilisateurs)
```

---

## 📊 Test du système de quantité dans /biens/create

### Créer 10 chaises identiques

```
1. Aller sur : http://localhost:8000/biens/create
2. Remplir :
   - Désignation : "Chaise"
   - Catégorie : (automatique)
   - État : "Bon"
   - Localisation : (choisir)
   - Affectation : (choisir)
   - Emplacement : (choisir)
   - Nature Juridique : (choisir)
   - Source Financement : (choisir)
   - Date Acquisition : 2026
   - ⭐ Quantité : 10 ← IMPORTANT
3. Cliquer "Créer l'immobilisation"
4. ✅ Message : "10 immobilisations créées avec succès"
5. Aller sur : http://localhost:8000/biens
6. Rechercher : "Chaise"
7. ✅ Observer : 10 chaises avec NumOrdre différents
   - Chaise (NumOrdre: 1001)
   - Chaise (NumOrdre: 1002)
   - Chaise (NumOrdre: 1003)
   - ...
   - Chaise (NumOrdre: 1010)
```

---

## 🎨 Points à vérifier visuellement

### Design et UX
- [ ] Menu Stock dépliable avec Alpine.js
- [ ] Icônes emoji visibles (🏪 📦 📥 📤)
- [ ] Codes couleurs fonctionnels (🔴 🟡 🟢)
- [ ] Recherche en temps réel (debounce 300ms)
- [ ] Pagination fonctionne
- [ ] Messages flash affichés
- [ ] Modals de suppression s'ouvrent
- [ ] Boutons disabled selon permissions
- [ ] Responsive sur mobile

### Fonctionnalités métier
- [ ] Stock se met à jour automatiquement
- [ ] Validation stock insuffisant fonctionne
- [ ] Alertes s'affichent quand stock ≤ seuil
- [ ] Historique complet dans détail produit
- [ ] Statistiques correctes dans dashboard
- [ ] RBAC bloque les accès non autorisés
- [ ] Quantité crée plusieurs biens identiques

---

## ⚠️ Problèmes potentiels et solutions

### Problème : Erreur "Class not found"
**Solution** : 
```bash
composer dump-autoload
php artisan optimize:clear
```

### Problème : Menu Stock ne s'ouvre pas
**Solution** : Vérifier que Alpine.js est chargé dans le layout

### Problème : Stock ne se met pas à jour
**Solution** : Vérifier que les events dans `StockEntree` et `StockSortie` fonctionnent

### Problème : Erreur 403 pour Admin
**Solution** : Vérifier que le champ `role` de l'utilisateur est bien `'admin'`

---

## 🎉 Bravo !

Si tous les tests passent, vous avez maintenant :
- ✅ Un système d'optimisations généralisé
- ✅ Un dashboard inventaire corrigé
- ✅ Un module Stock complet et professionnel
- ✅ Une fonction quantité pour les immobilisations

**Le système est prêt pour la production ! 🚀**
