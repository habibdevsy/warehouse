<?php

namespace App\Models;

use App\Models\Category;
use App\Models\InTransaction;
use App\Models\OutTransaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    protected $fillable = ['name','commercial_name','price','quantity','category_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function inTransactions()
    {
       return $this->hasMany(InTransaction::class, 'item_id');
    }

    public function outTransactions()
    {
       return $this->hasMany(OutTransaction::class, 'item_id');
    }
}
