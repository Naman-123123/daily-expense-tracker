<?php
session_start();
include('database.php');

if (isset($_POST['submit'])) {
  $userid = (int) $_SESSION['detsuid'];
  $dateincome = valid_date($_POST['dateincome']); // Form field for income date
  $category = (int) $_POST['category'];     // Form field for CategoryId
  $description = trim($_POST['description']); // Form field for description
  $amountitem = (string) (float) $_POST['amount'];   // Form field for income amount
  $incomeid = (int) $_POST['incomeid'];     // Form field for the income row's ID

  // Update query for the tblincome table
  $query = db_query($db, "UPDATE tblincome SET
                              IncomeDate=?,
                              category=(SELECT CategoryName FROM tblcategory WHERE CategoryId=? AND UserId=?),
                              CategoryId=?,
                              IncomeAmount=?,
                              Description=?
                            WHERE ID=? AND UserId=?",
    "siiissii", $dateincome, $category, $userid, $category, $amountitem, $description, $incomeid, $userid);
  
  if ($query) {
    $message = "Income updated successfully";
    echo "<script type='text/javascript'>alert('$message');</script>";
    echo " <script type='text/javascript'>window.location.href = 'manage-transaction.php';</script>";
  } else {
    $message = "Income could not be updated";
    echo "<script type='text/javascript'>alert('$message');</script>";
  }
}
?>  