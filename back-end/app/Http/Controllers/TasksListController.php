<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    public function show(TasksList $tasksList): TasksList
    {
        return $tasksList;
    }

    public function store(Request $request): JsonResponse
    {
        $tasksList = TasksList::create($request->all());

        return response()->json($tasksList, 201);
    }

    public function update(Request $request, TasksList $tasksList): JsonResponse
    {
        $tasksList->update($request->all());

        return response()->json($tasksList);
    }

    public function delete(TasksList $tasksList): JsonResponse
    {
        $tasksList->tasks()->delete();
        $tasksList->delete();

        return response()->json(null, 204);
    }
}
