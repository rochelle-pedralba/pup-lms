document.addEventListener('DOMContentLoaded', () => {
    fetch('../php/profile.php')
        .then(response => response.json())
        .then(data => {
            const dateOfBirth = new Date(data.date_Of_Birth);
            const formattedDate = dateOfBirth.toLocaleDateString();

            document.getElementById('first_Name_Name_Display').textContent = data.first_Name;
            document.getElementById('middle_Name_Name_Display').textContent = data.middle_Name;
            document.getElementById('last_Name_Name_Display').textContent = data.last_Name;
            document.getElementById('user_ID_Name_Display').textContent = data.user_ID;
            document.getElementById('user_ID').textContent = data.user_ID;
            document.getElementById('id_Number').textContent = data.id_Number;
            document.getElementById('first_Name').textContent = data.first_Name;
            document.getElementById('middle_Name').textContent = data.middle_Name;
            document.getElementById('last_Name').textContent = data.last_Name;
            document.getElementById('date_Of_Birth').textContent = formattedDate;
            document.getElementById('email_Address').textContent = data.email_Address;
            document.getElementById('mobile_Number').textContent = data.mobile_Number;
            document.getElementById('city').textContent = data.city;
            document.getElementById('province').textContent = data.province;
            document.getElementById('region').textContent = data.region;
            document.getElementById('country').textContent = data.country;
            document.getElementById('zip_Code').textContent = data.zip_Code;

            
        })
        .catch(error => console.error('Error fetching user data:', error));
});
