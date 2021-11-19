<?php
    //Session started for each user login and user ID is extracted to provide user specific functionalities.
    session_start();    
    
    if(! isset($_SESSION['userID'])) {
         header("Location:index.php");  
    } elseif ($_SESSION['userID'] != 1) { // if not admin, redirect to user page
        header("Location:MusicLibrary_user.php");  
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <style>@import url('https://fonts.googleapis.com/css2?family=Merienda+One&display=swap');</style>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="css\LibraryStylesheet.css">
    <link rel="stylesheet" type="text/css" href="bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\css\bootstrap.min.css">
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">

    <title>MusicLibraryAdmin</title>

    <script>

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

        //function to fill the results as per the function call and display results on screen
        function fillResults(){
        document.getElementById('results_Container').style.display = 'block';   
        document.getElementById('results_Container').innerHTML="<?php echo GetResults(); ?>";
    }

        //function to add to history
        function PlaySong(SongID){
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=playsong&SongID="+SongID;
	        xmlhttp.open("GET", url, true);
	        xmlhttp.send();
        }

        //XMLHTTP Ajax request to delete song in the row user has chosen
        function DeleteRecord(SongID,row_num){
            document.getElementById("results_table").rows[row_num].style.display = 'none'; 
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=DELETEsong&SongID="+SongID;
	        xmlhttp.open("GET", url, false);
	        xmlhttp.send();
	    
	        swal({title:"Song deleted from Database!",icon:"success"});
        }

        update_song_id=0;
        //function to hide all containers and show the update container when user choses update song
        function UpdateRecord(SongID,row_num){
            
            document.getElementById('results_table').style.display = 'none';
            document.getElementById('results_table_head').style.display = 'none';
            document.getElementById('add_song_container').style.display = 'none';
            document.getElementById('update_song_container').style.display = 'block';

            update_song_id = SongID;
	       
        	$rowobj = document.getElementById("results_table").rows[row_num];
        
	    	document.getElementById("update_title").value = $rowobj.cells[1].innerHTML ; 
	        document.getElementById("update_album").value = $rowobj.cells[2].innerHTML ; 
	        document.getElementById("update_artist").value =  $rowobj.cells[3].innerHTML ; 
	        document.getElementById("update_composer").value =  $rowobj.cells[4].innerHTML ; 
            document.getElementById("update_genre").value =  $rowobj.cells[5].innerHTML ; 
	       
        }

        function AddRecord(){
            document.getElementById('results_table').style.display = 'none';
            document.getElementById('results_table_head').style.display = 'none';
            document.getElementById('update_song_container').style.display = 'none';
            document.getElementById('add_song_container').style.display = 'block'; 
        }

        //XMLHTTP Ajax request to update song in the row user has chosen with all attributes filled
        function UpdateSong(){
             xmlhttp=new XMLHttpRequest();
	         var url = "actions_ajax.php?action=updatesong&SongID="+update_song_id;
	         url = url + "&Title='"+document.getElementById("update_title").value+"'";
	         url = url + "&Album='"+document.getElementById("update_album").value+"'";
             url = url + "&Artist='"+document.getElementById("update_artist").value+"'";
             url = url + "&Composer='"+document.getElementById("update_composer").value+"'";
	         url = url + "&Genre='"+document.getElementById("update_genre").value+"'";
	         
	         xmlhttp.open("GET", url, false);
	         xmlhttp.send();
               
	        if (xmlhttp.responseText == "duplicate")
	         {
		        swal({title:"Song with same attributes already exsist!",icon:"info"});
	         }
	         else
	         {
		        swal({title:"Song Updated!",icon:"success"});
	         } 
        }
        
    </script>
</head>

<body>

    <div id="left">
    <form method="POST" enctype="multipart/form-data">
    <h1 id="brand" style="margin-top: 40px;"><a href="MusicLibrary_admin.php"  style="text-decoration: none;color: white">HERTZ</a></h1>
    <div class="left-bar">
    <button type="submit" class="btn btn-outline-info" name="home_button" value="home" ><i class="fas fa-home"></i><span> Home</span></button>
    <button type="button" class="btn btn-outline-info" name="add_song_button" value="add_song" onclick="AddRecord()" ><i class='fas fa-plus-square'></i><span> Add Song</span></button>
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
            <a href="Logout.php" title="Logout"><button class="btn btn-danger" type="submit">LOGOUT</button></a>
        </div>
        </nav>
        </div>
        
        <form method="POST" enctype="multipart/form-data">
        <div id="update_song_container" style="display:None">
            <h2>Update Song</h2>
            <table style="color:white; text-shadow: 0 0 5px black; font-size:22px;">
                <tr><td>Title</td></tr>
                <tr><td><input type="text" id="update_title" class="update_table" /></td></tr>
                <tr><td>Album</td></tr>
                <tr><td><input type="text" id="update_album" class="update_table" /></td></tr>
                <tr><td>Artist</td></tr>
                <tr><td><input type="text" id="update_artist" class="update_table" /></td></tr>
                <tr><td>Composer</td></tr>
                <tr><td><input type="text" id="update_composer" class="update_table" /></td></tr>
                <tr><td>Genre</td></tr>
                <tr><td><input type="text" id="update_genre" class="update_table" /></td></tr>
                <tr style="display: table; width: 100%; justify-content: space-between; margin-top: 15px;">
                    <td><button type="button" onClick="javasript:UpdateSong();">Update</button></td>
					<td><a href="MusicLibrary_admin.php" title="Cancel"><button type="button" > Cancel</button></a></td>
                </tr>
            </table>
        </div>

        <div id="add_song_container" style="display:None">
            <h2>Add Song</h2>
            <table style="color:white; text-shadow: 0 0 5px black; font-size:22px;">
                <tr><td>Title</td></tr>
                <tr><td><input type="text" id="add_title" name="add_title" placeholder="Enter Title" /></td></tr>
                <tr><td>Album</td></tr>
                <tr><td><input type="text" id="add_album" name="add_album" placeholder="Enter Album Name" /></td></tr>
                <tr><td>Artist</td></tr>
                <tr><td><input type="text" id="add_artist" name="add_artist" placeholder="Enter Artist Name" /></td></tr>
                <tr><td>Composer</td></tr>
                <tr><td><input type="text" id="add_composer" name="add_composer" placeholder="Enter Composer Name" /></td></tr>
                <tr><td>Genre</td></tr>
                <tr><td><input type="text" id="add_genre" name="add_genre" placeholder="Enter Genre" /></td></tr>
                <tr><td>Audio File</td></tr>
                <tr><td id="filer"><input type='file' name='file'/></td></tr>
                <tr style="display: table; width: 100%; justify-content: space-between; margin-top: 15px;">
                    <td><button type="submit" name="add_button" value="add">Add</button></td>
					<td><a href="MusicLibrary_admin.php" title="Cancel"><button type="button" > Cancel</button></a></td>
                </tr>
            </table>
        </div>
        </form>

        <div id="results_Container" style="display: block;">
        <script> fillResults(); </script>
        </div>
    </div>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\jquery-3.6.0.min.js"></script>
    <script src="bootstrap-5.0.2-dist\bootstrap-5.0.2-dist\js\bootstrap.min.js"></script>
</body>
</html>





<?php
     //function to connect to the db with login details and the database selection.
    //Modify the localhost,username,password,database name as per individual credentials.
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
    //function to search songs in the library on all fields
    function SearchSongs()
    {  
        //echo "hi";
        $conn = ConnectDB();    
        $val = mysqli_real_escape_string ($conn, $_POST['searchquery']);
        $query = "SELECT * FROM song WHERE Title LIKE '%".$val."%' or Album LIKE '%".$val."%' or Artist LIKE '%".$val."%' or Composer LIKE '%".$val."%' or Genre LIKE '%".$val."%'";
        if ($result = mysqli_query($conn, $query)) 
        {
            $returnVal = table_songs($result);
            mysqli_close($conn);
            return $returnVal;
        }
        else
        {
          echo(mysqli_error($conn));
        }
    }  

    //function to display all songs from library
    function GetAllSongs(){

        $conn = ConnectDB();      
        $query = "SELECT * FROM song";
        if ($result = mysqli_query($conn, $query)) 
        {
            $returnVal = table_songs($result);
            mysqli_close($conn);
            return $returnVal;
        }
        else
        {
           echo(mysqli_error($conn));
        }
    
    }

    //Function to add song to a library
    function AddSong()
    {   
        $file = $_FILES['file'];
        $fileName = $_FILES['file']['name'];
        $fileDest = 'Music/'.$fileName;
        print_r($file);
        move_uploaded_file($_FILES['file']['tmp_name'],$fileDest);

		$conn = ConnectDB();

		$Title = $_POST['add_title'];
		$Album = $_POST['add_album'];
		$Artist = $_POST['add_artist'];
		$Composer = $_POST['add_composer'];
        $Genre = $_POST['add_genre'];
		$Path = $fileDest;

		$message = "<script>swal({title:'Song Added!',icon:'success'});<script>";
		$query = "select SongID from song where Title='{$Title}' and Album='{$Album}' and Artist='{$Artist}' and Composer='{$Composer}' and Genre='{$Genre}' and Filepath='{$Path}'";
     
		if ($result = mysqli_query($conn, $query)) 
        {   
            if (mysqli_num_rows($result) > 0)
		    {
		    	$message = "<script>swal({title:'Song with same attributes already exists!',icon:'info'});<script>";	
	    	}
		   else
		    {
			    $query = "insert into song (Title, Album, Artist, Composer, Genre , Filepath) values ('{$Title}', '{$Album}', '{$Artist}', '{$Composer}', '{$Genre}', '{$Path}')" ;
                mysqli_query($conn, $query) or die("Unable to Insert");
                
		    }
        }
        else
        {
            echo mysqli_error($conn);
        }
       	echo $message;        
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
        echo "<h2 id = 'results_table_head'>ALL SONGS</h2>";
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
            echo "<td><a type='button' title ='Add to Playlist' onclick='UpdateRecord(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-edit'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Like/Unlike Song' onclick='DeleteRecord(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-trash'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";            
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
            echo "<td><a type='button' title ='Add to Playlist' onclick='UpdateRecord(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-edit'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Like/Unlike Song' onclick='DeleteRecord(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-trash'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";            
            echo "</tr>";      
            $row_count++;
        }
       echo "</table>";
    }

    function table_history($result){
        echo "<h2 id = 'results_table_head'>HISTORY</h2>";
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
            echo "<td><a type='button' title ='Add to Playlist' onclick='UpdateRecord(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-edit'>&nbsp;</i></a>&nbsp;</td>";
            echo "<td><a type='button' title ='Like/Unlike Song' onclick='DeleteRecord(".$row['SongID'] . "," . $row_count ." )' style='background-color:transparent; border-color:transparent;'><i class='fas fa-trash'>&nbsp;</i></a>&nbsp;</td></tr></table></td>";            
            echo "</tr>";       
            $row_count++;
        }
       echo "</table>";
    }

    //Function to get call the respective operations as per user's selection of operation
    function GetResults()
    {
        if (isset($_POST['home_button'])) {
            return GetAllSongs();
        } 
        else if (isset($_POST['add_song_button'])) {
            return GetPlayList();
        }
        else if (isset($_POST['Recent_button'])) {
            return GetHistory();
        }
        else if (isset($_POST['add_button'])) {
            return AddSong();
        }
        else if (isset($_POST['search_button'])) {
            return SearchSongs();
        }
        else {
            return GetAllSongs();
        }
    }

?>
