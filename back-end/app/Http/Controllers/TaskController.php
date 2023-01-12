<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Task;

class TaskController extends Controller
{
    public function index(): Collection
    {
        return Task::all();
    }

    public function show(Request $request): JsonResponse
    {
        $task_id = $request->route('task');
        $api_token = $request->header('api-key');
        $found_user = DB::table('users')
            ->where('api_token', $api_token)
            ->get();
        $task = DB::table('tasks')
            ->where('creator_id', $found_user[0]->id)
            ->where('id', $task_id)
            ->get()->first();
        return response()->json($task ?: "null", 201);
    }

    public function store(Request $request): JsonResponse
    {
        $api_token = $request->header('api-key');
        $found_user = DB::table('users')
            ->where('api_token', $api_token)
            ->get();
        DB::table('tasks')->insert([
            'name' => $request->name,
            'description' => $request->description,
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
            'creator_id' => $found_user[0]->id,
            'taskslist_id' => $request->taskslist_id,

        ]);
        $task = Task::latest('id')->first();

        return response()->json($task, 201);
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $task->update($request->all());

        return response()->json($task);
    }

    public function delete(Task $task): JsonResponse
    {
        $task->delete();

        return response()->json(null, 204);
    }
}
