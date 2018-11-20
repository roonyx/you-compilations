<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Compilations\Compilation;

/**
 * Class IndexController
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compilations = Compilation::query()
            ->latest()
            ->limit(15)
            ->get();

        return view('index', [
            'compilations' => $compilations,
        ]);
    }
}
