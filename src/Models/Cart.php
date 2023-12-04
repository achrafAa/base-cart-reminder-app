<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    protected $fillable = ['customer_email', 'customer_fullname', 'created_at'];

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * @return HasOne
     */
    public function cartReminderNotification(): HasOne
    {
        return $this->hasOne(CartReminderNotification::class);
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->items()->delete();
        $this->cartReminderNotification()->delete();
        parent::delete();
    }
}
