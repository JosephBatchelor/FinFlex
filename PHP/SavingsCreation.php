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
    
    //checking date is not present
    $sql4 = 'SELECT * FROM Savings WHERE DataToken = "'.$_SESSION['token'].'"';

      $result4 = mysqli_query($conn, $sql4);
      $resultCheck4 = mysqli_num_rows($result4);
      if ($resultCheck4 > 0) {
          while($row4 = mysqli_fetch_assoc($result4)){
                $boo = false;
            if($row4['name'] == $_POST['input1']){
                echo'
                                  <div id = "savingserrormsgcontainer">
                                  <p class = "savingserrormsg">Widget '.$_POST['input1'].' already exist, please enter a new name</p>
                                  </div>
                                   <script>
                                  var delayInMilliseconds = 5000; //5 second
                    
                                 setTimeout(function() {
                                    document.getElementById("savingserrormsgcontainer").style.display = "none";
                                 }, delayInMilliseconds);
                                 
                                 $("#savingsdisplay").load("SavingsDisplay.php");
                                 </script>
                                  ';
                break;
            }else {
                $boo = true;
            }
          }
          if($boo != false){
              if($_POST['input1'] != null and $_POST['input2'] != null and $_POST['input3'] != null){
                $name = $_POST['input1'];
                $amount = $_POST['input2'];
                $date = $_POST['input3'];
        
             $sql4 = 'INSERT INTO Savings (DataToken, name, targetamount, enddate, currentamount, startdate) VALUES ("'.$_SESSION['token'].'", "'.$name.'", "'.$amount.'", "'.$date.'", "0", "'.date("d-m-y").'")';
              $result4 = mysqli_query($conn, $sql4);
              echo'
              <div id = "savingsucessmsgcontainer">
              <p class = "savingsucessmsg">Widget '.$_POST['input1'].' has been sucessfully created</p>
              </div>   
              <script>
              var delayInMilliseconds = 5000; //5 second

             setTimeout(function() {
                document.getElementById("savingsucessmsgcontainer").style.display = "none";
             }, delayInMilliseconds);
                                              $("#savingsdisplay").load("SavingsDisplay.php");
             </script>
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