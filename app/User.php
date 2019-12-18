<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int            $id
 * @property string         $name
 * @property string         $email
 * @property \Carbon\Carbon $email_verified_at
 * @property string         $password
 * @property string         $api_token
 * @property string         $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Webhook[] $webhooks
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /** @var array */
    protected $fillable = [
        'name', 'email', 'password', 'api_token',
    ];

    /** @var array */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /** @var array */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    public function webhooks(): HasMany
    {
        return $this->hasMany(Webhook::class);
    }

    public function mails(): BelongsToMany
    {
        return $this->belongsToMany(Mail::class)
            ->withPivot(['acknowledged']);
    }

    public function unreadMails(): BelongsToMany
    {
        return $this->mails()
            ->wherePivot('acknowledged', null);
    }
}
