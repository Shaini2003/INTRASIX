<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User Reviews - Intrasix</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color:rgb(119, 88, 126);
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            font-size: 2em;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
        }
        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            font-size: 0.9em;
            letter-spacing: 0.5px;
        }
        td {
            color: #555;
        }
        tr:hover {
            background-color: #f9f9f9;
            transition: background-color 0.3s ease;
        }
        .delete-btn {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.3s ease;
        }
        .delete-btn:hover {
            background-color: #cc0000;
        }
        .no-reviews {
            text-align: center;
            color: #777;
            font-size: 1.2em;
            padding: 20px;
        }
        /* Responsive Design */
        @media (max-width: 768px) {
            table, th, td {
                display: block;
                width: 100%;
            }
            th, td {
                padding: 10px;
            }
            tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
            th {
                display: none; /* Hide headers on small screens */
            }
            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
            }
            td:before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                font-weight: bold;
                color: #333;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>User Reviews</h2>

        <?php
        // Database connection
        include 'config.php'; // Assumes config.php has $conn defined

        // Fetch all reviews
        $sql = "SELECT * FROM review_table ORDER BY datetime DESC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr>
                    <th>Review ID</th>
                    <th>User Name</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Action</th>
                  </tr>";
            
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td data-label='Review ID'>" . $row['review_id'] . "</td>";
                echo "<td data-label='User Name'>" . $row['user_name'] . "</td>";
                echo "<td data-label='Rating'>" . $row['user_rating'] . "</td>";
                echo "<td data-label='Review'>" . $row['user_review'] . "</td>";
                echo "<td data-label='Action'>
                        <form method='POST' action='delete_review.php' onsubmit='return confirm(\"Are you sure you want to delete this review?\");'>
                            <input type='hidden' name='review_id' value='" . $row['review_id'] . "'>
                            <button type='submit' class='delete-btn'>Delete</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='no-reviews'>No reviews found.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>