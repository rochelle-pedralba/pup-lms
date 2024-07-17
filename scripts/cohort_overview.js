function populateCohorts() {
    const cohortGrid = document.getElementById('cohort-grid');
    cohortGrid.innerHTML = ''; // Clear existing content
    cohorts.forEach(cohort => {
        // Repopulate cohorts
        const cohortItem = document.createElement('article');
        cohortItem.classList.add('cohort-item');

        // Cohort header
        const cohortHeader = document.createElement('header');
        cohortHeader.classList.add('cohort-header');
        const cohortID = document.createElement('h3');
        cohortID.textContent = cohort.id + ' - ' + cohort.name;
        cohortHeader.appendChild(cohortID);

        const cohortOptions = document.createElement('div');
        cohortOptions.classList.add('cohort-options');

        const optionsButton = document.createElement('button');
        optionsButton.textContent = '...';
        optionsButton.onclick = () => toggleOptions(optionsButton);
        cohortOptions.appendChild(optionsButton);

        const optionsList = document.createElement('ul');
        optionsList.classList.add('options');

        const editOption = document.createElement('li');
        const editLink = document.createElement('a');
        editLink.href = `../admin/cohort/edit_cohort_form.php?cohort_ID=${cohort.id}`;
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
        cohortOptions.appendChild(optionsList);

        cohortHeader.appendChild(cohortOptions);
        cohortItem.appendChild(cohortHeader);

        // Cohort footer
        const cohortFooter = document.createElement('footer');
        cohortFooter.classList.add('cohort-footer');
        const sizeText = document.createElement('p');
        sizeText.textContent = 'Cohort Size: ' + cohort.size;
        cohortFooter.appendChild(sizeText);

        cohortItem.appendChild(cohortFooter);

        cohortGrid.appendChild(cohortItem);
    });

    // 'Add Cohort' 
    const addCohortItem = document.createElement('article');
    addCohortItem.classList.add('cohort-item', 'add-cohort-item');
    addCohortItem.onclick = () => { window.location.href = '../admin/cohort/add_cohort.html'; };
    const addCohortContent = document.createElement('div');
    addCohortContent.innerHTML = '<p>+ Add Cohort</p>';
    addCohortItem.appendChild(addCohortContent);
    cohortGrid.appendChild(addCohortItem);
}


// Function to toggle display of options (Edit, Archive) for each course
function toggleOptions(button) {
    const cohortItem = button.closest('.cohort-item');
    const optionsList = cohortItem.querySelector('.options');
    optionsList.classList.toggle('show');
}

// Function to search courses
function searchCohorts() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const filteredCohorts = cohorts.filter(cohort =>
        cohort.name.toLowerCase().includes(searchInput)
    );

    // Update course grid with filtered courses
    const cohortGrid = document.getElementById('cohort-grid');
    cohortGrid.innerHTML = ''; // Clear existing content
    filteredCohorts.forEach(course => {
        // Repopulate filtered courses
        const cohortItem = document.createElement('article');
        cohortItem.classList.add('cohort-item');

        // Course header
        const cohortHeader = document.createElement('header');
        cohortHeader.classList.add('cohort-header');
        const cohortID = document.createElement('h3');
        cohortID.textContent = cohort.id + ' - ' + cohort.name;
        cohortHeader.appendChild(cohortID);

        const cohortOptions = document.createElement('div');
        cohortOptions.classList.add('cohort-options');

        const optionsButton = document.createElement('button');
        optionsButton.textContent = '...';
        optionsButton.onclick = () => toggleOptions(optionsButton);
        cohortOptions.appendChild(optionsButton);

        const optionsList = document.createElement('ul');
        optionsList.classList.add('options');

        const editOption = document.createElement('li');
        const editLink = document.createElement('a');
        editLink.href = '../admin/cohort/edit_cohort_form.php'
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
        cohortOptions.appendChild(optionsList);

        cohortHeader.appendChild(cohortOptions);
        cohortItem.appendChild(cohortHeader);

        // Course footer
        const cohortFooter = document.createElement('footer');
        cohortFooter.classList.add('cohort-footer');
        const sizeText = document.createElement('p');
        sizeText.textContent = 'Cohort Size: ' + cohort.size;
        cohortFooter.appendChild(sizeText);

        cohortItem.appendChild(cohortFooter);

        cohortGrid.appendChild(cohortItem);
    });
}

function sortCohorts() {
    const sortBy = document.getElementById('sort-by').value;

    if (sortBy === 'name') {
        courses.sort((a, b) => a.name.localeCompare(b.name));
    }

    // Repopulate courses on page
    populateCourses();
}

function toggleView() {
    const viewType = document.getElementById('view-type').value;

    if (viewType === 'card') {
        document.getElementById('cohort-grid').classList.remove('list-view');
    } else if (viewType === 'list') {
        document.getElementById('cohort-grid').classList.add('list-view');
    }
}

function showArchiveModal(cohortId) {
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
        window.location.href = `../admin/cohort/archive_cohort.php?cohort_ID=${cohortId}`;
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log("DOMContentLoaded event fired"); // Check if event is triggered
    populateCohorts();
});

