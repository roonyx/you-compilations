<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Compilations\Compilation;
use Illuminate\Support\Collection;

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
            ->latest()
            ->limit(15)
            ->get();

        return view('index', [
            'compilations' => $compilations,
        ]);
    }
}
