<?php
    require "../db_connect.php";
    require "../message_display.php";
    require "verify_member.php";
    require "header_member.php";
?>

<html>
    <head>
        <title>LMS</title>
        <link rel="stylesheet" type="text/css" href="../css/global_styles.css">
        <link rel="stylesheet" type="text/css" href="css/home_style.css">
        <link rel="stylesheet" type="text/css" href="../css/custom_radio_button_style.css">
<style>
      /* Style for the search bar */
      .search-box {
                text-align: center;
                margin-bottom: 20px;
            }

            .search-box input[type="text"] {
                width: 300px; 
                padding: 10px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 4px;
                margin-left: 67%; 
                background-color: transparent;
                color: black;
            }

            .search-box input[type="submit"] {
                padding: 10px 20px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 4px;
                background-color: #484848;
                color: white;
                cursor: pointer;
            }

            .search-box input[type="submit"]:hover {
                background-color: #484848;
            }

        
            table {
                width: 100%;
                border-collapse: collapse;
            }

            table th, table td {
                padding: 10px;
                text-align: left;
                border: 1px solid #ccc;
            }

            /* Responsive Styles */
            @media (max-width: 768px) {
                .search-box input[type="text"], .search-box input[type="submit"] {
                    width: 80%; 
                    margin-left: 10%; 
                }

                /* Make table more responsive */
                table {
                    font-size: 12px;
                }

                table th, table td {
                    padding: 8px;
                }
            }

            @media (max-width: 480px) {
                .search-box input[type="text"], .search-box input[type="submit"] {
                    width: 90%; 
                    margin-left: 5%; 
                }

           
                table {
                    font-size: 10px;
                }

                table th, table td {
                    padding: 6px;
                }

                
                .search-box {
                    text-align: left;
                }
            }
</style>
    </head>
    <body>
        <?php
            // Initialize the search query
            $searchQuery = "";
            if (isset($_POST['search'])) {
                $searchQuery = $_POST['search'];
            }
            
            // Modify the query to filter based on the search input
            if (!empty($searchQuery)) {
                $query = $con->prepare("SELECT * FROM book WHERE title LIKE ? OR author LIKE ? OR category LIKE ? ORDER BY title");
                $searchTerm = "%".$searchQuery."%";
                $query->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
            } else {
                $query = $con->prepare("SELECT * FROM book ORDER BY title");
            }
            
            $query->execute();
            $result = $query->get_result();
            if (!$result) {
                die("ERROR: Couldn't fetch books");
            }
            $rows = mysqli_num_rows($result);
            if ($rows == 0) {
                echo "<h2 align='center'>No books available</h2>";
            } else {
                echo "<form class='cd-form' method='POST' action='#'>";
                echo "<center><legend>List of Available Books</legend></center>";
                
                // Search box
                echo "<div class='search-box' style='text-align:center;'>";
                echo "<input type='text' name='search' placeholder='Search by title, author, or category' value='" . htmlspecialchars($searchQuery) . "' />";
                echo "<input type='submit' value='Search' />";
                echo "</div>";

                echo "<div class='error-message' id='error-message'>
                        <p id='error'></p>
                    </div>";
                echo "<table>";
                echo "<tr>
                        <th></th>
                        <th>Book ID<hr></th>
                        <th>Book Title<hr></th>
                        <th>Author<hr></th>
                        <th>Category<hr></th>
                        <th>Copies<hr></th>
                    </tr>";
                for ($i = 0; $i < $rows; $i++) {
                    $row = mysqli_fetch_array($result);
                    echo "<tr>
                            <td>
                                <label class='control control--radio'>
                                    <input type='radio' name='rd_book' value=".$row[0]." />
                                <div class='control__indicator'></div>
                            </td>";
                    for ($j = 0; $j < 5; $j++) {
                        echo "<td>".$row[$j]."</td>";
                    }
                    echo "</tr>";
                }
                echo "</table>";
                echo "<br /><br /><input type='submit' name='m_request' value='Request Book' />";
                echo "</form>";
            }

            if (isset($_POST['m_request'])) {
                if (empty($_POST['rd_book'])) {
                    echo error_without_field("Please select a book to issue");
                } else {
                    $query = $con->prepare("SELECT copies FROM book WHERE isbn = ?;");
                    $query->bind_param("s", $_POST['rd_book']);
                    $query->execute();
                    $copies = mysqli_fetch_array($query->get_result())[0];
                    if ($copies == 0) {
                        echo error_without_field("No copies of the selected book are available");
                    } else {
                        $query = $con->prepare("SELECT request_id FROM pending_book_requests WHERE member = ?;");
                        $query->bind_param("s", $_SESSION['username']);
                        $query->execute();
                        if (mysqli_num_rows($query->get_result()) == 1) {
                            echo error_without_field("You can only request one book at a time");
                        } else {
                            $query = $con->prepare("SELECT book_isbn FROM book_issue_log WHERE member = ?;");
                            $query->bind_param("s", $_SESSION['username']);
                            $query->execute();
                            $result = $query->get_result();
                            if (mysqli_num_rows($result) >= 3) {
                                echo error_without_field("You cannot issue more than 3 books at a time");
                            } else {
                                $rows = mysqli_num_rows($result);
                                for ($i = 0; $i < $rows; $i++) {
                                    if (strcmp(mysqli_fetch_array($result)[0], $_POST['rd_book']) == 0) {
                                        break;
                                    }
                                }
                                if ($i < $rows) {
                                    echo error_without_field("You have already issued a copy of this book");
                                } else {
                                    $query = $con->prepare("INSERT INTO pending_book_requests(member, book_isbn) VALUES(?, ?);");
                                    $query->bind_param("ss", $_SESSION['username'], $_POST['rd_book']);
                                    if (!$query->execute()) {
                                        echo error_without_field("ERROR: Couldn't request book");
                                    } else {
                                        echo success("Selected book has been requested. Soon you'll' be notified when the book is issued to your account!");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        ?>
    </body>
</html>
