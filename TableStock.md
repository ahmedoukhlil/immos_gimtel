md = """# Modélisation Base de Données – Gestion de Stock

## Tables principales

### categories

- id (PK)

- libelle

- observations

### fournisseurs

- id (PK)

- libelle

- observations

### Demandeurs

- id (PK)

- Nom

- Poste/Service/Direction

### Produit

- id (PK)

- libelle

- categorie_id (FK → categories.id)

- stock_initial

- stock_actuel

- seuil_alerte

- descriptif

- stockage

- observations

### entrees_stock

- id (PK)

- date_entree

- reference_commande

- piece_id (FK → pieces.id)

- fournisseur_id (FK → fournisseurs.id)

- quantite

- observations

### sorties_stock

- id (PK)

- date_sortie

- piece_id (FK → pieces.id)

- technicien_id (FK → techniciens.id)

- quantite

- observations

## Relations

- categories 1—N pieces

- produit 1—N entrees_stock

- produit1—N sorties_stock

- fournisseurs 1—N entrees_stock

- Demandeurs 1—N sorties_stock





