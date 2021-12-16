<?php 
    include("sources/header.php");
    check_user();
    add_song();
    $message = playlist_input();
?>
<table class="center-align">
    <tr>
        <th>Member Username</th>
        <th>Membership Category</th>
    </tr>
    <tr>
        <td><?php echo $_SESSION['username']; ?></td>
        <td><?php echo $_SESSION['member-category']; ?></td>
    </tr>
</table>

<form action="playlist.php" method="POST">
    <div class="center-align">
        <input id="playlist-input-box" type="text" name="playlist-name" placeholder="Playlist name..." oninput="verify()">
        <input id="playlist-button" class="no-border" type="submit" name="submit" value="Create Playlist" disabled>
        <p style="color:red;text-align:center;"><?php global $message; echo $message; ?></p>
    </div>
</form>

<?php display_playlists(); ?>

<script>
function verify()
    {

        if(document.getElementById("playlist-input-box").value!="")
        {
            document.getElementById("playlist-button").disabled = false;
        }
        else{
            document.getElementById("playlist-button").disabled = true;
        }
    }
</script>


<?php 
    include("sources/footer.php");
?>