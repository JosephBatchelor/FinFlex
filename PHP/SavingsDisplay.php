
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
    
            $sql4 = 'SELECT * FROM Savings WHERE DataToken = "'.$_SESSION['token'].'";';

      $result4 = mysqli_query($conn, $sql4);
      $resultCheck4 = mysqli_num_rows($result4);
      if ($resultCheck4 > 0) {
         $x = 0;
        
          while($row4 = mysqli_fetch_assoc($result4)){
             
            $startamount = 0;
            $currentamount = (int)$row4["currentamount"];
            $endamount = (int)$row4["targetamount"];;
          
            
            $diff = $endamount - $startamount;
            $cdiff = $currentamount - $startamount;
            $percentage = ($cdiff / $diff)* 100;
            echo '
           <tr class = "BOX8";>
                <td class = ""> <p class = "status">'.$row4['name'].'</p></td></td>
            </tr>
            '; 
            echo '
            <tr class = "BOX9">
                    <td class = "description2">   <p class = "startdatetext">Start date:</p> <p class = "startdate">'.$row4['startdate'].'</p>     <p class = "enddatetext">Final date:</p>   <p class = "enddate">'.$row4['enddate'].'</p>   <p class = "targetamounttext">Target amount:</p>  <p class = "targetamount">£ '.$row4['targetamount'].'</p>  <p class = "Currentamounttext">Current amount:</p>  <p class = "Currentamount">£ '.$row4['currentamount'].'</p> </td
            </tr>
            <tr class = "BOX10">
            <td class = "progressBarContainer2">
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

 