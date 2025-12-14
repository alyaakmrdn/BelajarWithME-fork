<!DOCTYPE html>
<html>
<head>
    <title>Firebase Test</title>
</head>
<body>

<h2>Firebase Realtime DB: User List</h2>

<ul>
    @foreach($users as $key => $user)
        <li>{{ $key }} — {{ $user['name'] }} ({{ $user['role'] }})</li>
    @endforeach
</ul>

</body>
</html>
