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
        
    $startdate = $_POST['text1'];
    $sd = date("Y-m-d", strtotime("$startdate"));
    $enddate = $_POST['text2'];
    $ed = date("Y-m-d", strtotime($enddate));

        //pulls all transactions data.    
    $sql4 = 'SELECT * FROM transactions WHERE Account_id = "'.$temp[$_COOKIE["test"]].'" AND DataToken = "'.$_SESSION['token'].'" AND DateOfTransaction BETWEEN  "'.$sd.'" AND "'.$ed.'" ORDER BY DateOfTransaction ASC;';

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
             function transupdate(){
             var date = "'.$date.'";
             myChart2.data.labels['.$x.'] = date;
             myChart2.data.datasets[0].data['.$x.'] = '.$row4['transaction_amount'].';
             myChart2.update();
            }
             transupdate();
             </script>
            ';
            
                         $x++;

          }
        
      
  }
     
        //pull all balance data     
      
      } 
      
      
            }                   
        }
      }
      }

      
?>
    <!--$sql4 = 'SELECT * FROM transactions WHERE Account_id = "56c7b029e0f8ec5a2334fb0ffc2fface" AND DataToken = "j4irlzwxf19y81rqu3eq2" AND DateOfTransaction BETWEEN  "'.$sd.'" AND "'.$ed.'" ORDER BY DateOfTransaction ASC;';-->


