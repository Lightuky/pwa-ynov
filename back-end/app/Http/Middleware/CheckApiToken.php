<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $apiToken = $request->header('api_key');
        $hashedToken = hash('sha256', $apiToken);

        if ($apiToken == null) {
            return response()->json(['error' => "Aucun Token d'API n'a été fourni."]);
        } else {
            $api_client = DB::table('users')->where('api_token', $hashedToken)->first();

            if ($api_client == null) {
                return response()->json(['error' => "Ce Token d'API n'est pas valide."]);
            } else {
                return $next($request);
            }
        }
    }
}
