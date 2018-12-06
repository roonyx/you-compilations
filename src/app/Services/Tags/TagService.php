<?php
/**
 * File: TagService.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Services\Tags;

use App\Models\Tag;
use App\Models\User;
use App\Repositories\Compilations\TagRepository;
use App\Http\Requests\Compilations\Tags\TagRequest;
use Illuminate\Support\Collection;

/**
 * Class TagService
 * @package Services\Tags
 */
class TagService
{
    /**
     * @var TagRepository
     */
    public $repository;

    /**
     * TagService constructor.
     * @param TagRepository $repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store tags from request
     *
     * @param TagRequest $request
     * @return array|Collection
     * @throws \Exception
     */
    public function store(TagRequest $request)
    {
        if (!$request->has('tags')) {
            return [];
        }

        /** @var int[] $ids */
        $ids = $request->get('tags');

        /** @var User $user */
        $user = \Auth::authenticate();

        if ($tags = $this->getTags($ids)) {
            $user->syncTags($tags);
        }

        return $tags;
    }

    /**
     * @param array $ids
     * @return Collection
     * @throws \Exception
     */
    protected function getTags(array $ids): Collection
    {
        $idsTags = \array_filter($ids, function ($value) {
            return \filter_var($value, \FILTER_VALIDATE_INT);
        });

        $names = \array_diff($ids, $idsTags);
        /** @var Tag[]|Collection $tags */
        $tags = Tag::findMany($idsTags);

        foreach ($names as $name) {
            /** @var Tag $tag */
            $tag = $this->repository->getByName($name);

            if (\is_null($tag)) {
                $tag = $this->repository->create(['name' => $name]);
            }

            $tags->push($tag);
        }

        return $tags;
    }
}
