<?php
/**
 * File: CompilationLog.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-09
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Models\Compilations;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CompilationLog
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\CompilationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\CompilationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\CompilationLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\CompilationLog whereByUserId($value)
 *
 * @mixin \Eloquent
 */
class CompilationLog extends Model
{
    /**
     * Table name
     */
    public const TABLE = 'compilation_logs';

    /**
     * Queue result success
     */
    public const SUCCESS = 'success';
    /**
     * Queue result failed
     */
    public const FAILED = 'failed';
    /**
     * Waiting in queue
     */
    public const WAITING = 'waiting';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'status',
    ];

    /**
     * The attributes that should be guarded for arrays.
     *
     * @var array
     */
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return bool
     */
    public function isSuccessfully(): bool
    {
        return $this->status === static::SUCCESS;
    }

    /**
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->status === static::FAILED;
    }

    /**
     * @return bool
     */
    public function isWaiting(): bool
    {
        return $this->status === static::WAITING;
    }
}
