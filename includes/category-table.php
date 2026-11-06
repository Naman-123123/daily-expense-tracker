<?php
session_start();
$userid = (int) ($_SESSION['detsuid'] ?? 0);

// Connect to the database (shared settings, so it uses the same daily_expense database as every other page)
include('database.php');

// Retrieve total expense of each category
$result = db_query($db, "SELECT category, SUM(ExpenseCost) AS total_expense FROM tblexpense WHERE UserId = ? GROUP BY category", "i", $userid);

// Create an array to store the results
$data = array();

// Loop through the results and add them to the array
while ($result && $row = mysqli_fetch_assoc($result)) {
  $data[] = array(
    'category' => $row['category'],
    'total_expense' => $row['total_expense'],
  );
}

// Calculate the total expense
$total_expense = array_reduce($data, function($acc, $item) {
  return $acc + $item['total_expense'];
}, 0);

// Calculate the percentage for each category (0 when there are no expenses yet)
foreach ($data as &$item) {
  $item['percentage'] = $total_expense > 0 ? ($item['total_expense'] / $total_expense) * 100 : 0;
}
unset($item);

// Set the header to output JSON
header('Content-Type: application/json');
// Output the data as JSON
echo json_encode($data);
exit; // Add exit to ensure no other output is sent
