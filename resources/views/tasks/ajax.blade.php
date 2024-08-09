<!-- resources/views/tasks/ajax.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tasks</h1>

    <div id="error_message" class="alert alert-danger" style="display: none;"></div>
    <div id="success_message" class="alert alert-success" style="display: none;"></div>

    <form id="taskForm" name="taskForm">
        @csrf
        <input type="hidden" id="task_id">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <button type="submit" id="saveBtn" class="btn btn-success">Save Task</button>
    </form>

    <!-- Toggle Button -->
    <button id="toggleTasksBtn" class="btn btn-primary mt-4">
        {{ $displayAll ? 'Show Pending Tasks' : 'Display All Tasks' }}
    </button>

    <h2 class="mt-4">{{ $displayAll ? 'All Tasks List' : 'Pending Tasks List' }}</h2>
    <table class="table table-bordered" id="tasksTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
            <tr id="task_id_{{ $task->id }}">
                <td>{{ $task->id }}</td>
                <td>{{ $task->title }}</td>
                <td>{{ $task->description }}</td>
                <td>
                    <span class="badge badge-{{ $task->is_completed ? 'success' : 'warning' }}">
                        {{ $task->is_completed ? 'Completed' : 'Pending' }}
                    </span>
                </td>
                <td>
                    @if (!$task->is_completed)
                    <a href="javascript:void(0)" data-id="{{ $task->id }}" class="btn btn-success btn-sm completeBtn">Complete</a>
                    @endif
					<a href="javascript:void(0)" data-id="{{ $task->id }}" class="btn btn-danger btn-sm deleteBtn">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        // Toggle Display All or Pending Tasks
        $('#toggleTasksBtn').click(function () {
            let displayAll = {{ $displayAll ? 'true' : 'false' }};
            let url = displayAll ? '{{ route('tasks.index') }}' : '{{ route('tasks.index') }}?display_all=true';

            window.location.href = url;
        });

        // Create or Update Task
        $('#taskForm').submit(function (e) {
            e.preventDefault();
            let task_id = $('#task_id').val();
            let formData = $(this).serialize();

            $.ajax({
                url: task_id ? '/tasks/' + task_id : '/tasks',
                method: task_id ? 'PUT' : 'POST',
                data: formData,
                success: function (response) {
                    $('#taskForm')[0].reset();
                    $('#task_id').val('');
                    $('#saveBtn').text('Save Task');
                    $('#success_message').text(task_id ? 'Task updated successfully' : 'Task created successfully').show().fadeOut(3000);

                    if (task_id) {
                        let taskRow = $('#task_id_' + task_id);
                        taskRow.find('td:nth-child(2)').text(response.title);
                        taskRow.find('td:nth-child(3)').text(response.description);
                        taskRow.find('td:nth-child(4)').html('<span class="badge badge-' + (response.is_completed ? 'success' : 'warning') + '">' + (response.is_completed ? 'Completed' : 'Pending') + '</span>');
                    } else {
                        $('#tasksTable tbody').append('<tr id="task_id_' + response.id + '"><td>' + response.id + '</td><td>' + response.title + '</td><td>' + response.description + '</td><td><span class="badge badge-warning">Pending</span></td><td><a href="javascript:void(0)" data-id="' + response.id + '" class="btn btn-warning btn-sm editBtn">Edit</a><a href="javascript:void(0)" data-id="' + response.id + '" class="btn btn-danger btn-sm deleteBtn">Delete</a><a href="javascript:void(0)" data-id="' + response.id + '" class="btn btn-success btn-sm completeBtn">Complete</a></td></tr>');
                    }
                },
                error: function (xhr) {
                    $('#error_message').text(xhr.responseJSON.message).show().fadeOut(3000);
                }
            });
        });

        // Edit Task
        $(document).on('click', '.editBtn', function () {
            let task_id = $(this).data('id');

            $.get('/tasks/' + task_id + '/edit', function (response) {
                $('#task_id').val(response.id);
                $('#title').val(response.title);
                $('#description').val(response.description);
                $('#saveBtn').text('Update Task');
            });
        });

        // Delete Task
        $(document).on('click', '.deleteBtn', function () {
            let task_id = $(this).data('id');

            if (confirm('Are you sure you want to delete this task?')) {
                $.ajax({
                    url: '/tasks/' + task_id,
                    method: 'DELETE',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        $('#task_id_' + task_id).remove();
                        $('#success_message').text(response.success).show().fadeOut(3000);
                    },
                    error: function (xhr) {
                        $('#error_message').text(xhr.responseJSON.message).show().fadeOut(3000);
                    }
                });
            }
        });

        // Complete Task
        $(document).on('click', '.completeBtn', function () {
            let task_id = $(this).data('id');

            if (confirm('Are you sure you want to mark this task as complete?')) {
                $.ajax({
                    url: '/tasks/' + task_id + '/complete',
                    method: 'POST',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        let taskRow = $('#task_id_' + task_id);
                        taskRow.find('td:nth-child(4)').html('<span class="badge badge-success">Completed</span>');
                        taskRow.find('.completeBtn').remove();
                        $('#success_message').text(response.success).show().fadeOut(3000);
                    },
                    error: function (xhr) {
                        $('#error_message').text(xhr.responseJSON.message).show().fadeOut(3000);
                    }
                });
            }
        });
    });
</script>
@endsection
