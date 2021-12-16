<?php require_once("sources/functions.php") ?>

<!DOCTYPE html>
<html>
<head>
    <title>24/7Music</title>
    <link rel="stylesheet" href="sources/styles.css">
</head>
<body>
        <ul class="navigation-bar">
            <li class="organization" id="indexli"><a href="index.php">24/7</a></li>
            <li id="searchli"><a href="search.php">Search</a></li>
            <li id="songsli"><a href="songs.php">Songs</a></li>
            <li id="artistsli"><a href="artists.php">Artists</a></li>
            <li id="albumsli"><a href="albums.php">Albums</a></li>
            <?php display_user(); ?>
        </ul>
        <p class="error-display" style="text-align:center;"><?php display_message(); ?></p>
