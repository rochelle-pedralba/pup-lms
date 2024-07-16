<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pup_lms";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  echo "Connection failed: " . mysqli_connect_error();
} else {
    // Retrieve courses from database
    $sql = "SELECT course_ID, course_Name, course_Description, no_Of_Years FROM course";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $courses = array();
        while ($row = mysqli_fetch_assoc($result)) {
            // // Check if course is archived
            // if ($row["isArchived"] != '1') {
                $courses[] = array(
                    "course_id" => $row["course_ID"], 
                    "course_name" => $row["course_Name"],
                    "course_desc" => $row["course_Description"],
                    "no_of_years" => $row["no_Of_Years"] . " Years",
                );
            // }
        }
    } else {
        echo "No active courses found in the database.";
    }
}

// // Handle POST request to update course
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     if (isset($_POST['action']) && $_POST['action'] == 'archive') {
//         $course_id = $_POST['course-id'];

//         // Update the course in the database to set isArchived = 1
//         $update_sql = "UPDATE course SET isArchived='1' WHERE course_ID='$course_id'";

//         if (mysqli_query($conn, $update_sql)) {
//             echo "Course archived successfully.";
//             exit();
//         } else {
//             echo "Error updating course: " . mysqli_error($conn);
//         }
//     }
// }

// // Handle POST request to update course
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $course_id = $_POST['courseid'];
//     $course_name = $_POST['course_name'];
//     $course_desc = $_POST['course_desc'];
//     $course_duration = $_POST['no_of_years'];

//     // Update the course in the database
//     $update_sql = "UPDATE course SET course_Name='$course_name', course_Description='$course_desc', no_Of_Years='$no_of_years' WHERE course_ID='$course_id'";

//     if (mysqli_query($conn, $update_sql)) {
//         header("Location: Faculty.php");
//         exit();
//     } else {
//         echo "Error updating course: " . mysqli_error($conn);
//     }
// }
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, Admin | Course Overview</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" type="text/css" href="../../styles/overview.css">
</head>

<body>
    <header class="head">
            <div class="logo">
              <img src="../../assets/PUP_logo.png" alt="PUP Logo">
            </div>
            <div class="title">
              <h1>PUP Learning Management System</h1>
            </div>
    </header>

    <div class="container">
        <header class="header">
            <h1>Welcome, Admin!</h1>
            <h2>Course Overview</h2>
        </header>
        <section class="footer">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search..." oninput="searchCourses()">
            </div>
            <div class="sort-options">
                <label for="sort-by" class="sort-label">Sort by:</label>
                <select id="sort-by" class="sort-select" onchange="sortCourses()">
                    <option value="name">Course Name</option>
                    <option value="duration">Duration</option>
                </select>
                <label for="view-type" class="sort-label" style="margin-left: 20px;">View:</label>
                <select id="view-type" class="sort-select" onchange="toggleView()">
                    <option value="card">Card View</option>
                    <option value="list">List View</option>
                </select>
            </div>
        </section>
        <main class="course-grid" id="course-grid">
            <?php foreach ($courses as $course): ?>
                <article class="course-item" id="course-item-<?php echo $course['id']; ?>">
                    <!-- Course item content here -->
                </article>
            <?php endforeach; ?>
        </main>
    </div>


    <!-- Modal -->
    <!-- <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Course</h2>
            <form id="edit-course-form" action="Faculty.php" method="POST">
            <input type="hidden" id="course-id" name="course-id">
                <div class="form-group">
                    <label for="course-name">Course Name:</label>
                    <input type="text" id="course-name" name="course-name" required>
                </div>
                <div class="form-group">
                    <label for="course-code">Course Code:</label>
                    <input type="text" id="course-code" name="course-code" required>
                </div>
              
                <div class="form-group">
                    <label for="course-description">Description:</label>
                    <textarea id="course-description" name="course-description" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="course-duration">Duration:</label>
                    <input type="text" id="course-duration" name="course-duration" required>
                </div>
                <button type="submit">Save Changes</button>
            </form>
        </div> -->
    </div>

    <footer id="footer">
            <div>
              <p>&copy; 2021 PUP Learning Management System</p>
            </div>
    </footer>

    <script>
        let courses = <?php echo json_encode($courses); ?>;
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="../../scripts/course_overview.js"></script>
</body>
</html>