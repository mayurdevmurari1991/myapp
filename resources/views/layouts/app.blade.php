<!-- resources/views/layouts/app.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Tasks - AJAX CRUD</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ url('/') }}">Daily Tasks</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tasks.index') }}">Tasks</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-4">
        @yield('content')
    </div>
</body>
</html>
