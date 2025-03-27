<?php
    require "../db_connect.php";
    require "../message_display.php";
    require "verify_librarian.php";
    require "header_librarian.php";
?>

<html>
<head>
    <title>LMS</title>
    <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
    <link rel="stylesheet" type="text/css" href="../css/custom_checkbox_style.css">
    <link rel="stylesheet" type="text/css" href="css/pending_book_requests_style.css">
</head>
<style>
    /* Styling for the back button */
    .back-button-container {
        position: fixed;
        top: 0;
        left: 20px;
        z-index: 999; 
    }

    .back-button {
        text-decoration: none; 
    }

    .back-button-style {
        background-color: #343642;
        margin-top: 80px;
        color: white;
        padding: 12px 24px;
        font-size: 18px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .back-button-style:hover {
        background-color: grey; 
    }
</style>
<body>
    <!-- Back Button -->
    <div class="back-button-container" style="text-align: center; margin-top: 20px;">
        <a href="home.php" class="back-button">
            <button class="back-button-style">Back to Home</button>
        </a>
    </div>

    <?php
        $query = $con->prepare("SELECT * FROM pending_book_requests");
        $query->execute();
        $result = $query->get_result();
        $rows = mysqli_num_rows($result);
        
        if ($rows == 0) {
            echo "<h2 align='center'>No pending book requests</h2>";
        } else {
            echo "<form class='cd-form' method='POST' action='#'>";
            echo "<center><legend>Pending Book Requests</legend></center>";
            echo "<div class='error-message' id='error-message'>
                    <p id='error'></p>
                </div>";
            echo "<table width='100%' cellpadding=10 cellspacing=10>
                    <tr>
                        <th></th>
                        <th>Username<hr></th>
                        <th>Book ID<hr></th>
                        <th>Time<hr></th>
                        <th>Due Date<hr></th>
                    </tr>";

            for ($i = 0; $i < $rows; $i++) {
                $row = mysqli_fetch_array($result);
                $defaultDueDate = date('Y-m-d', strtotime('+14 days')); // Default to 14 days
                
                echo "<tr>";
                echo "<td>
                        <label class='control control--checkbox'>
                            <input type='checkbox' name='cb_" . $i . "' value='" . $row[0] . "' />
                            <div class='control__indicator'></div>
                        </label>
                    </td>";
                for ($j = 1; $j < 4; $j++)
                    echo "<td>" . $row[$j] . "</td>";
                
                // Input field for due date
                echo "<td>
                        <input type='date' name='due_date_" . $i . "' value='" . $defaultDueDate . "' required>
                      </td>";
                echo "</tr>";
            }

            echo "</table><br /><br />";
            echo "<div style='float: right;'>";
            echo "<input type='submit' value='Allow' name='l_allow' />&nbsp;&nbsp;&nbsp;";
            echo "<input type='submit' value='Reject' name='l_reject' />";
            echo "</div>";
            echo "</form>";
        }

        if (isset($_POST['l_allow'])) {
            $requests = 0;
            for ($i = 0; $i < $rows; $i++) {
                if (isset($_POST['cb_' . $i])) {
                    $requestId = $_POST['cb_' . $i];
                    $dueDate = $_POST['due_date_' . $i]; // Get the user-selected due date
                    
                    // Fetch request details
                    $query = $con->prepare("SELECT member, book_isbn FROM pending_book_requests WHERE request_id = ?;");
                    $query->bind_param("s", $requestId);
                    $query->execute();
                    $requestRow = mysqli_fetch_array($query->get_result());

                    // Insert into book_issue_log with the selected due date
                    $query = $con->prepare("INSERT INTO book_issue_log (member, book_isbn, due_date) VALUES (?, ?, ?);");
                    $query->bind_param("sss", $requestRow[0], $requestRow[1], $dueDate);
                    if (!$query->execute())
                        die(error_without_field("ERROR: Couldn't issue the book"));

                    // Update book copies
                    $query = $con->prepare("UPDATE book SET copies = copies - 1 WHERE isbn = ?;");
                    $query->bind_param("s", $requestRow[1]);
                    if (!$query->execute())
                        die(error_without_field("ERROR: Couldn't update book copies"));

                    // Delete the request
                    $query = $con->prepare("DELETE FROM pending_book_requests WHERE request_id = ?;");
                    $query->bind_param("s", $requestId);
                    if (!$query->execute())
                        die(error_without_field("ERROR: Couldn't delete the request"));

                    $requests++;
                }
            }
            if ($requests > 0)
                echo success("Successfully allowed " . $requests . " requests. Due dates set as selected.");
            else
                echo error_without_field("No request selected");
        }

        // Handle "Reject" functionality
        if (isset($_POST['l_reject'])) {
            $requests = 0;
            for ($i = 0; $i < $rows; $i++) {
                if (isset($_POST['cb_' . $i])) {
                    $requestId = $_POST['cb_' . $i];
                    $query = $con->prepare("DELETE FROM pending_book_requests WHERE request_id = ?;");
                    $query->bind_param("s", $requestId);
                    if (!$query->execute())
                        die(error_without_field("ERROR: Couldn't delete the request"));
                    $requests++;
                }
            }
            if ($requests > 0)
                echo success("Successfully rejected " . $requests . " requests");
            else
                echo error_without_field("No request selected");
        }
    ?>
</body>
</html>
