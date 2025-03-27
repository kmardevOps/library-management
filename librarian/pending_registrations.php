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
        <link rel="stylesheet" type="text/css" href="css/pending_registrations_style.css">
        
        <!-- Modal Styles -->
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
            background-color: #343642;            ; 
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
            .modal {
                display: none; /* Hidden by default */
                position: fixed;
                z-index: 1; /* Sit on top */
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/opacity */
                padding-top: 60px;
            }
            
            .modal-content {
                background-color: #fefefe;
                margin: 5% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 80%;
            }
            
            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
        
    </head>
    <body>
          <!-- Back Button -->
	<div class="back-button-container" style="text-align: center; margin-top: 20px;">
                <a href="home.php" class="back-button">
                    <button class="back-button-style">Back to Home</button>
                </a>
            </div>
        <?php
            $query = $con->prepare("SELECT username, name, email FROM pending_registrations");
            $query->execute();
            $result = $query->get_result();
            $rows = mysqli_num_rows($result);
            if ($rows == 0)
                echo "<h2 align='center'>None at the moment!</h2>";
            else {
                echo "<form class='cd-form' method='POST' action='#'>";
                echo "<center><legend>Pending Membership Registration</legend></center>";
                echo "<div class='error-message' id='error-message'>
                        <p id='error'></p>
                    </div>";
                echo "<table width='100%' cellpadding=10 cellspacing=10>
                        <tr>
                            <th></th>
                            <th>Username<hr></th>
                            <th>Name<hr></th>
                            <th>Email<hr></th>
                        </tr>";
                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_array($result);
                    echo "<tr>";
                    echo "<td>
                            <label class='control control--checkbox'>
                                <input type='checkbox' name='cb_" . $i . "' value='" . $row[0] . "' />
                                <div class='control__indicator'></div>
                            </label>
                        </td>";
                    for ($j = 0; $j < 3; $j++)
                        echo "<td>" . $row[$j] . "</td>";
                    echo "</tr>";
                }
                echo "</table><br /><br />";
                echo "<div style='float: right;'>";
                echo "<input type='submit' value='Confirm Verification' name='l_confirm' />&nbsp;&nbsp;&nbsp;";
                echo "<input type='submit' value='Reject' name='l_delete' />";
                echo "</div>";
                echo "</form>";
            }
            
            if (isset($_POST['l_confirm'])) {
                $members = 0;
                for ($i = 0; $i < $rows; $i++) {
                    if (isset($_POST['cb_' . $i])) {
                        $username = $_POST['cb_' . $i];
                        $query = $con->prepare("SELECT * FROM pending_registrations WHERE username = ?;");
                        $query->bind_param("s", $username);
                        $query->execute();
                        $row = $query->get_result()->fetch_assoc(); // Use fetch_assoc() for clarity
            
                        // Check if the password is missing or null
                        if (empty($row['password'])) {
                            echo error_without_field("ERROR: Password for the user '$username' is missing in pending registrations.");
                            continue; // Skip this record
                        }
            
                        // Insert the user into the members table
                        $query = $con->prepare("INSERT INTO member(username, password, name, email) VALUES(?, ?, ?, ?);");
                        $query->bind_param("ssss", $row['username'], $row['password'], $row['name'], $row['email']);
                        if (!$query->execute())
                            die(error_without_field("ERROR: Couldn't insert values"));
            
                        // Delete the user from pending_registrations table after insertion
                        $deleteQuery = $con->prepare("DELETE FROM pending_registrations WHERE username = ?;");
                        $deleteQuery->bind_param("s", $username);
                        if (!$deleteQuery->execute())
                            die(error_without_field("ERROR: Couldn't delete the user from pending registrations"));
            
                        $members++;
                    }
                }
                if ($members > 0)
                    echo success("Successfully added " . $members . " members and removed from pending registrations.");
                else
                    echo error_without_field("No registration selected or some records were skipped due to errors");
            }    

            if (isset($_POST['l_delete'])) {
                $requests = 0;
                for ($i = 0; $i < $rows; $i++) {
                    if (isset($_POST['cb_' . $i])) {
                        $username = $_POST['cb_' . $i];
                        $query = $con->prepare("DELETE FROM pending_registrations WHERE username = ?;");
                        $query->bind_param("s", $username);
                        if (!$query->execute())
                            die(error_without_field("ERROR: Couldn't delete values"));
            
                        $requests++;
                    }
                }
                if ($requests > 0) {
                    echo success("Successfully rejected " . $requests . " requests.");
                    echo "<script>showModal();</script>";
                } else {
                    echo error_without_field("No registration selected");
                }
            }            
        ?>

        <!-- The Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <p>Account Rejected Successfully!</p>
            </div>
        </div>

        <!-- Modal JavaScript -->
        <script>
            // Show modal function
            function showModal() {
                document.getElementById("myModal").style.display = "block";
            }

            // Close modal function
            function closeModal() {
                document.getElementById("myModal").style.display = "none";
            }

            // Close the modal if the user clicks outside of it
            window.onclick = function(event) {
                var modal = document.getElementById("myModal");
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>

    </body>
</html>
