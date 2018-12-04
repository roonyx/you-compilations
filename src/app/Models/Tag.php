<?php
/**
 * File: Tag.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * App\Models\Tag
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag whereByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Tag[]|Collection whereByNames($value)
 *
 * @mixin \Eloquent
 */
class Tag extends Model
{
    /**
     * Table name
     */
    public const TABLE = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
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
     * @param $names
     * @return Tag[]|Collection
     */
    public function scopeWhereByNames($names): Collection
    {
        return $this->where('name', '=', $names)->get();
    }

    /**
     * Get tags by user id
     *
     * @param int $userId
     * @return Tag[]|array
     */
    public function scopeWhereByUserId(int $userId): array
    {
        return self::query()
            ->leftJoin('user_tags', 'user_tags.tag_id', '=', 'tag.id')
            ->where('user_tags.user_id', '=', $userId)
            ->first();
    }
}
