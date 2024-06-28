<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $userRole = \Auth::user()->role;

        // Pengecualian untuk rute create komentar
        if ($request->route()->getName() === 'komentars.create' || $request->route()->getName() === 'komentars.store') {
            return $next($request);
        }

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        return response()->view('errors.access_denied', [], 403);
    }
}
