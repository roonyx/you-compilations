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
use App\Models\User;
use Illuminate\Http\Request;
use Repositories\Users\TagRepository;
use Services\Tags\TagService;
use App\Http\Controllers\Controller;
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
     * @param TagService $service
     */
    public function __construct(TagService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = \Auth::getUser();
        /** @var Tag[] $tags */
        $tags  = $user->tags();


    }

    /**
     * @param TagRequest $request
     * @return array
     * @throws \Exception
     */
    public function store(TagRequest $request)
    {
        if ($this->service->store($request)) {
            return [
                'success' => true,
            ];
        }

        throw new BadRequestHttpException('Error when store tags.');
    }

    /**
     * @param TagRequest $request
     * @return array
     * @throws \Exception
     */
    public function edit(TagRequest $request)
    {
        if ($this->service->store($request)) {
            return [
                'success' => true,
            ];
        }

        throw new BadRequestHttpException('Error when edit tag.');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function destroy(Request $request, $id)
    {
        /** @var TagRepository $repository */
        $repository = app(TagRepository::class);
        if ($repository->delete($id)) {
            return [
                'success' => true,
            ];
        }

        throw new BadRequestHttpException('Error when delete tag.');
    }
}
