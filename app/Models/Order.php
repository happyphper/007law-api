<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $user_id
 * @property int $service_id
 * @property int $status
 * @property string $out_trade_no
 * @property string $paid_at
 * @property string $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOutTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @property string $description
 * @property string $response
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereResponse($value)
 * @property string|null $paid_amount
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaidAmount($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory, SerializeDate;

    protected $guarded = ['id'];

    const STATUS_WAIT = 1;
    const STATUS_PAID = 2;

    const STATUS_REFUND = 3;

    public $dates = [
        'paid_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
