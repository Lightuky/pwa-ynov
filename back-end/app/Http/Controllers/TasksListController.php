<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\TasksList;

class TasksListController extends Controller
{
    public function index(): Collection
    {
        return TasksList::all();
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
