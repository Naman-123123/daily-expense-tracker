<?php
session_start(); // added session_start() to start session
include('database.php');

if (isset($_POST['submit'])) {
  $userid = (int) $_SESSION['detsuid'];
  $dateexpense = valid_date($_POST['dateexpense']);
  $category = (int) $_POST['category'];
  $description = trim($_POST['description']); // changed 'category-description' to 'description'
  $costitem = (string) (float) $_POST['cost'];
  $expenseid = (int) $_POST['expenseid'];
  // CategoryId is updated too, so the edit form shows the new category next time
  $query = db_query($db, "UPDATE tblexpense SET ExpenseDate=?, CategoryId=?, category=(SELECT CategoryName FROM tblcategory WHERE CategoryId=? AND UserId=?), ExpenseCost=?, Description=? WHERE ID=? AND UserId=?",
    "siiissii", $dateexpense, $category, $category, $userid, $costitem, $description, $expenseid, $userid);
  if ($query) {
    $message = "Expense updated successfully";
    echo "<script type='text/javascript'>alert('$message');</script>";
    echo " <script type='text/javascript'>window.location.href = 'manage-transaction.php';</script>";
  } else {
    $message = "Expense could not be updated";
    echo "<script type='text/javascript'>alert('$message');</script>";
  }
}
?>
