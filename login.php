<?php 
    include("sources/header.php");
    $message = login();
?>
<form action="login.php" method="POST">
    <input class="username-box center-align" type="text" name="username" placeholder="Username" autofocus>
    <input class="password-box center-align" type="password" name="password" placeholder="Password">
    <input class="center-align smbutton no-border" type="submit" name="submit" value="Login">
    <p style="color: red; text-align: center;"><?php global $message; echo $message; ?></p>
</form>


<?php 
    include("sources/footer.php");
?>