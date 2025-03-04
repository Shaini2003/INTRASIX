<?php

include 'includes/dbh.php'; 

// Check if the form data for review submission is set
if (isset($_POST["rating_data"])) {

    // Prepare data to insert
    $data = array(
        'user_name'   => $_POST["user_name"],
        'user_rating' => $_POST["rating_data"],
        'user_review' => $_POST["user_review"],
        'datetime'    => time()
    );

    // SQL query to insert the review data into the database
    $query = "
    INSERT INTO review_table 
    (user_name, user_rating, user_review, datetime) 
    VALUES (?, ?, ?, ?);
    ";

    // Prepare and execute query
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        // Bind parameters to the query
        mysqli_stmt_bind_param($stmt, "ssss", $data['user_name'], $data['user_rating'], $data['user_review'], $data['datetime']);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo "Your Review & Rating Successfully Submitted";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// If action is set to load data, fetch reviews and send as JSON
if (isset($_POST["action"])) {
    // Variables to store review statistics
    $average_rating = 0;
    $total_review = 0;
    $five_star_review = 0;
    $four_star_review = 0;
    $three_star_review = 0;
    $two_star_review = 0;
    $one_star_review = 0;
    $total_user_rating = 0;
    $review_content = array();

    // SQL query to fetch all reviews
    $query = "SELECT * FROM review_table ORDER BY review_id DESC";

    // Execute the query and get the result
    $result = mysqli_query($conn, $query);

    // If there are results
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $review_content[] = array(
                'user_name'   => $row["user_name"],
                'user_review' => $row["user_review"],
                'rating'      => $row["user_rating"],
                'datetime'    => date('l jS, F Y h:i:s A', $row["datetime"])
            );

            // Count the ratings
            switch ($row["user_rating"]) {
                case 5: $five_star_review++; break;
                case 4: $four_star_review++; break;
                case 3: $three_star_review++; break;
                case 2: $two_star_review++; break;
                case 1: $one_star_review++; break;
            }

            // Calculate total review count and sum of ratings
            $total_review++;
            $total_user_rating += $row["user_rating"];
        }

        // Calculate average rating
        if ($total_review > 0) {
            $average_rating = $total_user_rating / $total_review;
        }

        // Prepare the output data
        $output = array(
            'average_rating' => number_format($average_rating, 1),
            'total_review'   => $total_review,
            'five_star_review' => $five_star_review,
            'four_star_review' => $four_star_review,
            'three_star_review' => $three_star_review,
            'two_star_review' => $two_star_review,
            'one_star_review' => $one_star_review,
            'review_data'    => $review_content
        );

        // Return the JSON data
        echo json_encode($output);
    } else {
        echo json_encode(['error' => 'Failed to load reviews']);
    }
}

?>