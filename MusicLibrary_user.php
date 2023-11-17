<?php
    //Session started for each user login and user ID is extracted to provide user specific functionalities.
    session_start();

    if(! isset ($_SESSION['userID'])) {
         header("Location:index.php");    
        }    
?>

<!DOCTYPE html>
<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>@import url('https://fonts.googleapis.com/css2?family=Merienda+One&display=swap');</style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <title>User Login</title>
    <link rel="stylesheet" type="text/css" href="css\LibraryStylesheet.css">
    <link rel="stylesheet" type="text/css" href="bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
    <script>

    function fillResults(){
        document.getElementById('results_Container').style.display = 'block';   
        document.getElementById('results_Container').innerHTML="<?php echo GetResults(); ?>";
    }

    $(document).ready(function() {
		var playing = false;

		$('.play-pause').click(function() {
			$('.play-pause i').removeClass('fa-pause').addClass('fa-play');
			if ($(this).siblings('.audio').get(0).paused) {
      			//pause all audio
      			$('.audio').each(function(){
      				this.pause();
      			});
      			//start the sibbling audio of the current clicked button, the get function gets the dom object instead of the jQuery object
      			$(this).siblings('.audio').get(0).play();
      			$(this).find('.fa').removeClass('fa-play').addClass('fa-pause');

      		} else {
      			$(this).siblings('.audio').get(0).pause();
      			$(this).find('.fs').removeClass('fa-pause').addClass('fa-play');
      		}
      	});
	});

    </script>

    <script>

        //XMLHTTP Ajax request to like song in the row user has chosen
        function LikeSong(SongID){ 
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=likesong&SongID="+SongID;
           // alert(url);
	        xmlhttp.open("GET", url, false);
            //alert(url);
	        xmlhttp.send();
	            if (xmlhttp.responseText == "duplicate")
		        swal({title:"Song removed from liked!",icon:"info"});
	        else
		        swal({title:"Song liked!",icon:"success"});
        }
        
         //XMLHTTP Ajax request to play song in the row user has chosen
        function PlaySong(SongID,Filepath){
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=playsong&SongID="+SongID;
	        xmlhttp.open("GET", url, true);
	        xmlhttp.send();
        }

        //XMLHTTP Ajax request to add song the in the row user has chosen to already existing playlist
        function AddSongtoPl(SongID,rowcount){  
	        document.getElementById("results_table").style.display = 'none';
	        document.getElementById("results_Container").style.display = 'inline';
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=getplaylists&SongID="+SongID;
	        xmlhttp.open("GET", url, false);
	        xmlhttp.send(null);
	        document.getElementById("results_Container").innerHTML = xmlhttp.responseText;	
        }

        //XMLHTTP Ajax request to add song the in the row user has chosen to new playlist
        function AddToPlaylist(SongID, pl_id)
        {
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=addtolist&SongID="+SongID;
	        url = url + "&pl_id="+pl_id;
	        xmlhttp.open("GET", url, false);
	        xmlhttp.send(null);
	        if (xmlhttp.responseText == "duplicate")
		        swal({title:"Song already in this playlist!",icon:"info"});
	        else
		        swal({title:"Song added to the playlist!",icon:"success"});

	        document.getElementById("results_table").style.display = 'block';
	        document.getElementById("results_Container").innerHTML = '' 
        }

        //XMLHTTP Ajax request to create new playlist
        function CreateNewPlaylist(SongID)
        {
	        var name = document.getElementById("newpl_name").value;
	        if (name.length == 0)
	        {
		        swal({title:"Please enter a name!",icon:"warning"});
		        return;
	        }
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=addtonewlist&SongID="+SongID;
	        url = url + "&pl_name="+name;
	        xmlhttp.open("GET", url, false);
	        xmlhttp.send(null);
	        if (xmlhttp.responseText == "duplicate")
            swal({title:"Song already in this playlist!",icon:"info"});
	        else
            swal({title:"Song added to the new playlist!",icon:"success"});
        }

        //XMLHTTP Ajax request to edit playlist
        function EditPlaylist(pl_id)
        {   
	        document.getElementById("pl_results_table").style.display = 'none';
	        document.getElementById("results_Container").style.display = 'block';
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=openplaylist&pl_id="+pl_id;
	
	        xmlhttp.open("GET", url, false);
	        xmlhttp.send();
	        if (xmlhttp.statusCode != 400)
	        {
		        document.getElementById("results_Container").innerHTML = xmlhttp.responseText;	
	        }
	        else
	        {
		        alert("Cannot Open Playlist!");
		        alert(xmlhttp.responseText);
	        }
        }

        //XMLHTTP Ajax request to delete playlist
        function DeletePlaylist(pl_id, row_num)
        {
	        document.getElementById("pl_results_table").rows[row_num].style.display = 'none'; 
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=deletepl&pl_id="+pl_id;
	        xmlhttp.open("GET", url, false);
	        xmlhttp.send();
            swal({title:"Playlist deleted!",icon:"success"});
	        
        }

        //XMLHTTP Ajax request to delete chosen song in the row from playlist
        function DELETEFromPl(SongID, pl_id, row_num)
        {   
	        document.getElementById("playlist_ed").rows[row_num].style.display = 'none'; 
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=DELETEFromPL&pl_id=" + pl_id;
	        url = url + "&SongID="+SongID;
    
	        xmlhttp.open("GET", url, false);
	        xmlhttp.send();
            swal({title:"Song deleted from playlist!",icon:"success"});
        }

</script>

</head>
<body>

    <div id="left">
    <form method="POST">
    <h1 id="brand" style="margin-top: 40px;"><a href="MusicLibrary_user.php"  style="text-decoration: none;color: white">HERTZ</a></h1>
    <div class="left-bar">
    <button type="submit" class="btn btn-outline-info" name="home_button" value="home" ><i class="fas fa-home"></i><span> Home</span></button>
    <button type="submit" class="btn btn-outline-info" name="playlist_button" value="playlist" ><i class="fas fa-th-list"></i><span> Your Library</span></button>
    <button type="submit" class="btn btn-outline-info" name="liked_button" value="liked" ><i class="fab fa-gratipay"></i><span> Liked Songs</span></button> 
    <button type="submit" class="btn btn-outline-info" name="Recent_button" value="Recents" ><i class="fas fa-history"></i><span> Recently Played</span></button>
    </div>
    </form>
    </div>

    <div id="middle">

        <div id="top">
        <nav class="navbar navbar-light">
        <div class="container-fluid">
            <form method="POST">
            <div id="search-button">
            <input type="text" id="searchquery" name="searchquery" class="search" placeholder=" Search Songs, Artists..." />
            <button class="btn btn-dark" type="submit" name="search_button" value="search" ><i class="fas fa-search" style="color: grey;"></i> </button>
            </div>
            </form>
            <div class="btn-group dropstart">
            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">Profile summary</a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
            <a class="dropdown-item"><?php GetUserSummary();?></a>
            <hr class="dropdown-divider">
            <div class="d-grid gap-2 d-md-flex justify-content-center">
            <a href="Logout.php" title="Logout"><button class="btn btn-outline-danger my-2 my-sm-0" type="submit">LOGOUT</button></a>
            </div>
            </ul>
            </div>
            
        </div>
        </nav>
        </div>

    <div id="results_Container">
        <script> fillResults(); </script>
    </div>
    </div>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\jquery-3.6.0.min.js"></script>
    <script src="bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.min.js"></script>
</body>



<?php
    function connectDB()
    {
        $conn = mysqli_connect("localhost:3306", "root", "", "dbproject");   
        //echo"connected DB"     ;
        if (!$conn) 
        {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        return $conn;
    }

    function SearchSongs(){  
        $conn = ConnectDB();    
        $val = mysqli_real_escape_string ($conn, $_POST['searchquery']);
        $query = "SELECT * FROM song WHERE Title LIKE '%".$val."%' or Album LIKE '%".$val."%' or Artist LIKE '%".$val."%' or Composer LIKE '%".$val."%' or Genre LIKE '%".$val."%'";
        if ($result = mysqli_query($conn, $query)) 
        {
            $returnVal = table_search($result);
            mysqli_close($conn);
            return $returnVal;
        }
        else
        {
            printf("Error: %s\n", mysqli_error($conn));
        }
    }  
    
    //function to display all songs from library
    function GetAllSongs(){
        $userID = $_SESSION['userID'];
        $conn = ConnectDB();      
        $query = "SELECT * FROM song order by SongID DESC LIMIT 8";
        $query1 = "SELECT * FROM `song` WHERE Genre IN (SELECT Genre from `song` WHERE SongID IN (SELECT SongID FROM `history` WHERE userID = {$userID}) GROUP BY Genre) LIMIT 6;";
        $query2 = "SELECT * FROM `song` WHERE Artist IN (SELECT Artist from `song` WHERE SongID IN (SELECT SongID FROM `history` WHERE userID = {$userID}) GROUP BY Artist) LIMIT 6;";
        if ($result = mysqli_query($conn, $query)) 
        {
            $returnVal = table_songs($result);
            $result1 = mysqli_query($conn, $query1);
            $result2 = mysqli_query($conn, $query2);
            $returnVal .= table_rec_genre($result1);
            $returnVal .= table_rec_artist($result2);
            mysqli_close($conn);
            return $returnVal;
        }
        else
        {
            printf("Error: %s\n", mysqli_error($conn));
        }
    }

    //function to display all playlist that belong to the user
     function GetPlayList(){
        $userID = $_SESSION['userID'];
        $conn = ConnectDB();
        $query = "SELECT * FROM playlist_info where userID = {$userID}";
        
        if ($result = mysqli_query($conn, $query)) 
        {
            $returnVal = table_pl($result);
            mysqli_close($conn);
            return $returnVal;
        }
        else
        {
            printf("Error: %s\n", mysqli_error($conn));
        }
    
    }
    //function to display all songs liked by the user
    function GetLikes(){
        $userID = $_SESSION['userID'];
        $conn = ConnectDB();      
        $query = "SELECT * FROM song WHERE SongID IN ( SELECT SongID FROM likes WHERE userID={$userID});";
        if ($result = mysqli_query($conn, $query)) 
        {
            $returnVal = liked_songs($result);
            mysqli_close($conn);
            return $returnVal;
        }
        else
        {
            printf("Error: %s\n", mysqli_error($conn));
        }
    }

    //function to display all songs played by the user
    function GetHistory(){
        $userID = $_SESSION['userID'];
        $conn = ConnectDB();      
        $query = "SELECT * from song join history on song.SongID = history.SongID where userID={$userID} ORDER BY played_time DESC LIMIT 20;";
        if ($result = mysqli_query($conn, $query)) 
        {
            $returnVal = table_history($result);
            mysqli_close($conn);
            return $returnVal;
        }
        else
        {
            printf("Error: %s\n", mysqli_error($conn));
        }
    }

    //Function to display all songs in a table
    function table_songs($result){
        echo "<h2>NEW ARRIVALS</h2>";
        if(mysqli_num_rows($result)==0){
            return "<img src='https://caterhub-cdn.s3-us-west-1.amazonaws.com/assets/no_listings.png' alt='no listing avilable' width='200px' height='200px'><h4>OOPS!!! NO SONGS HERE!!!</h4>";
        }
        
         $row_count = 1;
         
        echo "<table class='table table-dark table-hover' table border = '1' id='results_table' name='results_table' style='opacity: 0.65;'>";
        echo "<tr>";
        echo "<th><b>S.No</b></th>";
        echo "<th><b>Title</b></th>";
        echo "<th><b>Album</b></th>";
        echo "<th><b>Artist</b></th>";
        echo "<th><b>Composer</b></th>";
        echo "<th><b>Genre</b></th>";
        echo "<th><b>Action</b></th>";
        echo "</tr>";
        while ($row=mysqli_fetch_array($result)) {
            echo "<tr>";
            echo  "<td>" . (string)$row_count . "</td>";
            echo  "<td>" . $row['Title'] . "</td>";
            echo  "<td>" . $row['Album'] . "</td>";
            echo  "<td>" . $row['Artist'] . "</td>";
            echo  "<td>" . $row['Composer'] . "</td>";
            echo  "<td>" . $row['Genre'] . "</td>";
            echo "<td><table><tr><td><audio class='audio' src='Music/".$row['Title'].".mp3'></audio><a class='play-pause' title='play/pause' type='button' onclick='PlaySong(".$row['SongID'] ." )' style='background-color:transparent; border-color:transparent;'><i class='fa fa-play'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Add to Playlist' onclick='AddSongtoPl(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-plus-square'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Like/Unlike Song' onclick='LikeSong(".$row['SongID'] . " )' style='background-color:transparent; border-color:transparent;'><i class='far fa-heart'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";            
            echo "</tr>";      
            $row_count++;
        }
       echo "</table>";
    }

    function table_rec_genre($result){
        if(mysqli_num_rows($result)==0){
            return "";
        }
        echo "<h2 style='padding-bottom:0px;'>Recommendations</h2>";
        echo "<h4 style='padding: 10px;'>Based on Genre you listen</h4>";
        
         $row_count = 1;
         
        echo "<table class='table table-dark table-hover' table border = '1' id='results_table' name='results_table' style='opacity: 0.65;'>";
        echo "<tr>";
        echo "<th><b>S.No</b></th>";
        echo "<th><b>Title</b></th>";
        echo "<th><b>Album</b></th>";
        echo "<th><b>Artist</b></th>";
        echo "<th><b>Composer</b></th>";
        echo "<th><b>Genre</b></th>";
        echo "<th><b>Action</b></th>";
        echo "</tr>";
        while ($row=mysqli_fetch_array($result)) {
            echo "<tr>";
            echo  "<td>" . (string)$row_count . "</td>";
            echo  "<td>" . $row['Title'] . "</td>";
            echo  "<td>" . $row['Album'] . "</td>";
            echo  "<td>" . $row['Artist'] . "</td>";
            echo  "<td>" . $row['Composer'] . "</td>";
            echo  "<td>" . $row['Genre'] . "</td>";
            echo "<td><table><tr><td><audio class='audio' src='Music/".$row['Title'].".mp3'></audio><a class='play-pause' title='play/pause' type='button' onclick='PlaySong(".$row['SongID'] ." )' style='background-color:transparent; border-color:transparent;'><i class='fa fa-play'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Add to Playlist' onclick='AddSongtoPl(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-plus-square'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Like/Unlike Song' onclick='LikeSong(".$row['SongID'] . " )' style='background-color:transparent; border-color:transparent;'><i class='far fa-heart'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";            
            echo "</tr>";      
            $row_count++;
        }
       echo "</table>";
    }

    function table_rec_artist($result){
        
        if(mysqli_num_rows($result)==0){
            return "";
        }
        echo "<h4 style='padding: 10px;'>Based on Artist you listen</h4>";
        $row_count = 1;
         
        echo "<table class='table table-dark table-hover' table border = '1' id='results_table' name='results_table' style='opacity: 0.65;'>";
        echo "<tr>";
        echo "<th><b>S.No</b></th>";
        echo "<th><b>Title</b></th>";
        echo "<th><b>Album</b></th>";
        echo "<th><b>Artist</b></th>";
        echo "<th><b>Composer</b></th>";
        echo "<th><b>Genre</b></th>";
        echo "<th><b>Action</b></th>";
        echo "</tr>";
        while ($row=mysqli_fetch_array($result)) {
            echo "<tr>";
            echo  "<td>" . (string)$row_count . "</td>";
            echo  "<td>" . $row['Title'] . "</td>";
            echo  "<td>" . $row['Album'] . "</td>";
            echo  "<td>" . $row['Artist'] . "</td>";
            echo  "<td>" . $row['Composer'] . "</td>";
            echo  "<td>" . $row['Genre'] . "</td>";
            echo "<td><table><tr><td><audio class='audio' src='Music/".$row['Title'].".mp3'></audio><a class='play-pause' title='play/pause' type='button' onclick='PlaySong(".$row['SongID'] ." )' style='background-color:transparent; border-color:transparent;'><i class='fa fa-play'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Add to Playlist' onclick='AddSongtoPl(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-plus-square'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Like/Unlike Song' onclick='LikeSong(".$row['SongID'] . " )' style='background-color:transparent; border-color:transparent;'><i class='far fa-heart'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";            
            echo "</tr>";      
            $row_count++;
        }
       echo "</table>";
    }

    function table_search($result){
        echo "<h2>SEARCH RESULTS</h2>";
        if(mysqli_num_rows($result)==0){
            return "<img src='https://caterhub-cdn.s3-us-west-1.amazonaws.com/assets/no_listings.png' alt='no listing avilable' width='200px' height='200px'><h4>OOPS!!! NO SONGS HERE!!!</h4>";
        }
        
         $row_count = 1;
         
        echo "<table class='table table-dark table-hover' table border = '1' id='results_table' name='results_table' style='opacity: 0.65;'>";
        echo "<tr>";
        echo "<th><b>S.No</b></th>";
        echo "<th><b>Title</b></th>";
        echo "<th><b>Album</b></th>";
        echo "<th><b>Artist</b></th>";
        echo "<th><b>Composer</b></th>";
        echo "<th><b>Genre</b></th>";
        echo "<th><b>Action</b></th>";
        echo "</tr>";
        while ($row=mysqli_fetch_array($result)) {
            echo "<tr>";
            echo  "<td>" . (string)$row_count . "</td>";
            echo  "<td>" . $row['Title'] . "</td>";
            echo  "<td>" . $row['Album'] . "</td>";
            echo  "<td>" . $row['Artist'] . "</td>";
            echo  "<td>" . $row['Composer'] . "</td>";
            echo  "<td>" . $row['Genre'] . "</td>";
            echo "<td><table><tr><td><audio class='audio' src='Music/".$row['Title'].".mp3'></audio><a class='play-pause' title='play/pause' type='button' onclick='PlaySong(".$row['SongID'] ." )' style='background-color:transparent; border-color:transparent;'><i class='fa fa-play'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Add to Playlist' onclick='AddSongtoPl(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-plus-square'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Like/Unlike Song' onclick='LikeSong(".$row['SongID'] . " )' style='background-color:transparent; border-color:transparent;'><i class='far fa-heart'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";            
            echo "</tr>";      
            $row_count++;
        }
       echo "</table>";
    }

    function liked_songs($result){
        echo "<h2>LIKED</h2>";
        if(mysqli_num_rows($result)==0){
            return "<img src='https://caterhub-cdn.s3-us-west-1.amazonaws.com/assets/no_listings.png' alt='no listing avilable' width='200px' height='200px'><h4>OOPS!!! NO SONGS HERE!!!</h4>";
        }
        
         $row_count = 1;
         
        echo "<table class='table table-dark table-hover' table border = '1' id='results_table' name='results_table' style='opacity: 0.65;'>";
        echo "<tr>";
        echo "<th><b>S.No</b></th>";
        echo "<th><b>Title</b></th>";
        echo "<th><b>Album</b></th>";
        echo "<th><b>Artist</b></th>";
        echo "<th><b>Composer</b></th>";
        echo "<th><b>Genre</b></th>";
        echo "<th><b>Action</b></th>";
        echo "</tr>";
        while ($row=mysqli_fetch_array($result)) {
            echo "<tr>";
            echo  "<td>" . (string)$row_count . "</td>";
            echo  "<td>" . $row['Title'] . "</td>";
            echo  "<td>" . $row['Album'] . "</td>";
            echo  "<td>" . $row['Artist'] . "</td>";
            echo  "<td>" . $row['Composer'] . "</td>";
            echo  "<td>" . $row['Genre'] . "</td>";
            echo "<td><table><tr><td><audio class='audio' src='Music/".$row['Title'].".mp3'></audio><a class='play-pause' title='play/pause' type='button' onclick='PlaySong(".$row['SongID'] ." )' style='background-color:transparent; border-color:transparent;'><i class='fa fa-play'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Add to Playlist' onclick='AddSongtoPl(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-plus-square'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Unlike Song' onclick='LikeSong(".$row['SongID'] . " )' style='background-color:transparent; border-color:transparent;'><i class='far fa-heart'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";            
            echo "</tr>";      
            $row_count++;
        }
       echo "</table>";
    }

    //Function to display all playlist in a table
     function table_pl($result){
        echo "<h2>PLAYLISTS</h2>";
        if(mysqli_num_rows($result)==0){
            return "<img src='https://caterhub-cdn.s3-us-west-1.amazonaws.com/assets/no_listings.png' alt='no listing avilable' width='200px' height='200px'><h4>OOPS!!! NO AVAILABLE PLAYLISTS!!!</h4>";
        }
        $row_count = 1;
        echo "<table class='table table-dark table-hover' table border = '1' id='pl_results_table' name='pl_results_table' style='opacity: 0.65;'>";
        echo "<tr>";
        echo "<th><b>S.No</b></td>";
        echo "<th><b>PlaylistName</b></th>";
        echo "<th><b>Action</b></th>";
        echo "</tr>";
        while ($row=mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . (string)$row_count . "</td>";
        echo "<td>" . $row['playlist_name'] . "</td>";
        echo "<td><table><tr><td><a title='Edit/View Playlist' type='button' onclick='EditPlaylist(".$row['playlist_ID'].");' style='background-color:transparent; border-color:transparent;'><i class='fas fa-edit'>&nbsp;</i></a>&nbsp;</td>";
        echo "<td><a title='Delete Playlist' type='button' onclick='DeletePlaylist(".$row['playlist_ID'].",".$row_count.");' style='background-color:transparent; border-color:transparent;'><i class='fas fa-trash'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";
        echo "</tr>"; 
        $row_count++;     
        }
        echo "</table>";
    }

    //Function to display history of user in a table
    function table_history($result){
        echo "<h2>HISTORY</h2>";
        if(mysqli_num_rows($result)==0){
            return "<img src='https://caterhub-cdn.s3-us-west-1.amazonaws.com/assets/no_listings.png' alt='no listing avilable' width='200px' height='200px'><h4>OOPS!!! NO SONGS HERE!!!</h4>";
        }
        $row_count = 1;
        echo "<table class='table table-dark table-hover' table border = '1' id='results_table' name='results_table' style='opacity: 0.65;'>";
        echo "<tr>";
        echo "<th><b>Song Title</b></th>";
        echo "<th><b>Album</b></th>";
        echo "<th><b>Last Played</b></td>";
        echo "<th><b>Actions</b></th>";
        echo "</tr>";
        while ($row=mysqli_fetch_array($result)) {
        echo "<tr>";
        echo "<td>" . $row['Title'] . "</td>";
        echo "<td>" . $row['Album'] . "</td>";
        echo "<td>" . $row['played_time'] . "</td>";
        echo "<td><table><tr><td><audio class='audio' src='Music/".$row['Title'].".mp3'></audio><a class='play-pause' title='play/pause' type='button' onclick='PlaySong(".$row['SongID'] . " )'><i class='fa fa-play'>&nbsp;</i></a>&nbsp;</td>";
        echo "<td><a type='button' title ='Add to Playlist' onclick='AddSongtoPl(".$row['SongID'] . "," . $row_count ." )' ><i class='fas fa-plus-square'>&nbsp;</i></a>&nbsp;</td>";
        echo "<td><a type='button' title ='Like/Unlike Song' onclick='LikeSong(".$row['SongID'] . " )'><i class='far fa-heart'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";       
        echo "</tr>"; 
        $row_count++;     
        }
        echo "</table>";
    }

    function GetResults()
    {
        if (isset($_POST['home_button'])) {
            return GetAllSongs();
        } 
        else if (isset($_POST['playlist_button'])) {
            return GetPlayList();
        }
        else if (isset($_POST['liked_button'])) {
            return GetLikes();
        }
        else if (isset($_POST['Recent_button'])) {
            return GetHistory();
        } 
        else if (isset($_POST['search_button'])) {
            return SearchSongs();
        }
        else {
            return GetAllSongs();
        }
    }

    function GetUserSummary()
    {
        $userID = $_SESSION['userID'];

        $conn = ConnectDB();
        
        $query = "select first_name from user where userID = {$userID}";
        if ($result = mysqli_query($conn, $query)) 
        {
            $returnVal = mysqli_fetch_array($result, MYSQLI_ASSOC);
            echo " Hello ". $returnVal['first_name']. "!!";

            $query = "select count(*) as playlistCount from playlist_info where userID = {$userID}";    
            $result = mysqli_query($conn, $query);
            $returnVal = mysqli_fetch_array($result, MYSQLI_ASSOC);
            echo "<br/> PlaylistsOwned: ". $returnVal['playlistCount'];
            
            
            $result = mysqli_query($conn, "select count(*) as likedSongCount from likes where userID = {$userID}");
            $returnVal = mysqli_fetch_array($result, MYSQLI_ASSOC);
            echo "<br/> SongsLiked: ". $returnVal['likedSongCount'];

            $result = mysqli_query($conn, "select count(*) as playedSongs from history where userID = {$userID}");
            $returnVal = mysqli_fetch_array($result, MYSQLI_ASSOC);
            echo "<br/> SongsPlayed: ". $returnVal['playedSongs'];
            
        }
        else
        {
            printf("Error: %s\n", mysqli_error($conn));
        }
    }
?>
