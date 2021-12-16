<?php 
session_start();
    require_once("connection.php");
    function query($string)
    {
        global $dbConn;
        return mysqli_query($dbConn,$string);
    }

    function set_message($string)
    {
        $_SESSION['error-message'] = $string;
    }

    function display_message()
    {
        if(isset($_SESSION['error-message']))
        {   
            echo $_SESSION['error-message'];
            unset($_SESSION['error-message']);
        }
    }

    function escape_string($string)
    {
        global $dbConn;
        return mysqli_real_escape_string($dbConn,$string);
    }

    function confirm($result)
    {
        if(!$result)
        {
            global $dbConn;
            die("Query Failed" . mysqli_error($dbConn));
        }
    }


    function search_artists($search_keywords)
    {
        $query = query("SELECT * FROM artist WHERE artist_name LIKE '%{$search_keywords}%'");
        confirm($query);
        if(mysqli_num_rows($query)!=0)
        {
            echo "<h3>Artists</h3><hr>";
            while ($row = mysqli_fetch_array($query))
            {
                    
                $result=<<<DELIMETER
                <a href="play.php?artist={$row['artist_id']}" style="text-decoration: none; color: black;">
                    <div class="results-block">
                        <img src="twa/thumbs/artist/{$row['thumbnail']}" alt="{$row['artist_name']} thumbnail" width="101" height="101" class="thumbnails">
                        <h4 class="results-headings">{$row['artist_name']}</h4>
                    </div>
                </a>
DELIMETER;
                echo $result;
            }
            return TRUE;
        }
        else
            return FALSE;
    }

    function search_albums($search_keywords)
    {
        $query = query("SELECT * FROM album WHERE album_name LIKE '%{$search_keywords}%'");
        confirm($query);
        if(mysqli_num_rows($query)!=0)
        {
            echo "<h3>Albums</h3><hr>";
            while ($row = mysqli_fetch_array($query))
            {
                $query1 = query("SELECT * FROM artist WHERE artist_id = {$row['artist_id']}");
                $row1 = mysqli_fetch_array($query1);
                $result=<<<DELIMETER
                <a href="play.php?album={$row['album_id']}" style="text-decoration: none; color: black;">
                    <div class="results-block">
                        <img src="twa/thumbs/album/{$row['thumbnail']}" alt="{$row['album_name']} thumbnail" width="101" height="101" class="thumbnails">
                        <h4 class="results-headings">{$row['album_name']}</h4>
                        <h4 class="results-headings">~<i>by {$row1['artist_name']}</i></h4>
                    </div>
                </a>
DELIMETER;
                echo $result;
            }
            return TRUE;
        }
        else
            return FALSE;
    }

    function search_songs($search_keywords)
    {
        $query = query("SELECT * FROM track WHERE track_title LIKE '%{$search_keywords}%'");
        confirm($query);
        if(mysqli_num_rows($query)!=0)
        {
            echo "<h3>Songs</h3><hr>";
            while ($row = mysqli_fetch_array($query))
            {
                $query1 = query("SELECT * FROM artist WHERE artist_id = {$row['artist_id']}");
                $row1 = mysqli_fetch_array($query1);
                $query2 = query("SELECT * FROM album WHERE album_id = {$row['album_id']}");
                $row2 = mysqli_fetch_array($query2);
                
                $result=<<<DELIMETER
                <a href="play.php?song={$row['track_id']}" style="text-decoration: none; color: black;">
                    <div class="results-block">
                        <img src="twa/thumbs/track/track.png" alt="{$row['track_title']} thumbnail" width="101" height="101" class="thumbnails">
                        <h4 class="results-headings">{$row['track_title']}</h4>
                        <h4 class="results-headings">~<i>by {$row1['artist_name']}</i></h4>
                        <h4 class="results-headings" style="margin-left:0;"><i> from {$row2['album_name']}</i></h4>
                        <br>
                        <h4 class="results-headings">Duration: {$row['track_length']}</h4>
                    </div>
                </a>
DELIMETER;
                echo $result;
            }
            return TRUE;
        }
        else
            return FALSE;
    }

    function search()
    {
        $message = "";
        $count = 0;
        if(isset($_POST['submit']))
        {
            if(isset($_POST['searchKeywords']))
            {
                $search_keywords = escape_string($_POST['searchKeywords']);
                //searching Artists
                if(!search_artists($search_keywords))
                    $count++;
                //Searching Albums
                if(!search_albums($search_keywords))
                    $count++;
                //Searching Songs
                if(!search_songs($search_keywords))
                    $count++;
            }
        }
        if($count == 3)
        {
            $message = "No results found!";
        }
        return $message;
    }

    function get_song($id)
    {
        $query = query("SELECT * FROM track WHERE track_id = {$id}");
        confirm($query);
        if(mysqli_num_rows($query)!=0)
        {
            $row = mysqli_fetch_array($query);
            $query1 = query("SELECT * FROM artist WHERE artist_id = {$row['artist_id']}");
            $row1 = mysqli_fetch_array($query1);
            $query2 = query("SELECT * FROM album WHERE album_id = {$row['album_id']}");
            $row2 = mysqli_fetch_array($query2);
            $result=<<<DELIMETER
            <div class="center-align">
            <iframe src="https://open.spotify.com/embed/track/{$row['spotify_track']}" width="300" height="380" frameborder="0" allowtransparency="true" allow="encryptedmedia"></iframe>
            </div>
            <h3 style="text-align:center;">Song by {$row1['artist_name']} from Album {$row2['album_name']}</h3>
            <div class="center-align">
                <form action="playlist.php" method="post">
                    <input type="hidden" value={$id} name="track">
                    <select name="playlist">
DELIMETER;
            echo $result;
            $query3 = query("SELECT * FROM memberPlaylist WHERE member_id={$_SESSION['member-id']}");
            while($row3 = mysqli_fetch_array($query3))
            {
                echo "<option value={$row3['playlist_id']}>{$row3['playlist_name']}</option>";
            }
            $result=<<<DELIMETER
                    </select>
                    <input type="submit" name="add" value="Add to Playlist">
                </form>
            </div>
DELIMETER;
            echo $result;
        }
        else
        {
            set_message("Track not found!");
            header("Location:index.php");
        }
    }


    function get_artist($id)
    {
        $row = mysqli_fetch_array(query("SELECT * FROM artist WHERE artist_id = {$id}"));
        $head = <<<DELIMETER
        <div style="margin: 10px;">
        <img src="twa/thumbs/artist/{$row['thumbnail']}" alt="{$row['artist_name']} thumbnail" width='101' height='101' class='thumbnails'>
        <h3 class='play-header-heading'>{$row['artist_name']}</h3>
        <hr>
    </div>
DELIMETER;
        echo $head;
        $query = query("SELECT * FROM album WHERE artist_id = {$id}");
        confirm($query);
        if(mysqli_num_rows($query)!=0)
        {
            while ($row = mysqli_fetch_array($query))
            {
                $result=<<<DELIMETER
                <a href="play.php?album={$row['album_id']}" style="text-decoration: none; color: black;">
                    <div class="results-block">
                        <img src="twa/thumbs/album/{$row['thumbnail']}" alt="{$row['album_name']} thumbnail" width="101" height="101" class="thumbnails">
                        <h4 class="results-headings">{$row['album_name']}</h4>
                        <br>
                        <h4 class="results-headings"><i>Released on: {$row['album_date']}</i></h4>
                    </div>
                </a>
DELIMETER;
                echo $result;
            }
        }
        else
        {
            set_message("Artist not found!");
            header("Location:index.php");
        }

    }


    function get_album($id)
    {
        $row = mysqli_fetch_array(query("SELECT * FROM album WHERE album_id = {$id}"));
        $row1 = mysqli_fetch_array(query("SELECT * FROM artist WHERE artist_id = {$row['artist_id']}"));
        $head = <<<DELIMETER
        <div style="margin: 10px;">
        <img src="twa/thumbs/album/{$row['thumbnail']}" alt="{$row['album_name']} thumbnail" width='101' height='101' class='thumbnails'>
        <h3 class='play-header-heading'>{$row['album_name']} ~<i>by {$row1['artist_name']}</i></h3>
        <hr>
    </div>
DELIMETER;
        echo $head;
        $query = query("SELECT * FROM track WHERE album_id = {$id}");
        confirm($query);
        if(mysqli_num_rows($query)!=0)
        {
            while ($row = mysqli_fetch_array($query))
            {
                $result=<<<DELIMETER
                <a href="play.php?song={$row['track_id']}" style="text-decoration: none; color: black;">
                    <div class="results-block">
                    <img src="twa/thumbs/track/track.png" alt="{$row['track_title']} thumbnail" width="101" height="101" class="thumbnails">
                        <h4 class="results-headings" style="height:fit-content;">{$row['track_title']}</h4>
                    </div>
                </a>
DELIMETER;
                echo $result;
            }
        }
        else
        {
            set_message("Artist not found!");
            header("Location:index.php");
        }
    }

    function get_playlist($id)
    {
        $query = query("SELECT * FROM playlist WHERE playlist_id = {$id}");
        confirm($query);
        if(mysqli_num_rows($query)!=0)
        {
            $query4 = query("SELECT * FROM memberplaylist WHERE playlist_id = {$id}");
            $row4 = mysqli_fetch_array($query4);
            echo "<h3 style='text-align:center;'>{$row4['playlist_name']}</h3><hr>";

            while ($row = mysqli_fetch_array($query))
            {
                $query3 = query("SELECT * FROM track WHERE track_id = {$row['track_id']}");
                $row3 = mysqli_fetch_array($query3);
                $query1 = query("SELECT * FROM artist WHERE artist_id = {$row3['artist_id']}");
                $row1 = mysqli_fetch_array($query1);
                $query2 = query("SELECT * FROM album WHERE album_id = {$row3['album_id']}");
                $row2 = mysqli_fetch_array($query2);
                $result=<<<DELIMETER
                <a href="play.php?song={$row3['track_id']}" style="text-decoration: none; color: black;">
                    <div class="results-block">
                        <img src="twa/thumbs/track/track.png" alt="{$row3['track_title']} thumbnail" width="101" height="101" class="thumbnails">
                        <h4 class="results-headings">{$row3['track_title']}</h4>
                        <h4 class="results-headings">~<i>by {$row1['artist_name']}</i></h4>
                        <h4 class="results-headings" style="margin-left:0;"><i> from {$row2['album_name']}</i></h4>
                        <br>
                        <h4 class="results-headings">Duration: {$row3['track_length']}</h4>
                    </div>
                </a>
DELIMETER;
                echo $result;
            }
        }
        else
        {
            set_message("Empty or Invalide Playlist!");
            header("Location:index.php");
        }
    }


    function check_user()
    {
        if (!isset($_SESSION['username'])) {
            set_message("User not logged in!");
            header("Location: index.php");
        }
    }

    function login()
    {
        $message = "";
        if(isset($_SESSION['username']))
        {
            set_message($_SESSION['username'] . " already logged in!");
            header('Location:index.php');
        }
        if(isset($_POST['submit']))
        {
            if (isset($_POST['username'])&& isset($_POST['password'])) {
                $username = escape_string($_POST['username']);
                $password = escape_string($_POST['password']);
                $password_conv = hash("sha256",$password);
                $query = query("SELECT * FROM membership WHERE username='{$username}' AND password = '{$password_conv}'");
                if(mysqli_num_rows($query)==0)
                {
                    $message = "Username or Password is incorrect";
                }
                else
                {
                    $row = mysqli_fetch_array($query);
                    $_SESSION['username'] = $username;
                    $_SESSION['name'] = $row['firstname'] ." ". $row['surname'];
                    $_SESSION['member-category'] = $row['category'];
                    $_SESSION['member-id'] = $row['member_id'];
                    set_message("Logged In");
                    header('Location:index.php');
                }
            }
        }
        return $message;
    }

    function playlist_input(){
        if(isset($_POST['submit']))
        {
            if (isset($_POST['playlist-name'])) {
                $playlist_name = escape_string($_POST['playlist-name']);
                $query = query("INSERT INTO memberplaylist (member_id,playlist_name) VALUES ({$_SESSION['member-id']},'{$playlist_name}')");
                confirm($query);
                return "Playlist added";
            }
        }
    }


    function display_playlists(){
        $query = query("SELECT * FROM memberplaylist WHERE member_id = {$_SESSION['member-id']}");
        confirm($query);
        while($row = mysqli_fetch_array($query))
        {
            echo "<a href='play.php?playlist={$row['playlist_id']}' style='text-decoration: none; color: black;'><div class='results-block' style='height:initial;padding-left:15px;'>
                <h3>{$row['playlist_name']}</h3>
            </div></a>";
        }
    }

    function display_user()
    {
        if(!isset($_SESSION['username']))
        {
            $disp =<<<DELIMETER
            <li style="float: right;" id="loginli"><a href="login.php">Login</a></li>
DELIMETER;
        }
        else
        {
            $disp =<<<DELIMETER
            <li><a href="playlist.php" id="playlistli">Playlist</a></li>
            <li style="float: right;"><a href="logout.php">Logout</a></li>
            <li style="float: right;pointer-events:none;"><a>{$_SESSION['username']}</a></li>
DELIMETER;
        }
        echo $disp;
    }
    
    function add_song()
    {
        if(isset($_POST['add']))
        {
            $query = query("SELECT * FROM playlist WHERE playlist_id = {$_POST['playlist']} AND track_id = {$_POST['track']}");
            if(mysqli_num_rows($query)==0)
            {
                $id = $_POST['add'];
                $query = query("INSERT INTO playlist (playlist_id,track_id) VALUES ({$_POST['playlist']},{$_POST['track']})");
            }
            else
            {
                set_message("Song Already in the playlist!");
                header("Location:playlist.php");
            }
        }
    }

?>