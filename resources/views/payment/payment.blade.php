<?php
// Simulate payment process
if ($_GET['course_id']) {
    $courseId = $_GET['course_id'];
    // For testing, payment is automatically approved
    $paymentStatus = 'paid';
    echo "Payment successful! Student now has access to course materials.<br>";
    echo "<a href='course_materials.php?course_id=$courseId'>Go to Course</a>";
}
