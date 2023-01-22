<?php
    session_start();
   include_once 'includes/dbh.inc.php';//Creates a conneciton to the databse
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <script src="js/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <title>Online Banking Service</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="accountstylesheet.css" rel="stylesheet">
<script src="javascript\account.js"></script>
<script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
        
  </head>
  <body>
<div class="container">

  <div class="banner">
      <img class = "logo" src="photos\branch-logo.png">
      
      
  </div>

  <div class="accountarea">
    <!-- Logging & out area -->
    <?php
      if (isset($_SESSION['userId'])) {
        $id = $_SESSION['userId'];
        

        $username = '';
        $firstname = '';
        $lastname = '';

        $sql = "SELECT * FROM personalinformation WHERE idUsers= $id;";

        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $username = $row['uidUsers'];
            $firstname = $row['firstname'];
              $lastname = $row['lastname'];
          }
        }

        echo '
        <div class="action" action="includes/logout.inc.php" method="post">
          <div class="Profile" onclick="menuToggle();">
            <img src="photos\defaultUserphoto.png">
          </div>
          <h3>'.$firstname." ".$lastname.'</h3>
          <h4>'.$username.'<h4/>
          </div>
          ';
      }else {
          header("Location: https://jb1828.brighton.domains/FinalYearProject/index.php");
          exit();
      }
     ?>
    </div>

    <div class="indexnav">
      <ul class="navbar">
        <a class="navlinks" href="index.php">Home</a>
          <a class="navlinks" href=""> News</a>
          <a class="navlinks" href=""> About</a>
          <a class="navlinks" href=""> Contact</a>
          <a class="navlinks" href="help.php"> Help</a>
      </ul>


    </div>

    <div class="content">
      <div class="Sidebar">
        <div class="menu">
          <ul>
            <br><br>
           <li> <button class = "SideBut" onclick="ToggleOverview()"><ion-icon class="icon" name="home"></ion-icon> Overview</button> </li>
           <li> <button class = "SideBut"> <ion-icon class="icon" name="share-alt"></ion-icon> Transfer</button> </li>
           <li> <button class = "SideBut" onclick="ToggleServices()"><ion-icon class="icon" name="repeat"></ion-icon> Analytics</button> </li>
           <li> <button class = "SideBut" onclick="ToggleProfile()"> <ion-icon class="icon" name="reverse-camera"></ion-icon> Profile</button> </li>
           <li> <button class = "SideBut" onclick="ToggleSavings()" id = "savingsbutt"><ion-icon class="icon" name="wallet"></ion-icon> Savings</button>  </li>
           <li> <button class = "SideBut"><ion-icon class="icon" name="pricetags"></ion-icon> Offers</button>  </li>
          <li> <button class = "SideBut"><ion-icon class="icon" name="notifications"></ion-icon> Announcements</button>  </li>
          <li> <button class = "SideBut" type="submit"><ion-icon class="icon" name="settings"></ion-icon> Settings</button> </li>
          <form action="includes/logout.inc.php" method="post">
          <li> <button class = "SideBut" type="submit" name="logout-submit"> <ion-icon class="icon" name="log-out"></ion-icon> Logout</button> </li>
          </form>

          </ul>
        </div>
      </div>

      <div id="Mainarea" >
          
        <div id="Overview">
            
            <?php
   session_start();
   include_once 'includes/dbh.inc.php';//Creates a conneciton to the databse
   
    if (isset($_SESSION['userId'])) {
      $id = $_SESSION['userId'];

      
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
                echo'
                <script>
        $(document).ready(function(){
             $("#accounts").load("AccountFunction.php");
              $("#accounts").submit(function(e) {
            e.preventDefault();
            });
            
             $("#topupsdisplay").load("SavingsTopUp.php");
                      $("#topupsdisplay").submit(function(e) {
                    e.preventDefault();
                    });
            $("#accountselection").load("SavingAccountsDisplay.php");
            
            $("#operations1gdisplay").load("Operation1.php");

        });
            </script>
                ';
            }                   
        }
      }
      }
   ?>
            
           <div class="dropdown">
              <button class="dropbtn">Accounts</button>
              <div class="dropdown-content">
              <div id="accounts">
                  
              </div>
              </div>
              </div>
              
             <div id="Balance">
                <h6 class ="TransactionTitle">Balance</h6>
                  <div class = "chartBox">
                      <canvas id="myChart"></canvas>
                  </div>              
                <div id="chartDisplay">
                </div>       
            </div>
          
            <script>
            const ctx = document.getElementById("myChart").getContext("2d");
            const myChart = new Chart(ctx, {
             type: "doughnut",
             data: {
             labels: ["Available", "Current", "Overdraft"],
             datasets: [{
             label: "Account balance",
             data: [0,0,0],
             backgroundColor: [
                         "rgba(255, 99, 132, 1)",
                         "rgba(54, 162, 235, 1)",
                         "rgba(255, 206, 86, 1)"
                     ],
                     borderColor: [
                         "rgba(255, 99, 132, 1)",
                         "rgba(54, 162, 235, 1)",
                         "rgba(255, 206, 86, 1)"
                     ],
                     borderWidth: 1
                 }]
             },
             options: {
             scales: {
                xAxes: [{
                    gridLines: {
                        display:false
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display:false
                    }  
                    
                }]
            }
             }
         });
         </script>
 
 
          <div id="INandOUT">
            <h6 class ="BalanceTitle">Net value</h6>
              <div class = "chartBox3">
                  <canvas id="myChart3"></canvas>
              </div>              
            <div id="chartDisplay3">
            </div>       
          </div>
    
    <script>
    const ctx3 = document.getElementById("myChart3").getContext("2d");
    const myChart3 = new Chart(ctx3, {
     type: "doughnut",
     data: {
     labels: ["In", "Out"],
     datasets: [{
     label: "Account balance",
     data: [0,0],
     backgroundColor: [
                 "rgba(0, 255, 0, 1)",
                 "rgba(255, 0, 0, 1)"
                 
                 
             ],
             borderColor: [
                 "rgba(0, 255, 0, 1)",
                 "rgba(255, 0, 0, 1)"
             ],
             borderWidth: 1
         }]
     },
     options: {
     scales: {
        xAxes: [{
            gridLines: {
                display:false
            }
        }],
        yAxes: [{
            gridLines: {
                display:false
            }  
            
        }]
    }
     }
 });
 </script>
    

          <div id="TransactionsHistory">
              <h6 class ="TransactionTitle">Recent Transactions</h6>
              
            <div id="transDisplay">
            </div>
          </div>
          
          <div id="Timeline">
              <div class = "Filtersection">
                 <script>
                    $(document).ready(function () {

                $("#Fbutton").click(function() {
                var val1 = $("#text1").val();
                var val2 = $("#text2").val();
                $.ajax({
                    type: "POST",
                    url: "post.php",
                    data: { text1: val1, text2: val2 },
                    success: function(response) {
                            $("#chartDisplay2").html(response);
                    }
                    });
                            });
                    });
                </script>
                  <p class="startitle">Start:</p>
                    <input class = "date1" type = "date" id = "text1"></input>  
                  <p class = "endtitle">End:</p>
                    <input  class = "date2" type = "date" id = "text2"></input>  
                    <br>
                    <button class = "filterbutton" id="Fbutton">Filter</button>
                    <button class = "Resetbutton" id="Rbutton">Reset</button>
            </div>
            
              <div class = "chartBox2">
                  <canvas id="myChart2"></canvas>
            </div>              
            <div id="chartDisplay2">
                
            </div> 
          </div>
          
          <script>
            const ctx2 = document.getElementById("myChart2").getContext("2d");
            
            const data = {
                labels: [],
                datasets: [{
                label: "Transactions",
                data: [],
                backgroundColor: "rgba(255, 99, 132, 0.2)",
                borderColor: "rgb(255, 99, 132)",
                borderWidth: 1
            }]
            }; 
            
            const myChart2 = new Chart(ctx2, {
             type: "line",
             data:data
         });
         </script>

          <div id="StandingOrders">
            <h6 class ="TransactionTitle">Standing Orders</h6>
            <div id="StandingOrdersDisplay">
                
            </div>
          </div>  
          
          
          <div id="DirectDebtit">
            <h6 class ="DirectDebtitTitle">Direct Debits</h6>

            <div id="DirectDebtitDisplay">
            </div>
          </div> 
        </div>
        
        
        <div id="Savings">
      
        <div class = "Savingswidgets">
              <p class ="savingtitle">Savings</p>
                <div class = "widgetContainer">
                <button class="open-button" onclick="openForm()"><p class = "newtext">New</p></button>
                    <div id = "innerbanner">
                    
                        <div id ="backgroundcover"></div>
                        <div class="form-popup" id="myForm">
                          <div class="form-container">
                            
                            <script>
                            $(document).ready(function () {
                            $("#savingsdisplay").load("SavingsDisplay.php");

                            $("#Cbutton").click(function() {
                            var ip1 = $("#input1").val();
                            var ip2 = $("#input2").val();
                            var ip3 = $("#input3").val();
                            $.ajax({
                            type: "POST",
                            url: "SavingsCreation.php",
                            data: { input1: ip1, input2: ip2, input3: ip3},
                            success: function(response) {
                                    $("#innerbanner").html(response);
                            }
                            });
                            $("#savingsdisplay").load("SavingsDisplay.php");
                                    });
                            });
                            </script>
                            
                            <h2>Savings creation</h2>
                        
                            <label class = "Nametitle"><b>Widget Name</b></label>
                            <input class = "Nameinput" id = "input1" type="text" placeholder="Name" id="WidgetName" required>
                            
                            <label class = Amounttitle><b>Target Amount</b></label>
                            <input class = "Amountinput" id = "input2" type="text" placeholder="Amount" id="Amount" required>
                            
                            <label class = "Endatetitle"><b>Completion date</b></label>
                            <input class = "EndDateinput" id = "input3" type="date" placeholder="End date" id="Enddate" required>
                        
                            <button type="button" class="Createbtn" id="Cbutton" >Create</button>
                            <button type="button" class="btncancel" onclick="closeForm()">X</button>
                          </div>
                        </div>
                        <script>
                        function openForm() {
                          document.getElementById("myForm").style.display = "block";
                          document.getElementById('backgroundcover').style.display = "block";
                        }
                        
                        function closeForm() {
                          document.getElementById("myForm").style.display = "none";
                          document.getElementById('backgroundcover').style.display = "none";
                        }
                        
                        </script>
                        
                    </div>
                    
                    <div id = "savingsdisplay"> 
                    
                    
                          
                    </div>
                    
                </div>
        </div>
        
         <div class = "widgetinformation">
              <p class ="savingtitle">Top up</p>
              
            <script>
                            $(document).ready(function () {

                            $("#Sbutton").click(function() {
                            var tuinput= $("#topupsdisplay").val();
                            var tuamount= $("#topupamount").val();
                            var accountval= $("#accountselection").val();

                            $.ajax({
                            type: "POST",
                            url: "SavingsTopUpFunction.php",
                            data: { tdvalue: tuinput, tdvalue2: tuamount, tdvalue3: accountval },
                            success: function(response) {
                                    $("#topupmsgdisplay").html(response);
                            }
                            });
                                    });
                            });
                            
                       function sendbtnclick() {
                           
                            $("#savingsdisplay").load("SavingsDisplay.php");
                        }     
                            
            </script>
                            
            <div class = "topupcontainer">
                
              <p class = "profinfo">
              Transfer and top-up your savings widget in three easy step. Transfers can be done anytime, anywhere. 
              <br>
              <br>
              <b>1).</b>Select a saving widget.&nbsp &nbsp &nbsp &nbsp<b>2).</b> Select an account.&nbsp &nbsp &nbsp &nbsp<b>3).</b>Enter an amount.&nbsp &nbsp &nbsp &nbsp<b>4).</b>Send your request. 
              </p>
              <br>
                <p class = "widgettext">Savings widget:</p>
                <select id = "topupsdisplay"> 
                
                </select>
                <br><br>
                <p class = "accounttext">Account:</p>
                <select id = "accountselection"> 
                
                </select>
                  <br><br>
                <p class = "amounttext">Amount:</p>
                 <input type="text" id="topupamount" name="tua" placeholder="£">
                 <br><br>
                 <button type="submit" class="sendbtn" id="Sbutton" onclick="sendbtnclick()">Send</button>
                
                <div id= "topupmsgdisplay"> 
                
                </div>

                </div>
            </div>
            
            
            
            
            
            
            <div class = "widthchart">
              <p class ="savingtitle">Recent Transactions</p>

            </div>
        
        </div>
        
        
        
        
        

        <div id="analytics">
        
            <div id="Operation1">
            <h6 class ="Operation1Title">Operations1</h6>
            
            
            <div id= "operations1gdisplay"> 
                
                </div>
          </div> 


        </div>
        
        
        
        

        <div id="Profile">
          <div class="UserData">

            <form action="includes/update.inc.php" method="post">
              <?php
                if (isset($_SESSION['userId'])) {
                  $id = $_SESSION['userId'];
                  $username = '';
                  $firstname = '';
                  $middlename = '';
                  $lastname = '';
                  $emailaddress = '';
                  $phonenumber = '';
                  $country = '';


                  $sql = "SELECT * FROM personalinformation WHERE idUsers= $id;";

                  $result = mysqli_query($conn, $sql);
                  $resultCheck = mysqli_num_rows($result);
                  if ($resultCheck > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                      $username = $row['uidUsers'];
                      $firstname = $row['firstname'];
                      $middlename = $row['middlename'];
                      $lastname = $row['lastname'];
                      $emailaddress = $row['emailUsers'];
                      $phonenumber = $row['phonenumber'];
                      $dateofbirth = $row['DateOfBirth'];
                      $today = date("y-m-d");
                      $diff = date_diff(date_create($dateofbirth), date_create($today));
                      $age = $diff->format('%y');
                      $country = $row['country'];
                      $gender = $row['Gender'];
                      $postcode = $row['PostCode'];
                      $addressline1 = $row['AddressLine1'];
                      $addressline2 = $row['AddressLine2'];
                    }
                  }}
                  ?>

                      <div class="infodisplay">

                      <h2 class = "minihead1">
                       General
                      </h2>
                      <div class="entry1">
                        <p class="entrytitle">Username: </p>
                        <p class="entrytitle">First Name: </p>
                        <p class="entrytitle">Middle Name: </p>
                        <p class="entrytitle">Last Name: </p>
                      </div>
                      <div class="data1">
                      <input type="text" class = "DialogInput" name="uid" value="<?php echo $username; ?>">
                      <input type="text" class = "DialogInput" name="fname" value="<?php echo $firstname; ?>">
                      <input type="text" class = "DialogInput" name="Mname" value="<?php echo $middlename; ?>">
                      <input type="text" class = "DialogInput" name="Lname" value="<?php echo $lastname; ?>">
                      </div>

                      <h2 class = "minihead4">
                       Personal
                      </h2>
                      <div class="entry2">
                      <p class="entrytitle">Date Of Birth: </p>
                      <p class="entrytitle">Age: </p>
                      <p class="entrytitle">Gender: </p>
                      </div>
                      <div class="data2">
                      <input type="date" class = "DialogInput"  value="<?php echo $dateofbirth; ?>">
                      <input type="text" class = "DialogInput"  value="<?php echo $age; ?>">
                      <select class = "DialogInput" name="Gender">
                        <option class = "first" value=""><?php echo $gender;?></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                      </select>
                      </div>

                      <h2 class = "minihead2">
                       Contact
                      </h2>
                      <div class="entry3">
                        <p class="entrytitle">Email address: </p>
                        <p class="entrytitle">Phone Number: </p>
                      </div>
                      <div class="data3">
                      <input type="text" class = "DialogInput" name="mail" value="<?php echo $emailaddress; ?>">
                      <input type="text" class = "DialogInput" name="phonenum" value="<?php echo $phonenumber; ?>">
                      </div>



                      <h2 class = "minihead3">
                       Location
                      </h2>
                      <div class="entry4">
                        <p class="entrytitle">Country: </p>
                        <p class="entrytitle">PostCode: </p>
                        <p class="entrytitle">Addressline 1: </p>
                        <p class="entrytitle">Addressline 2: </p>

                      </div>
                      <div class="data4">
                      <input type="text" class = "DialogInput" name="country" value="<?php echo $country; ?>">
                      <input type="text" class = "DialogInput" name="PostCode" value="<?php echo $postcode;?>">
                      <input type="text" class = "DialogInput" name="AddressLine1" value="<?php echo $addressline1; ?>">
                      <input type="text" class = "DialogInput" name="AddressLine2" value="<?php echo $addressline2; ?>">
                      </div>
                      </div>
                  <button id = "subbut" type="submit" name="update-submit">Submit<ion-icon class = "icon4" name="send"></ion-icon></button>
            </form>
            <button id = "editbut" onclick="ToggleEdit()"><ion-icon class = "icon2" name="create"></ion-icon>Edit</button>
            <button id = "closebut" onclick="ToggleClose()"><ion-icon class = "icon3" name="close"></ion-icon></button>


          </div>
          <div class="extention">
            <div class="aboutdisplay">
              <h2 class = "mainhead">
               About
              </h2>
              <p class = "profinfo">
              Set your profile and details. Providing additional information can help develop bluh bluh bluh.
              <br>
              <br>
              Your username and profile image represent you throughout bluh bluh , and must be appropiate for all audiences.
              </p>

              <div class="action2">

              <div class="Profile2">
                <img src="photos\defaultUserphoto.png">
              </div>
            </div>


            </div>
          </div>

          <div class="PasswordReset">
            <div class="aboutdisplay">
              <h2 class = "mainhead">
               Password Reset
              </h2>
            </div>
          </div>

          <div class="SocialMedia">
            <div class="aboutdisplay">
            <h2 class = "mainhead">
             Social Media
            </h2>
            <p class = "profinfo">
              Connect with your social media's and share your experience.
            </p>
            </div>
          </div>

        </div>

      </div>


</script>


    </div>

</div>

  </body>
</html>



