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
        
        
        //Displays all account data
      
            echo '
                <script>
                
                    $(document).ready(function () {

                    $("#accountDisplayALL").click(function(){
                    $("#transDisplay").load("TransactionDataALL.php");
                    $("#chartDisplay").load("BalanceDataALL.php");
                    $("#chartDisplay2").load("TimelineDataALL.php");
                    $("#chartDisplay3").load("INandOUTDataALL.php");
                });
                    });
                </script>
            <div id = "accountDisplayALL" value="ALL" name="manipulate">
            <div class ="disContainer1">
            <h5 class = "displayname1">All ACCOUNTS<h5>
            </div>
            </div>
            ';
        
        //Displays allows accoutns from users creates a display for each one   
    for( $x = 0; $x <=$resultCheck2-1; $x++){
      $sql3 = 'SELECT * FROM accounts WHERE Account_id = "'.$temp[$x].'" AND DataToken = "'.$row["DataToken"].'";';
      $result3 = mysqli_query($conn, $sql3);
      $resultCheck3 = mysqli_num_rows($result3);
      if ($resultCheck3 > 0) {
          $row3 = mysqli_fetch_assoc($result3);
            echo '
                <script>
                
                    $(document).ready(function () {

                    $("#accountDisplay'.$x.'").click(function(){
                    var index = '.$x.';
                    
                        createCookie("test", index, "10");
                   
                    // Function to create the cookie
                    function createCookie(name, value, days) {
                        var expires;
                          
                        if (days) {
                            var date = new Date();
                            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                            expires = "; expires=" + date.toGMTString();
                        }
                        else {
                            expires = "";
                        }
                          
                        document.cookie = escape(name) + "=" + 
                            escape(value) + expires + "; path=/";
                    }
          
                    $("#transDisplay").load("TransactionData.php");
                    $("#chartDisplay").load("BalanceData.php");
                    $("#chartDisplay2").load("TimelineData.php");
                    $("#chartDisplay3").load("INandOUTData.php");
                    $("#StandingOrdersDisplay").load("StandingOrdersData.php");
                    $("#DirectDebtitDisplay").load("DirectDebtData.php");



                });
                    });

                
                </script>
            <div id = "accountDisplay'.$x.'" value="'.$x.'" name="manipulate">
            <div class ="disContainer2">
            <h5 class = "displayname2">'.$row3['display_name'].'<h5>
            </div>
            </div>
            ';
      }
    }
      
      } 
      
      
            }                   
        }
      }
      }
   ?>

