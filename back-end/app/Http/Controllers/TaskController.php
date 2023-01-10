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

    public function show(Task $task): Task
    {
        return $task;
    }

    public function store(Request $request): JsonResponse
    {
        $task = Task::create($request->all());

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
