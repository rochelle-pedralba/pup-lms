function populateCourses() {
    const courseGrid = document.getElementById('course-grid');
    courseGrid.innerHTML = ''; // Clear existing content

    courses.forEach(course => {
        const courseItem = document.createElement('article');
        courseItem.classList.add('course-item');

        // Course header
        const courseHeader = document.createElement('header');
        courseHeader.classList.add('course-header');
        const courseID = document.createElement('h3');
        courseID.textContent = course.id + ' - ' + course.name;
        courseHeader.appendChild(courseID);

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
        editLink.href = `../admin/course/edit_course_form.php?course_ID=${course.id}`;
        editLink.textContent = 'Edit';
        editOption.appendChild(editLink);

        const archiveOption = document.createElement('li');
        const archiveLink = document.createElement('a');
        archiveLink.href = '#';
        archiveLink.textContent = 'Archive';
        archiveLink.onclick = () => showArchiveModal(course.id);
        archiveOption.appendChild(archiveLink);

        optionsList.appendChild(editOption);
        optionsList.appendChild(archiveOption);
        courseOptions.appendChild(optionsList);

        courseHeader.appendChild(courseOptions);
        courseItem.appendChild(courseHeader);

        // Course description
        const courseDescription = document.createElement('section');
        courseDescription.classList.add('course-desc');
        const descriptionText = document.createElement('p');
        descriptionText.textContent = course.description;
        courseDescription.appendChild(descriptionText);
        courseItem.appendChild(courseDescription);

        const courseCohort = document.createElement('section');
        courseCohort.classList.add('course-cohort');
        const cohortText = document.createElement('p');
        cohortText.textContent = 'COHORT: ' + course.cohort;
        courseCohort.appendChild(cohortText);
        courseItem.appendChild(courseCohort);

        const courseCollege = document.createElement('section');
        courseCollege.classList.add('course-college');
        const collegeText = document.createElement('p');
        collegeText.textContent = 'COLLEGE: ' + course.college;
        courseCohort.appendChild(collegeText);
        courseItem.appendChild(courseCollege);

        // Course footer
        const courseFooter = document.createElement('footer');
        courseFooter.classList.add('course-footer');
        const durationText = document.createElement('p');
        durationText.textContent = 'Duration: ' + course.duration;
        courseFooter.appendChild(durationText);

        courseItem.appendChild(courseFooter);

        courseGrid.appendChild(courseItem);
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

// Function to search courses
function searchCourses() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const filteredCourses = courses.filter(course =>
        course.name.toLowerCase().includes(searchInput) ||
        course.description.toLowerCase().includes(searchInput)
    );

    const courseGrid = document.getElementById('course-grid');
    courseGrid.innerHTML = ''; // Clear existing content

    filteredCourses.forEach(course => {
        const courseItem = document.createElement('article');
        courseItem.classList.add('course-item');

        // Course header
        const courseHeader = document.createElement('header');
        courseHeader.classList.add('course-header');
        const courseID = document.createElement('h3');
        courseID.textContent = course.id + ' - ' + course.name;
        courseHeader.appendChild(courseID);

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
        editLink.href = `../admin/course/edit_course_form.php?course_ID=${course.id}`;
        editLink.textContent = 'Edit';
        editOption.appendChild(editLink);

        const archiveOption = document.createElement('li');
        const archiveLink = document.createElement('a');
        archiveLink.href = '#';
        archiveLink.textContent = 'Archive';
        archiveLink.onclick = () => showArchiveModal(cohort.id);
        archiveOption.appendChild(archiveLink);

        optionsList.appendChild(editOption);
        optionsList.appendChild(archiveOption);
        courseOptions.appendChild(optionsList);

        courseHeader.appendChild(courseOptions);
        courseItem.appendChild(courseHeader);

        // Course description
        const courseDescription = document.createElement('section');
        courseDescription.classList.add('course-desc');
        const descriptionText = document.createElement('p');
        descriptionText.textContent = course.description;
        courseDescription.appendChild(descriptionText);
        courseItem.appendChild(courseDescription);

        const courseCohort = document.createElement('section');
        courseCohort.classList.add('course-cohort');
        const cohortText = document.createElement('p');
        cohortText.textContent = 'COHORT: ' + course.cohort;
        courseCohort.appendChild(cohortText);
        courseItem.appendChild(courseCohort);

        const courseCollege = document.createElement('section');
        courseCollege.classList.add('course-college');
        const collegeText = document.createElement('p');
        collegeText.textContent = 'COLLEGE: ' + course.college;
        courseCohort.appendChild(collegeText);
        courseItem.appendChild(courseCollege);

        // Course footer
        const courseFooter = document.createElement('footer');
        courseFooter.classList.add('course-footer');
        const durationText = document.createElement('p');
        durationText.textContent = 'Duration: ' + course.duration;
        courseFooter.appendChild(durationText);

        courseItem.appendChild(courseFooter);

        courseGrid.appendChild(courseItem);
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

function showArchiveModal(courseId) {
    const modal = document.getElementById('archiveModal');
    const span = document.getElementsByClassName('close')[0];
    const confirmBtn = document.getElementById('confirmArchive');
    const cancelBtn = document.getElementById('cancelArchive');

    modal.style.display = 'block';

    span.onclick = function() {
        modal.style.display = 'none';
    }

    cancelBtn.onclick = function() {
        modal.style.display = 'none';
    }

    confirmBtn.onclick = function() {
        window.location.href = `../admin/course/archive_course.php?course_ID=${courseId}`;
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
}

// Initial populate courses on page load
document.addEventListener('DOMContentLoaded', function() {
    populateCourses();
});
