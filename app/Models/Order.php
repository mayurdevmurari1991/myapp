<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number', 'inventory_id', 'user_id', 'quantity', 'status'
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
