<?php
/**
 * File: RepositoryInterface.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface RepositoryInterface
 * @package Repositories
 */
interface RepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model|\Eloquent
     * @throws \Exception
     */
    public function create(array $attributes);

    /**
     * @param $model Model|\Eloquent
     * @param array $attributes
     */
    public function update(Model $model, array $attributes);

    /**
     * @param int $id
     * @return Model|\Eloquent|null
     */
    public function get(int $id);

    /**
     * @param int $id
     * @return Model|\Eloquent
     */
    public function find(int $id);

    /**
     * @return Model|\Eloquent
     */
    public function getModel();
}
