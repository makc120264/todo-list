<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    public function edit(Task $task)
    {
        if ($task->user_id !== Auth::id()) {
            abort(403);
        }
        return view('tasks.edit', compact('task'));
    }

    /**
     * @param Request $request
     * @param null $taskId
     * @return JsonResponse|View|Application
     */
    public function index(Request $request, $taskId = null): JsonResponse|View|Application
    {
        $query = Task::where('user_id', Auth::id());

        if ($taskId) {
            $query->where('id', $taskId);
        }

        $tasks = $query->get();

        if ($request->is('api/*')) {
            return response()->json($tasks);
        }

        return view('tasks.index', compact('tasks'));
    }


    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:pending,in progress,completed',
            ]);

            $task = Task::create([
                'user_id' => Auth::id(),
                'title' => empty($data["title"]) ? $request->title : $data["title"],
                'description' => empty($data['description']) ? $request->description : $data["description"],
                'status' => empty($data['status']) ? $request->status : $data["status"],
            ]);

            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Task created successfully',
                    'task' => $task,
                ], 200);
            }

            return redirect()->route('tasks.index')->with('success', 'Task added successfully!');
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong, please try again later.'], 500);
        }
    }


    /**
     * @param Request $request
     * @param Task $task
     * @return JsonResponse|RedirectResponse
     */
    public function update(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        try {
            if ($task->user_id !== Auth::id()) {
                return $request->is('api/*')
                    ? response()->json(['error' => 'Unauthorized'], 403)
                    : redirect()->route('tasks.index')->with('error', 'Unauthorized');
            }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|in:pending,in progress,completed',
            ]);

            $task->update($validated);

            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Task updated successfully',
                    'task' => $task,
                ]);
            }

            return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['error' => 'Something went wrong, please try again later.'], 500);
        }
    }

    /**
     * @param Request $request
     * @param Task $task
     * @return JsonResponse|RedirectResponse
     */
    public function destroy(Request $request, Task $task): JsonResponse|RedirectResponse
    {
        if ($task->user_id !== Auth::id()) {
            if ($request->is('api/*')) {
                return response()->json(['error' => 'Unauthorized'], 403);
            } else {
                return redirect()->route('tasks.index')->with('error', 'Unauthorized');
            }
        }

        $task->delete();

        if ($request->is('api/*')) {
            return response()->json(['message' => 'Task deleted successfully']);
        } else {
            return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
        }
    }

}
