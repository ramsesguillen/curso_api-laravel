<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    const PROUDCTO_DISPONIBLE = 'disponible';
    const PROUDCTO_NO_DISPONIBLE = 'no disponible';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];

    protected $dates = ['deleted_at'];

    protected $hidden = [
        'pivot'
    ];

    public function estaDisponible()
    {
        return $this->status == Product::PROUDCTO_DISPONIBLE;
    }

    public function seller()
    {
        return $this->belongsTo( Seller::class );
    }


    public function transactions()
    {
        return $this->hasMany( Transaction::class );
    }

    public function categories()
    {
        return $this->belongsToMany( Category::class );
    }
}
