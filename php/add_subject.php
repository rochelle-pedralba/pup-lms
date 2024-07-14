<?php

date_default_timezone_set('Asia/Manila');

require_once 'includes/dbh_inc.php';
require_once 'includes/execute_query_inc.php';
require_once 'includes/error_model_inc.php';

$sql = "CREATE TABLE IF NOT EXISTS subject_section_designation (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    program VARCHAR(255) NOT NULL,
    course VARCHAR(255) NOT NULL,
    subj VARCHAR(255) NOT NULL,
    subj_code VARCHAR(100) NOT NULL,
    year_level VARCHAR(100) NOT NULL,
    section VARCHAR(100) NOT NULL,
    faculty VARCHAR(255) NOT NULL
)";

$conn->close();
?>