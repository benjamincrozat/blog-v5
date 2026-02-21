<?php

namespace App\Http\Controllers\User;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Defines the ListUserCommentsController implementation.
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
