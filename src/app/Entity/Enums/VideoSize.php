<?php
/**
 * File: VideoSize.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-09
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Entity\Enums;

/**
 * Class VideoSize
 * @package Entity\Enums
 */
class VideoSize
{
    /**
     *  medium (320x180)
     */
    const MEDIUM = 'medium';
    /**
     * high (480x360)
     */
    const HIGH = 'high';
    /**
     * standard (640x480)
     */
    const STANDARD = 'standard';
    /**
     * maxres (1280x720)
     */
    const MAXRES = 'maxres';
}
