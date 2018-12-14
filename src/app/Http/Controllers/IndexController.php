<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Contracts\View\Factory;
use App\Models\Compilations\Compilation;

/**
 * Class IndexController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /** @var Compilation[] $compilations */
        $compilations = Compilation::query()
            ->with('videos')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('index', [
            'compilations' => $compilations,
        ]);
    }

    /**
     * @return Factory|View
     */
    public function about()
    {
        return view('about');
    }
}
