<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesPage extends Model
{
    protected $fillable = [
        'user_id',
        'product_name',
        'description',
        'features',
        'target_audience',
        'price',
        'unique_selling_point',
        'generated_html',
        'hero_image_url',
        'template',
        'parent_id',
        'feedback',
        'version',
        'status',
        'error_message',
    ];

    protected $casts = [
        'version' => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The original page this version was regenerated from. */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(SalesPage::class, 'parent_id');
    }

    /** All regenerated versions that reference this page. */
    public function versions(): HasMany
    {
        return $this->hasMany(SalesPage::class, 'parent_id')->orderBy('version');
    }

    // ── Accessors ─────────────────────────────────────────────────────────

    /** Return features as a trimmed array. */
    public function getFeaturesArrayAttribute(): array
    {
        return array_filter(array_map('trim', explode(',', $this->features)));
    }

    /** True when this row is an original (not a regeneration). */
    public function getIsOriginalAttribute(): bool
    {
        return is_null($this->parent_id);
    }

    // ── Scopes ────────────────────────────────────────────────────────────

    /** Only original pages (not regenerated versions). */
    public function scopeOriginals($query)
    {
        return $query->whereNull('parent_id');
    }

    /** Search by product name. */
    public function scopeSearch($query, string $term)
    {
        return $query->where('product_name', 'like', "%{$term}%");
    }
}
