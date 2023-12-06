<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartReminderNotification extends Model
{
    protected $fillable = ['attempt', 'cart_id', 'sent_at'];

    /**
     * @return BelongsTo
     */
    public function carts()
    {
        return $this->belongsTo(Cart::class);
    }
}
