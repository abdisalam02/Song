<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment = mysqli_real_escape_string($con, $_POST['comments']);
    $name = mysqli_real_escape_string($con, $_POST['name']);

    // Insert into addinfo table
    $query2 = "INSERT INTO addinfo (name, comments, date) VALUES (?, ?, NOW())";
    $result2 = mysqli_prepare($con, $query2);

    if ($result2) {
        mysqli_stmt_bind_param($result2, 'ss', $name, $comment);
        $result2 = mysqli_stmt_execute($result2);

        if ($result2) {
            
            // Redirect to avoid form resubmission
            header("Location: songspage.php");
            exit;
        } else {
            // Handle the execution error
            echo "Error executing prepared statement: " . mysqli_stmt_error($result2);
        }
        mysqli_stmt_close($result2);
    } else {
        // Handle statement preparation error
        echo "Statement preparation error: " . mysqli_error($con);
    }
}

$query = "SELECT name, song, date FROM info ORDER BY date DESC";
$result = mysqli_query($con, $query);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Bagel+Fat+One&family=Caprasimo&family=Nabla&family=Pixelify+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css?v=2.0">
    <script>
        function getRandomHexCode() {
    // Define hex code ranges for purple, pink, blue, and white gradients
    const gradients = {
        purple: ['#800080', '#9400D3', '#9932CC'],
        pink: ['#FF69B4', '#FF1493', '#C71585'],
        // blue: ['#0000FF', '#4169E1', '#6495ED'],
        // white: ['#FFFFFF', '#F8F8FF', '#F5F5F5']
    };

    // Choose a random gradient
    const gradientKeys = Object.keys(gradients);
    const randomGradientKey = gradientKeys[Math.floor(Math.random() * gradientKeys.length)];
    const randomGradient = gradients[randomGradientKey];

    // Choose a random color from the selected gradient
    return randomGradient[Math.floor(Math.random() * randomGradient.length)];
}


        document.addEventListener('DOMContentLoaded', function() {
            var cards = document.querySelectorAll('.random-color');

            cards.forEach(function(card) {
                // Apply the random color to each card
                card.style.backgroundColor = getRandomHexCode();
            });
        });
    </script>
    <title>Document</title>
</head>
<body>
    <div class="container">
    <!-- <img src="https://i.pinimg.com/originals/59/72/6b/59726bafe21ffdc04f61d22ba827131e.gif" alt="" class="lisa"> -->
    <h1 class="title1">Songs Yall have added</h1>
    <div class="nav">
            <a href="index.php">Home</a>
            <a href="songspage.php">Songs</a>
        </div>
        <!-- <button><a href="index.php" class="submit">Home</a></button> -->
        
    <div class="cards">
    <?php
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

mysqli_close($con);
?>
</div>
<div class="comments form">
<h2 class="title2">Comments</h2>
<h4>Leave a feedback on features youd like, and bugs u run into</h4>
<form action="" method="post"> 
<input type="text" name="name" class="input" placeholder="Your Name">
<!-- <input type="text" name="comments" class="input2" placeholder="Your comments"> -->
<textarea name="comments" class="input2" placeholder="Your comments"></textarea>
<!-- <input type="submit" value="Submit" class="submit"> -->
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
                    <div class="text">Submit</div>
           </button>
</form>

</div>

</div>

</body>
</html>