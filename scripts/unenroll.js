function unenrollFunc(studentName, studentID) {
    var unenroll = confirm("Are you sure you want to unenroll " + studentName + "?");
    if (unenroll) {

        if (studentID) {
            try {

                const xhr = new XMLHttpRequest();
                xhr.open('POST', '../../../php/archive_enrolled.php?studentName=', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onreadystatechange = function() {
                    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                        window.location.reload();
                    }
                }
                xhr.send('studentID=' + encodeURIComponent(studentID));

            } catch (e) {
                console.error("Error posting data: ", e);

                return;
            }
            } else {
                alert("No selected students.");
            }
    }
}
