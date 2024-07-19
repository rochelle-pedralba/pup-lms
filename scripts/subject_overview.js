function populatesubjects() {
    const subjectGrid = document.getElementById('subject-grid');
    subjectGrid.innerHTML = ''; // Clear existing content

    subjects.forEach(subject => {
        const subjectItem = document.createElement('article');
        subjectItem.classList.add('subject-item');

        // subject header
        const subjectHeader = document.createElement('header');
        subjectHeader.classList.add('subject-header');
        const subjectID = document.createElement('h3');
        subjectID.textContent = subject.id + ' - ' + subject.name;
        subjectHeader.appendChild(subjectID);

        const subjectOptions = document.createElement('div');
        subjectOptions.classList.add('subject-options');

        const optionsButton = document.createElement('button');
        optionsButton.textContent = '...';
        optionsButton.onclick = () => toggleOptions(optionsButton);
        subjectOptions.appendChild(optionsButton);

        const optionsList = document.createElement('ul');
        optionsList.classList.add('options');

        const editOption = document.createElement('li');
        const editLink = document.createElement('a');
        editLink.href = `../admin/subject/edit_subject_form.php?subject_ID=${subject.id}`;
        editLink.textContent = 'Edit';
        editOption.appendChild(editLink);

        const archiveOption = document.createElement('li');
        const archiveLink = document.createElement('a');
        archiveLink.href = '#';
        archiveLink.textContent = 'Archive';
        archiveLink.onclick = () => showArchiveModal(subject.id);
        archiveOption.appendChild(archiveLink);

        optionsList.appendChild(editOption);
        optionsList.appendChild(archiveOption);
        subjectOptions.appendChild(optionsList);

        subjectHeader.appendChild(subjectOptions);
        subjectItem.appendChild(subjectHeader);

        // subject description
        const subjectDescription = document.createElement('section');
        subjectDescription.classList.add('subject-desc');
        const descriptionText = document.createElement('p');
        descriptionText.textContent = subject.description;
        subjectDescription.appendChild(descriptionText);
        subjectItem.appendChild(subjectDescription);

        const subjectCohort = document.createElement('section');
        subjectCohort.classList.add('subject-cohort');
        const cohortText = document.createElement('p');
        cohortText.textContent = 'COHORT: ' + subject.cohort;
        subjectCohort.appendChild(cohortText);
        subjectItem.appendChild(subjectCohort);

        const subjectCourse = document.createElement('section');
        subjectCourse.classList.add('subject-Course');
        const CourseText = document.createElement('p');
        CourseText.textContent = 'COURSE: ' + subject.course;
        subjectCohort.appendChild(CourseText);
        subjectItem.appendChild(subjectCourse);

        // subject footer
        const subjectFooter = document.createElement('footer');
        subjectFooter.classList.add('subject-footer');
        const SemesterText = document.createElement('p');
        SemesterText.textContent = 'SEMESTER: ' + subject.semester;

        subjectFooter.appendChild(SemesterText);
        subjectItem.appendChild(subjectFooter);
        subjectGrid.appendChild(subjectItem);


    });

    // Add 'Add subject' button item at the end
    const addsubjectItem = document.createElement('article');
    addsubjectItem.classList.add('subject-item', 'add-subject-item');
    addsubjectItem.onclick = () => { window.location.href = 'add_subject.html'; };
    
    const addsubjectContent = document.createElement('div');
    addsubjectContent.innerHTML = '<p>+ Add subject</p>';
    addsubjectItem.appendChild(addsubjectContent);
    subjectGrid.appendChild(addsubjectItem);
}

// Function to toggle display of options (Edit, Archive) for each subject
function toggleOptions(button) {
    const subjectItem = button.closest('.subject-item');
    const optionsList = subjectItem.querySelector('.options');
    optionsList.classList.toggle('show');
}

// Function to search subjects
function searchsubjects() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const filteredsubjects = subjects.filter(subject =>
        subject.name.toLowerCase().includes(searchInput) ||
        subject.description.toLowerCase().includes(searchInput)
    );

    const subjectGrid = document.getElementById('subject-grid');
    subjectGrid.innerHTML = ''; // Clear existing content

    filteredsubjects.forEach(subject => {
        const subjectItem = document.createElement('article');
        subjectItem.classList.add('subject-item');

        // subject header
        const subjectHeader = document.createElement('header');
        subjectHeader.classList.add('subject-header');
        const subjectID = document.createElement('h3');
        subjectID.textContent = subject.id + ' - ' + subject.name;
        subjectHeader.appendChild(subjectID);

        const subjectOptions = document.createElement('div');
        subjectOptions.classList.add('subject-options');

        const optionsButton = document.createElement('button');
        optionsButton.textContent = '...';
        optionsButton.onclick = () => toggleOptions(optionsButton);
        subjectOptions.appendChild(optionsButton);

        const optionsList = document.createElement('ul');
        optionsList.classList.add('options');

        const editOption = document.createElement('li');
        const editLink = document.createElement('a');
        editLink.href = `../admin/subject/edit_subject_form.php?subject_ID=${subject.id}`;
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
        subjectOptions.appendChild(optionsList);

        subjectHeader.appendChild(subjectOptions);
        subjectItem.appendChild(subjectHeader);

        // subject description
        const subjectDescription = document.createElement('section');
        subjectDescription.classList.add('subject-desc');
        const descriptionText = document.createElement('p');
        descriptionText.textContent = subject.description;
        subjectDescription.appendChild(descriptionText);
        subjectItem.appendChild(subjectDescription);

        const subjectCohort = document.createElement('section');
        subjectCohort.classList.add('subject-cohort');
        const cohortText = document.createElement('p');
        cohortText.textContent = 'COHORT: ' + subject.cohort;
        subjectCohort.appendChild(cohortText);
        subjectItem.appendChild(subjectCohort);

        const subjectCourse = document.createElement('section');
        subjectCourse.classList.add('subject-Course');
        const CourseText = document.createElement('p');
        CourseText.textContent = 'COURSE: ' + subject.course;
        subjectCohort.appendChild(CourseText);
        subjectItem.appendChild(subjectCourse);

        // subject footer
        const subjectFooter = document.createElement('footer');
        subjectFooter.classList.add('subject-footer');
        const SemesterText = document.createElement('p');
        SemesterText.textContent = 'SEMESTER: ' + subject.semester;
        subjectFooter.appendChild(SemesterText);

        subjectItem.appendChild(subjectFooter);

        subjectGrid.appendChild(subjectItem);
    });
}

function sortsubjects() {
    const sortBy = document.getElementById('sort-by').value;

    if (sortBy === 'name') {
        subjects.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy === 'Semester') {
        subjects.sort((a, b) => a.Semester.localeCompare(b.Semester));
    }

    // Repopulate subjects on page
    populatesubjects();
}

function toggleView() {
    const viewType = document.getElementById('view-type').value;

    if (viewType === 'card') {
        document.getElementById('subject-grid').classList.remove('list-view');
    } else if (viewType === 'list') {
        document.getElementById('subject-grid').classList.add('list-view');
    }
}

function showArchiveModal(subjectId) {
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
        window.location.href = `../admin/subject/archive_subject.php?subject_ID=${subjectId}`;
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
}

// Initial populate subjects on page load
document.addEventListener('DOMContentLoaded', function() {
    populatesubjects();
});
