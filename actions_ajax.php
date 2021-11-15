<?php
//to validate if any operation is SELECTed from choice. if no operation SELECTed return.
if ( $_GET['action'] == ''){
    	return;
}
//Session started for each user login and user ID is extracted to provide user specific functionalities.
session_start();
$userID = $_SESSION['userID'];
if(! isset ($_SESSION['userID'])) {
        header("Location:index.php");    
}    
//extract the action in the XMLHTTP request sent.
$action = $_GET['action'];
//funtion to DELETE song from the backend using sql DELETE query
if (! strcmp($action, "DELETEsong") )
{
    $conn = ConnectDB();
    $SongID = mysqli_real_escape_string ($conn, $_GET['SongID']);
    $query = "DELETE from playlist where SongID = {$SongID}";
    mysqli_query($conn, $query);
    $query = "DELETE from song where SongID = {$SongID}";
    //echo($query);
    mysqli_query($conn, $query);
   
} 
//funtion to add song to likes table using sql INSERT query
else if (! strcmp($action, "likesong")){
   
    $conn = ConnectDB();
    $SongID = mysqli_real_escape_string($conn,$_GET['SongID']);
    //validate if such entry already exists
    $query="SELECT * from likes where SongID={$SongID} AND userID={$userID};";
    $result = mysqli_query($conn, $query);
		if (mysqli_num_rows($result) > 0)
		{
			$query="DELETE from likes where SongID={$SongID} AND userID={$userID};";
			mysqli_query($conn, $query);
			echo "duplicate";
			return;
		}
    $query= "INSERT into likes (userID,SongID) values  ({$userID},{$SongID});";
    mysqli_query($conn, $query);
}
//funtion to add song to history table using sql INSERT query
else if (! strcmp($action, "playsong")){
   
    $conn = ConnectDB();
    $SongID = mysqli_real_escape_string($conn,$_GET['SongID']);
	$query = "DELETE FROM history WHERE SongID = {$SongID} AND userID = {$userID};";
	mysqli_query($conn, $query);
    $query= "INSERT into history (userID,SongID,played_time) values  ({$userID},{$SongID},NOW());";    
    mysqli_query($conn, $query);
    
}
//funtion to UPDATE song to song table using sql UPDATE query
else if (! strcmp($action, "UPDATEsong"))
{   
	$conn = ConnectDB();
 	$SongID = $_GET['SongID'];
	$Title =  $_GET['Title'];
	$Album =  $_GET['Album'];
	$Artist =  $_GET['Artist'];
	$Composer =  $_GET['Composer'];
    $Genre =  $_GET['Genre'];
	$query = "SELECT SongID from song where Title={$Title} and Album={$Album} and Artist={$Artist} and Composer={$Composer} and Genre={$Genre}";
	$result = mysqli_query($conn, $query);
	if (mysqli_num_rows($result) > 1)
	{
		echo "duplicate";
		return;
	}
	$myrow = mysqli_fetch_array($result, MYSQLI_ASSOC);
    //echo $myrow;
	if (mysqli_num_rows($result) > 0 && $myrow["SongID"] != $SongID)
	{
		echo "duplicate";
		return;
	}
	$query = "UPDATE song SET Title={$Title}, Album={$Album}, Artist={$Artist}, Composer={$Composer},Genre={$Genre} where SongID={$SongID}";
   
    //echo $query;
	mysqli_query($conn, $query);
}
//funtion to fetch playlists from playlist_info table using sql SELECT query
else if (! strcmp($action, "getplaylists"))
	{
		$conn = ConnectDB();
		$SongID = $_GET['SongID'];
		$query = "SELECT * FROM playlist_info where userID = {$userID}";
        //echo $query;
		$result = mysqli_query($conn, $query);
        
		echo tablePlaylists($result, $SongID);
	}
//funtion to add song to playlist table using sql INSERT query
else if (! strcmp($action, "addtolist"))
	{
		$conn = ConnectDB();
		$SongID = $_GET['SongID'];
		$pl_id = $_GET['pl_id'];
		$query = "SELECT * FROM playlist where SongID={$SongID} and playlist_ID={$pl_id}";
		$result = mysqli_query($conn, $query);
        //validate if duplicate entry exists
		if (mysqli_num_rows($result) > 0)
		{
			echo "duplicate";
			return;
		}
		$query = "INSERT into PLAYLIST (SongID,playlist_ID) values  ({$SongID}, {$pl_id});";
		if (!mysqli_query($conn, $query))
        {
            echo "Error: " . mysqli_error($conn);
        }
	}
//funtion to add song to new playlist by creating entry in playlist_info and playlist table
else if (! strcmp($action, "addtonewlist") )
	{
		$conn = ConnectDB();
		$SongID = $_GET['SongID'];
		$pl_name = $_GET['pl_name'];
		$query = "SELECT * from playlist_info where playlist_name = '{$pl_name}' and userID = {$userID}";
		//echo $query;
		$result = mysqli_query($conn, $query);

		if (mysqli_num_rows($result) > 0)
		{
			echo "duplicate";
			return;
		}
		$query = "INSERT into playlist_info (playlist_name, userID) values ('{$pl_name}', {$userID})";
		mysqli_query($conn, $query);
		$query = "SELECT playlist_ID from playlist_info where playlist_name = '{$pl_name}' and userID = {$userID}";
		$result = mysqli_query($conn,$query);
		$row = mysqli_fetch_row($result);
		$plId = $row[0];
		$query = "INSERT into PLAYLIST (SongID , playlist_ID) values  ({$SongID}, {$plId});";
		mysqli_query($conn, $query);
	}
//funtion to open a playlist and view all songs in that playlist
else if (! strcmp($action, "openplaylist"))
	{
		$conn = ConnectDB();
		$pl_id = $_GET['pl_id'];
		$query = "SELECT playlist_name from playlist_info where playlist_ID={$pl_id}";
        //echo ($query);
		$result = mysqli_query($conn, $query);
        //echo mysqli_num_rows($result);
		$myrow = mysqli_fetch_array($result, MYSQLI_NUM);
		//echo $myrow[0];

		$query = "SELECT * from song where SongID in (SELECT SongID from playlist where playlist_ID = {$pl_id})";
        //echo($query);
		$result = mysqli_query($conn, $query);
		echo tableSongs($result, $pl_id, $myrow[0]); // last field is the name!	
	}
//function to delte song from playlist
else if (! strcmp($action, "DELETEFromPL"))
	{   
        //echo ("DELETE from pl");
		$conn = ConnectDB();
		$pl_id = $_GET['pl_id'];
		$song_id = $_GET['SongID'];
		$query = "DELETE from playlist where SongID={$song_id} and playlist_ID={$pl_id}";
        //echo ($query);
		if (!mysqli_query($conn, $query))
        {
            $error = "1". mysqli_error($conn);
        }
	}
//function to DELETE a playlist 
else if (! strcmp($action, "deletepl"))
	{
		$conn = ConnectDB();
		$pl_id = $_GET['pl_id'];
		$query = "DELETE from playlist where playlist_ID = {$pl_id}";
		mysqli_query($conn, $query);
		$query = "DELETE from playlist_info where playlist_ID = {$pl_id}";
		mysqli_query($conn, $query);
	}
//function to establish connection with db and choose database 
function ConnectDB()
{
    $conn = mysqli_connect("localhost:3306", "root", "", "dbproject");   
    //alert($conn);
    if (!$conn) 
    {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
	//$conn = mysqli_connect("localhost", "root", "") or die("Cannot Connect to DB");
	//mysqli_SELECT_db("vc_playlist",$db);
	return $conn;
}
//function to display songs inside a playlist in a table format
function tableSongs($result, $pl_id, $pl_name)
{
    $str = "<h1>{$pl_name}</h1><TABLE style='opacity: 0.65; overflow:auto;' class='table table-dark table-hover' id='playlist_ed' name='playlist_ed' >";
    $str .= "<tr><th><b>#</b></th><th><b>Title</b></th><th><b>Album</b></th><th><b>Artist</b></th><th><b>Composer</b></th><th><b>Genre</b></th><th><b>Action</b></th></tr>";

    $row_count = 1;
    while ($myrow = mysqli_fetch_array($result))
    {
        $str .= "<TR><TD>";
        $str .= (string)$row_count;
        $str .= "</td><td>";
        $str .= $myrow["Title"];
        $str .= "</td><td>";
        $str .= $myrow["Album"];
        $str .= "</td><td>";
        $str .= $myrow["Artist"];
        $str .= "</td><TD>";
        $str .= $myrow["Composer"];
        $str .= "</td><TD>";
        $str .= $myrow["Genre"];
        $str .= "</td>";
        // $str .= "<td><table><tr><td><audio class='audio' src='".$myrow['Filepath']."'></audio><a class='play-pause' title='play/pause' type='button' onclick='PlaySong(".$myrow['SongID'] ." )'><i class='fa fa-play'></i></a></td>";
        $str .= "<td><table><tr><td><a type='button' title ='Like Song' onclick='LikeSong(".$myrow['SongID'] . " )'><i class='far fa-heart'>&nbsp;</i></a>&nbsp;</td>";
        $str .= "<td><a title='DELETE from Playlist' type='button' onclick='DELETEFromPl(".$myrow['SongID'].",".$pl_id.",".$row_count.");' ><i class='fas fa-trash'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";
        //$str .= "<a href='javascript:DELETEFromPl(".$myrow["SongID"].",".$pl_id.",".$row_count.");'>DELETEFromPl</a>";
        $str .= "</tr>";

        $row_count++;
    }

    $str .= "</TABLE>";
    if ($row_count > 1)
        return $str;
    else
        return "<img src='https://caterhub-cdn.s3-us-west-1.amazonaws.com/assets/no_listings.png' alt='no listing avilable' width='200px' height='200px'><h4>OOPS!!! NO SONGS HERE!!!</h4>";
}
//function to display songs inside a playlist in a table format
function tablePlaylists($result, $SongID)
{
    $str_create = "<h1>Playlist</h1><br><br><br>";
    $str_create .= "<input id='newpl_name' name='newpl_name' style='border-radius:8px;border:0;margin-left:15px;' placeholder=' Enter playlist name' type='text'></input>";
    $str_create .= "<button class='btn btn-outline-info' type='button' style='display:inline;color:black;margin-left:10px;' onClick='CreateNewPlaylist({$SongID});'>Create</button><br><br><br>"; 
    $str = "<TABLE style='opacity: 0.65;overflow:auto;' class='table table-dark table-hover' id='playlist_ed' name='playlist_ed' >";
    $str .= "<tr><th><b>#</b></th><th><b>Name</b></th><th><b>Action</b></th></tr>";

    $row_count = 1;
    while ($myrow = mysqli_fetch_array($result))
    {
        $str .= "<TR><TD>";
        $str .= (string)$row_count;
        $str .= "</td><td>";
        $str .= $myrow["playlist_name"];

        $str .= "</td><TD>";
        $str .= "<a href='javascript:AddToPlaylist (".$SongID.",".$myrow["playlist_ID"].");'>Add here</a>";
        $str .= "</td></tr>";

        $row_count++;
    }
    $str .= "</TABLE>";
    if ($row_count > 1)
        return $str_create . $str;
    else
        return $str_create . "<img src='https://caterhub-cdn.s3-us-west-1.amazonaws.com/assets/no_listings.png' alt='no listing avilable' width='200px' height='200px'><h4>OOPS!!! NO SONGS HERE!!!</h4>";
}
?>