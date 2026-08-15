<?php

namespace App\Http\Controllers\User;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

/**
 * Shows the signed-in user's own link-submission history.
 *
 * The query always starts from the user in the current request and includes
 * pending, approved, and declined links in pages of 10. The auth middleware
 * rejects guests before this controller runs.
 */
class ListUserLinksController extends Controller
{
    public function __invoke(Request $request) : View
    {
        return view('user.links', [
            'links' => $request->user()->links()->paginate(10),
        ]);
    }
}
