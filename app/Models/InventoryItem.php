<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'description', 'price'];

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'stocks')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('sku', 'like', "%{$search}%");
    }

    public function scopePriceRange($query, $min, $max)
    {
        return $query->when($min, fn($q) => $q->where('price', '>=', $min))
            ->when($max, fn($q) => $q->where('price', '<=', $max));
    }
}
