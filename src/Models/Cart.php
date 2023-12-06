<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cart extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['customer_email', 'customer_fullname', 'reminder_attempt', 'created_at'];

    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function cartReminderNotification(): HasMany
    {
        return $this->hasMany(CartReminderNotification::class);
    }
}
