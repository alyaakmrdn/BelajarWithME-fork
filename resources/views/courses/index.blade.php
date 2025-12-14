<h2>Available Courses</h2>
@foreach($courses as $id => $course)
    <div>
        <h4>{{ $course['title'] }} (Class: {{ $course['class'] }})</h4>
        <p>Price: ${{ $course['price'] }}</p>
        <a href="{{ route('courses.show', $id) }}">Enrol</a>
    </div>
@endforeach
