<?php 
    include("sources/header.php");
?>

<?php 

if(!isset($_GET['playlist']) && !isset($_GET['song']) && !isset($_GET['artist']) && !isset($_GET['album']))
{
    set_message("Please select a component to display!");
    header("Location:index.php");
}
else
{
    if(isset($_GET['song']))
    {
        get_song($_GET['song']);
    }
    elseif(isset($_GET['artist'])) {
        get_artist($_GET['artist']);
    }
    elseif(isset($_GET['album'])){
        get_album($_GET['album']);
    }
    elseif(isset($_GET['playlist']))
    {
        get_playlist($_GET['playlist']);
    }
}



?>





<?php 
    include("sources/footer.php");
?>