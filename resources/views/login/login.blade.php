<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #e6f0ff; /* light blue background */
        }
        .login-card {
            max-width: 400px;
            margin: 80px auto;
            border-radius: 12px;
        }
        .btn-blue {
            background-color: #0000ff;
            color: white;
        }
        .btn-blue:hover {
            background-color: #0000cc;
            color: white;
        }
        .text-blue {
            color: #0000ff;
        }
        a.text-blue-link {
            color: #0000ff;
            text-decoration: none;
        }
        a.text-blue-link:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card shadow login-card p-4">

        <h2 class="text-center mb-3 text-blue">Login</h2>

        <!-- Show error -->
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf

            <!-- Email -->
            <label class="fw-bold">Email</label>
            <input type="email" name="email" class="form-control mb-3" required>

            <!-- Password -->
            <label class="fw-bold">Password</label>
            <input type="password" name="password" class="form-control mb-4" required>

            <button type="submit" class="btn btn-blue w-100">Login</button>
        </form>

        <p class="mt-3 text-center">
            No account? <a href="/signup" class="text-blue-link">Signup</a>
        </p>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
