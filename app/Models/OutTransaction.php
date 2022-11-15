<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutTransaction extends Model
{
    use HasFactory;
    protected $fillable = ['code','quantity','item_id'];

    public function item() {
        return $this->belongsTo(Item::class);
    }
}
