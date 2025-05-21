<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #0079BF;
            --secondary-color: #70B500;
            --accent-color: #FF9F1A;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .hero {
            background: linear-gradient(135deg, var(--primary-color), #005c90);
            color: white;
            padding: 6rem 0;
        }

        .feature-icon {
            background-color: var(--primary-color);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #005c90;
            border-color: #005c90;
        }

        .btn-outline-light:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .section {
            padding: 5rem 0;
        }

        .demo-img {
            border-radius: 0.5rem;
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.1);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        /* Animation */
        .fade-in {
            animation: fadeIn 1s ease-out;
        }

        .slide-up {
            animation: slideUp 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .delay-1 {
            animation-delay: 0.2s;
        }

        .delay-2 {
            animation-delay: 0.4s;
        }

        .delay-3 {
            animation-delay: 0.6s;
        }
    </style>
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-md navbar-dark py-3" style="background-color: var(--primary-color);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">{{ config('app.name', 'Laravel') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item">
                                <a href="{{ route('login') }}" class="nav-link">Log in</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 fade-in">
                    <h1 class="display-4 fw-bold mb-4">Manage Projects With Ease</h1>
                    <p class="lead mb-4">A simple, intuitive task management system for teams of any size. Organize, track, and collaborate on your projects with ease.</p>
                    <div class="d-flex flex-wrap gap-2">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4 me-md-2">Get Started</a>
                        @endif
                        <a href="#features" class="btn btn-outline-light btn-lg px-4">Learn More</a>
                    </div>
                </div>
                <div class="col-md-6 d-none d-md-block slide-up">
                    <img src="https://images.pexels.com/photos/3182812/pexels-photo-3182812.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Team Collaboration" class="img-fluid mt-4 demo-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section bg-light" id="features">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-lg-8 mx-auto">
                    <h2 class="fw-bold">Powerful Features</h2>
                    <p class="lead text-muted">Everything you need to manage your projects effectively</p>
                </div>
            </div>
            <div class="row g-4">
                <div class="col-md-4 slide-up delay-1">
                    <div class="card h-100 p-4">
                        <div class="feature-icon">
                            <i class="fas fa-columns"></i>
                        </div>
                        <h3 class="h5 fw-bold">Kanban Boards</h3>
                        <p class="text-muted">Visualize your workflow with customizable columns for different stages of your project.</p>
                    </div>
                </div>
                <div class="col-md-4 slide-up delay-2">
                    <div class="card h-100 p-4">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="h5 fw-bold">Team Collaboration</h3>
                        <p class="text-muted">Invite team members, assign tasks, and collaborate in real-time with comments.</p>
                    </div>
                </div>
                <div class="col-md-4 slide-up delay-3">
                    <div class="card h-100 p-4">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h3 class="h5 fw-bold">Real-time Notifications</h3>
                        <p class="text-muted">Stay updated with instant notifications when tasks are assigned or comments are added.</p>
                    </div>
                </div>
                <div class="col-md-4 slide-up delay-1">
                    <div class="card h-100 p-4">
                        <div class="feature-icon">
                            <i class="fas fa-paperclip"></i>
                        </div>
                        <h3 class="h5 fw-bold">File Attachments</h3>
                        <p class="text-muted">Attach files to tasks, making it easy to share documents and resources.</p>
                    </div>
                </div>
                <div class="col-md-4 slide-up delay-2">
                    <div class="card h-100 p-4">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="h5 fw-bold">Advanced Search</h3>
                        <p class="text-muted">Find tasks quickly with powerful search functionality and filters.</p>
                    </div>
                </div>
                <div class="col-md-4 slide-up delay-3">
                    <div class="card h-100 p-4">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="h5 fw-bold">Responsive Design</h3>
                        <p class="text-muted">Access your projects from any device with our mobile-friendly interface.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="https://images.pexels.com/photos/3182746/pexels-photo-3182746.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Project Management" class="img-fluid demo-img">
                </div>
                <div class="col-md-6">
                    <h2 class="fw-bold mb-4">How It Works</h2>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">1</div>
                        </div>
                        <div>
                            <h4 class="h5 fw-bold">Create a Team</h4>
                            <p class="text-muted">Start by creating a team and inviting members to collaborate.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">2</div>
                        </div>
                        <div>
                            <h4 class="h5 fw-bold">Set Up Projects</h4>
                            <p class="text-muted">Create projects with custom task lists to organize your work.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-4">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">3</div>
                        </div>
                        <div>
                            <h4 class="h5 fw-bold">Add Tasks</h4>
                            <p class="text-muted">Create tasks, assign team members, and set due dates.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">4</div>
                        </div>
                        <div>
                            <h4 class="h5 fw-bold">Track Progress</h4>
                            <p class="text-muted">Move tasks between lists to track progress and collaborate in real-time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section text-center" style="background-color: var(--primary-color); color: white;">
        <div class="container">
            <h2 class="fw-bold mb-4">Ready to get started?</h2>
            <p class="lead mb-5">Join thousands of teams who use our platform to manage their projects effectively.</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">Sign Up for Free</a>
            @endif
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 bg-dark text-white">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">{{ config('app.name', 'Laravel') }}</h5>
                    <p class="text-muted">A powerful task management system for teams of any size.</p>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Company</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">About</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Careers</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-2 mb-4 mb-md-0">
                    <h5 class="fw-bold mb-3">Resources</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Blog</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-muted">Help Center</a></li>
                        <li><a href="#" class="text-decoration-none text-muted">Tutorials</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold mb-3">Stay Connected</h5>
                    <p class="text-muted mb-3">Subscribe to our newsletter for updates and tips.</p>
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Your email">
                        <button type="submit" class="btn btn-outline-light">Subscribe</button>
                    </form>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="text-muted mb-md-0">&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-decoration-none text-muted">Privacy Policy</a>
                    <a href="#" class="text-decoration-none text-muted">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>