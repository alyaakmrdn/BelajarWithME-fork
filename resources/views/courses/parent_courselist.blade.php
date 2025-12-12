<?php
include 'dummy_courses.php';
?>

<h2>Available Courses</h2>
<div class="row">
<?php foreach ($courses as $course): ?>
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= $course['title'] ?></h5>
                <p class="card-text">Class: <?= $course['class'] ?></p>
                <p class="card-text">Price: $<?= $course['price'] ?></p>
                <a href="enrol.php?course_id=<?= $course['id'] ?>" class="btn btn-primary">Enrol Now</a>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
