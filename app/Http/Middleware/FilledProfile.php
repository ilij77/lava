<?php

namespace App\Http\Middleware;

use Closure;

class FilledProfile
{
    public function handle($request, Closure $next)
    {
        $user=\Auth::user();
        if (empty($user->hasFilledProfile())){
            return redirect()->route('cabinet.profile.home')
                ->with('error','Please fill your profile and verify your phone.');
        }
        return $next($request);
    }
}
