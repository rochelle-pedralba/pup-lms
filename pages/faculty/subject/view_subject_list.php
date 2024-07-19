<?php
date_default_timezone_set('Asia/Manila');

require_once '../../../php/includes/dbh_inc.php'; 
require_once '../../../php/includes/execute_query_inc.php';
require_once '../../../php/includes/error_model_inc.php';
require_once '../../../php/includes/config_session_inc.php';

if (!isset($_SESSION['user_ID'])) {
    header("Location: ../../login.html");
    exit;
}

$user_ID = $_SESSION['user_ID'];

if (!$mysqli) {
    echo "Connection failed: " . mysqli_connect_error();
} else {
    // Prepare the SQL statement to prevent SQL injection
    $stmt = $mysqli->prepare("SELECT * FROM subject WHERE user_ID = ?");
    
    // Bind parameters to the prepared statement
    $stmt->bind_param("i", $user_ID); // 'i' denotes the type is integer

    // Execute the prepared statement
    $stmt->execute();

    // Store the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $subjects = array();
        while ($row = $result->fetch_assoc()) {
            $subjects[] = array(
                "id" => $row["subject_ID"],
                "name" => $row["subject_Name"],
                "description" => $row["subject_Description"],
                "cohort" => $row["cohort_ID"],
                "course" => $row["course_ID"],
                "semester" => $row["semester"],
                "ay" => $row["ay"],
                "section" => $row["section"],
                "year" => $row["year"]
            );
        }
    } else {
        $subjects = [];
    }

    // Close the statement
    $stmt->close();
}


mysqli_close($mysqli);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty | Subject Overview</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" type="text/css" href="../../../styles/subject_overview.css">
</head>

<body>
    <header class="head">
            <div class="logo">
              <img src="../../../assets/PUP_logo.png" alt="PUP Logo">
            </div>
            <div class="title">
              <h1>PUP Learning Management System</h1>
            </div>
    </header>

    <div class="container">
        <header class="header">
            <h1>Subject Overview</h1>
        </header>
        <section class="footer">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search..." oninput="searchSubjects()">
            </div>
            <div class="sort-options">
                <label for="sort-by" class="sort-label">Sort by:</label>
                <select id="sort-by" class="sort-select" onchange="sortSubjects()">
                    <option value="name">Subject Name</option>
                    <option value="duration">Duration</option>
                </select>
                <label for="view-type" class="sort-label" style="margin-left: 20px;">View:</label>
                <select id="view-type" class="sort-select" onchange="toggleView()">
                    <option value="card">Card View</option>
                    <option value="list">List View</option>
                </select>
            </div>
        </section>
        <main class="subject-grid" id="subject-grid">
            <?php foreach ($subjects as $subject): ?>
                <article class="subject-item" id="subject-item-<?php echo $subject['id']; ?>">
                    <!-- Subject item content here -->
                </article>
            <?php endforeach; ?>
        </main>
        <div class="back-item" onclick="window.location.href='../../index_faculty.php'">
            <div class="back-header">
                <h3>Back</h3>
            </div>
        </div> 
    </div>

    </div>

    <footer id="footer">
            <div>
              <p>&copy; 2021 PUP Learning Management System</p>
            </div>
    </footer>

    <script>
        let subjects = <?php echo json_encode($subjects); ?>;
        console.log(subjects);
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="../../../scripts/subject_overview.js"></script>

    <div id="archiveModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to archive this course?</p>
            <button id="confirmArchive">Yes</button>
            <button id="cancelArchive">No</button>
        </div>
    </div>
</body>
</html>