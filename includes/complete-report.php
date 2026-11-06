<?php
session_start();
error_reporting(0);
include('database.php');
if (strlen($_SESSION['detsuid'] == 0)) {
  header('location:logout.php');
} else {
  $userid = (int) $_SESSION['detsuid'];
  // only real YYYY-MM-DD dates get through, so they are safe in the SQL and the page
  $fromdate = valid_date($_GET['startDate'] ?? '');
  $todate = valid_date($_GET['endDate'] ?? '');
?>

  <!DOCTYPE html>
  <html lang="en" dir="ltr">

  <head>
    <meta charset="UTF-8">
    <title>Expenditure - Complete Report</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <style>
        /* Specific styles for printing */
        @media print {
            .amount-expense {
                color: #dc3545 !important; /* Red for expense */
            }
            .amount-income {
                color: #28a745 !important; /* Green for income */
            }
            .sidebar, nav {
                display: none;
            }
            .home-section {
                margin-left: 0;
            }
        }
        .amount-expense {
            color: #dc3545;
            font-weight: bold;
        }
        .amount-income {
            color: #28a745;
            font-weight: bold;
        }
    </style>
  </head>

  <body onload="window.print()">
    <div class="sidebar">
      <div class="logo-details">
        <i class='bx bx-album'></i>
        <span class="logo_name">Expenditure</span>
      </div>
      <ul class="nav-links">
        <li><a href="home.php"><i class='bx bx-grid-alt'></i><span class="links_name">Dashboard</span></a></li>
        <li><a href="add-expenses.php"><i class='bx bx-box'></i><span class="links_name">Expenses</span></a></li>
        <li><a href="add-income.php"><i class='bx bx-box'></i><span class="links_name">Income</span></a></li>
        <li><a href="manage-transaction.php"><i class='bx bx-list-ul'></i><span class="links_name">Manage List</span></a></li>
        <li><a href="analytics.php"><i class='bx bx-pie-chart-alt-2'></i><span class="links_name">Analytics</span></a></li>
        <li><a href="report.php" class="active"><i class="bx bx-file"></i><span class="links_name">Report</span></a></li>
        <li><a href="user_profile.php"><i class='bx bx-cog'></i><span class="links_name">Setting</span></a></li>
        <li class="log_out"><a href="logout.php"><i class='bx bx-log-out'></i><span class="links_name">Log out</span></a></li>
      </ul>
    </div>
    <section class="home-section">
      <nav>
        <div class="sidebar-button">
          <i class='bx bx-menu sidebarBtn'></i>
          <span class="dashboard">Expenditure</span>
        </div>
        <?php
        $uid = $_SESSION['detsuid'];
        $ret = mysqli_query($db, "select name  from users where id='$uid'");
        $row = mysqli_fetch_array($ret);
        $name = $row['name'];
        ?>
        <div class="profile-details">
          <img src="images/maex.png" alt="">
          <span class="admin_name"><?php echo $name; ?></span>
          <i class='bx bx-chevron-down' id='profile-options-toggle'></i>
          <ul class="profile-options" id='profile-options'>
            <li><a href="user_profile.php"><i class="fas fa-user-circle"></i> User Profile</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
          </ul>
        </div>
      </nav>

      <div class="home-content">
        <div class="overview-boxes">
          <div class="col-md-12">
            <br>
            <div class="card">
              <div class="card-header">
                <h4 class.card-title">Complete Report from <?php echo $fromdate; ?> to <?php echo $todate; ?></h4>
              </div>
              <div class="card-body">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>S.NO.</th>
                      <th>Date</th>
                      <th>Category</th>
                      <th>Description</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Get combined data
                    $sql = "(SELECT ID, IncomeDate as date, category, IncomeAmount as amount, Description, 'income' as type FROM tblincome WHERE (IncomeDate BETWEEN '$fromdate' and '$todate') && (UserId='$userid')) 
                            UNION ALL 
                            (SELECT ID, ExpenseDate as date, category, ExpenseCost as amount, Description, 'expense' as type FROM tblexpense WHERE (ExpenseDate BETWEEN '$fromdate' and '$todate') && (UserId='$userid')) 
                            ORDER BY date DESC";
                    $ret = mysqli_query($db, $sql);
                    $cnt = 1;
                    
                    // Get total income
                    $inc_total_query = mysqli_query($db, "SELECT sum(IncomeAmount) as totalincome from tblincome where (IncomeDate BETWEEN '$fromdate' and '$todate') && (UserId='$userid')");
                    $inc_total_row = mysqli_fetch_array($inc_total_query);
                    $total_income = $inc_total_row['totalincome'] ?? 0;

                    // Get total expense
                    $exp_total_query = mysqli_query($db, "SELECT sum(ExpenseCost) as totalexpense from tblexpense where (ExpenseDate BETWEEN '$fromdate' and '$todate') && (UserId='$userid')");
                    $exp_total_row = mysqli_fetch_array($exp_total_query);
                    $total_expense = $exp_total_row['totalexpense'] ?? 0;

                    // Calculate grand total
                    $grand_total = $total_income - $total_expense;

                    while ($row = mysqli_fetch_array($ret)) {
                    ?>
                      <tr>
                        <td><?php echo $cnt; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo $row['Description']; ?></td>
                        <?php if ($row['type'] == 'income') : ?>
                            <td class="amount-income"> +<?php echo $row['amount']; ?></td>
                        <?php else : ?>
                            <td class="amount-expense"> -<?php echo $row['amount']; ?></td>
                        <?php endif; ?>
                      </tr>
                    <?php
                      $cnt++;
                    } ?>
                    <tr>
                      <th colspan="4" style="text-align:right">Total Income</th>
                      <td class="amount-income"><b> +<?php echo $total_income; ?></b></td>
                    </tr>
                    <tr>
                      <th colspan="4" style="text-align:right">Total Expense</th>
                      <td class="amount-expense"><b> -<?php echo $total_expense; ?></b></td>
                    </tr>
                     <tr>
                      <th colspan="4" style="text-align:right">Grand Total (Net)</th>
                      <td colspan="1"><b><?php echo $grand_total; ?></b></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </body>
  </html>
<?php } ?>