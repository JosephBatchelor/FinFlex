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
                echo '<h1>Connect your banks now </h1>
                <a href="https://jb1828.brighton.domains/FinalYearProject/Signintemp" target="popup" onclick="window.open"https://jb1828.brighton.domains/FinalYearProject/Signintemp','name','width=800,height=1000")">Connect now</a>';
            }else{
                //if user has connected their a bank then their data should be displayed.
      $_SESSION['token'] = $row["DataToken"];

      $sql2 = 'SELECT * FROM accounts WHERE DataToken = "'.$row["DataToken"].'";';
      $result2 = mysqli_query($conn, $sql2);
      $resultCheck2 = mysqli_num_rows($result2);
      if ($resultCheck2 > 0) {
         
        for( $i = 1; $i <=$resultCheck2; $i++){
          $row2 = mysqli_fetch_row($result2);
          array_push($temp,$row2[1]);
        }
    
      $sql4 = 'SELECT * FROM DirectDebt WHERE Account_id = "'.$temp[$_COOKIE["test"]].'" AND DataToken = "'.$_SESSION['token'].'" ;';
      $result4 = mysqli_query($conn, $sql4);
      $resultCheck4 = mysqli_num_rows($result4);
      if ($resultCheck4 > 0) {
          
           echo '
           <tr class = "BOX6";>
                <td class = ""> <p class = "status">Direct Debits</p></td></td>
            </tr>
            '; 
         
          while($row4 = mysqli_fetch_assoc($result4)){

            echo '
            <tr class = "BOX7">
                    <td class = "description3"> <p class = "name">'.$row4['name'].'</p>   <p class = "statusactivity">'.$row4['status'].'</p> <p class = "paymentamount">£ '.$row4['previous_payment_amount'].'</p> <p class = "paymenttimestamp">'.strtok($row4['previous_payment_timestamp'], "T").'</p></td>
            </tr>
            ';              
             
          }
  }
  
  
  
  
  
           } 
            }                   
        }
      }
      }

      
?>

