<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a Subject</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: baseline;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
        }
        #subject_form {
            padding: 20px;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #subject_form h1 {
            text-align: center;
            margin: 20px;
            padding: 10px;
        }
        label {
            font-weight: bold;
            padding: 5px;
        }
        input, select, textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        textarea {
            height: 80px;
            resize: vertical;
        }
        button {
            background-color:maroon;
            justify-content: center;
            color: white;
            border: none;
            padding: 10px 60px;
            cursor: pointer;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div id="subject_form">
        <h1>ADD SUBJECT</h1>
        <form method="POST" action="../../../php/add_subject.php">
            <label for="cohort_name">Campus:</label>
            <select id="cohort_name" name="cohort_name">
                <option selected disabled>Select Campus</option>
            </select>

            <input type="hidden" name="cohort_ID" id="cohort_ID" value="">
            
            <label for="course_ID">Course:</label>
            <input type="text" id="course_ID" name="course_ID" placeholder="BSCS" required>
            </select>
            
            <label for="ay">Academic Year:</label>
            <input type="text" id="ay" name="ay" placeholder="2425" required>
            
            <label for="semester">Semester:</label>
            <input type="text" id="semester" name="semester" placeholder="2" required>
            
            <label for="subject_ID">Subject Code:</label>
            <input type="text" id="subject_ID" name="subject_ID" placeholder="COSC 10023" required>
            
            <label for="subject_name">Subject:</label>
            <input type="text" id="subject_name" name="subject_name" placeholder="Web Development" required>
            
            <label for="subject_description">Subject Description:</label>
            <textarea type="text" id="subject_description" name="subject_description" placeholder="Enter description"></textarea>
            
            <label for="year">Year Level:</label>
            <input type="text" id="year" name="year" min="1" max="5" placeholder="3" required>
            
            <label for="section">Section:</label>
            <input type="text" id="section" name="section" placeholder="5" required>
            
            <button type="submit">SUBMIT</button>
        </form>
    </div>
    <script src="../../../scripts/add_subject.js">
        
    </script>    
</body>
</html>
