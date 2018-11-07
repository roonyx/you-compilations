<?php
/**
 * File: TagService.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace Services\Tags;

use App\Models\Tag;
use App\Models\User;
use Repositories\Users\TagRepository;
use App\Http\Requests\Compilations\Tags\TagRequest;

/**
 * Class TagService
 * @package Services\Tags
 */
class TagService
{
    /**
     * @var TagRepository
     */
    protected $repository;

    /**
     * TagService constructor.
     * @param TagRepository $repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Store tag from request
     *
     * @param TagRequest $request
     * @return Tag[]|array
     * @throws \Exception
     */
    public function store(TagRequest $request): array
    {
        if (!$request->has('names')) {
            return [];
        }

        /** @var string[] $names */
        $names = $request->get('names');
        /** @var User $user */
        if ($user = \Auth::getUser()) {

            /** @var Tag[] $tags */
            if ($tags = $this->getTagsByNames($names)) {
                $user->syncTags($tags);
            }

            return $tags;
        }

        return [];
    }

    /**
     * @param array $names
     * @throws \Exception
     * @return int[]|array
     */
    protected function getTagsByNames(array $names): array
    {
        $tags = [];

        foreach ($names as $name) {
            /** @var Tag $tag */
            $tag = $this->repository->getByName($name);

            if (is_null($tag)) {
                $tag = $this->repository->create(['name' => $name]);
            }

            $tags[] = $tag->getKey();
        }

        return $tags;
    }
}
