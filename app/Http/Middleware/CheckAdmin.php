<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // allow the request to continue if the user has roles
        // https://stackoverflow.com/questions/65391062/laravel-query-builder-with-the-user-and-auth
        if (Auth::check() && User::has('roles')->find(Auth::id())) {
            return $next($request);
        } else {
            // return to home with a rejection message otherwise
            return redirect(route('posts.index'))->with('status', 'You cannot access admin pages.');
        }
    }
}
