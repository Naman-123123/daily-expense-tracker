<?php
session_start();
include('database.php');

// Get updated data for the chart, in the same shape home.php draws it with: dates as labels, totals as data
$userid = (int) ($_SESSION['detsuid'] ?? 0);
$query = db_query($db, "SELECT ExpenseDate, SUM(ExpenseCost) as total_cost FROM tblexpense WHERE UserId=? AND ExpenseDate > DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY ExpenseDate", "i", $userid);
$labels = array();
$data = array();
while ($query && $result = mysqli_fetch_array($query)) {
  $labels[] = date('Y-m-d', strtotime($result['ExpenseDate']));
  $data[] = (float) $result['total_cost'];
}

// Return data as JSON
header('Content-Type: application/json');
echo json_encode(array('labels' => $labels, 'data' => $data));
