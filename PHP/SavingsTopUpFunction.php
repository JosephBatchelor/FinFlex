<?php
   session_start();
   include_once 'includes/dbh.inc.php';//Creates a conneciton to the databse


 if (isset($_SESSION['userId'])) {
      $id = $_SESSION['userId'];
      $temp = array();

      
    //finds the current user logged by using sesssion data.
      $sql = "SELECT * FROM personalinformation WHERE idUsers= $id;";

      $result = mysqli_query($conn, $sql);
      $resultCheck = mysqli_num_rows($result);
      if ($resultCheck > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            if (is_null($row['DataToken'])) {

                //If users hasnt connected any banks they will be preseneted with the ability to do so.
               }else{
      $_SESSION['token'] = $row["DataToken"];

      $sql2 = 'SELECT * FROM accounts WHERE DataToken = "'.$row["DataToken"].'";';
      $result2 = mysqli_query($conn, $sql2);
      $resultCheck2 = mysqli_num_rows($result2);
      if ($resultCheck2 > 0) {
         
        for( $i = 1; $i <=$resultCheck2; $i++){
          $row2 = mysqli_fetch_row($result2);
          array_push($temp,$row2[1]);
        }
        
    
      $sql4 = 'SELECT * FROM Savings WHERE DataToken = "'.$_SESSION['token'].'" AND name = "'.$_POST['tdvalue'].'"';
      $result4 = mysqli_query($conn, $sql4);
      $resultCheck4 = mysqli_num_rows($result4);
      if ($resultCheck4 > 0) {
          $row = mysqli_fetch_assoc($result4);
          $new = (float)$_POST['tdvalue2'];
          $current = (float)$row["currentamount"];
          $Updatedamount = $current + $new;
                          
              $sql4 = 'SELECT * FROM Balance WHERE DataToken = "'.$_SESSION['token'].'" AND account_id = "'.$temp[$_POST['tdvalue3']].'"';
              $result4 = mysqli_query($conn, $sql4);
              $resultCheck4 = mysqli_num_rows($result4);
                  if ($resultCheck4 > 0) {
                          $row = mysqli_fetch_assoc($result4);
                          $accountBalance = (float)$row["available"];
                            if($new > $accountBalance){
                              echo '
                              <div class = "TopUPmsgContainer">
                              <p>'.$temp[$_POST['tdvalue3']].' has insufficient funds.</p>
                              </div>
                              ';                            }else{
                              $newbalance = $accountBalance -$new;
                              $sql4 = 'UPDATE Balance SET available = "'.$newbalance.'" WHERE DataToken = "'.$_SESSION['token'].'" AND account_id = "'.$temp[$_POST['tdvalue3']].'"';
                              $result4 = mysqli_query($conn, $sql4); 
                              
                              $sql4 = 'UPDATE Savings SET currentamount = "'.$Updatedamount.'" WHERE DataToken = "'.$_SESSION['token'].'" AND name = "'.$_POST['tdvalue'].'"';
                              $result4 = mysqli_query($conn, $sql4);
                              
                              $sql4 = 'INSERT into transactions (DataToken, Account_id, account_amount, account_currency, description, transaction_amount, transaction_category, transaction_id, transaction_type, DateOfTransaction) 
                              VALUES ("'.$_SESSION['token'].'", "'.$temp[$_POST['tdvalue3']].'","'.$newbalance.'.00", "GBP", "'.$_POST['tdvalue'].' savings", "'.$new.'", "Savings", "123", "Internal", "'.date("Y/m/d h:i:s").'")';
                              $result4 = mysqli_query($conn, $sql4); 
                              echo '
                              <div class = "TopUPmsgContainer">
                              <p>'.$_POST['tdvalue'].' was sucessfully Top up.</p>
                              </div>
                              ';
                            }
                        } 
            }                   
        }
      }
      }
          
      }
     
 }
?>