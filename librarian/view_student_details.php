<?php
    require "../db_connect.php";
    require "verify_librarian.php";
    require "header_librarian.php";
?>

<html>
    <head>
        <title>View Student Details</title>
        <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
        <link rel="stylesheet" type="text/css" href="css/view_student_details_style.css">
    </head>
        <style>
    /*----------Table styles----------*/
      
        .student-table {
        width: 90%;
        max-width: 1000px;
        margin: 4em auto;
        font-size: 1.2rem;
        color: #333;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
        background: #D0DBFF; 
        }

        /* Table Header */
        .student-table thead {
        background-color: #6C7AE0; 
        color: white;
        font-weight: bold;
        }

        .student-table th, .student-table td {
        padding: 14px 16px;
        text-align: left;
        }

        .student-table thead tr:first-child th:first-child {
        border-top-left-radius: 12px;
        }
        .student-table thead tr:first-child th:last-child {
        border-top-right-radius: 12px;
        }

        /* Alternating Row Colors */
        .student-table tr:nth-child(even) {
        background: #FFFFFF;
        }

        .student-table tr:nth-child(odd) {
        background: #F5F7FF;
        }

        /* Hover Effect */
        .student-table tr:hover {
        background: #E0E5FF; 
        }

        /* Table Borders */
        .student-table th:first-child, .student-table td:first-child {
        padding-left: 20px;
        }

        .student-table th:last-child, .student-table td:last-child {
        padding-right: 20px;
        }

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

        </style>
    <body>
               <!-- Back Button -->
	<div class="back-button-container" style="text-align: center; margin-top: 20px;">
                <a href="home.php" class="back-button">
                    <button class="back-button-style">Back to Home</button>
                </a>
            </div>
        <?php
            // Query to fetch all student details from the member table
            $query = $con->prepare("SELECT id, username, name, email FROM member;");
            $query->execute();
            $result = $query->get_result();
            $rows = mysqli_num_rows($result);

            if ($rows == 0) {
                echo "<h2 align='center'>No student records found!</h2>";
            } else {
                echo "<table class='student-table'>
                        <thead>
                            <tr>
                                <th style='background-color:#343642;'>Username</th>
                                <th style='background-color:#343642;'>Full Name</th>
                                <th style='background-color:#343642;'>Email</th>
                            </tr>
                        </thead>
                        <tbody>";

                while ($row = mysqli_fetch_array($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "</tr>";
                }

                echo "    </tbody>
                      </table>";
            }
        ?>
    </body>
</html>
