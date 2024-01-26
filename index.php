<?php
session_start();
include('connection.php');

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $song = mysqli_real_escape_string($con, $_POST['song']);
    $comments = mysqli_real_escape_string($con, $_POST['comments']);

    if (!empty($name) && !empty($song)) {
        // Using prepared statements to prevent SQL injection
        $query = "INSERT INTO info (name, song, date) VALUES (?, ?, NOW())";
        $stmt = mysqli_prepare($con, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 'ss', $name, $song);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                echo "Database connection is working"; // Add a message to check if the database connection is working
                header("Location: songspage.php");
                exit;
            } else {
                echo "Database connection error: " . mysqli_error($con); // Display the error message if there's an issue
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "Statement preparation error: " . mysqli_error($con);
        }
    } else if ($name == "") {
        $errors[] = "Name field missing";
    } else if ($song == "") {
        $errors[] = "Song field missing";
    } else {
        $errors[] = "Enter some valid info";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Song of the Day</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Caprasimo&family=Nabla&family=Pixelify+Sans&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=3.0">
</head>
<body>
<div class="container">
    <img src="https://i.pinimg.com/originals/2b/61/c4/2b61c4ff39f35d52f8c516f58bacd3bf.gif" alt="" class="lisa">
    <img src="https://media0.giphy.com/media/4oMoIbIQrvCjm/giphy.gif" alt="" class="bart">
    <h1 class="title1">Song of the Day</h1>
    <div class="nav">
        <a href="index.php">Home</a>
        <a href="songspage.php">Songs</a>
    </div>

    <?php
    // Display errors, if any
    if (!empty($errors)) {
        echo '<div style="color: red;">';
        foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
        }
        echo '</div>';
    }
    ?>

    <div >
        <form class="form" action="" method="post">
            <div><h2 class="title2">What's your song of the day?</h2></div>
            <input type="text" name="name" class="input" placeholder="Your Name">
            <input type="text" name="song" class="input" placeholder="Name of Song">
            <button class="btn cube cube-hover" type="submit">
                    <div class="bg-top">
                        <div class="bg-inner"></div>
                    </div>
                    <div class="bg-right">
                        <div class="bg-inner"></div>
                    </div>
                    <div class="bg">
                        <div class="bg-inner"></div>
                    </div>
                    <div class="text">Add Song</div>
           </button>        </form>
    </div>
    <a href="songspage.php" class="view">View More Songs</a>
    <div class="cards">

    <?php
    $query = "SELECT name, song, date FROM info ORDER BY date DESC LIMIT 3";
    $result = mysqli_query($con, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $rowDate = $row['date']; // Assign $row['date'] for each iteration

        echo '<div class="stack" >';
        echo '<div class="card random-color">';
        echo '<div class="image">';
        $imageUrl = fetchSongImage($row['song']);
        echo '<img src="' . $imageUrl . '" alt="Song Image" class="song-image">';
        echo '<h1 class="title"><strong>Song:</strong> ' . $row['song'] . '</h1>';
        echo '<p class="title"><strong>Song added by:</strong> ' . $row['name'] . '</p>';
        echo '<p class="title"><strong>Date Added:</strong> ' . date('F j, Y g:i a', strtotime($rowDate)) . '</p>';
        echo '<h3 class="title"><strong>Link to song:</strong> <a href="https://open.spotify.com/search/' . urlencode($row['song']) . '" target="_blank">' . "here" . '</a></h3>';

        // Call function to fetch image and display it
        
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    // Function to fetch song image
   // Function to fetch song image
function fetchSongImage($songName) {
    // Your Spotify API client ID and client secret
    $clientId = 'fe06704a7b964fe9aea22b83f3655c61';
    $clientSecret = '4de8a22d366a4cc9880cec6de6a955a4';

    // Construct the URL to obtain an access token
    $tokenUrl = 'https://accounts.spotify.com/api/token';
    $tokenData = array(
        'grant_type' => 'client_credentials'
    );

    // Encode client ID and client secret for authentication
    $authHeader = base64_encode($clientId . ':' . $clientSecret);

    // Make a POST request to obtain the access token
    $tokenOptions = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Authorization: Basic $authHeader\r\n" .
                        "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($tokenData)
        )
    );

    $tokenContext = stream_context_create($tokenOptions);
    $tokenResponse = file_get_contents($tokenUrl, false, $tokenContext);
    $tokenData = json_decode($tokenResponse, true);

    // Extract access token from response
    $accessToken = isset($tokenData['access_token']) ? $tokenData['access_token'] : '';

    // Construct API URL to search for the song
    $searchUrl = 'https://api.spotify.com/v1/search?q=' . urlencode($songName) . '&type=track&limit=1';

    // Make request to Spotify API with access token in the header
    $searchOptions = array(
        'http' => array(
            'method' => 'GET',
            'header' => "Authorization: Bearer $accessToken\r\n"
        )
    );

    $searchContext = stream_context_create($searchOptions);
    $searchResponse = file_get_contents($searchUrl, false, $searchContext);
    $searchData = json_decode($searchResponse, true);

    // Extract image URL from API response
    $imageUrl = isset($searchData['tracks']['items'][0]['album']['images'][0]['url']) ? $searchData['tracks']['items'][0]['album']['images'][0]['url'] : 'placeholder.png';
    return $imageUrl;
}

    ?>
    </div>
</div>
<script src="script.js"></script>
</body>
</html>
