<?php
/**
 * File: TagRepository.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace Repositories\Users;

use App\Models\Tag;
use Repositories\RepositoryInterface;

/**
 * Class TagRepository
 * @package Repositories\Users
 */
class TagRepository implements RepositoryInterface
{
    /**
     * @param array $attributes
     * @return Tag
     * @throws \Exception
     */
    public function create(array $attributes): Tag
    {
        $user = Tag::create($attributes);
        return $user;
    }

    /**
     * @param Tag $tag
     * @param array $attributes
     */
    public function update($tag, array $attributes): void
    {
        $tag->update($attributes);
    }

    /**
     * @param int $id
     * @return Tag|null
     */
    public function get(int $id): ?Tag
    {
        return Tag::whereKey($id)->first();
    }

    /**
     * @param string $name
     * @return Tag|null
     */
    public function getByName(string $name): ?Tag
    {
        return Tag::whereName($name)->first();
    }

    /**
     * @param int $id
     * @return Tag
     */
    public function find(int $id): Tag
    {
        return Tag::findOrFail($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return (bool)\DB::table(Tag::TABLE)
            ->delete($id);
    }

    /**
     * @return Tag
     */
    public function getModel(): Tag
    {
        return new Tag();
    }

    /**
     * @param string $name
     * @return bool
     */
    public function isNameExist(string $name): bool
    {
        return \DB::table(Tag::TABLE)
            ->where('name', '=', $name)
            ->exists();
    }
}
