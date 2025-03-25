@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Task List</h2>

        <ul>
            @foreach($tasks as $task)
                <li>{{ $task->title }} - {{ $task->status }}</li>
            @endforeach
        </ul>
    </div>
@endsection
