<?php
/**
 * File: CompilationController.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Http\Controllers\Compilations;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\Factory;
use App\Repositories\Compilations\CompilationLogRepository;
use App\Http\Middleware\CompilationBuilder;
use App\Models\Compilations\Compilation;
use App\Http\Controllers\Controller;

/**
 * Class CompilationController
 * @package Http\Controllers\Compilations
 */
class CompilationController extends Controller
{
    /**
     * @var CompilationLogRepository
     */
    protected $repository;

    /**
     * Create a new controller instance.
     *
     * @param CompilationLogRepository $repository
     */
    public function __construct(CompilationLogRepository $repository)
    {
        $this->repository = $repository;
        // Setup first compilation in queue
        $this->middleware([
            CompilationBuilder::class,
        ]);
    }

    /**
     * @return Factory|View
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function index(): View
    {
        /** @var User $user */
        $user = \Auth::authenticate();
        /** @var Compilation[]|Collection $compilations */
        $compilations = $user->compilations()
            ->with('videos')
            ->latest()
            ->paginate(6);

        $isStandingInQueue = $this->repository->isStandingInQueue($user, Carbon::now());

        return view('compilations', compact('compilations', 'isStandingInQueue'));
    }

    /**
     * @param Request $request
     * @param Compilation $compilation
     * @return Factory|View
     */
    public function show(Request $request, Compilation $compilation): View
    {
        $videos = $compilation->videos;
        return view('compilation', compact('compilation', 'videos'));
    }
}
