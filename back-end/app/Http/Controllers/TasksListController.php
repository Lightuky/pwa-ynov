<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\TasksList;

class TasksListController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $api_token = $request->header('api-key');
        $found_user = DB::table('users')
            ->where('api_token', $api_token)
            ->get();
        $user_taskslists = DB::table('tasks_lists')
            ->where('creator_id', $found_user[0]->id)
            ->get();
        foreach ($user_taskslists as $user_taskslist) {
            $user_taskslist->tasks = DB::table('tasks')
                ->where('creator_id', $found_user[0]->id)
                ->where('taskslist_id', $user_taskslist->id)
                ->get();
        }
        return response()->json($user_taskslists, 201);
    }

    public function show(Request $request): JsonResponse
    {
        $taskslist_id = $request->route('taskslist');
        $api_token = $request->header('api-key');
        $found_user = DB::table('users')
            ->where('api_token', $api_token)
            ->get();
        $taskslist = DB::table('tasks_lists')
            ->where('creator_id', $found_user[0]->id)
            ->where('id', $taskslist_id)
            ->get()->first();

        return response()->json($taskslist ?: "null", 201);
    }

    public function store(Request $request): JsonResponse
    {
        $api_token = $request->header('api-key');
        $found_user = DB::table('users')
            ->where('api_token', $api_token)
            ->get();
        DB::table('tasks_lists')->insert([
            'name' => $request->name,
            'creator_id' => $found_user[0]->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $tasksList = TasksList::latest('id')->first();

        return response()->json($tasksList, 201);
    }

    public function update(Request $request): JsonResponse
    {
        $taskslist_id = $request->route('taskslist');
        $api_token = $request->header('api-key');
        $found_user = DB::table('users')
            ->where('api_token', $api_token)
            ->get();
        $taskslist = DB::table('tasks_lists')
            ->where('creator_id', $found_user[0]->id)
            ->where('id', $taskslist_id)
            ->update(['name' => $request->name]);

        return response()->json($taskslist, 201);
    }

    public function delete(TasksList $tasksList): JsonResponse
    {
        $tasksList->tasks()->delete();
        $tasksList->delete();

        return response()->json(null, 204);
    }
}
