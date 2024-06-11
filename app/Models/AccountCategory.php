<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountCategory extends Model
{
    use HasFactory;

    // Relations
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class, 'category_id');
    }
}
