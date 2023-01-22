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
        
        //pulls all transactions data. 
      $sql4 = 'SELECT * FROM PendingTransactions WHERE Account_id = "'.$temp[$_COOKIE["test"]].'" AND DataToken = "'.$_SESSION['token'].'" ;';
      $result4 = mysqli_query($conn, $sql4);
      $resultCheck4 = mysqli_num_rows($result4);
      if ($resultCheck4 > 0) {
         
            echo '
           <tr class = "BOX2";>
                <td class = "">  <p class = "">Pending Transactions</p> </td></td>
            </tr>
            '; 
         
          while($row4 = mysqli_fetch_assoc($result4)){

            echo '
           <tr class = "BOX";>
                <td class = "description">  <p class = "Desriptiontext">'.$row4['description'].'</p>     <p class = "DateText">'.strtok($row4['DateOfTransaction'], "T").'</p>   <p class = "Transactiontext" style=" color:orange;">£'.number_format($row4['transaction_amount'],2).'</p> </td></td>
            </tr>
            ';              
            
          }
  }
      
      
          
      $sql4 = 'SELECT * FROM transactions WHERE Account_id = "'.$temp[$_COOKIE["test"]].'" AND DataToken = "'.$_SESSION['token'].'" ;';
      $result4 = mysqli_query($conn, $sql4);
      $resultCheck4 = mysqli_num_rows($result4);
      if ($resultCheck4 > 0) {
         
            echo '
           <tr class = "BOX2";>
                <td class = "">  <p class = "">Processed Transactions</p> </td></td>
            </tr>
            '; 
         
          while($row4 = mysqli_fetch_assoc($result4)){
            if(intval($row4['transaction_amount'])>0){

            echo '
           <tr class = "BOX";>
                <td class = "description">  <p class = "Desriptiontext">'.$row4['description'].'</p>     <p class = "DateText">'.strtok($row4['DateOfTransaction'], "T").'</p>   <p class = "Transactiontext" style=" color:green;">£'.number_format($row4['transaction_amount'],2).'</p>  <p class = "AccountAmountText">£'.$row4['account_amount'].'</p> </td></td>
            </tr>
            ';              
            }else{
            echo '
            <tr class = "BOX">
                    <td class = "description">  <p class = "Desriptiontext">'.$row4['description'].'</p>     <p class = "DateText">'.strtok($row4['DateOfTransaction'], "T").'</p>   <p class = "Transactiontext" style=" color:red;">£'.number_format($row4['transaction_amount'],2).'</p>  <p class = "AccountAmountText">£'.$row4['account_amount'].'</p> </td>
            </tr>
            ';              
              };
          }
  }
  
  
  
  
  
           } 
            }                   
        }
      }
      }

      
?>