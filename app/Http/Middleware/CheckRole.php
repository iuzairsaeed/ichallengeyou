<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\School;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role = '')
    {
        $user = $request->user();
        $disabledMessage = 'Your account has been disabled. Please contact support.';
        if($user && $user->isActive)
        {
            if($user->role == $role){
                return $next($request);
            }
            auth()->logout();
            if (! $request->expectsJson()) {
                return abort(403, 'Unauthorized action.');
            }
            return response(['message' => 'Unauthorized action.'], 403);
        }
        auth()->logout();
        if (! $request->expectsJson()) {
            return abort(403, $disabledMessage);
        }
        return response(['message' => $disabledMessage], 403);
    }
}
