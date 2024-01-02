<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Service
 *
 * @property int $id
 * @property string $title
 * @property string $cover
 * @property string $subtitle
 * @property string $content
 * @property string $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Service newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Service query()
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereUpdatedAt($value)
 * @property int $type
 * @method static \Illuminate\Database\Eloquent\Builder|Service whereType($value)
 * @mixin \Eloquent
 */
class Service extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const TYPE_CHAT = 1;
    const TYPE_IP = 2;

    public static function types()
    {
        return [
            self::TYPE_CHAT => '智能咨询',
            self::TYPE_IP => '短视频IP打造',
        ];
    }

    protected function cover(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => str_contains($value, 'http') ? $value : config('app.url') . \Storage::disk('local')->url($value),
            set: fn(string $value) => str_contains($value, 'http') ? str_replace(config('app.url') . '/storage', '', $value) : $value,
        );
    }
}
