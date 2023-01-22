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
       
        //pulls all transactions data.    
    $sql4 = 'SELECT * FROM transactions WHERE DataToken = "'.$_SESSION['token'].'" ORDER BY DateOfTransaction ASC;';

      $result4 = mysqli_query($conn, $sql4);
      $resultCheck4 = mysqli_num_rows($result4);
      if ($resultCheck4 > 0) {
         $x = 0;
         
 echo'
          <script>
          function Dataclear(){
          while(myChart2.data.datasets[0].data.length != 0){
           myChart2.data.labels.pop();
            myChart2.data.datasets[0].data.pop();
          }
          }
          Dataclear();
          </script>
          ';
      
          while($row4 = mysqli_fetch_assoc($result4)){
             $DoT = strtok($row4['DateOfTransaction'], "T");
             $cvrt = strtotime($DoT);
             $date = date('M d', $cvrt);
                      

            echo '
            <script>
             function update(){
             var date = "'.$date.'";
             myChart2.data.labels['.$x.'] = date;
             myChart2.data.datasets[0].data['.$x.'] = '.$row4['transaction_amount'].';
             myChart2.update();
            }
             update();
             </script>
            ';
            
                         $x++;

          }
      
  }
     
      } 
      
      
            }                   
        }
      }
      }

      
?>

