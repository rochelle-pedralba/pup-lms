<?php
date_default_timezone_set('Asia/Manila');

require_once '../../php/includes/dbh_inc.php'; 
require_once '../../php/includes/execute_query_inc.php';
require_once '../../php/includes/error_model_inc.php';


if (!$mysqli) {
    echo "Connection failed: " . mysqli_connect_error();
} else {
    // Retrieve courses from database
    $sql = "SELECT college_ID, college_Name, description FROM COLLEGE";
    $result = mysqli_query($mysqli, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($mysqli));
    }

    if (mysqli_num_rows($result) > 0) {
        $colleges = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $colleges[] = array(
                "id" => $row["college_ID"],
                "name" => $row["college_Name"],
                "desc" => $row["description"]
            );
        }
    } else {
        $colleges = [];
    }
}

mysqli_close($mysqli);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | College Overview</title>
    <link rel="icon" type="image/png" href="logo.png">
    <link rel="stylesheet" type="text/css" href="../../styles/college_overview.css">
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
            <h1>College Overview</h1>
        </header>
        <section class="footer">
            <div class="search-bar">
                <input type="text" id="search-input" placeholder="Search..." oninput="searchColleges()">
            </div>
            <div class="sort-options">
                <label for="sort-by" class="sort-label">Sort by:</label>
                <select id="sort-by" class="sort-select" onchange="sortColleges()">
                    <option value="name">College Name</option>
                </select>
                <label for="view-type" class="sort-label" style="margin-left: 20px;">View:</label>
                <select id="view-type" class="sort-select" onchange="toggleView()">
                    <option value="card">Card View</option>
                    <option value="list">List View</option>
                </select>
            </div>
        </section>
        <main class="college-grid" id="college-grid">
            <?php foreach ($colleges as $college): ?>
                <article class="college-item" id="college-item-<?php echo $college['id']; ?>">
                    <!-- Course item content here -->
                </article>
            <?php endforeach; ?>
        </main>
        <div class="back-item" onclick="window.location.href='overview.html'">
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
        let colleges = <?php echo json_encode($colleges); ?>;
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="../../scripts/college_overview.js"></script>

    <div id="archiveModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Are you sure you want to archive this cohort?</p>
            <button id="confirmArchive">Yes</button>
            <button id="cancelArchive">No</button>
        </div>
    </div>
</body>
</html>