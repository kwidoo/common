<?php

namespace Velia\Common\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Velia\Commnon\Casts\ErrorResponseCast;
use Velia\Common\Traits\Belongs\ToUser;

class RoutingLog extends Model
{
    use HasFactory;
    use ToUser;

    public const USER_FOREIGN_KEY = 'user_id';

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'mysql2_logger';

    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'request' => 'json',
        'headers' => 'json',
        'error' => ErrorResponseCast::class,
        'response' => 'json',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->setConnection('mysql')->belongsTo(User::class, self::USER_FOREIGN_KEY);
    }

    /**
     * @param mixed $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (is_array($this->attributes['request']) && array_key_exists($name, $this->attributes['request'])) {
            return $this->attributes['request'][$name];
        }
        return parent::__get($name);
    }
}
