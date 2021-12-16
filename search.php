<?php 
    include("sources/header.php");
?>


<form action="search.php" method="POST" class="form">
    <div class="center-align" style="margin-top:10px;">
        <input id="search-box" type="text" name="searchKeywords" oninput="verify()" placeholder="Search..." autofocus>
        <input id="search-button" class="no-border" type="submit" name="submit" value="Search" disabled>
    </div>
</form>
<p id="error-text" style="color:red;text-align:center;"></p>

<div style="margin: 10px;">
    <?php
    $message = search(); 
    ?>
</div>

<script>
    document.getElementById("error-text").innerHTML="<?php global $message; echo $message; ?>";
    function verify()
    {

        if(document.getElementById("search-box").value!="")
        {
            document.getElementById("search-button").disabled = false;
        }
        else{
            document.getElementById("search-button").disabled = true;
        }
    }
</script>
<?php 
    include("sources/footer.php");
?>