<?php
/**
 * File: TagService.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Services\Tags;

use App\Models\User;
use App\Models\Compilations\Tag;
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
     * @param array $tagNames
     * @return Collection
     * @throws \Exception
     */
    protected function getTags(array $tagNames): Collection
    {
        $idsTags = \array_filter($tagNames, function ($value) {
            return is_numeric($value);
        });

        /** @var Collection|Tag[] $tags */
        $tags = Tag::findMany($idsTags);
        $creatingTagsNames = \array_diff($tagNames, $idsTags);

        foreach ($creatingTagsNames as $name) {
            $tag = $this->repository->getByName($name);

            if (\is_null($tag)) {
                $tag = $this->repository->create(['name' => $name]);
            }

            $tags->push($tag);
        }

        return $tags;
    }
}
