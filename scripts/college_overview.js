function populateColleges() {
    const collegeGrid = document.getElementById('college-grid');
    collegeGrid.innerHTML = '';
    colleges.forEach(college => {
        // Repopulate college
        const collegeItem = document.createElement('article');
        collegeItem.classList.add('college-item');

        // college header
        const collegeHeader = document.createElement('header');
        collegeHeader.classList.add('college-header');
        const collegeID = document.createElement('h3');
        collegeID.textContent = college.id + ' - ' + college.name;
        collegeHeader.appendChild(collegeID);

        const collegeOptions = document.createElement('div');
        collegeOptions.classList.add('college-options');

        const optionsButton = document.createElement('button');
        optionsButton.textContent = '...';
        optionsButton.onclick = () => toggleOptions(optionsButton);
        collegeOptions.appendChild(optionsButton);

        const optionsList = document.createElement('ul');
        optionsList.classList.add('options');

        const editOption = document.createElement('li');
        const editLink = document.createElement('a');
        editLink.href = `../admin/college/edit_college_form.php?college_ID=${college.id}`;
        editLink.textContent = 'Edit';
        editOption.appendChild(editLink);

        const archiveOption = document.createElement('li');
        const archiveLink = document.createElement('a');
        archiveLink.href = '#';
        archiveLink.textContent = 'Archive';
        archiveLink.onclick = () => showArchiveModal(college.id);
        archiveOption.appendChild(archiveLink);

        optionsList.appendChild(editOption);
        optionsList.appendChild(archiveOption);
        collegeOptions.appendChild(optionsList);

        collegeHeader.appendChild(collegeOptions);
        collegeItem.appendChild(collegeHeader);

        // College footer
        const collegeFooter = document.createElement('footer');
        collegeFooter.classList.add('college-footer');
        const descText = document.createElement('p');
        descText.textContent =  college.desc;
        collegeFooter.appendChild(descText);

        collegeItem.appendChild(collegeFooter);

        collegeGrid.appendChild(collegeItem);
    });

    // 'Add College' 
    const addCollegeItem = document.createElement('article');
    addCollegeItem.classList.add('college-item', 'add-college-item');
    addCollegeItem.onclick = () => { window.location.href = '../admin/college/add_college.html'; };
    const addCollegeContent = document.createElement('div');
    addCollegeContent.innerHTML = '<p>+ Add College</p>';
    addCollegeItem.appendChild(addCollegeContent);
    collegeGrid.appendChild(addCollegeItem);
}

// Function to toggle display of options (Edit, Archive) for each course
function toggleOptions(button) {
    const collegeItem = button.closest('.college-item');
    const optionsList = collegeItem.querySelector('.options');
    optionsList.classList.toggle('show');
}

// Function to search courses
function searchColleges() {
    const searchInput = document.getElementById('search-input').value.toLowerCase();
    const filteredColleges = colleges.filter(college =>
        college.name.toLowerCase().includes(searchInput)
    );

    // Update course grid with filtered courses
    const collegeGrid = document.getElementById('college-grid');
    collegeGrid.innerHTML = ''; // Clear existing content
    filteredColleges.forEach(college => {
        // Repopulate cohorts
        const collegeItem = document.createElement('article');
        collegeItem.classList.add('college-item');

        // Cohort header
        const collegeHeader = document.createElement('header');
        collegeHeader.classList.add('college-header');
        const collegeID = document.createElement('h3');
        collegeID.textContent = college.id + ' - ' + college.name;
        collegeHeader.appendChild(collegeID);

        const collegeOptions = document.createElement('div');
        collegeOptions.classList.add('college-options');

        const optionsButton = document.createElement('button');
        optionsButton.textContent = '...';
        optionsButton.onclick = () => toggleOptions(optionsButton);
        collegeOptions.appendChild(optionsButton);

        const optionsList = document.createElement('ul');
        optionsList.classList.add('options');

        const editOption = document.createElement('li');
        const editLink = document.createElement('a');
        editLink.href = `../admin/college/edit_college_form.php?college_ID=${college.id}`;
        editLink.textContent = 'Edit';
        editOption.appendChild(editLink);

        const archiveOption = document.createElement('li');
        const archiveLink = document.createElement('a');
        archiveLink.href = '#';
        archiveLink.textContent = 'Archive';
        archiveLink.onclick = () => showArchiveModal(college.id);
        archiveOption.appendChild(archiveLink);

        optionsList.appendChild(editOption);
        optionsList.appendChild(archiveOption);
        collegeOptions.appendChild(optionsList);

        collegeHeader.appendChild(collegeOptions);
        collegeItem.appendChild(collegeHeader);

        // College footer
        const collegeFooter = document.createElement('footer');
        collegeFooter.classList.add('college-footer');
        const descText = document.createElement('p');
        descText.textContent =  college.desc;
        collegeFooter.appendChild(descText);

        collegeItem.appendChild(collegeFooter);

        collegeGrid.appendChild(collegeItem);
    });
}

function sortColleges() {
    const sortBy = document.getElementById('sort-by').value;

    if (sortBy === 'name') {
        colleges.sort((a, b) => a.name.localeCompare(b.name));
    }

    populateColleges();
}

function toggleView() {
    const viewType = document.getElementById('view-type').value;

    if (viewType === 'card') {
        document.getElementById('college-grid').classList.remove('list-view');
    } else if (viewType === 'list') {
        document.getElementById('college-grid').classList.add('list-view');
    }
}

function showArchiveModal(collegeId) {
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
        window.location.href = `../admin/college/archive_college.php?college_ID=${collegeId}`;
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log("DOMContentLoaded event fired"); // Check if event is triggered
    populateColleges();
});

