<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #e6f0ff; /* light blue background */
        }
        .signup-card {
            max-width: 450px;
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

    <div class="card shadow signup-card p-4">

        <h2 class="text-center mb-3 text-blue">Create Account</h2>

        <!-- Show error -->
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="/signup" method="POST">
            @csrf

            <!-- Email -->
            <label class="fw-bold">Email</label>
            <input type="email" name="email" class="form-control mb-3" required>

            <!-- Password -->
            <label class="fw-bold">Password (min 6 characters)</label>
            <input type="password" name="password" class="form-control mb-3" required>

            <!-- Role -->
            <label class="fw-bold">Select Role</label>
            <select name="role" class="form-select mb-4" required>
                <option value="">-- Select --</option>
                <option value="student">Student</option>
                <option value="parent">Parent</option>
                <option value="lecturer">Lecturer</option>
                <option value="admin">Admin</option>
            </select>

            <button type="submit" class="btn btn-blue w-100">Create Account</button>
        </form>

        <p class="mt-3 text-center">
            Already have an account? 
            <a href="/login" class="text-blue-link">Login</a>
        </p>

    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
