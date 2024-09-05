<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'published',
        'inStock',
        'quantity',
        'price',
        'updated_by',
        'created_by',
        'deleted_by'

    ]; 
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function brand() : BelongsTo 
    {
        return $this->belongsTo(Brand::class);
    } 

    public function category() : BelongsTo 
    {
        return $this->belongsTo(Category::class);
    } 
}
