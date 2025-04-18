<?php
    require "../db_connect.php";
    require "verify_librarian.php";
    require "header_librarian.php";
?>

<html>
    <head>
        <title>LMS</title>
        <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
    </head>
    <body>
        <?php
            $query = "CALL generate_due_list();";
            $result = mysqli_query($con, $query);
            
            // Check if query execution was successful
            if (!$result) {
                
                echo "<h2>Error executing query: " . mysqli_error($con) . "</h2>";
                exit();
            }

            // Now we know the query succeeded, we can check the number of rows
            $rows = mysqli_num_rows($result);

            if ($rows > 0) {
                $successfulEmails = 0;
                $idArray = array(); 
                $header = 'From: <noreply@library.com>' . "\r\n";
                $subject = "Return your book today";

                // Loop through each row of the result set
                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_array($result);
                    $to = $row[1];  
                    $message = "This is a reminder to return the book '".$row[3]."' with ISBN ".$row[2]." to the library.";
                    
                    // Send email and check success
                    if (mail($to, $subject, $message, $header)) {
                        $idArray[$i] = $row[0];  
                        $successfulEmails++;
                    }
                }

                mysqli_next_result($con); 

                // Update the database with the issue IDs of successfully emailed users
                for ($i = 0; $i < $successfulEmails; $i++) {
                    $query = $con->prepare("UPDATE book_issue_log SET last_reminded = CURRENT_DATE WHERE issue_id = ?");
                    $query->bind_param("d", $idArray[$i]);
                    $query->execute();
                }

                if ($successfulEmails > 0) {
                    echo "<h2 align='center'>Successfully notified ".$successfulEmails." members</h2>";
                } else {
                    echo "ERROR: Couldn't notify any member.";
                }
            } else {
                echo "<h2 align='center'>No Pending Reminders</h2>";
            }
        ?>
    </body>
</html>
