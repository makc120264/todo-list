<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function tasksIndex(): Factory|View|Application
    {
        $tasks = Task::where('user_id', Auth::id())->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * @param $taskId
     * @return JsonResponse
     */
    public function index($taskId = null): JsonResponse
    {
        $query = Task::where('user_id', Auth::id());

        if ($taskId) {
            $query->where('id', $taskId);
        }

        $tasks = $query->get();

        return response()->json($tasks);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in progress,completed',
        ]);

        $task = Task::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json($task, 201);
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return JsonResponse
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,in progress,completed',
        ]);

        $task->update($request->all());

        return response()->json($task);
    }

    /**
     * @param Task $task
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        if ($task->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
