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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
    href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.css"
    rel="stylesheet"
    >
     <link rel="stylesheet" href="css/LibraryStylesheet.css">   
    <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">
    <link rel="stylesheet" href="bootstrap-5.0.2-dist/bootstrap-5.0.2-dist/css/bootstrap.min.css">

    <script type="text/javascript" src="https://code.jquery.com/jquery-1.7.1.min.js"></script>
    <title>Hertz - Admin</title>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        
        //function to fill the results as per the function call and display results on screen
        function fillResults(){
            //alert("fillresult"); 
            document.getElementById('results_Container').style.display = 'inline';
            
           
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


        //XMLHTTP Ajax request to delete song in the row user has chosen
        function DeleteRecord(SongID,row_num){
            //alert(SongID);
            //alert(row_num);
            document.getElementById("results_table").rows[row_num].style.display = 'none'; 
	        xmlhttp=new XMLHttpRequest();
	        var url = "actions_ajax.php?action=deletesong&SongID="+SongID;
	        xmlhttp.open("GET", url, false);
	        xmlhttp.send();
	        //alert(xmlhttp.responseText);
	        alert('Deleted!');
        }
        update_song_id=0;
        //function to hide all containers and show the update container when user choses update song
        function UpdateRecord(SongID,row_num){
            // alert(SongID);
            //  alert(row_num);
             update_song_id = SongID;
	        
             document.getElementById("results_table").style.display = 'none';
	        document.getElementById("update_song_container").style.display = 'block'; 
	       
        	$rowobj = document.getElementById("results_table").rows[row_num];
        //  alert( document.getElementById("update_genre"));
	    	document.getElementById("update_title").value = $rowobj.cells[1].innerHTML ; 
	        document.getElementById("update_album").value = $rowobj.cells[2].innerHTML ; 
	        document.getElementById("update_artist").value =  $rowobj.cells[3].innerHTML ; 
	        document.getElementById("update_composer").value =  $rowobj.cells[4].innerHTML ; 
            document.getElementById("update_genre").value =  $rowobj.cells[5].innerHTML ; 
	       
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
            //alert(xmlhttp.responseText);   
	        if (xmlhttp.responseText == "duplicate")
	         {
		        alert("Song with Similar Attributes exists in the library!");
	         }
	         else
	         {
		        alert("Update Success!");
	         }
            
             document.getElementById("mainform").submit();
             
        }
        
    </script>
</head>
<body style="background-color: #e0e0e0;">
<form method="POST">
<div class="sidenav">
  <a href="#" id="brand">HERTZ</a>
  <a href="#"> <button type="submit" name="home_button" value="home" ><i class="fas fa-home"></i> Home</button> </a>
  <a href="#"> <button type="submit" name="add_song_button" value="add_song"><i class="far fa-plus-square"></i></i> Add New song</button> </a>
</div>
</form>

<div class="main">
    <form method="POST">
    <div class="topnav">
        <input type="text" id="searchquery" name="searchquery" class="search" placeholder=" Songs, Artists,Albums" />
        <button type="submit" name="search_button" style="display: inline;" value="search" ><i class="fas fa-search" style="color: slategray;"></i> </button>
        <a href="#"><button name="logout_button" value="logout" type="submit" style="display: inline;float:right;margin-top:2%;margin-right:5%;color:navy"><i class="fas fa-power-off"></i> Log Out</button></a>
    </div>
    <div id="results_Container">  
        <script> fillResults(); </script>
    </div>
    </form>
</div>
   
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

    function get_add_form(){
        
        echo "<div id='add_song_container' style='margin-left:35%'>";
        echo    "<div>";
        echo        "<h5>Song Name</h5>";
        echo    "</div>";
        echo    "<input type='text' id='add_title' name='add_title' placeholder='Enter song name...'> <br>";
        echo    "<div>";
        echo        "<h5 >Album Name</h5>" ;
        echo    "</div>";
        echo    "<input type='text' id='add_album' name='add_album' placeholder='Enter album name...''><br>";
        echo    "<div>";
        echo        "<h5 >Artist Name</h5>";
        echo    "</div>";
        echo    "<input type='text' id='add_artist' name='add_artist' placeholder='Enter artist name...'><br>";
        echo    "<div>";
        echo       "<h5 >Composer</h5>";
        echo    "</div>";
        echo    "<input type='text' id='add_composer' name='add_composer' placeholder='Enter composer name...''><br>";
        echo    "<div>";
        echo        "<h5 >Genre</h5>";
        echo    "</div>";
        echo    "<input type='text' id='add_genre' name='add_genre' placeholder='Enter genre...'><br><br>";
        echo    "<form method='post' enctype='multipart/form-data'>";
        echo    "<input type='file' name='file'/> <br>";
        echo    "<input type='submit' value='submit' name='submit'>";
        echo    "</form>";
        echo    "</div>";
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
            //printf ("Select returned %d rows.\n", mysqli_num_rows($result));
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
           //echo "isset";
        $conn = ConnectDB();      
        $query = "SELECT * FROM song";
        if ($result = mysqli_query($conn, $query)) 
        {
            //printf("Select returned %d rows.\n", mysqli_num_rows($result));
            $returnVal = table_songs($result);
            //echo ($returnVal);
            mysqli_close($conn);
            return $returnVal;
        }
        else
        {
           echo(mysqli_error($conn));
        }
    
    }
    
    //Function to add song to a library
    if (isset($_POST['submit']))
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

		$message = "Song Added!";
		$query = "select SongID from song where Title='{$Title}' and Album='{$Album}' and Artist='{$Artist}' and Composer='{$Composer}' and Genre='{$Genre}' and Filepath='{$Path}'";
        //echo $query;
		if ($result = mysqli_query($conn, $query)) 
        {   //echo("enterd if");
            if (mysqli_num_rows($result) > 0)
		    {
		    	$message = "Song with these attributes already exists!";	
                

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
       	echo "<h2 style:'margin-top:50px;'><font color='green'><center>".$message."</center></font></h2>";        
		
		
		
    }

    //Function to display all songs in a table
    function table_songs($result){
        if(mysqli_num_rows($result)==0){
            return "<img src='https://caterhub-cdn.s3-us-west-1.amazonaws.com/assets/no_listings.png' alt='no listing avilable' width='200px' height='200px' style='margin-left:42%;display:block;'><h1 style='text-align:center;'>Oops! Nothing Found.</h1>";
        }
        
         $row_count = 1;
         
        echo "<table class='table table-hover' id='results_table' name='results_table'>";
        echo "<tr>";
        echo "<th style='border-color: black;'><b>S.No</b></th>";
        echo "<th style='border-color: black;'><b>Title</b></th>";
        echo "<th style='border-color: black;'><b>Album</b></th>";
        echo "<th style='border-color: black;'><b>Artist</b></th>";
        echo "<th style='border-color: black;'><b>Composer</b></th>";
        echo "<th style='border-color: black;'><b>Genre</b></th>";
        echo "<th style='border-color: black;'><b>Action</b></th>";
        echo "</tr>";
        while ($row=mysqli_fetch_array($result)) {
            echo "<tr>";
            echo  "<td style='border-color: darkgray;'>" . (string)$row_count . "</td>";
            echo  "<td style='border-color: darkgray;'>" . $row['Title'] . "</td>";
            echo  "<td style='border-color: darkgray;'>" . $row['Album'] . "</td>";
            echo  "<td style='border-color: darkgray;'>" . $row['Artist'] . "</td>";
            echo  "<td style='border-color: darkgray;'>" . $row['Composer'] . "</td>";
            echo  "<td style='border-color: darkgray;'>" . $row['Genre'] . "</td>";
            echo "<td style='border-color: darkgray;'><table><tr><td><a type='button' title='Edit Song details' onclick='UpdateRecord(".$row['SongID'] . "," . $row_count ." )' ><i class='fas fa-edit'></i></a></td>";
            echo "<td style='border-color: darkgray;'><audio class='audio' src='Music/".$row['Title'].".mp3'></audio><a class='play-pause' title='play/pause' type='button'><i class='fa fa-play'></i></a></td>";
            echo "<td style='border-color: darkgray;'><a type='button' title='Delete song' onclick='DeleteRecord(".$row['SongID'] . "," . $row_count ." )' ><i class='fas fa-trash'></i></a></td></tr></table></td>";
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
            return get_add_form();
        }
        else if (isset($_POST['search_button'])) {
            return SearchSongs();
        }
        else {
            return GetAllSongs();
        }
    }

?>
