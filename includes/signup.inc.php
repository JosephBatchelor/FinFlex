<?php
// This will check that the user has usered the sumbit button to access the sign up page instead of via url and checks if connection to db was scuessful
if (isset($_POST['signup-submit'])) {
//Creates a connection to the database.
  require 'dbh.inc.php';
//Different input data retrieved from the user.
  $username = $_POST['uid'];
  $email = $_POST['mail'];
  $firstname = $_POST['fname'];
  $middlename = $_POST['Mname'];
  $lastname = $_POST['Lname'];
  $phonenumber = $_POST['phonenum'];
  $country = $_POST['country'];
  $password = $_POST['pwd'];
  $paswordRepeat = $_POST['pwd-repeat'];
  $dateofbirth = $_POST['DateOfBirth'];
  $gender = $_POST['Gender'];
  $today = date("y-m-d");
  $diff = date_diff(date_create($dateofbirth), date_create($today));
  $age = $diff->format('%y');
  $postcode = $_POST['PostCode'];
  $addressline1 = $_POST['AddressLine1'];
  $addressline2 = $_POST['AddressLine2'];

//Checks if anoy of the fields sent are Empty.
  if (empty($username) || empty($email) ||  empty($password)|| empty($paswordRepeat)|| empty($dateofbirth) || empty($gender) ||  empty($age)) {
    header("Location: ../signup.php?error=emptyfields&uid=".$username."&email=".$email);// returns an error emptyfields&uid msg within the header.
    exit();
  }
  //filters through the email to verify that it is an legitmate address.
  else if (!filter_var($email.FILTER_VALIDATE_EMAIL) && !preg_match("/^[a-zA-Z0-9]*$/", $username)) {
    header("Location: ../signup.php?error=invalidemail&uid");// returns an error invalidemail&uid msg within the header.
      exit();
  }

  else if(!filter_var($email.FILTER_VALIDATE_EMAIL)){ //Checks for a valid email
   header("Location: ../signup.php?error=invalid&uid=".$username);
     exit();
 }
 else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){//Checks for a valid username
   header("Location: ../signup.php?error=invaliduid&email=".$email);
     exit();

  }

  else if ($password !== $paswordRepeat) {//Checks that the passwords do match
      header("Location: ../signup.php?error=passwordcheck&uid=".$username."&email=".$email);
        exit();

    }
    else {
     //creating prepeared to safly exact data. Prevetns sql code from being entered within the submit.
     //This statement will be used to check if the username already exists.
     $sql = "SELECT uidUsers FROM users WHERE uidUsers=?";
     $stmt = mysqli_stmt_init($conn);
     //Checks if the password doesnt exist.
      if (!mysqli_stmt_prepare($stmt, $sql)) {
       header("Location: ../signup.php?error=sqlerror");
         exit();

     }

     else {
      //Inserts a data type (String)
      mysqli_stmt_bind_param($stmt, "s", $username);
      mysqli_stmt_execute($stmt);//Runs the username to see for match within database
      mysqli_stmt_store_result($stmt);
      $result = mysqli_stmt_num_rows($stmt); //Checks the amount of matches 0 meaning no match.
      if ($result > 0) {
        header("Location: ../signup.php?error=usertaken&email=".$email);
          exit();

      }

      else{
        $sql = "INSERT INTO personalinformation (uidUsers, emailUsers, pwdUsers,firstname,middlename,lastname,phonenumber,country,DateOfBirth,Gender,Age,PostCode,Addressline1,Addressline2) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";//prepeared statemnet
      //$sql = "INSERT INTO personalinformation (uidUsers, emailUsers, pwdUsers,firstname,middlename,lastname,phonenumber,country,DateOfBirth,Gender,Age,PostCode,Addressline1,Addressline2) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";//prepeared statemnet

        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt, $sql)) {
          header("Location: ../signup.php?error=sqlerror");
            exit();

        }
        else {
                  $hash = password_hash($password, PASSWORD_DEFAULT);//Hashes the password
                  mysqli_stmt_bind_param($stmt, "ssssssisssisss", $username,$email,$hash,$firstname,$middlename,$lastname,$phonenumber,$country,$dateofbirth,$gender,$age,$postcode,$addressline1,$addressline2);
                  mysqli_stmt_execute($stmt);
                  $sql = "INSERT INTO users (uidUsers, emailUsers, pwdUsers) VALUES (?,?,?)";//prepeared statemnet
                  mysqli_stmt_bind_param($stmt, "sss", $username,$email,$hash);
                  $stmt = mysqli_stmt_init($conn);
                  if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../signup.php?error=sqlerror");
                      exit();

                  }
                  else {
                            $hash = password_hash($password, PASSWORD_DEFAULT);//Hashes the password
                            mysqli_stmt_bind_param($stmt, "sss", $username,$email,$hash);
                            mysqli_stmt_execute($stmt);
                            header("Location: ../signup.php?signup=success");
                              exit();
                            }
                  }
    }
   }
}
mysqli_stmt_close($stmt);//closes the statements
    mysqli_close($conn); //Closes the connection to the databse
    }
    else {
      header("Location: ../signup.php");
        exit();
}
