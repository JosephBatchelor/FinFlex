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
                //if user has connected their a bank then their data should be displayed.
      $_SESSION['token'] = $row["DataToken"];

      $sql2 = 'SELECT * FROM accounts WHERE DataToken = "'.$row["DataToken"].'";';
      $result2 = mysqli_query($conn, $sql2);
      $resultCheck2 = mysqli_num_rows($result2);
      if ($resultCheck2 > 0) {
         
        //pulls all transactions data.    
  $sql4 = 'SELECT * FROM Balance WHERE DataToken = "'.$_SESSION['token'].'";';
      $result4 = mysqli_query($conn, $sql4);
      $resultCheck4 = mysqli_num_rows($result4);
      if ($resultCheck4 > 0) {
         
           $rowcount=mysqli_num_rows($result4);

          while($row4 = mysqli_fetch_assoc($result4)){
            echo '
            
            <script>
            
             function update(){
             myChart.data.datasets[0].data[1] = '.$row4['available'].';
             myChart.data.datasets[0].data[2] = '.$row4['current'].';
             myChart.data.datasets[0].data[3] = '.$row4['overdraft'].';
             myChart.update();
            }
             update();
             </script>

            ';
          }
      
  }
     
      } 
      
      
            }                   
        }
      }
      }

      
?>
