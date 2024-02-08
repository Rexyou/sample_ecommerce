<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = Auth::user();

        if($user->type == User::TYPE_ADMIN && $user->status == User::STATUS_ACTIVE){
            return $next($request);
        }

        return finalResponse(errorResponse("", "user_not_authenticated", Response::HTTP_UNAUTHORIZED));
    }
}
