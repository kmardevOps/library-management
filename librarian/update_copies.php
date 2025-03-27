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
		<link rel="stylesheet" href="css/update_copies_style.css">
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
			<center><legend>Update Book Copies</legend></center>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				
				<div class="icon">
					<input class="b-isbn" type='text' name='b_isbn' id="b_isbn" placeholder="Book ID" required />
				</div>
					
				<div class="icon">
					<input class="b-copies" type="number" name="b_copies" placeholder="Copies to add" required />
				</div>
						
				<input type="submit" name="b_add" value="Update Book Copies" />
		</form>
	</body>
	
	<?php
		if(isset($_POST['b_add']))
		{
			$query = $con->prepare("SELECT isbn FROM book WHERE isbn = ?;");
			$query->bind_param("s", $_POST['b_isbn']);
			$query->execute();
			if(mysqli_num_rows($query->get_result()) != 1)
				echo error_with_field("Invalid ISBN", "b_isbn");
			else
			{
				$query = $con->prepare("UPDATE book SET copies = copies + ? WHERE isbn = ?;");
				$query->bind_param("ds", $_POST['b_copies'], $_POST['b_isbn']);
				if(!$query->execute())
					die(error_without_field("ERROR: Couldn\'t update book copies"));
				echo success("Number of book copies has been updated");
			}
		}
	?>
</html>