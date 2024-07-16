
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
            editLink.href = '../pages/admin/course/archive_course.php?id=' + course.id; // Include course ID as a query parameter
            editLink.textContent = 'Edit';
            // Remove or comment out the onclick event handler if it exists
            // editLink.onclick = (event) => editCourse(event, course.id);
            editOption.appendChild(editLink);

            const archiveOption = document.createElement('li');
            const archiveLink = document.createElement('a');
            archiveLink.href = '../pages/admin/course/archive_course.php';
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
        addCourseItem.onclick = () => { window.location.href = '../admin/course/add_course.html'; };
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