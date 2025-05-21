<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #0079BF;
            --secondary-color: #70B500;
            --accent-color: #FF9F1A;
            --success-color: #38B000;
            --warning-color: #FFC43D;
            --danger-color: #E63946;
            --light-color: #F8F9FA;
            --dark-color: #172B4D;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8F9FA;
            color: #172B4D;
        }

        .navbar-brand {
            font-weight: 600;
        }

        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: #fff;
            border-right: 1px solid #E9ECEF;
        }

        .sidebar .nav-link {
            color: #495057;
            padding: 0.75rem 1.25rem;
            border-radius: 0.25rem;
            margin: 0.25rem 0;
        }

        .sidebar .nav-link:hover {
            background-color: #F1F3F5;
        }

        .sidebar .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .kanban-board {
            overflow-x: auto;
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            min-height: calc(100vh - 130px);
        }

        .kanban-column {
            background-color: #EBF0F7;
            border-radius: 0.5rem;
            width: 300px;
            min-width: 300px;
            max-height: 100%;
            display: flex;
            flex-direction: column;
        }

        .kanban-column-header {
            padding: 0.75rem;
            font-weight: 600;
            border-bottom: 1px solid #DEE2E6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kanban-column-body {
            padding: 0.75rem;
            overflow-y: auto;
            flex-grow: 1;
        }

        .kanban-task {
            background-color: white;
            border-radius: 0.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 0.75rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: box-shadow 0.15s ease;
        }

        .kanban-task:hover {
            box-shadow: 0 2px 5px rgba(0,0,0,0.15);
        }

        .kanban-task-title {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .kanban-task-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.875rem;
            color: #6C757D;
            margin-top: 0.75rem;
        }

        .task-badges {
            display: flex;
            gap: 0.5rem;
        }

        .dropzone {
            min-height: 10rem;
            border: 2px dashed #DEE2E6;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .comment {
            margin-bottom: 1rem;
            background-color: #F8F9FA;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .comment-user {
            font-weight: 500;
        }

        .comment-time {
            color: #6C757D;
            font-size: 0.875rem;
        }

        .attachments {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            background-color: #F8F9FA;
            border-radius: 0.25rem;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
        }

        .attachment-icon {
            margin-right: 0.75rem;
            color: #6C757D;
        }

        .attachment-details {
            flex-grow: 1;
        }

        .attachment-filename {
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .attachment-info {
            color: #6C757D;
            font-size: 0.75rem;
        }

        .task-detail-section {
            margin-bottom: 2rem;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #0079BF;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .avatar-sm {
            width: 24px;
            height: 24px;
            font-size: 0.75rem;
        }

        /* Animation classes */
        .slide-in {
            animation: slideIn 0.3s forwards;
        }

        .fade-in {
            animation: fadeIn 0.3s forwards;
        }

        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Dragula styles */
        .gu-mirror {
            position: fixed !important;
            margin: 0 !important;
            z-index: 9999 !important;
            opacity: 0.8;
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)";
            filter: alpha(opacity=80);
        }

        .gu-hide {
            display: none !important;
        }

        .gu-unselectable {
            -webkit-user-select: none !important;
            -moz-user-select: none !important;
            -ms-user-select: none !important;
            user-select: none !important;
        }

        .gu-transit {
            opacity: 0.2;
            -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)";
            filter: alpha(opacity=20);
        }
    </style>

    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    
    <!-- Dragula for drag and drop -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.7.3/dragula.min.js"></script>
    
    <!-- Laravel Echo and Pusher for real-time updates -->
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js"></script>

    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        @auth
            // Setup Laravel Echo for authenticated users
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('PUSHER_APP_KEY') }}',
                cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
                forceTLS: true,
                authorizer: (channel, options) => {
                    return {
                        authorize: (socketId, callback) => {
                            fetch('/broadcasting/auth', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    socket_id: socketId,
                                    channel_name: channel.name
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                callback(false, data);
                            })
                            .catch(error => {
                                callback(true, error);
                            });
                        }
                    };
                },
            });

            // Listen for user notifications
            window.Echo.private('user.{{ Auth::id() }}')
                .listen('.task.assigned', (e) => {
                    // Create a notification
                    const notification = document.createElement('div');
                    notification.classList.add('toast', 'fade-in');
                    notification.innerHTML = `
                        <div class="toast-header">
                            <strong class="me-auto">Task Assigned</strong>
                            <small>Just now</small>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            You've been assigned the task: ${e.title}
                        </div>
                    `;
                    
                    const toastContainer = document.querySelector('.toast-container') || 
                        (() => {
                            const container = document.createElement('div');
                            container.classList.add('toast-container', 'position-fixed', 'bottom-0', 'end-0', 'p-3');
                            document.body.appendChild(container);
                            return container;
                        })();
                    
                    toastContainer.appendChild(notification);
                    const toast = new bootstrap.Toast(notification);
                    toast.show();
                });
        @endauth
    </script>

    @stack('scripts')
</body>
</html>