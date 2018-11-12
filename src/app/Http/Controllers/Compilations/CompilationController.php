<?php
/**
 * File: CompilationController.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-06
 * Copyright (c) 2018
 */

declare(strict_types=1);

namespace App\Http\Controllers\Compilations;

use App\Models\Tag;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Compilations\Compilation;
use App\Http\Middleware\CompilationBuilder;

/**
 * Class CompilationController
 * @package Http\Controllers\Compilations
 */
class CompilationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Setup first compilation in queue
        $this->middleware([
            CompilationBuilder::class,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function index(): View
    {
        /** @var User $user */
        $user = \Auth::getUser();
        /** @var Tag[] $tags */
        $tags = $user->tags;

        $compilations = $user->compilations()
            ->with('videos')
            ->paginate(5);

        return view('compilations', [
            'tags' => $tags,
            'compilations' => $compilations,
        ]);
    }

    /**
     * @param Request $request
     * @param Compilation $compilation
     * @return \Illuminate\Contracts\View\Factory|View
     */
    public function show(Request $request, Compilation $compilation): View
    {
        /** @var User $user */
        $user = \Auth::getUser();
        /** @var Tag[] $tags */
        $tags = $user->tags();

        $videos = $compilation->videos;

        return view('compilation', [
            'tags' => $tags,
            'compilation' => $compilation,
            'videos' => $videos
        ]);
    }
}
