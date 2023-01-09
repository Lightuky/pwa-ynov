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
     * @param $operations
     * @param $contents
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $operations, $contents): mixed
    {
        $apiToken = $request->header('api_key');
        $hashedToken = hash('sha256', $apiToken);


        if ($apiToken == null) {
            return response()->json(['error' => "Aucun Token d'API n'a été fourni."]);
        } else {
            $api_client = DB::table('api_clients')->where('api_token', $hashedToken)->first();

            if ($api_client == null) {
                return response()->json(['error' => "Ce Token d'API n'est pas valide."]);
            } else {
                $tokenOperations = json_decode($api_client->operations);
                $foundOperations = in_array($operations, $tokenOperations);
                $tokenContents = json_decode($api_client->perm_contents);
                $foundContents = in_array($contents, $tokenContents);
                $errorResponse = "Ce Token API n'a pas accès aux ";

                if (!$foundOperations || !$foundContents) {
                    if (!$foundOperations && !$foundContents) {
                        $errorResponse .= "méthodes '" . ucfirst($operations) . "' ainsi qu'aux ressources '" . ucfirst($contents) . "'";
                    } else {
                        if (!$foundOperations) {
                            $errorResponse .= "méthodes '" . ucfirst($operations) . "'";
                        } else {
                            $errorResponse .= "ressources '" . ucfirst($contents) . "'";
                        }
                    }
                    return response()->json(['error' => $errorResponse]);
                } else {
                    return $next($request);
                }
            }
        }
    }
}
