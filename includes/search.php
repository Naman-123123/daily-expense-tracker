<?php
session_start();
include 'database.php';
// Retrieve search query and limit from POST parameters
$userid = (int) ($_SESSION['detsuid'] ?? 0);
$searchQuery = $_POST['query'] ?? '';
$limit = max(1, (int) ($_POST['limit'] ?? 10));
// Search this user's expenses by description
$result = db_query($db, "SELECT * FROM tblexpense WHERE UserId = ? AND Description LIKE ? LIMIT ?", "isi", $userid, '%' . $searchQuery . '%', $limit);
// Check if any expenses were found
if ($result && mysqli_num_rows($result) > 0) {
  // Loop through each expense and generate HTML for expense details
  while($row = mysqli_fetch_assoc($result)) {
    echo '<div class="expense">';
    echo '<h4>' . htmlspecialchars($row['Description']) . '</h4>';
    echo '<p>Amount: ' . htmlspecialchars($row['ExpenseCost']) . '</p>';
    echo '<p>Date: ' . htmlspecialchars($row['ExpenseDate']) . '</p>';
    echo '</div>';
  }
} else {
  // No expenses were found
  echo '<p>No expenses found.</p>';
}
