<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeacherOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Unauthorized',
                'data' => null
            ], 401);
        }

        if ($request->user()->role !== 'teacher') {
            return response()->json([
                'status' => 'fail',
                'message' => 'Forbidden',
                'data' => null
            ], 403);
        }

        return $next($request);
    }
}
