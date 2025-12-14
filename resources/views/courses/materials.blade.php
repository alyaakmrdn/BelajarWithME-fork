<h2>{{ $course['title'] }} - Materials</h2>
<ul>
@foreach($course['materials'] as $material)
    <li>{{ $material['type'] }}: <a href="{{ $material['url'] }}">{{ $material['title'] }}</a></li>
@endforeach
</ul>
