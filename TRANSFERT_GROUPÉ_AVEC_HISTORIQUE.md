# 🔄 Transfert Groupé d'Immobilisations avec Historique

## ✅ Fonctionnalités implémentées

### 1. **Transfert Groupé**
- Sélection multiple d'immobilisations
- Transfert simultané vers un même emplacement
- Validation et gestion des erreurs par immobilisation
- Transaction pour garantir la cohérence

### 2. **Historique Complet**
- Enregistrement automatique de tous les transferts
- Conservation des anciens emplacements (libellés)
- Groupement par ID de transfert
- Traçabilité complète (qui, quand, pourquoi)

## 📊 Structure de la base de données

### Table `historique_transferts`

```sql
- id (PK)
- NumOrdre (FK vers gesimmo)
- ancien_idEmplacement (FK vers emplacement, nullable)
- nouveau_idEmplacement (FK vers emplacement)
- ancien_emplacement_libelle (string)
- nouveau_emplacement_libelle (string)
- ancien_affectation_libelle (string)
- nouveau_affectation_libelle (string)
- ancien_localisation_libelle (string)
- nouveau_localisation_libelle (string)
- transfert_par (FK vers users)
- date_transfert (datetime)
- raison (text, nullable)
- groupe_transfert_id (string, nullable)
- created_at, updated_at
```

**Index créés** :
- `NumOrdre`
- `date_transfert`
- `groupe_transfert_id`
- `transfert_par`

**Clés étrangères** :
- `NumOrdre` → `gesimmo.NumOrdre` (CASCADE)
- `ancien_idEmplacement` → `emplacement.idEmplacement` (SET NULL)
- `nouveau_idEmplacement` → `emplacement.idEmplacement` (RESTRICT)
- `transfert_par` → `users.idUser` (RESTRICT)

## 🎯 Workflow de transfert

### Étape 1 : Sélection des immobilisations
1. Rechercher des immobilisations (par ordre, désignation, emplacement, localisation)
2. Cliquer "Ajouter" pour chaque immobilisation souhaitée
3. Voir la liste des immobilisations sélectionnées
4. Possibilité de retirer des immobilisations

### Étape 2 : Sélection du nouvel emplacement
1. Choisir la **Localisation** de destination
2. Choisir l'**Affectation** (filtrée par localisation)
3. Choisir l'**Emplacement** (filtré par affectation)
4. Optionnel : Ajouter une **raison** du transfert

### Étape 3 : Validation
1. Vérification que :
   - Au moins une immobilisation est sélectionnée
   - L'emplacement de destination est valide
   - L'emplacement est différent de l'actuel
2. Transaction DB pour garantir la cohérence
3. Pour chaque immobilisation :
   - Mise à jour de `idEmplacement`
   - Enregistrement dans l'historique
4. Génération d'un ID de groupe (`TRF-YYYYMMDDHHMMSS-XXXXXX`)

### Étape 4 : Confirmation
- Message de succès avec nombre de transferts réussis
- Message d'erreur si certains transferts ont échoué
- Réinitialisation du formulaire

## 📜 Consultation de l'historique

### Vue `/biens/transfert/historique`

**Filtres disponibles** :
- **Recherche** : Par ordre, emplacement, raison
- **Groupe de transfert** : Voir tous les transferts d'un même groupe
- **Date début** : Filtrer à partir d'une date
- **Date fin** : Filtrer jusqu'à une date

**Informations affichées** :
- Date et heure du transfert
- Numéro d'ordre et désignation de l'immobilisation
- Ancien emplacement (avec affectation et localisation)
- Nouvel emplacement (avec affectation et localisation)
- Raison du transfert
- Utilisateur qui a effectué le transfert
- ID du groupe de transfert

**Pagination** : 20 transferts par page

## 🔧 Composants créés/modifiés

### Modèles
- ✅ `app/Models/HistoriqueTransfert.php` - Modèle pour l'historique

### Composants Livewire
- ✅ `app/Livewire/Biens/TransfertBien.php` - Transfert groupé avec historique
- ✅ `app/Livewire/Biens/HistoriqueTransferts.php` - Consultation de l'historique

### Vues
- ✅ `resources/views/livewire/biens/transfert-bien.blade.php` - Interface de transfert
- ✅ `resources/views/livewire/biens/historique-transferts.blade.php` - Interface historique

### Migration
- ✅ `database/migrations/2026_01_24_231835_create_historique_transferts_table.php`

### Routes
- ✅ `GET /biens/transfert` - Formulaire de transfert
- ✅ `GET /biens/transfert/historique` - Consultation historique

### Menu
- ✅ Lien "🔄 Transfert Immobilisation" dans le menu Immobilisations
- ✅ Lien "📜 Historique Transferts" dans le menu Immobilisations

## 🎨 Interface utilisateur

### Vue de transfert (`/biens/transfert`)

**Layout en 2 colonnes** :
- **Colonne gauche** : Sélection des immobilisations
  - Champ de recherche
  - Liste des immobilisations disponibles (avec boutons Ajouter/Retirer)
  - Liste des immobilisations sélectionnées
  
- **Colonne droite** : Nouvel emplacement
  - Sélection hiérarchique (Localisation → Affectation → Emplacement)
  - Champ raison (optionnel)
  - Résumé du transfert
  - Boutons d'action

### Vue d'historique (`/biens/transfert/historique`)

**Section filtres** :
- 4 champs de filtrage (recherche, groupe, dates)
- Bouton de réinitialisation

**Tableau** :
- 7 colonnes avec toutes les informations
- Pagination en bas
- Message si aucun résultat

## 🔒 Sécurité et validations

### Validations
- ✅ Au moins une immobilisation sélectionnée
- ✅ Toutes les immobilisations existent
- ✅ Localisation, affectation, emplacement valides
- ✅ Cohérence hiérarchique (localisation → affectation → emplacement)
- ✅ Raison limitée à 500 caractères

### Sécurité
- ✅ Vérification des permissions (`canManageInventaire()`)
- ✅ Transaction DB pour garantir la cohérence
- ✅ Vérification que l'emplacement est différent
- ✅ Gestion des erreurs par immobilisation

### Traçabilité
- ✅ Enregistrement de l'utilisateur qui effectue le transfert
- ✅ Date et heure précises
- ✅ Conservation des libellés (même si les emplacements sont supprimés)
- ✅ ID de groupe pour regrouper les transferts simultanés

## 📈 Cas d'usage

### Cas 1 : Transfert d'un bureau complet
```
Sélectionner 20 chaises + 5 tables
→ Transférer vers "Bureau B"
→ Tous les transferts ont le même groupe_transfert_id
```

### Cas 2 : Correction d'erreur
```
1 chaise mal affectée
→ Transférer vers le bon emplacement
→ Raison: "Correction d'erreur d'affectation"
```

### Cas 3 : Réorganisation
```
10 immobilisations de différents emplacements
→ Transférer vers un nouvel emplacement centralisé
→ Raison: "Réorganisation des stocks"
```

## 🚀 Améliorations futures possibles

1. **Export Excel** : Exporter l'historique en Excel
2. **Rapport PDF** : Générer un rapport de transfert
3. **Annulation** : Possibilité d'annuler un transfert (avec historique)
4. **Notifications** : Notifier les responsables des emplacements
5. **Statistiques** : Graphiques de transferts par période
6. **Recherche avancée** : Plus de critères de filtrage
7. **Transfert en masse depuis liste** : Sélectionner depuis la liste des biens

## 🎉 Résultat

Un système complet de transfert groupé avec historique complet, permettant de :
- ✅ Transférer plusieurs immobilisations simultanément
- ✅ Conserver l'historique de tous les transferts
- ✅ Tracer qui a fait quoi et quand
- ✅ Regrouper les transferts effectués ensemble
- ✅ Consulter et filtrer l'historique facilement

Tout est prêt et fonctionnel ! 🚀
