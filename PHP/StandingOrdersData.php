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
    
      $sql4 = 'SELECT * FROM StandingOrders WHERE Account_id = "'.$temp[$_COOKIE["test"]].'" AND DataToken = "'.$_SESSION['token'].'" ;';
      $result4 = mysqli_query($conn, $sql4);
      $resultCheck4 = mysqli_num_rows($result4);
      if ($resultCheck4 > 0) {
         
          while($row4 = mysqli_fetch_assoc($result4)){
              
            $sd = str_replace("T"," ", strtok($row4['first_payment_date'], "."));
            $nd = str_replace("T"," ", strtok($row4['next_payment_date'], "."));
            $fd = str_replace("T"," ", strtok($row4['final_payment_date'], "."));
            
            $start = "$sd";
            $current = "$nd";
            $end = "$fd";
            $start = strtotime($start);
            $current =  strtotime($current);
            $end = strtotime($end);
            
            $diff = $end - $start;
            $cdiff = $current - $start;
            $percentage = ($cdiff / $diff)* 100;
            
            
            echo '
           <tr class = "BOX4";>
                <td class = ""> <p class = "status">'.$row4['status'].'</p></td></td>
            </tr>
            '; 
            echo '
            <tr class = "BOX3">
                    <td class = "description2">   <p class = "first_payment_test">Start date:</p> <p class = "first_payment_date">'.strtok($row4['first_payment_date'], "T").'</p>     <p class = "final_payment_text">Final date:</p>   <p class = "final_payment_date">'.strtok($row4['final_payment_date'], "T").'</p>   <p class = "next_payment_datetext">next payment date:</p>   <p class = "next_payment_amounttext">Amount:</p> <p class = "next_payment_date">'.strtok($row4['next_payment_date'], "T").'</p>   <p class = "next_payment_amount">£ '.$row4['next_payment_amount'].'</p> </td>

            </tr>
            
            <tr class = "BOX5">
            
            <td class = "progressBarContainer">
          <progress id="file" max="100" value="'.$percentage.'"> '.$percentage.'% </progress>
          </td>          
          
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

