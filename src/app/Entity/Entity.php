<?php
/**
 * File: Entity.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-12-14
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Entity;

/**
 * Class Entity
 * @package App\Entity
 */
abstract class Entity
{
    /**
     * @param $object
     * @param $field
     * @param $default
     * @return mixed
     */
    protected static function getValue($object, $field, $default)
    {
        if (\property_exists($object, $field)) {
            return $object->{$field};
        }
        return $default;
    }
}
