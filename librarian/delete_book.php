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
        <!-- Include Modal CSS -->
        <style>
    /* Modal background (dark overlay) */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7); /* Darker backdrop */
        transition: opacity 0.3s ease;
    }

    /* Modal Content */
    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 30px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
        animation: slideIn 0.3s ease-out;
    }
    /* Modal animation */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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

    
    .close {
        color: #aaa;
        font-size: 30px;
        font-weight: bold;
        position: absolute;
        right: 20px;
        top: 10px;
        cursor: pointer;
    }

    /* Close button hover effect */
    .close:hover,
    .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }

    .modal h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
    }

    #confirmRemove,
    .modal button {
        background-color: #ff4d4d;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-right: 10px;
    }

    /* Hover effect for buttons */
    #confirmRemove:hover,
    .modal button:hover {
        background-color: #ff1a1a;
    }

    .modal button {
        background-color: #ccc;
    }

    .modal button:hover {
        background-color: #bbb;
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
        $query = $con->prepare("SELECT * FROM book ORDER BY title");
        $query->execute();
        $result = $query->get_result();
        if (!$result) {
            die("ERROR: Couldn't fetch books");
        }
        $rows = mysqli_num_rows($result);
        if ($rows == 0) {
            echo "<h2 align='center'>No books available</h2>";
        } else {
            echo "<form class='cd-form'>";
            echo "<div class='error-message' id='error-message'>
                    <p id='error'></p>
                  </div>";
            echo "<table width='100%' cellpadding=10 cellspacing=10>";
            echo "<tr>
                    <th></th>
                    <th>Book ID<hr></th>
                    <th>Book Title<hr></th>
                    <th>Author<hr></th>
                    <th>Category<hr></th>
                    <th>Copies<hr></th>
                    <th>Action<hr></th>
                  </tr>";
            for ($i = 0; $i < $rows; $i++) {
                $row = mysqli_fetch_array($result);
                echo "<tr>
                        <td>
                            <label class='control control--radio'>
                                <input type='radio' name='rd_book' value=".$row[0]." />
                            <div class='control__indicator'></div>
                        </td>";
                for ($j = 0; $j < 5; $j++) { // Adjusted to only loop through the 5 remaining fields
                    echo "<td>".$row[$j]."</td>";
                }
                // Modify the Remove link to trigger the modal with the book id
                echo "<td><div class='text-center'>
                        <a href='#' onclick='openRemoveModal(".$row['isbn'].")' style='color:#F66; text-decoration:none;'> Remove</a>
                      </div></td>";
                echo "</tr>";
            }
            echo "</table>";
            echo "</form>";
        }
    ?>

    <!-- Remove Confirmation Modal -->
    <div id="removeModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRemoveModal()">&times;</span>
            <h2>Are you sure you want to remove this book?</h2>
            <button id="confirmRemove" onclick="confirmRemove()">Yes</button>
            <button onclick="closeRemoveModal()">No</button>
        </div>
    </div>

    <script>
        var bookIdToRemove = null;

        // Open the modal with the correct book id
        function openRemoveModal(bookId) {
            bookIdToRemove = bookId;
            document.getElementById('removeModal').style.display = 'block';
        }

        // Close the modal
        function closeRemoveModal() {
            document.getElementById('removeModal').style.display = 'none';
        }

        // Confirm removal of the book
        function confirmRemove() {
            if (bookIdToRemove !== null) {
                // Redirect to delete book action
                window.location.href = 'dltbook.php?id=' + bookIdToRemove;
            }
        }

        // Close modal if clicked outside
        window.onclick = function(event) {
            if (event.target == document.getElementById('removeModal')) {
                closeRemoveModal();
            }
        }
    </script>
    </body>
</html>
