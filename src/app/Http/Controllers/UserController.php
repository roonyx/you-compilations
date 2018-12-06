<?php
/**
 * File: UserController.php
 * Author: Vladimir Pogarsky <pogarsky.vladimir@roonyx.team>
 * Date: 2018-11-13
 * Copyright Roonyx.tech (c) 2018
 */

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;

/**
 * Class UserController
 * @package Http\Controllers
 */
class UserController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = \Auth::user();
        $tags = $user->tags;

        return view('user.settings', [
            'tags' => $tags,
        ]);
    }
}
