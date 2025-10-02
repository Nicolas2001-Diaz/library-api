<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author', 'genre', 'stock'];

    protected $casts = ['stock' => 'integer'];

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
