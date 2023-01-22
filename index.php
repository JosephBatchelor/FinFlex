<?php
    session_start();
    include_once 'includes/dbh.inc.php';//Creates a conneciton to the databse
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Online Banking Service</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="Indexstylesheet.css" rel="stylesheet">
<script src="javascript/index.js"></script>



  </head>

  <body>
    <div class="container">


<div class="banner">
    <img class = "logo" src="photos\branch-logo.png">
</div>


<div class="accountarea">
  <!-- Logging & out area -->
  <?php
// Checks to see if user is already signed-in by usings its sesssion ID to check its value.   
    if (isset($_SESSION['userId'])) {
      $id = $_SESSION['userId'];
      $username = '';
      $firstname = '';
      $lastname = '';
// Retrieves users fname, sname and username using the session ID. 
      $sql = "SELECT * FROM users WHERE idUsers= $id;";
      $result = mysqli_query($conn, $sql);
      $resultCheck = mysqli_num_rows($result);
//Checks if session ID exists.
      if ($resultCheck > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $username = $row['uidUsers'];
          $firstname = $row['firstname'];
            $lastname = $row['lastname'];
        }
      }
//Chagnges HTML to provide a sign-out button and displays their profile picture in the corner.
//A drop down button is provide to allow the user to access their account.
      echo '  <form action="includes/logout.inc.php" method="post">
      <div class="action" action="includes/logout.inc.php" method="post">
        <div class="Profile" onclick="menuToggle();">
          <img src="photos\defaultUserphoto.png">
        </div>
        <div class="menu">
          <h3>'.$firstname." ".$lastname.'</h3>
          <h4>'.$username.'<h4/>
          <ul>
            <li> <a href="account.php"> My Account</a></li>
            <li> <a href="profile.php">My profile</a></li>
            <li> <a href="">Messages</a></li>
            <li> <a href="">Settings</a></li>
            <li> <button class = "logoutbut" type="submit" name="logout-submit">Logout</button> </li>
          </ul>
        </div>
      </div>
      </form>
        ';
    }else {
      echo '
          <form  class = "signinform" action="includes/login.inc.php" method="post">
              <input class = "signinput-user" type="text" name="mailuid" placeholder="Username">
              <input class = "signinput-password" type="password" name="pwd" placeholder="Password">
              <button class = "signinbutt" type="submit" name="login-submit">Login</button>
              <a class = "signupbutt" href="signup.php">SignUp</a>
            </form>';
        if (isset($_GET['error'])) {
          if ($_GET['error'] == "nouser") {
            echo '<p class = "signuperror">Invalid Crednetials</p>';
          }
        }

    }
   ?>


  </div>
  <!--   <a class="navlinks" href="account.php"> My Account</a> -->
<div class="indexnav">
  <ul class="navbar">
    <a class="navlinks" href="index.php">Home</a>
      <a class="navlinks" href=""> News</a>
      <a class="navlinks" href=""> About</a>
      <a class="navlinks" href=""> Contact</a>
      <a class="navlinks" href="help.php"> Help</a>
  </ul>

</div>


<div class="content">

    <p class="slogan">Banking made easy</p>
    <p class="sloganUndertext">Spend, save and manage your money, all in one place. Open a full UK bank account from your phone, for free.</p>
  <div class="parallax">
</div>


<div class="info2">
  <p class = "info2text">Blah Blah Blah</p>
  <img class="certification" src="photos/certi.png">
</div>

<div class="info1">

</div>

<div class="info3">

</div>

<div class="info4">

</div>




</div>

<div class="footer">
<p>footer</p>
</div>

</div>

  </body>
</html>
