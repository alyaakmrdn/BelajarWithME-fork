<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuition Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General styles */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f0ff;
            color: #333;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Header */
        header {
            background-color: #6a0dad; /* Purple */
            color: #fff;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            margin: 0;
        }

        nav a {
            margin-left: 20px;
            font-weight: 500;
        }

        /* Main Content */
        main {
            padding: 40px;
        }

        h2 {
            color: #4b0082;
        }

        /* Cards for subjects or courses */
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            flex: 1 1 250px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card h3 {
            margin-top: 0;
            color: #6a0dad;
        }

        .card button {
            background-color: #6a0dad;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
        }

        .card button:hover {
            background-color: #4b0082;
        }

        /* Form for adding content */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin-top: 20px;
        }

        form input, form textarea, form select {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        form button {
            background-color: #6a0dad;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 8px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #4b0082;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 20px;
            background-color: #6a0dad;
            color: white;
            margin-top: 40px;
        }
    </style>
</head>
<body>

    <header>
        <h1>Tuition Portal</h1>
        <nav>
            <a href="#">Dashboard</a>
            <a href="#">Subjects</a>
            <a href="#">Lecturers</a>
            <a href="#">Courses</a>
            <a href="#">Logout</a>
        </nav>
    </header>

    <main>
        <section>
            <h2>Subjects</h2>
            <div class="card-container">
                <div class="card">
                    <h3>Mathematics</h3>
                    <p>Lecturer: John Doe</p>
                    <button>Manage Content</button>
                </div>
                <div class="card">
                    <h3>Science</h3>
                    <p>Lecturer: Jane Smith</p>
                    <button>Manage Content</button>
                </div>
                <div class="card">
                    <h3>English</h3>
                    <p>Lecturer: Mark Lee</p>
                    <button>Manage Content</button>
                </div>
            </div>
        </section>

        <section>
            <h2>Add New Course Content</h2>
            <form>
                <label for="subject">Select Subject</label>
                <select id="subject" name="subject">
                    <option value="math">Mathematics</option>
                    <option value="science">Science</option>
                    <option value="english">English</option>
                </select>

                <label for="content-type">Content Type</label>
                <select id="content-type" name="content-type">
                    <option value="note">Note</option>
                    <option value="video">Video</option>
                    <option value="article">Article</option>
                    <option value="quiz">Quiz</option>
                </select>

                <label for="title">Title</label>
                <input type="text" id="title" name="title" placeholder="Content title">

                <label for="description">Description / URL</label>
                <textarea id="description" name="description" rows="4" placeholder="Add description or video URL"></textarea>

                <button type="submit">Add Content</button>
            </form>
        </section>
    </main>

    <footer>
        &copy; 2025 Tuition Portal. All Rights Reserved.
    </footer>

</body>
</html>
