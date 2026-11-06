<?php
session_start();
// Error reporting removed for production, but useful for debugging if needed
// error_reporting(E_ALL); 
// ini_set('display_errors', 1);

// Ensure user is logged in
if (strlen($_SESSION['detsuid'] ?? 0) == 0) { // Using null coalescing operator for safety
    // Optionally send an error response or just exit
    header('HTTP/1.1 401 Unauthorized');
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

$userid = $_SESSION['detsuid'];

// Connect to MySQL database - Assuming 'database.php' includes the connection $db
include('database.php');

// Check connection
if (!$db) {
  // Use proper JSON error response
  header('HTTP/1.1 500 Internal Server Error');
  echo json_encode(['error' => 'Database connection failed: ' . mysqli_connect_error()]);
  exit;
}

// Retrieve total expense of each category
$query = "SELECT category, SUM(ExpenseCost) AS total_expense FROM tblexpense WHERE UserId = ? GROUP BY category";
$stmt = mysqli_prepare($db, $query);

if (!$stmt) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Failed to prepare statement: ' . mysqli_error($db)]);
    exit;
}

mysqli_stmt_bind_param($stmt, "i", $userid); // Bind the user ID parameter
if (!mysqli_stmt_execute($stmt)) {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['error' => 'Failed to execute statement: ' . mysqli_stmt_error($stmt)]);
    mysqli_stmt_close($stmt);
    exit;
}

$result = mysqli_stmt_get_result($stmt);

// Create an array to store the results
$data = array();

// Loop through the results and add them to the array
while ($row = mysqli_fetch_assoc($result)) {
  // Ensure total_expense is treated as a number (float or int)
  $data[] = array(
    'category' => $row['category'],
    'total_expense' => (float)$row['total_expense'], // Cast to float
  );
}

mysqli_stmt_close($stmt); // Close the statement
mysqli_close($db); // Close the database connection

/* Note: The percentage calculation is not strictly needed by the Chart.js code 
   in analytics.php, but it doesn't hurt to leave it if needed elsewhere. 
   Calculation is removed here for simplicity as it's not the cause of the blank chart.
*/

// **** THIS WAS THE MISSING LINE ****
// Set the header to indicate JSON content type
header('Content-Type: application/json');

// Convert the array to JSON format and output it
echo json_encode($data);
exit; // Ensure no other output interferes
?>