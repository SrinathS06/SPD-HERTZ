<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>RegistrationForm_v1 by Colorlib</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		
		<!-- MATERIAL DESIGN ICONIC FONT -->
		<link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">

		<!-- STYLE CSS -->
		<link rel="stylesheet" href="css/style_register.css">
	</head>

	<body>

		<div class="wrapper" style="background-image: url('images/musical-background-2842924_1280.jpg');">
			<div class="inner">
				<div class="image-holder">
					<img src="images\pexels-photo-291629.png" alt="" height="600px">
				</div>
				<form name="newuser_login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
					<h3>Registration</h3>
					<div class="form-group">
						<input type="text" name="first_name" placeholder="First Name" class="form-control">
						<input type="text" name="last_name" placeholder="Last Name" class="form-control">
					</div>
					<div class="form-wrapper">
						<input type="text" name="user_name" placeholder="Username" class="form-control">
						<i class="zmdi zmdi-account"></i>
					</div>
					<div class="form-wrapper">
						<input type="text" name="e_mail" placeholder="Email Address" class="form-control">
						<i class="zmdi zmdi-email"></i>
					</div>
					<div class="form-wrapper">
						<select name="" id="" class="form-control">
							<option value="" disabled selected>Gender</option>
							<option value="male">Male</option>
							<option value="femal">Female</option>
							<option value="other">Other</option>
						</select>
						<i class="zmdi zmdi-caret-down" style="font-size: 17px"></i>
					</div>
					<div class="form-wrapper">
						<input type="password" name="password" placeholder="Password" class="form-control">
						<i class="zmdi zmdi-lock"></i>
					</div>
					<div class="form-wrapper">
						<input type="password" name="password2" placeholder="Confirm Password" class="form-control">
						<i class="zmdi zmdi-lock"></i>
					</div>
					<button type="submit" name="register">Register
						<i class="zmdi zmdi-arrow-right"></i>
					</button>
					<button type="button" onclick ="LoginPage()">Sign In
						<i class="zmdi zmdi-arrow-left"></i>
					</button>
				</form>
			</div>
		</div>
		
	</body>
</html>

</style>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
//script to load new user registration page on click of sign up button.
function LoginPage() {
		window.location.assign("index.php");
}
</script>

<?php
    //creates session for each user
    session_start();
    //when user submits the form on clicking the Register button ,below code is executed in order to validate and create new user in the database.
    if(isset($_POST['register'])) 
    { 
        //To validate if any of the fields are empty.  
        if (empty($_POST['user_name']) || empty($_POST['password']) || empty($_POST['password2']) || empty($_POST['e_mail']) || empty($_POST['first_name']) ||  empty($_POST['last_name'])){
            echo "<script>swal({title:'Please enter all details.',icon:'info'});</script>";    
        }
        //if not extract all info submitted and store in variables.
        elseif($_POST['password'] != $_POST['password2']){
            echo "<script>swal({title:'Passwords doesn't match!',icon:'info'});</script>";
        }
        elseif(!filter_var($_POST['e_mail'], FILTER_VALIDATE_EMAIL)){
            echo "<script>swal({title:'e-mail format is incorrect!',icon:'info'});</script>";
        }
        else {
            $user_name = $_POST['user_name'];
            $password = $_POST['password'];
            $first_name = $_POST['first_name'];
            $last_name = $_POST['last_name'];
            $e_mail = $_POST['e_mail'];
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $conn=connectDB();
            // to check if user with same username already exists.
            $query="select * from user where user_name='{$user_name}';";
            $result=mysqli_query($conn,$query);            
            if(mysqli_num_rows($result) > 0 ){
                
                echo "<script>swal({title:'Username already exists, Please try another one.',icon:'info'});</script>";
            }
            // if the username does not exist create an entry for the new user registered.
            else {
                $query = "INSERT INTO user (user_name, password, first_name, last_name, e_mail) VALUES ('{$user_name}', '{$hash}', '{$first_name}', '{$last_name}','{$e_mail}')";
               
                if (!mysqli_query($conn, $query))
                {
                    $error = mysqli_error($conn);
                    echo $error;
                }
                header('Location: registersuccess.html');
                // echo "";
                // echo "";
                // echo "</p>"
                
               return;
            }       
        }
     
    }
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
?>
