<?php 
require_once("sources/functions.php");
if(isset($_SESSION['username']))
{
    session_destroy();
    session_start();
    set_message("Logged Out");
}
else
{
    set_message("User already logged out!!");
}
header("Location:index.php");
?>