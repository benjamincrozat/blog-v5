<?php

namespace App\Http\Controllers\User;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Shows the signed-in user's own comment history.
 *
 * The query always starts from the user in the current request, so it cannot be
 * pointed at another account. Comments are split into pages of 10, and the auth
 * middleware rejects guests before this controller runs.
 */
class ListUserCommentsController extends Controller
{
    public function __invoke(Request $request) : View
    {
        return view('user.comments', [
            'comments' => $request->user()->comments()->paginate(10),
        ]);
    }
}
