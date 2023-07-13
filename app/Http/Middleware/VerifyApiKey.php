<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Crypt;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key');

        if (!$apiKey) {
            return response()->json(['message' => 'API key missing'], 401);
        }

        $isValidKey = Crypt::decryptString($apiKey) === config('api.key');

        if (!$isValidKey) {
            return response()->json(['message' => 'Invalid API key'], 401);
        }

        return $next($request);
    }
}
