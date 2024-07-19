function unenrollFunc(studentName) {
    var unenroll = confirm("Are you sure you want to unenroll " + studentName + "?");
    if (unenroll) {
        var encodedStudentName = encodeURIComponent(studentName);
        // Redirect to PHP script with encoded studentName as a query parameter
        window.location.href = '../../../php/archive_enrolled_course.php?studentName=' + encodedStudentName;
    }
}
