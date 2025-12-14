<h2>Enrol in {{ $course['title'] }}</h2>
<p>Class: {{ $course['class'] }} | Price: ${{ $course['price'] }}</p>

<form action="{{ route('courses.payment', $id) }}" method="POST">
    @csrf
    <button type="submit">Pay & Enrol</button>
</form>
