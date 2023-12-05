<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['customer_email', 'customer_fullname', 'created_at'];

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return HasMany
     */
    public function cartReminderNotification(): HasMany
    {
        return $this->hasMany(CartReminderNotification::class);
    }

}
