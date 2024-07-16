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
    $sql = "SELECT course_ID, course_Name, course_Code, course_Description, no_Of_Years, isArchived FROM course";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $courses = array();
        while ($row = mysqli_fetch_assoc($result)) {
            // Check if course is archived
            if ($row["isArchived"] != '1') {
                $courses[] = array(
                    "id" => $row["course_ID"], 
                    "name" => $row["course_Name"],
                    "code" => $row["course_Code"],
                    "description" => $row["course_Description"],
                    "duration" => $row["no_Of_Years"] . " Years",
                    "isArchived" => $row["isArchived"]
                );
            }
        }
    } else {
        echo "No active courses found in the database.";
    }
}

// Handle POST request to update course
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && $_POST['action'] == 'archive') {
        $course_id = $_POST['course-id'];

        // Update the course in the database to set isArchived = 1
        $update_sql = "UPDATE course SET isArchived='1' WHERE course_ID='$course_id'";

        if (mysqli_query($conn, $update_sql)) {
            echo "Course archived successfully.";
            exit();
        } else {
            echo "Error updating course: " . mysqli_error($conn);
        }
    }
}

// Handle POST request to update course
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course-id'];
    $course_name = $_POST['course-name'];
    $course_code = $_POST['course-code'];
    $course_description = $_POST['course-description'];
    $course_duration = $_POST['course-duration'];

    // Update the course in the database
    $update_sql = "UPDATE course SET course_Name='$course_name', course_Code='$course_code', course_Description='$course_description', no_Of_Years='$course_duration' WHERE course_ID='$course_id'";

    if (mysqli_query($conn, $update_sql)) {
        header("Location: Faculty.php");
        exit();
    } else {
        echo "Error updating course: " . mysqli_error($conn);
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome, Admin | Faculty Course Overview</title>
    <link rel="icon" type="image/png" href="logo.png">
    <style>
    :root {
        --maroon: maroon;
        --background-color: #f4f4f4;
        --text-color: #333;
        --header-text-color: #555;
        --white: #fff;
        --gray: #777;
        --light-gray: #f0f0f0;
        --dark-gray: #555;
    }
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--background-color);
        color: var(--text-color);
        margin: 0;
        padding: 0;
    }
    .container {
        max-width: 1200px;
        margin: 30px auto;
        background: var(--white);
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative; /* Added for positioning */
    }
    .header {
        text-align: center;
        margin-bottom: 30px;
    }
    .header h1 {
        color: var(--maroon);
        margin-bottom: 10px;
    }
    #course-container {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 20px;
}

.course-item {
border: 1px solid #ccc;
padding: 15px;
background-color: #f9f9f9;
}

.course-item[style*="display: none;"] {
    display: none; /* Ensure archived courses are visually hidden */
}


.course-header {
display: flex;
justify-content: space-between;
align-items: center;
}

.course-options {
position: relative;
}

.options {
display: none;
position: absolute;
background-color: #fff;
box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
padding: 5px 0;
list-style-type: none;
right: 0;
}

.options.show {
display: block;
}

.options li {
padding: 5px 10px;
}

.course-description {
margin-top: 10px;
}

.course-footer {
margin-top: 10px;
font-size: 0.8rem;
color: #666;
}

    .header h2 {
        color: var(--header-text-color);
        margin: 0;
    }
    .course-grid-wrapper {
        position: absolute;
        bottom: 20px;
        right: 20px;
    }
    .course-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-top: 20px;
    }
    .course-item {
        width: calc(33.333% - 20px);
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: var(--white);
        transition: transform 0.2s;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        height: 400px; /* Fixed height for uniform size */
        cursor: pointer;
    }

    .course-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .course-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background-color: var(--maroon);
        color: var(--white);
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .course-header h3 {
        margin: 0;
        font-size: 18px;
    }
    .course-header p {
        margin: 0;
        font-size: 14px;
        text-align: right;
        width: 50%;
    }
    .course-description {
        padding: 15px 20px;
        text-align: justify;
        flex: 1;
    }
    .course-description p {
        margin: 0;
        color: var(--dark-gray);
        font-size: 14px;
        line-height: 1.6;
    }
    .course-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background-color: var(--light-gray);
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }
    .course-footer p {
        color: var(--gray);
        font-size: 14px;
    }
    .footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        background-color: var(--maroon);
        padding: 10px 20px;
        border-radius: 8px;
        color: var(--white);
    }
    .search-bar {
        flex: 1;
        padding: 8px;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        margin-right: 10px;
    }
    .sort-options {
        display: flex;
        align-items: center;
    }
    .sort-label {
        margin-right: 10px;
        font-size: 14px;
    }
    .sort-select {
        padding: 8px;
        border-radius: 4px;
        font-size: 14px;
    }
    .list-view .course-item {
        width: 100%;
        margin-bottom: 20px;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .list-view .course-item:hover {
        transform: none;
        box-shadow: none;
    }
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }
    .modal-content {
        background-color: var(--white);
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .close {
        color: var(--dark-gray);
        float: right;
        font-size: 28px;
        font-weight: bold;
    }
    .close:hover,
    .close:focus {
        color: var(--gray);
        text-decoration: none;
        cursor: pointer;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }
    .form-group textarea {
        resize: vertical;
    }
    .form-group button {
        background-color: var(--maroon);
        color: var(--white);
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }
    .form-group button:hover {
        background-color: #800000;
    }
    .add-course-item {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        cursor: pointer;
        color: var(--gray);
    }
    .add-course-item:hover {
        background-color: var(--light-gray);
        color: var(--maroon);
    }
    .course-options ul {
list-style-type: none;
padding: 0;
margin: 0;
background-color: var(--white);
box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
border-radius: 4px;
position: absolute;
top: 30px;
right: 0;
display: none;
}

.course-options li {
padding: 10px;
cursor: pointer;
transition: background-color 0.3s;
}

.course-options li:hover {
background-color: var(--light-gray);
}

.course-options li:first-child {
border-top-left-radius: 4px;
border-top-right-radius: 4px;
}

.course-options li:last-child {
border-bottom-left-radius: 4px;
border-bottom-right-radius: 4px;
}

.course-options.open ul {
display: block;
}

.course-options button {
background: none;
border: none;
cursor: pointer;
color: var(--white);
font-size: 18px;
}


.course-options button:focus {
outline: none;
}
.options {
display: none; /* Hide options by default */
position: absolute; /* Position the options absolutely */
background-color: #fff; /* White background */
border: 1px solid #ccc; /* Gray border */
padding: 5px; /* Padding around options */
z-index: 1; /* Ensure options appear above other content */
}
</style>
</head>
<body>
    <div class="container">
        <header class="header">
            <h1>Welcome, Admin!</h1>
            <h2>Faculty Course Overview</h2>
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
    <div id="myModal" class="modal">
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
        </div>
    </div>
    
    <script>
        let courses = <?php echo json_encode($courses); ?>;

        function populateCourses() {
            const courseGrid = document.getElementById('course-grid');
            courseGrid.innerHTML = ''; // Clear existing content

            courses.forEach(course => {
                if (course.isArchived === '0') {
                const courseItem = document.createElement('article');
                courseItem.classList.add('course-item');

                // Course header
                const courseHeader = document.createElement('header');
                courseHeader.classList.add('course-header');

                const courseName = document.createElement('h3');
                courseName.textContent = course.name;
                courseHeader.appendChild(courseName);

                const courseOptions = document.createElement('div');
                courseOptions.classList.add('course-options');

                const optionsButton = document.createElement('button');
                optionsButton.textContent = '...';
                optionsButton.onclick = () => toggleOptions(optionsButton);
                courseOptions.appendChild(optionsButton);

                const optionsList = document.createElement('ul');
                optionsList.classList.add('options');

                const editOption = document.createElement('li');
                const editLink = document.createElement('a');
                editLink.href = '#';
                editLink.textContent = 'Edit';
                editLink.onclick = (event) => editCourse(event, course.id);
                editOption.appendChild(editLink);

                const archiveOption = document.createElement('li');
                const archiveLink = document.createElement('a');
                archiveLink.href = '#';
                archiveLink.textContent = 'Archive';
                archiveLink.onclick = (event) => archiveCourse(event, course.id);
                archiveOption.appendChild(archiveLink);

                optionsList.appendChild(editOption);
                optionsList.appendChild(archiveOption);
                courseOptions.appendChild(optionsList);

                courseHeader.appendChild(courseOptions);
                courseItem.appendChild(courseHeader);

                // Course description
                const courseDescription = document.createElement('section');
                courseDescription.classList.add('course-description');
                const descriptionText = document.createElement('p');
                descriptionText.textContent = course.description;
                courseDescription.appendChild(descriptionText);
                courseItem.appendChild(courseDescription);

                // Course footer
                const courseFooter = document.createElement('footer');
                courseFooter.classList.add('course-footer');
                const durationText = document.createElement('p');
                durationText.textContent = 'Duration: ' + course.duration;
                courseFooter.appendChild(durationText);

                const codeText = document.createElement('p');
                codeText.textContent = 'Course Code: ' + course.code; // Display course code here
                courseFooter.appendChild(codeText);

                courseItem.appendChild(courseFooter);

                courseGrid.appendChild(courseItem);
                }
            });

            // Add 'Add Course' button item at the end
            const addCourseItem = document.createElement('article');
            addCourseItem.classList.add('course-item', 'add-course-item');
            addCourseItem.onclick = openModal;
            const addCourseContent = document.createElement('div');
            addCourseContent.innerHTML = '<p>+ Add Course</p>';
            addCourseItem.appendChild(addCourseContent);
            courseGrid.appendChild(addCourseItem);
            }
        
        // Function to toggle display of options (Edit, Archive) for each course
        function toggleOptions(button) {
            const courseItem = button.closest('.course-item');
            const optionsList = courseItem.querySelector('.options');
            optionsList.classList.toggle('show');
        }

        // Function to open modal for editing a course
        function openModal() {
            const modal = document.getElementById('myModal');
            modal.style.display = 'block';

            // Clear form fields
            document.getElementById('edit-course-form').reset();
        }

        // Function to close the modal
        function closeModal() {
            const modal = document.getElementById('myModal');
            modal.style.display = 'none';
        }

        // Function to edit a course
        function editCourse(event, courseId) {
            event.preventDefault();
            const course = courses.find(c => c.id === courseId);
            if (!course) return;

            // Populate form fields
            document.getElementById('course-id').value = courseId;
            document.getElementById('course-name').value = course.name;
            document.getElementById('course-code').value = course.code;
            document.getElementById('course-description').value = course.description;
            document.getElementById('course-duration').value = course.duration;

            // Open modal
            openModal();

            // Save changes on form submit
            const editForm = document.getElementById('edit-course-form');
            editForm.onsubmit = function(event) {
                event.preventDefault();
                // Handle form submission via AJAX or default form submission
                editForm.submit(); // This submits the form with updated data to Faculty.php
            };
        }

        // Function to archive a course
        function archiveCourse(event, courseId) {
            event.preventDefault();

            // Send a POST request to Faculty.php to archive the course
            const formData = new FormData();
            formData.append('action', 'archive');
            formData.append('course-id', courseId);

            fetch('Faculty.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                console.log(result); // Log the result if needed
                // Assuming success, hide the archived course item
                const courseItem = document.getElementById(`course-item-${courseId}`);
                if (courseItem) {
                    courseItem.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error archiving course:', error);
            });
            
        }


        // Function to search courses
        function searchCourses() {
            const searchInput = document.getElementById('search-input').value.toLowerCase();
            const filteredCourses = courses.filter(course =>
                course.name.toLowerCase().includes(searchInput) ||
                course.description.toLowerCase().includes(searchInput)
            );

            // Update course grid with filtered courses
            const courseGrid = document.getElementById('course-grid');
            courseGrid.innerHTML = ''; // Clear existing content
            filteredCourses.forEach(course => {
                // Repopulate filtered courses
                // Same code as populateCourses() function, simplified for brevity
            });
        }

        // Function to sort courses
        function sortCourses() {
            const sortBy = document.getElementById('sort-by').value;

            if (sortBy === 'name') {
                courses.sort((a, b) => a.name.localeCompare(b.name));
            } else if (sortBy === 'duration') {
                courses.sort((a, b) => a.duration.localeCompare(b.duration));
            }

            // Repopulate courses on page
            populateCourses();
        }

        // Function to toggle between card and list view (dummy functionality for demo)
        function toggleView() {
            const viewType = document.getElementById('view-type').value;

            if (viewType === 'card') {
                document.getElementById('course-grid').classList.remove('list-view');
            } else if (viewType === 'list') {
                document.getElementById('course-grid').classList.add('list-view');
            }
        }

        // Initial populate courses on page load
        document.addEventListener('DOMContentLoaded', function() {
            populateCourses();
        });
    </script>
</body>
</html>