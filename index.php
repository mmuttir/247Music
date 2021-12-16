<?php 
    include("sources/header.php");
?>

<img src="sources/back.jpg" alt="back.jpg" width="100%" height="450px">

<h1 style="text-align:center;">24/7 Music</h1>
<button class="center-align smbutton no-border" onclick="window.location.href='search.php'">Search Music</button>
<p style="text-align:center;">OR SEE</p>
<div class="center-align inline">
    <button class="smbutton inbutton no-border" onclick="window.location.href='songs.php'" style="background-color: #049bff;">Songs</button>
    <button class="smbutton inbutton no-border" onclick="window.location.href='artists.php'" style="background-color: #ff9b04;">Artist</button>
    <button class="smbutton inbutton no-border" onclick="window.location.href='albums.php'" style="background-color: #ff1d04;">Albums</button>
</div>

<?php 
    include("sources/footer.php");
?>