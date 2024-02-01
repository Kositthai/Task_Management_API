<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateFields
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedFields): Response
    {

       $requestFields = collect(array_keys($request->all()));
       $containExpectedFields = $requestFields->contains(function($field) use ($allowedFields) {
            return !in_array($field, $allowedFields);
       } );

       if($containExpectedFields) {
        return response()->json(['error' => 'Unexpected fields detected.'], 422);
       }

        return $next($request);
    }
}
