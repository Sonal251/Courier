<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int            $id
 * @property string         $hash
 * @property string         $to
 * @property string         $content
 * @property \Carbon\Carbon $created_at
 * @property \App\User[]    $users
 */
class Mail extends Model
{
    const UPDATED_AT = null;

    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot(['acknowledged']);
    }
}
