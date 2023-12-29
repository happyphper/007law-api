<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property string $phone
 * @property string|null $avatar
 * @property string $openid
 * @property string $unionid
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUnionid($value)
 * @property bool|null $has_chat
 * @property string|null $chat_started_at
 * @property int|null $chat_count
 * @property string|null $chat_expired_at
 * @property bool|null $has_ip
 * @property string|null $ip_started_at
 * @property string|null $ip_expired_at
 * @method static \Illuminate\Database\Eloquent\Builder|User whereChatCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereChatExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereChatStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHasChat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereHasIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIpExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIpStartedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'has_chat' => 'bool',
        'has_ip' => 'bool',
    ];

    /**
     * 日期
     *
     * @var array|string[]
     */
    public array $dates = [
        'chat_started_at',
        'chat_expired_at',
        'ip_started_at',
        'ip_expired_at',
    ];

    public function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d');
    }

    protected function avatar(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => str_contains($value, 'http') ? $value : config('app.url') . \Storage::disk('local')->url($value),
        );
    }
}
