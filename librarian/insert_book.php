<?php
    require "../db_connect.php";
    require "../message_display.php";
    require "verify_librarian.php";
    require "header_librarian.php";
?>

<html>
    <head>
        <title>LMS</title>
        <link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
        <link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
        <link rel="stylesheet" href="css/insert_book_style.css">
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

        <form class="cd-form" method="POST" action="#">
            <center><legend>Add New Book Details</legend></center>
            
                <div class="error-message" id="error-message">
                    <p id="error"></p>
                </div>
                
                <div class="icon">
                    <input class="b-isbn" id="b_isbn" type="number" name="b_isbn" placeholder="Book ID" required />
                </div>
                
                <div class="icon">
                    <input class="b-title" type="text" name="b_title" placeholder="Book Title" required />
                </div>
                
                <div class="icon">
                    <input class="b-author" type="text" name="b_author" placeholder="Author Name" required />
                </div>
                
                <div>
                <h4>Category</h4>
                
                    <p class="cd-select icon">
                        <select class="b-category" name="b_category">
                            <option>History</option>
                            <option>Comics</option>
                            <option>Fiction</option>
                            <option>Non-Fiction</option>
                            <option>Biography</option>
                            <option>Medical</option>
                            <option>Fantasy</option>
                            <option>Education</option>
                            <option>Sports</option>
                            <option>Technology</option>
                            <option>Literature</option>
                        </select>
                    </p>
                </div>
                
                <div class="icon">
                    <input class="b-copies" type="number" name="b_copies" placeholder="Number of Copies" required />
                </div>
                
                <br />
                <input class="b-isbn" type="submit" name="b_add" value="Add book" />
        </form>
    <body>
    
    <?php
        if(isset($_POST['b_add']))
        {
            $query = $con->prepare("SELECT isbn FROM book WHERE isbn = ?;");
            $query->bind_param("s", $_POST['b_isbn']);
            $query->execute();
            
            if(mysqli_num_rows($query->get_result()) != 0)
                echo error_with_field("A book with that ISBN already exists", "b_isbn");
            else
            {
                $query = $con->prepare("INSERT INTO book (isbn, title, author, category, copies) VALUES(?, ?, ?, ?, ?);");
                $query->bind_param("ssssd", $_POST['b_isbn'], $_POST['b_title'], $_POST['b_author'], $_POST['b_category'], $_POST['b_copies']);
                
                if(!$query->execute())
                    die(error_without_field("ERROR: Couldn't add book"));
                echo success("New book record has been added");
            }
        }
    ?>
</html>
