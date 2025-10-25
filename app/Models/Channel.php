<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status',
    ];
    public function subscriptions() {
    return $this->belongsToMany(Subscription::class);
}

}
