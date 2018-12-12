<?php
/**
 * File: AvatarSize.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-12-12
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Entity\Enums;

/**
 * Class AvatarSize
 * @package App\Entity\Enums
 */
class AvatarSize
{
    /**
     * Default (88x88)
     */
    public const DEFAULT = 'default';
    /**
     * Medium (240x240)
     */
    public const MEDIUM = 'medium';
    /**
     * High (800x800)
     */
    public const HIGH = 'high';
}
