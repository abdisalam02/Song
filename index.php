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
    <script>
        function getRandomHexCode() {
    // Define hex code ranges for purple, pink, blue, and white gradients
    const gradients = {
        purple: ['#800080', '#9400D3', '#9932CC'],
        pink: ['#FF69B4', '#FF1493', '#C71585'],
        blue: ['#0000FF', '#4169E1', '#6495ED'],
        white: ['#FFFFFF', '#F8F8FF', '#F5F5F5']
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
    <title>Song of the Day</title>
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
            <!-- <img class="gif" src="https://img1.picmix.com/output/stamp/normal/5/2/2/3/1563225_5e4f1.gif" alt=""> -->
            <div><h2 class="title2">What's your song of the day?</h2></div>
            <input type="text" name="name" class="input" placeholder="Your Name">
            <input type="text" name="song" class="input" placeholder="Name of Song">
            <!-- <input type="submit" value="Add Song" class="submit"> -->
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
           </button>
        </form>
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
    // echo '<h1>Songs Yall have added</h1>';
    echo '<img src="https://png.pngtree.com/element_our/png_detail/20181013/music-icon-design-vector-png_133746.jpg" alt="" class="">';
    echo '<h1 class="title"><strong>Song:</strong> ' . $row['song'] . '</h1>';
    echo '<p class="title"><strong>Song added by:</strong> ' . $row['name'] . '</p>';
    echo '<p class="title"><strong>Date Added:</strong> ' . date('F j, Y g:i a', strtotime($rowDate)) . '</p>';
    echo '<h3 class="title"><strong>Link to song:</strong> <a href="https://open.spotify.com/search/' . urlencode($row['song']) . '" target="_blank">'  . "here". '</a></h3>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}
?>
</div>
</div>
<script src="script.js"></script>
</body>
</html>