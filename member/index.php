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
        
        <center><legend>Member Login</legend></center>
            
            <div class="error-message" id="error-message">
                <p id="error"></p>
            </div>
            
            <div class="icon">
                <input class="m-user" type="text" name="m_user" placeholder="Username" required />
            </div>
            
            <div class="icon">
                <input class="m-pass" type="password" name="m_pass" placeholder="Password" required />
            </div>
            
            <input type="submit" value="Login" name="m_login" />
            
            <br /><br /><br /><br />
            
            <p align="center">Don't have an account?&nbsp;<a href="register.php" style="text-decoration:none; color:red;">Register Now!</a>

            <p align="center"><a href="../index.php" style="text-decoration:none;">Go Back</a>
        </form>
    </body>
    
    <?php
        if(isset($_POST['m_login']))
        {
            $query = $con->prepare("SELECT id FROM member WHERE username = ? AND password = ?;");
            $query->bind_param("ss", $_POST['m_user'], sha1($_POST['m_pass']));
            $query->execute();
            $result = $query->get_result();
            
            if(mysqli_num_rows($result) != 1)
                echo error_without_field("Invalid details or Account has not been activated yet!");
            else 
            {
                $resultRow = mysqli_fetch_array($result);
                $_SESSION['type'] = "member";
                $_SESSION['id'] = $resultRow[0];
                $_SESSION['username'] = $_POST['m_user'];
                header('Location: home.php');
            }
        }
    ?>
    
</html>
