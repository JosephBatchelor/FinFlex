


<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Online Banking Service</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="signupstylesheet.css" rel="stylesheet">
<script src="javascript\signup.js"></script>
<script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>

  </head>

  <body>
    <div class="container">

      <div class="banner">
        <div class="indexnav">
          <ul class="navbar">
            <a class="navlinks" href="index.php">Home</a>
            <a class="navlinks" href=""> News</a>
            <a class="navlinks" href=""> About</a>
            <a class="navlinks" href=""> Contact</a>
            <a class="navlinks" href="help.php"> Help</a>
          </ul>
        </div>
      </div>


 <div class="imagedesign">

 </div>

 <!-- <div class="imagedesign2">

 </div> -->

<div class="content">

  <?php
    if (isset($_GET['error'])) {
      if ($_GET['error'] == "emptyfields") {
        echo '<div class="textblock2">
          <p class = "signuperror">All fields must be filled in</p>
        </div>';
      }
    }
    else{
      echo '<div class="textblock1">
        <p class = "displaytext">Become a member today</p>
      </div>';
    }
   ?>


  <div class="signupinfo">
  <form action="includes/signup.inc.php" method="post">

   <div id="FirstEntry">
     <ul>
       <li><input class = "signinput" type="text" name="uid" placeholder="Username"></li>
       <li><input class = "signinput" type="password" name="pwd" placeholder="Password"></li>
       <li><input class = "signinput" type="password" name="pwd-repeat" placeholder="Retype-Password"></li>
       <li><input class = "signinput" type="text" name="mail" placeholder="E-mail"></li>
       <li><input class = "signinput" type="tel" name="phonenum" placeholder="Phone Number"></li>
     </ul>
   </div>

  <div id="SecondEntry">
    <ul>
      <li><input class = "signinput" type="text" name="fname" placeholder="First name"></li>
      <li><input class = "signinput" type="text" name="Mname" placeholder="Middle name"></li>
      <li><input class = "signinput" type="text" name="Lname" placeholder="Last name"></li>
      <li><input class = "signinput" type="date" name="DateOfBirth" placeholder="Date Of Birth"></li>

      <li> <select class = "signselect" name="Gender">
        <option class = "first" value="">Select your Gender</option>
        <option value="M">Male</option>
        <option value="F">Female</option>
        <option value="Other">Other</option>
      </select></li>

    </ul>
  </div>

  <div id="ThirdEntry">
    <ul>
      <li> <select class = "signselect" name="country">
        <option class = "first" value="">Select your country</option>
        <option value="UK">UK</option>
        <option value="usa">USA</option>
      </select></li>
      <li><input class = "signinput" type="text" name="PostCode" placeholder="PostCode"></li>
      <li><input class = "signinput" type="text" name="AddressLine1" placeholder="AddressLine 1"></li>
      <li><input class = "signinput" type="text" name="AddressLine2" placeholder="AddressLine 2"></li>
    </ul>
  </div>

  <button id = "signbutton" type="submit" name="signup-submit">signup<ion-icon class = 'icon' name="send"></ion-icon></button>

    </form>

    <button id = "left" onclick="Left()"><ion-icon name="return-left"></ion-icon></button>
    <button id = "right" onclick="Right()"><ion-icon name="return-right"></ion-icon></button>

  </div>



</div>

<div class="footer">
<p>footer</p>
</div>

</div>

  </body>
</html>
