<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    //
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'price',
        'image_url',
        'stock',
    ];

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function user()
{
    return $this->belongsTo(User::class);
}

public function comments()
{
    return $this->hasMany(Comment::class);
}


    
}
