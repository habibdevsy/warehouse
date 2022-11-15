<?php

namespace App\Models;

use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['code','quantity','item_id'];

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
