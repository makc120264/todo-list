@extends('layouts.app')

@section('title', 'Task List')

@section('content')
    <div class="container">
        <h1 class="mb-4">Task List</h1>
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('tasks.store') }}" method="POST" class="d-flex">
                    @csrf
                    <input type="text" name="title" class="form-control me-2" placeholder="Enter task title" required>
                    <input type="text" name="description" class="form-control me-2" placeholder="Enter task title">
                    <select name="status" class="form-control me-2">
                        <option value="0">Select status</option>
                        <option value="pending">Pending</option>
                        <option value="in progress">In progress</option>
                        <option value="completed">Completed</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($tasks as $index => $task)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $task->title }}</td>
                    <td>{{ $task->description }}</td>
                    <td>
                    <span class="badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in progress' ? 'primary' : 'secondary') }}">
                        {{ ucfirst($task->status) }}
                    </span>
                    </td>
                    <td>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
