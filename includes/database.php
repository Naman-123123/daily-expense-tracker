<?php

$host = "localhost"; // change this to your MySQL hostname
$user = "root"; // change this to your MySQL username
$pass = ""; // change this to your MySQL password
$dbname = "daily_expense"; // change this to your MySQL database name

$db = new mysqli($host, $user, $pass, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if (!function_exists('db_query')) {
    // Runs a query with ? placeholders, so user input is never pasted into the SQL text.
    // Returns the result set for SELECT, true for INSERT/UPDATE/DELETE, false on error.
    function db_query($db, $sql, $types = "", ...$params) {
        $stmt = $db->prepare($sql);
        if (!$stmt) {
            return false;
        }
        if ($types !== "") {
            $stmt->bind_param($types, ...$params);
        }
        if (!$stmt->execute()) {
            return false;
        }
        $result = $stmt->get_result();
        return $result === false ? true : $result;
    }

    // Returns $value if it is a real date in YYYY-MM-DD format, otherwise today's date.
    function valid_date($value) {
        $date = DateTime::createFromFormat('Y-m-d', (string) $value);
        return ($date && $date->format('Y-m-d') === $value) ? $value : date('Y-m-d');
    }
}
