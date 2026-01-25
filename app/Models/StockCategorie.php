<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockCategorie extends Model
{
    use HasFactory;

    protected $table = 'stock_categories';

    protected $fillable = [
        'libelle',
        'observations',
    ];

    /**
     * RELATIONS
     */

    /**
     * Relation avec les produits de cette catégorie
     */
    public function produits(): HasMany
    {
        return $this->hasMany(StockProduit::class, 'categorie_id');
    }

    /**
     * ACCESSORS
     */

    /**
     * Nombre de produits dans cette catégorie
     */
    public function getNombreProduitsAttribute(): int
    {
        return $this->produits()->count();
    }
}
