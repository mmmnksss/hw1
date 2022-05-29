<?php
require_once 'redirect.php';
if (!$username = checkAuth()) {
    header('Location: landing.php');
    exit;
}

// Post insertion
if (!empty($_POST['title']) && !empty($_POST['story'])) {
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['pass'], $dbconfig['name']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $story = mysqli_real_escape_string($conn, $_POST['story']);
    $tenorURL = mysqli_real_escape_string($conn, $_POST['tenorURL']);

    $id = $_SESSION['id'];

    $query = "INSERT INTO posts(author,title,cap,gif) 
    VALUES ('$id',\"$title\",\"$story\",\"$tenorURL\")";
    if (mysqli_query($conn, $query)) $success = true;
    else $success = false;
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="./style/nav.css">
    <link rel="stylesheet" href="./style/create.css">
    <!-- <link rel="stylesheet" href="./style/landing.css"> -->
    <script src="./scripts/create.js" defer></script>
    <title>Create a post</title>
</head>

<body>
    <?php require_once 'navbuilder.php' ?>

    <main>
        <h2 id="create_start">First off, choose a nice GIF (or don't, it's up to you)</h2>
        <h3 id="create_h3">Leave the box empty to get trending GIFs</h3>
        <form id="tenor_search">
            <input id="tenor_fieldbox" type="text" placeholder="Search for a GIF on Tenor">
            <input id="tenor_button" type="submit" value="I'm feeling lucky">
            <div id="found_gifs">
            </div>
            <div id="confirm_box" class="success hidden">The GIF you last clicked has been selected.</div>
        </form>
        <h2 id="create_start">And now, create your post</h2>
        <form name="postForm" id="postForm" method="post">
            <div id="postName">
                <label name="title">Choose an awesome title</label>
                <input type="text" id="title" name="title" placeholder="This is a test" required>
            </div>
            <div id="postStory">
                <label name="story">Add a wonderful story</label>
                <textarea id="story" name="story" placeholder="Lorem ipsum dolor sit amet" rows="10" cols="130" required></textarea>
            </div>
            <input type="hidden" id="tenorURL" name="tenorURL" value="">
            <input type="submit" value="Upload your post">

            <?php
            if (isset($success)) echo '<div class="post_success">Your post has been published!</div>';
            else if ($success = false) echo "<div class='post_error'>Sorry, your post couldn't go through. Please try again.</div>";
            ?>
        </form>


        <form action="">

        </form>
    </main>
</body>

</html>