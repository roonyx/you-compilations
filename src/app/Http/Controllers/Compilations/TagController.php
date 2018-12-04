<?php
/**
 * File: TagController.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Http\Controllers\Compilations;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Services\Tags\TagService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TagResource;
use App\Http\Middleware\CompilationBuilder;
use App\Http\Requests\Compilations\Tags\TagRequest;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class TagController
 * @package App\Http\Controllers\Compilations
 */
class TagController extends Controller
{
    /**
     * @var TagService
     */
    protected $service;

    /**
     * TagController constructor.
     *
     * @param TagService $service
     */
    public function __construct(TagService $service)
    {
        $this->service = $service;

        // Setup first compilation in queue
        $this->middleware([
            CompilationBuilder::class,
        ]);
    }

    /**
     * @param Request $request
     * @param string $name
     * @return array|\Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request, ?string $name = '')
    {
        if (!empty($name)) {
            $tags = Tag::query()
                ->where('name', 'LIKE', "{$name}%")
                ->limit(15)
                ->get();

            return TagResource::collection($tags);
        }

        return [];
    }

    /**
     * @param TagRequest $request
     * @return array
     * @throws \Exception
     */
    public function store(TagRequest $request)
    {
        if ($this->service->store($request)) {
            return redirect('compilations');
        }

        throw new BadRequestHttpException('Error when store tags.');
    }
}
