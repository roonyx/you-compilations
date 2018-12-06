<?php
/**
 * File: TagResource.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-09
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Http\Resources\Api;

use App\Models\Tag;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class TagsResource
 * @package App\Http\Resources
 *
 * @mixin Tag
 */
class TagResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->name,
        ];
    }
}
