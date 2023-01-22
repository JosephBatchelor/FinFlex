<?php
// This will check that the user has usered the sumbit button to access the sign up page instead of via url and checks if connection to db was scuessful
if (isset($_POST['update-submit'])) {
  session_start();

  require 'dbh.inc.php';

  $id = $_SESSION['userId'];
  $username = $_POST['uidUsers'];
  $email = $_POST['mail'];
  $firstname = $_POST['fname'];
  $middlename = $_POST['Mname'];
  $lastname = $_POST['Lname'];
  $phonenumber = $_POST['phonenum'];
  $country = $_POST['country'];
  $gender = $_POST['Gender'];
  $postcode = $_POST['PostCode'];
  $addressline1 = $_POST['AddressLine1'];
  $addressline2 = $_POST['AddressLine2'];





       //creating prepeared to safly exact data. Prevetns sql code from being entered within the submit.
       //This statement will be used to check if the username already exists.
       $sql = "SELECT uidUsers FROM users WHERE uidUsers=?";
       $stmt = mysqli_stmt_init($conn);
       //Checks if the password doesnt exist.
        if (!mysqli_stmt_prepare($stmt, $sql)) {
         header("Location: ../account.php?error=sqlerror");
           exit();
       }
       else {
        //Inserts a data type (String)
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);//Runs the username to see for match within database
        mysqli_stmt_store_result($stmt);
        $result = mysqli_stmt_num_rows($stmt); //Checks the amount of matches 0 meaning no match.
        if ($result > 0) {
          header("Location: ../account.php?error=usertaken&email=".$email);
            exit();

        }

        else{
          $sql = "UPDATE personalinformation SET firstname = ?, firstname = ?, middlename = ?,lastname = ?,Gender = ?,phonenumber = ?,PostCode = ?,AddressLine1 = ?,AddressLine2 = ? WHERE idUsers = ?";//prepeared statemnet
          $stmt = mysqli_stmt_init($conn);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../account.php?error=sqlerror");
              exit();
          }
          else {
                    mysqli_stmt_bind_param($stmt, "sssssisssi", $firstname,$firstname,$middlename,$lastname,$gender,$phonenumber,$postcode,$addressline1,$addressline2, $id);
                    mysqli_stmt_execute($stmt);
                    header("Location: ../account.php?update=success");
                    }
              }
     }
  mysqli_stmt_close($stmt);//closes the statements
      mysqli_close($conn); //Closes the connection to the databse
      }
      else {
        header("Location: ../account.php");
        echo '<script src="javascript\account.js>',
        'ToggleProfile();',
        '</script>';
          exit();
  }
