<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\UserService
 *
 * @property int $id
 * @property int $user_id
 * @property int $service_id
 * @property int $type 类型：1次数/2时间
 * @property int $remaining_count
 * @property string|null $started_at
 * @property string|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|UserService newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserService newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserService query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserService whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserService whereRemainingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserService whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserService whereStartedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserService whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserService whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserService whereUserId($value)
 * @mixin \Eloquent
 */
class UserService extends Model
{
    use HasFactory;

    const TYPE_COUNT = 1;
    const TYPE_TIME = 2;
}
