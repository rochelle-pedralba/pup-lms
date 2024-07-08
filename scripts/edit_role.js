document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('searchForm');
    const userDetailsDiv = document.getElementById('user_Details');
    const selectElement = document.getElementById('new_Role');
    const hiddenUserIDField = document.getElementById('user_ID_hidden');

    const roles = [
        { value: '1', label: 'Manager' },
        { value: '2', label: 'Course Creator' },
        { value: '3', label: 'Course Specialist' },
        { value: '4', label: 'Non-Editing Teacher' },
        { value: '5', label: 'Student' }
    ];

    const roleMappings = {
        '1': 'Manager',
        '2': 'Course Creator',
        '3': 'Course Specialist',
        '4': 'Non-Editing Teacher',
        '5': 'Student',
        'NONE': 'NONE'
    };

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        const userID = document.getElementById('user_ID').value;

        fetch(`../php/edit_role.php?user_ID=${userID}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                const formatDate = (dateStr) => {
                    if (dateStr === 'NONE') return dateStr;
                    const date = new Date(dateStr);
                    return date.toLocaleDateString();
                };

                // Update user details
                document.getElementById('first_Name').textContent = data.first_Name ?? '';
                document.getElementById('middle_Name').textContent = data.middle_Name ?? '';
                document.getElementById('last_Name').textContent = data.last_Name ?? '';
                document.getElementById('userID').textContent = data.user_ID ?? '';
                document.getElementById('user_Role').textContent = roleMappings[data.user_Role] ?? 'INVALID ROLE';
                document.getElementById('date_Assigned').textContent = formatDate(data.date_Assigned);
                document.getElementById('previous_Role').textContent = roleMappings[data.previous_Role] ?? 'INVALID ROLE';
                document.getElementById('date_Change').textContent = formatDate(data.date_Change);

                selectElement.innerHTML = ''; 

                roles.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option.value;
                    optionElement.textContent = option.label;
                    selectElement.appendChild(optionElement);
                });

                selectElement.value = data.user_Role;
                hiddenUserIDField.value = data.user_ID;
                userDetailsDiv.style.display = 'block';
            })
            .catch(error => console.error('Error fetching user data:', error));
    });
});

function cancelUpdateRole() {
    window.location.href = "../pages/index_manager.php";
}
