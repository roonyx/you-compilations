<?php
/**
 * File: Compilation.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Models\Compilations;

use App\Models\User;
use App\Entity\Enums\VideoSize;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Compilations\Compilation
 *
 * @property string $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Compilation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Compilation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Compilations\Compilation whereUpdatedAt($value)
 *
 * @property Video[] $videos
 *
 * @mixin \Eloquent
 */
class Compilation extends Model
{
    /**
     * Table name
     */
    public const TABLE = 'compilations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
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
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function videos(): HasMany
    {
        return $this->hasMany(Video::class, 'compilation_id', 'id');
    }

    /**
     * @return Collection
     */
    public function authors(): Collection
    {
        return Author::query()
            ->select('authors.*')
            ->leftJoin(Video::TABLE, 'videos.author_id', '=', 'authors.id')
            ->leftJoin(Compilation::TABLE, 'compilations.id', '=', 'videos.compilation_id')
            ->where('compilations.id', '=', \DB::raw($this->id))
            ->get();
    }

    /**
     * @throws \Exception
     */
    public function duration()
    {
        $videos = $this->videos;

        $zeroTime = new \DateTime('00:00');
        $diffTime = clone $zeroTime;

        foreach ($videos as $video) {
            if ($video->duration) {
                $diffTime->add(
                    new \DateInterval($video->duration)
                );
            }
        }

        if ($zeroTime->getTimestamp() == $diffTime->getTimestamp()) {
            return '';
        }

        return $diffTime
            ->diff($zeroTime)
            ->format('%h:%i:%s');
    }

    /**
     * @param Compilation $compilation
     * @return array
     */
    public static function prettyImage(Compilation $compilation): string
    {
        /** @var Video[]|Collection $videos */
        $videos = $compilation->videos;

        if ($videos->isEmpty()) {
            return '';
        }

        /** @var Video $video */
        $video = $videos->first();

        return $video->prettyImage();
    }
}
