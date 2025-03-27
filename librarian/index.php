<?php
    session_start();
    require "../db_connect.php";
    require "../message_display.php";
    require "../verify_logged_out.php";
    require "../header.php";
?>

<html>
    <head>
        <title>LMS</title>
        <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
        <link rel="stylesheet" type="text/css" href="../css/form_styles.css">
        <link rel="stylesheet" type="text/css" href="css/index_style.css">
    </head>
    <body>
        <form class="cd-form" method="POST" action="#">
            <center><legend>Admin Login</legend></center>

            <div class="error-message" id="error-message">
                <p id="error"></p>
            </div>
            
            <div class="icon">
                <input class="l-user" type="text" name="l_user" placeholder="Username" required />
            </div>
            
            <div class="icon">
                <input class="l-pass" type="password" name="l_pass" placeholder="Password" required />
            </div>
            
            <input type="submit" value="Login" name="l_login"/>
        </form>
        <p align="center"><a href="../index.php" style="text-decoration:none;">Go Back</a></p>
    </body>
    
    <?php
        if (isset($_POST['l_login'])) {
            // Sanitize user input to prevent SQL Injection
            $username = mysqli_real_escape_string($con, $_POST['l_user']);
            $passwordHash = sha1($_POST['l_pass']); // Store hashed password in a variable
            
            // Prepare the SQL statement with placeholders for parameters
            $query = $con->prepare("SELECT id FROM librarian WHERE username = ? AND password = ?;");
            $query->bind_param("ss", $username, $passwordHash); // Bind the parameters
            
            // Execute the query
            $query->execute();
            $result = $query->get_result(); // Get the result set
            
            if (mysqli_num_rows($result) != 1) {
                // Display error if no matching record found
                echo error_without_field("Invalid username/password combination");
            } else {
                // Fetch the result properly and store session data
                $row = mysqli_fetch_assoc($result); // Fetch result as associative array
                $_SESSION['type'] = "librarian";
                $_SESSION['id'] = $row['id']; // Using the correct associative key for 'id'
                $_SESSION['username'] = $username;
                
                // Redirect to the home page
                header('Location: home.php');
                exit(); // Make sure to stop further script execution
            }
        }
    ?>
</html>
