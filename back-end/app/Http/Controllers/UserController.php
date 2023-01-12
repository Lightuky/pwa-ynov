<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function index(): Collection
    {
        return User::all();
    }

    public function show(User $user): User
    {
        return $user;
    }

    public function store(Request $request): JsonResponse
    {
        if ($request->filled('first_name') && $request->filled('last_name') && $request->filled('email') && $request->filled('password')) {
            $token = Str::random(60);
            $hashed_token = hash('sha256', $token);
            User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => hash('sha256', $request->password),
                'api_token' => $hashed_token,
            ]);

            $session = ['first_name' => $request->first_name, 'last_name' => $request->last_name, 'email' => $request->email, 'api_token' => $hashed_token];
            return response()->json($session, 201);
        }
        else {
            return response()->json("Un ou plusieurs champ sont vides ou non transmis.");
        }
    }

    public function login(Request $request): JsonResponse
    {
        if ($request->filled('email') && $request->filled('password')) {
            $found_user = DB::table('users')
                ->where('email', $request->email)
                ->where('password', hash('sha256', $request->password))
                ->get();
            if ($found_user->isEmpty()) {
                return response()->json("Impossible de récupérer un utilisateur avec les informations fournies.");
            }
            else {
                $session = [
                    'first_name' => $found_user[0]->first_name, 'last_name' => $found_user[0]->last_name,
                    'email' => $found_user[0]->email, 'api_token' => $found_user[0]->api_token
                ];
                return response()->json($session, 201);
            }
        }
        else {
            return response()->json("Un ou plusieurs champ sont vide ou non transmis.");
        }
    }

    public function update(Request $request, User $user): JsonResponse
    {
        $user->update($request->all());

        return response()->json($user);
    }

    public function delete(User $user): JsonResponse
    {
        $user->tasksLists()->delete();
        $user->delete();

        return response()->json(null, 204);
    }
}
