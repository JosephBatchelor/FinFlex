<?php
if (isset($_POST['login-submit'])) {

  require 'dbh.inc.php';//Creates a conneciton to the databse

  $mailuid = $_POST['mailuid'];
  $password = $_POST['pwd'];

  if (empty($mailuid) || empty($password) ) {
    header("Location: ../index.php?error=emptyfields");
      exit();
  }

  else {
    $sql = "SELECT * FROM users WHERE uidUsers=? OR emailUsers=?";
    $stmt = mysqli_stmt_init($conn);//Used to initialize an sql statement.
    if (!mysqli_stmt_prepare($stmt, $sql)) {
     header("Location: ../index.php?error=sqlerror");
       exit();
   }

   else {
     mysqli_stmt_bind_param($stmt, "ss", $mailuid, $mailuid);
     mysqli_stmt_execute($stmt);
     $result = mysqli_stmt_get_result($stmt); //Checks the amount of matches 0 meaning no match.
     if ($row = mysqli_fetch_assoc($result)) {
       $pwdcheck = password_verify($password, $row['pwdUsers']);
       if ($pwdcheck ==false) {
         header("Location: ../index.php?error=incorrctpassword");
           exit();
       }

       else if ($pwdcheck == true) {
          session_start();
          $_SESSION['userId'] = $row['idUsers'];
          $_SESSION['userUid'] = $row['uidUsers'];
          header("Location: ../index.php?login=success");
           exit();
       }
       else {
         header("Location: ../index.php?error=incorrctpassword");
           exit();
       }
     }

     else {
       header("Location: ../index.php?error=nouser");
         exit();
     }

    }
  }

}
else {
  header("Location: ../index.php");
    exit();
}