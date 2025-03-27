<?php
    require "../db_connect.php";
    require "../message_display.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
	<head>
		<title>LMS</title>
		<link rel="stylesheet" type="text/css" href="../member/css/home_style.css" />
        <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/home_style.css">
		<link rel="stylesheet" type="text/css" href="../member/css/custom_radio_button_style.css">
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
    <?php
			$query = $con->prepare("SELECT * FROM book ORDER BY title");
			$query->execute();
			$result = $query->get_result();
			if(!$result)
				die("ERROR: Couldn't fetch books");
			$rows = mysqli_num_rows($result);
			if($rows == 0)
				echo "<h2 align='center'>No books available</h2>";
			else
			{
				echo "<form class='cd-form'>";
				echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
				echo "<table width='100%' cellpadding=10 cellspacing=10>";
				echo "<tr>
        <th>Book ID<hr></th>
        <th>Book Title<hr></th>
        <th>Author<hr></th>
        <th>Category<hr></th>
        <th>Copies<hr></th>
      </tr>";
for($i=0; $i<$rows; $i++)
{
    $row = mysqli_fetch_array($result);
    echo "<tr>";
    for($j=0; $j<5; $j++) // Only display fields up to "copies"
        echo "<td>".$row[$j]."</td>";
    echo "</tr>";
}
				echo "</table>";
				
				echo "</form>";
			}
			
			
		?>

    </body>

</html>