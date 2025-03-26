@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')
    <div class="container">
        <h1 class="mb-4">Edit Task</h1>
        <form action="{{ route('logout') }}" method="POST" class="hstack gap-3">
            @csrf
            <button type="submit" class="btn btn-danger ms-auto my-3">Logout</button>
        </form>
        <div class="card">
            <div class="card-header bg-primary text-white">
                Edit Task
            </div>
            <div class="card-body">
                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <!-- Task Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ $task->title }}" required>
                    </div>
                    <!-- Task Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Task Description</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ $task->description }}</textarea>
                    </div>
                    <!-- Task Status -->
                    <div class="mb-3">
                        <label for="status" class="form-label">Task Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="" disabled>Select status</option>
                            <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in progress" {{ $task->status === 'in progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
