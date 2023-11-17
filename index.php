<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="css/owl.carousel.min.css">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">

    <title>Login - form</title>
  </head>
  <body style="background-color: #ece0e0;">
  

  <div class="d-lg-flex half">
    <div class="bg order-1 order-md-2" style="background-image: url('images/pexels-photo-7626820.jpg');"></div>
    <div class="contents order-2 order-md-1">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">
            <h3 style="text-align: center;"><strong style="font-size: 2em;font-weight:900;color:navy;">HERTZ</strong></h3><br>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" name="loginform">
              <div class="form-group first">
                <label for="username">Username</label>
                <input type="text" class="form-control" placeholder="Your Username" id="Uname" name="username">
              </div>
              <div class="form-group last mb-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" placeholder="Your Password" name="password" id="Pass">
              </div>
              
              <div class="d-flex mb-5 align-items-center">
                <label class="control control--checkbox mb-0"><span class="caption">Remember me</span>
                  <input type="checkbox" />
                  <div class="control__indicator"></div>
                </label> 
              </div>

              <input type="submit" value="Log In" name="submit" class="btn btn-block btn-primary"><br>
              <label><button class="btn btn-block btn-secondary" type="button" id="submit2" onclick ="javasript:NewUserRegistrationPage()">SIGN UP</button></label>
            </form>
          </div>
        </div>
      </div>
    </div>

    
  </div>
    
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    //script to load new user registration page on click of sign up button.
    function NewUserRegistrationPage() {
            window.location.assign("NewUserRegister.php");
    }
    </script>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
  </body>
</html>

<?php
    //creates session for each user
    session_start();

    //when user submits the form on clicking the submit button, below code is executed in order to authenticate the user by validating the user data in database.

    if(isset($_POST['submit'])) 
    { 
        if (empty($_POST['username'])) {
            echo "<script>swal({title:'Enter Username',icon:'info'});</script>";
        }
        else {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $conn=connectDB();
            //validates if user with the entered credentials exist in database. if yes proceed to next page.
            $query="select * from user where user_name='{$username}'";
            $result=mysqli_query($conn,$query);
            if(mysqli_num_rows($result) == 1 ){
                $row=mysqli_fetch_array($result);
                if($row['userID'] == 1){
                    if($password == $row['password']){
                        echo "Success";
                        $_SESSION['userID'] = $row['userID'];
                    }
                    else{
                      echo "<script>swal({title:'Invalid Username / Password',icon:'info'});</script>";
                    }
                }
                elseif($row['userID'] >= 1000){
                    if(password_verify($password,$row['password'])){
                        echo "Success";
                        $_SESSION['userID'] = $row['userID'];
                        //$_SESSION["user_name"] = $[user_name];
                    }
                }
                else{
                  echo "<script>swal({title:'Invalid Username / Password',icon:'info'});</script>";
                }
            }
            else{
              echo "<script>swal({title:'Invalid Username / Password',icon:'info'});</script>";
            }       
        }

        //Once the user is succcessfully validated if user is admin then redirected to MusicLibrary_admin page else to MusicLibrary_user page.
        if(isset($_SESSION['userID'])) {
            if(($_SESSION['userID']) == 1){
                header("Location:MusicLibrary_admin.php");
            }
            else{
                header("Location:MusicLibrary_user.php");        
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