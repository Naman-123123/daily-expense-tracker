<?php
session_start();
error_reporting(0);
include('database.php');
if (strlen($_SESSION['detsuid'] == 0)) {
  header('location:logout.php');
} else {

  $userid = (int) $_SESSION['detsuid'];

  // Get filter parameters from URL (page and limit are at least 1; the filter must be a known value
  // because it is written back into links and scripts on this page)
  $limit = isset($_GET['limit']) && !empty($_GET['limit']) ? max(1, intval($_GET['limit'])) : 10;
  $page = isset($_GET['page']) && !empty($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
  $type_filter = isset($_GET['type_filter']) && in_array($_GET['type_filter'], ['all', 'expense', 'income'], true) ? $_GET['type_filter'] : 'all';
  $offset = ($page - 1) * $limit;

  // Code deletion
  if (isset($_GET['delid']) && isset($_GET['type'])) {
    $rowid = intval($_GET['delid']);
    $type = $_GET['type'];
    
    // Determine which table to delete from
    $table_to_delete = ($type == 'income') ? 'tblincome' : 'tblexpense';

    $query = mysqli_query($db, "DELETE FROM $table_to_delete WHERE ID='$rowid' AND UserId='$userid'");
    if ($query) {
      echo "<script>alert('Record successfully deleted');</script>";
      // Redirect back to the same page with all filters preserved
      echo "<script>window.location.href='manage-transaction.php?page=$page&limit=$limit&type_filter=$type_filter'</script>";
    } else {
      echo "<script>alert('Something went wrong. Please try again');</script>";
    }
  }

?>


  <!DOCTYPE html>
  <html lang="en" dir="ltr">

  <head>
    <meta charset="UTF-8">
    <title>Expenditure</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-+SyhYdFyoOSvQxk89F0KTIJzWCEBmZwq3s0we3sNQ8WYt0Nv/b0+HkcgCZtqwWZzTJwR95G6Hrx0mEnm7MfXg==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
      .container {
        background-color: #f2f2f2;
        border-radius: 5px;
        box-shadow: 0px 0px 10px #aaa;
        padding: 20px;
        margin-top: 20px;
      }

      .form-group label {
        font-weight: bold;
      }

      .form-control {
        border-radius: 3px;
        border: 1px solid #ccc;
      }

      .invalid-feedback {
        color: red;
        font-size: 12px;
      }

      .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
      }

      .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
      }

      /* Styles for Income/Expense Amount */
      .amount-expense {
          background-color: #ffe8e8 !important;
          color: #c00;
          font-weight: bold;
      }
      .amount-income {
          background-color: #e8ffe8 !important;
          color: #060;
          font-weight: bold;
      }
      /* Style for total row */
      .total-row th, .total-row td {
        font-size: 1.1em;
        font-weight: bold;
      }


      .dropdown-item .fa-edit {
        color: #007bff;
      }

      .dropdown-item .fa-trash-alt {
        color: red;
      }

      /* Custom styles for the table */
      .table th,
      .table td {
        vertical-align: middle;
      }

      .table th {
        background-color: #f5f5f5;
        border-color: #ddd;
        font-weight: bold;
        text-transform: uppercase;
      }

      .table td {
        border-color: #ddd;
      }

      .table-striped tbody tr:nth-of-type(odd) {
        background-color: #f9f9f9;
      }

      .table-hover tbody tr:hover {
        background-color: #f5f5f5;
      }

      .btn-danger {
        background-color: #d9534f;
        border-color: #d43f3a;
      }

      .btn-danger:hover {
        background-color: #c9302c;
        border-color: #ac2925;
      }

      .btn-sm {
        font-size: 12px;
        padding: 4px 10px;
      }
    </style>
  </head>

  <body>
    <div class="sidebar">
      <div class="logo-details">
        <i class='bx bx-album'></i>
        <span class="logo_name">Expenditure</span>
      </div>
      <ul class="nav-links">
        <li>
          <a href="home.php">
            <i class='bx bx-grid-alt'></i>
            <span class="links_name">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="add-expenses.php">
            <i class='bx bx-box'></i>
            <span class="links_name">Expenses</span>
          </a>
        </li>
        <li>
          <a href="add-income.php">
            <i class='bx bx-box'></i>
            <span class="links_name">Income</span>
          </a>
        </li>
        <li>
          <a href="manage-transaction.php" class="active">
            <i class='bx bx-list-ul'></i>
            <span class="links_name">Manage List</span>
          </a>
        </li>
        <li>
          <a href="analytics.php">
            <i class='bx bx-pie-chart-alt-2'></i>
            <span class="links_name">Analytics</span>
          </a>
        </li>
        <li>
          <a href="report.php">
            <i class="bx bx-file"></i>
            <span class="links_name">Report</span>
          </a>
        </li>
        <li>
          <a href="user_profile.php">
            <i class='bx bx-cog'></i>
            <span class="links_name">Setting</span>
          </a>
        </li>
        <li class="log_out">
          <a href="logout.php">
            <i class='bx bx-log-out'></i>
            <span class="links_name">Log out</span>
          </a>
        </li>
      </ul>
    </div>
    <section class="home-section">
      <nav>
        <div class="sidebar-button">
          <i class='bx bx-menu sidebarBtn'></i>
          <span class="dashboard">Expenditure</span>
        </div>
        <div class="search-box">
          <input input type="text" id="search-input" class="form-control form-control-sm mx-2" placeholder="Search...">
          <i class='bx bx-search'></i>
        </div>
        
        <?php
        $ret = mysqli_query($db, "select name  from users where id='$userid'");
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
          <div class="col-sm-10 col-sm-offset-3 col-lg-12 col-lg-offset-2 main">
            <div class="row">
              <div class="col-lg-12">
                <div class="panel panel-default">
                  <div class="card">
                    <div class="card-header">
                      <div class="row">
                        <div class="col-md-6">
                          <h4 class="card-title">Manage Transactions</h4>
                        </div>
                        <div class="col-md-6 text-right">

                          <label>Type
                            <select class="form-control-sm mx-2" id="select-type">
                              <option value="all">All</option>
                              <option value="expense">Expense</option>
                              <option value="income">Income</option>
                            </select>
                          </label>

                          <label>Show
                            <select class="form-control-sm mx-2" id="select-entries">
                              <option value="10">10</option>
                              <option value="25">25</option>
                              <option value="50">50</option>
                              <option value="100">100</option>
                            </select>
                            entries
                          </label>
                        </div>
                        
                      </div>
                    </div>
                    <div class="panel-body">
                      <div class="col-md-12">
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered table-hover">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Description</th>
                                <th>Date</th>
                                <th>Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              // Base queries
                              $base_expense_query = "(SELECT ID, CategoryId, ExpenseDate as TransactionDate, category, ExpenseCost as Amount, Description, NoteDate, 'expense' as transaction_type FROM tblexpense WHERE UserId='$userid')";
                              $base_income_query = "(SELECT ID, CategoryId, IncomeDate as TransactionDate, category, IncomeAmount as Amount, Description, NoteDate, 'income' as transaction_type FROM tblincome WHERE UserId='$userid')";

                              // Build query based on filter
                              if ($type_filter == 'expense') {
                                $sql = $base_expense_query;
                                $count_sql = "SELECT COUNT(ID) as total FROM tblexpense WHERE UserId='$userid'";
                              } else if ($type_filter == 'income') {
                                $sql = $base_income_query;
                                $count_sql = "SELECT COUNT(ID) as total FROM tblincome WHERE UserId='$userid'";
                              } else {
                                $sql = $base_expense_query . " UNION ALL " . $base_income_query;
                                $count_sql = "SELECT SUM(total) as total FROM (
                                                (SELECT COUNT(ID) as total FROM tblexpense WHERE UserId='$userid')
                                                UNION ALL
                                                (SELECT COUNT(ID) as total FROM tblincome WHERE UserId='$userid')
                                              ) as combined_counts";
                              }

                              // Add sorting and pagination
                              $sql .= " ORDER BY TransactionDate DESC, NoteDate DESC LIMIT $limit OFFSET $offset";
                              
                              $ret = mysqli_query($db, $sql);
                              $cnt = $offset + 1;
                              while ($row = mysqli_fetch_array($ret)) {
                              ?>
                                <tr>
                                  <td><?php echo $cnt; ?></td>
                                  <td>
                                    <?php 
                                      $badge_class = ($row['transaction_type'] == 'expense') ? 'badge-danger' : 'badge-success';
                                      echo '<span class="badge ' . $badge_class . '">' . htmlspecialchars($row['category']) . '</span>';
                                    ?>
                                  </td>
                                  
                                  <?php 
                                    if ($row['transaction_type'] == 'expense') {
                                      echo '<td class="amount-expense"> -' . htmlspecialchars($row['Amount']) . '</td>';
                                    } else {
                                      echo '<td class="amount-income"> +' . htmlspecialchars($row['Amount']) . '</td>';
                                    }
                                  ?>
                                  
                                  <td><?php echo htmlspecialchars($row['Description']); ?></td>
                                  <td><?php echo $row['TransactionDate']; ?></td>
                                  
                                  <td>
                                    <div class="dropdown">
                                      <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton_<?php echo $row['ID']; ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Action
                                      </button>
                                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton_<?php echo $row['ID']; ?>">
                                        
                                        <?php if ($row['transaction_type'] == 'expense'): ?>
                                          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editExpenseModal_<?php echo $row['ID']; ?>"><i class="fas fa-edit"></i> Edit</a>
                                        <?php else: ?>
                                          <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editIncomeModal_<?php echo $row['ID']; ?>"><i class="fas fa-edit"></i> Edit</a>
                                        <?php endif; ?>
                                        
                                        <a class="dropdown-item" href="manage-transaction.php?delid=<?php echo $row['ID']; ?>&type=<?php echo $row['transaction_type']; ?>&page=<?php echo $page; ?>&limit=<?php echo $limit; ?>&type_filter=<?php echo $type_filter; ?>" onclick="return confirm('Are you sure you want to delete this record?');"><i class="fas fa-trash-alt"></i> Delete</a>
                                      </div>
                                    </div>

                                    <?php if ($row['transaction_type'] == 'expense'): ?>
                                    <div class="modal fade" id="editExpenseModal_<?php echo $row['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="editExpenseModalLabel_<?php echo $row['ID']; ?>" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="editExpenseModalLabel_<?php echo $row['ID']; ?>">Edit Expense</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <form method="post" action="update-expense.php">
                                            <div class="modal-body">
                                              <div class="form-group">
                                                <label for="edit-expense-date">Date:</label>
                                                <input type="date" class="form-control" id="edit-expense-date" name="dateexpense" value="<?php echo $row['TransactionDate']; ?>">
                                              </div>
                                              <div class="form-group">
                                                <label for="edit-expense-category">Category:</label>
                                                <select class="form-control" id="edit-expense-category" name="category">
                                                  <?php
                                                  $cat_query = mysqli_query($db, "SELECT * FROM tblcategory where UserId = $userid AND Mode = 'expense'");
                                                  while ($row_category = mysqli_fetch_array($cat_query)) {
                                                    $selected = ($row_category['CategoryId'] == $row['CategoryId']) ? 'selected' : '';
                                                    echo '<option value="' . $row_category['CategoryId'] . '" ' . $selected . '>' . $row_category['CategoryName'] . '</option>';
                                                  }
                                                  ?>
                                                </select>
                                              </div>
                                              <div class="form-group">
                                                <label for="edit-expense-cost">Cost:</label>
                                                <input type="number" class="form-control" id="edit-expense-cost" name="cost" value="<?php echo $row['Amount']; ?>">
                                              </div>
                                              <div class="form-group">
                                                <label for="edit-expense-description">Description:</label>
                                                <textarea class="form-control" id="edit-expense-description" name="description"><?php echo $row['Description']; ?></textarea>
                                              </div>
                                              <input type="hidden" name="expenseid" value="<?php echo $row['ID']; ?>">
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <?php endif; ?>

                                    <?php if ($row['transaction_type'] == 'income'): ?>
                                    <div class="modal fade" id="editIncomeModal_<?php echo $row['ID']; ?>" tabindex="-1" role="dialog" aria-labelledby="editIncomeModalLabel_<?php echo $row['ID']; ?>" aria-hidden="true">
                                      <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="editIncomeModalLabel_<?php echo $row['ID']; ?>">Edit Income</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <form method="post" action="update-income.php">
                                            <div class="modal-body">
                                              <div class="form-group">
                                                <label for="edit-income-date">Date:</label>
                                                <input type="date" class="form-control" id="edit-income-date-<?php echo $row['ID']; ?>" name="dateincome" value="<?php echo $row['TransactionDate']; ?>">
                                              </div>
                                              <div class="form-group">
                                                <label for="edit-income-category">Category:</label>
                                                <select class="form-control" id="edit-income-category-<?php echo $row['ID']; ?>" name="category">
                                                  <?php
                                                  $cat_query_inc = mysqli_query($db, "SELECT * FROM tblcategory where UserId = $userid AND Mode = 'income'");
                                                  while ($row_category_inc = mysqli_fetch_array($cat_query_inc)) {
                                                    $selected_inc = ($row_category_inc['CategoryId'] == $row['CategoryId']) ? 'selected' : '';
                                                    echo '<option value="' . $row_category_inc['CategoryId'] . '" ' . $selected_inc . '>' . $row_category_inc['CategoryName'] . '</option>';
                                                  }
                                                  ?>
                                                </select>
                                              </div>
                                              <div class="form-group">
                                                <label for="edit-income-amount">Amount:</label>
                                                <input type="number" class="form-control" id="edit-income-amount-<?php echo $row['ID']; ?>" name="amount" value="<?php echo $row['Amount']; ?>">
                                              </div>
                                              <div class="form-group">
                                                <label for="edit-income-description">Description:</label>
                                                <textarea class="form-control" id="edit-income-description-<?php echo $row['ID']; ?>" name="description"><?php echo $row['Description']; ?></textarea>
                                              </div>
                                              <input type="hidden" name="incomeid" value="<?php echo $row['ID']; ?>">
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                              <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                    <?php endif; ?>

                                  </td>
                                </tr>
                              <?php
                                $cnt++;
                              } ?>
                            </tbody>
                            <tfoot>
                                <?php
                                  // Get total income
                                  $inc_total_query = mysqli_query($db, "SELECT sum(IncomeAmount) as totalincome from tblincome where UserId='$userid'");
                                  $inc_total_row = mysqli_fetch_array($inc_total_query);
                                  $total_income = $inc_total_row['totalincome'] ?? 0;

                                  // Get total expense
                                  $exp_total_query = mysqli_query($db, "SELECT sum(ExpenseCost) as totalexpense from tblexpense where UserId='$userid'");
                                  $exp_total_row = mysqli_fetch_array($exp_total_query);
                                  $total_expense = $exp_total_row['totalexpense'] ?? 0;

                                  // Calculate grand total
                                  $grand_total = $total_income - $total_expense;

                                  // Display totals based on the filter
                                  if ($type_filter == 'income') {
                                ?>
                                    <tr class="total-row">
                                      <th colspan="2" style="text-align:right">Total Income (All Pages)</th>
                                      <td class="amount-income" colspan="4">+<?php echo number_format($total_income, 2); ?></td>
                                    </tr>
                                <?php
                                  } else if ($type_filter == 'expense') {
                                ?>
                                    <tr class="total-row">
                                      <th colspan="2" style="text-align:right">Total Expense (All Pages)</th>
                                      <td class="amount-expense" colspan="4">-<?php echo number_format($total_expense, 2); ?></td>
                                    </tr>
                                <?php
                                  } else { // 'all'
                                ?>
                                    <tr class="total-row">
                                      <th colspan="2" style="text-align:right">Total Income (All Pages)</th>
                                      <td class="amount-income" colspan="4">+<?php echo number_format($total_income, 2); ?></td>
                                    </tr>
                                    <tr class="total-row">
                                      <th colspan="2" style="text-align:right">Total Expense (All Pages)</th>
                                      <td class="amount-expense" colspan="4">-<?php echo number_format($total_expense, 2); ?></td>
                                    </tr>
                                    <tr class="total-row">
                                      <th colspan="2" style="text-align:right">Net Total (All Pages)</th>
                                      <td colspan="4"><?php echo number_format($grand_total, 2); ?></td>
                                    </tr>
                                <?php
                                  }
                                ?>
                            </tfoot>
                          </table>
                        </div>


                        <?php
                        // Get total pages for pagination
                        $result = mysqli_query($db, $count_sql);
                        $data = mysqli_fetch_assoc($result);
                        $total_pages = ceil($data['total'] / $limit);
                        ?>

                        <ul class="pagination justify-content-end">
                          <li class="page-item <?php if ($page <= 1) { echo 'disabled'; } ?>">
                            <a class="page-link"
                              href="<?php if ($page <= 1) { echo '#'; } else { echo "?page=" . ($page - 1) . "&limit=$limit&type_filter=$type_filter"; } ?>">Previous</a>
                          </li>
                          <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                              <a class="page-link" href="<?php echo "?page=$i&limit=$limit&type_filter=$type_filter"; ?>"><?php echo $i; ?></a>
                            </li>
                          <?php endfor; ?>
                          <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link"
                              href="<?php if ($page >= $total_pages) { echo '#'; } else { echo "?page=" . ($page + 1) . "&limit=$limit&type_filter=$type_filter"; } ?>">Next</a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </section>



    <script>
      $(document).ready(function() {
        
        // --- Search Box Logic ---
        // Store original table rows
        var originalRows = $('table tbody tr').clone();

        $('#search-input').on('keyup', function() {
            var value = $(this).val().toLowerCase();
            
            // Restore original rows before filtering
            $('table tbody').empty().append(originalRows.clone());

            if (value) {
                var found = false;
                $('table tbody tr').filter(function() {
                    var matches = $(this).text().toLowerCase().indexOf(value) > -1;
                    $(this).toggle(matches);
                    if (matches) found = true;
                });

                if (!found) {
                    $('table tbody').html('<tr><td colspan="6" style="text-align:center;">No data found</td></tr>');
                }
            }
            // If value is empty, the original rows are already restored
        });

        // --- Filter Logic ---
        var limit = "<?php echo $limit; ?>";
        var type_filter = "<?php echo $type_filter; ?>";
        
        $('#select-entries').val(limit);
        $('#select-type').val(type_filter);

        // Handle "Show entries" change
        $('#select-entries').on('change', function() {
          var newLimit = $(this).val();
          var urlParams = new URLSearchParams(window.location.search);
          urlParams.set('limit', newLimit);
          urlParams.set('page', 1); // Reset to page 1
          window.location.href = window.location.pathname + '?' + urlParams.toString();
        });

        // Handle "Type" filter change
        $('#select-type').on('change', function() {
          var newTypeFilter = $(this).val();
          var urlParams = new URLSearchParams(window.location.search);
          urlParams.set('type_filter', newTypeFilter);
          urlParams.set('page', 1); // Reset to page 1
          window.location.href = window.location.pathname + '?' + urlParams.toString();
        });

        // --- Profile Dropdown Logic ---
        const toggleButton = document.getElementById('profile-options-toggle');
        const profileOptions = document.getElementById('profile-options');
        toggleButton.addEventListener('click', () => {
          profileOptions.classList.toggle('show');
        });

      });

      // --- Sidebar Toggle Logic ---
      let sidebar = document.querySelector(".sidebar");
      let sidebarBtn = document.querySelector(".sidebarBtn");
      sidebarBtn.onclick = function() {
        sidebar.classList.toggle("active");
        if (sidebar.classList.contains("active")) {
          sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
        } else
          sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
      }
    </script>



  <?php } ?>
  </body>
</html>