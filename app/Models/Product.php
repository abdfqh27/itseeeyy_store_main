<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'stock',
        'expiry_date',
        'image',
        'description',
    ];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the stock movements for the product.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the number of days until the product expires
     * Returns negative value if already expired
     *
     * @return int
     */
    public function getDaysUntilExpiry(): int
    {
        return now()->startOfDay()->diffInDays($this->expiry_date->startOfDay(), false);
    }

    /**
     * Get formatted expiry date with day name, date, month and year
     *
     * @return string
     */
    public function getFormattedExpiryDate(): string
    {
        // Format: Monday, 01 January 2025
        return $this->expiry_date->format('l, d F Y');
    }

    /**
     * Check if the product is expired
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->getDaysUntilExpiry() <= 0;
    }

    /**
     * Check if the product is about to expire (within 10 days)
     *
     * @return bool
     */
    public function isAboutToExpire(): bool
    {
        $days = $this->getDaysUntilExpiry();
        return $days > 0 && $days <= 10;
    }
}
