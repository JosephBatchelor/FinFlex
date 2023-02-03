const {AuthAPIClient, DataAPIClient} = require("truelayer-client");
const express = require('express');
const app = express();
const bodyParser = require('body-parser');
var mysql = require('mysql');
const bcrypt = require('bcrypt');
var cookieParser = require('cookie-parser')
var MyApp = {};
const saltRounds = 10;
var urlencodedParser = bodyParser.urlencoded({ extended: false });

//Different view engiens and templates used to rende client design.
app.use(cookieParser());
app.set('view engine', 'ejs');

const redirect_uri = "https://jb1828.brighton.domains/FinalYearProject/truelayer-redirect";
// Create TrueLayer client instance
const client = new AuthAPIClient({
        client_id: "****",
        client_secret: "****"
});

// Define array of permission scopes
const scopes = ["info", "accounts", "balance", "transactions", "offline_access", "cards"]
const mockProviders = "uk-ob-all%20uk-oauth-all%20uk-cs-mock";



//Database config
 const con = mysql.createConnection({
     host: "****",
     database: "****",
     user: "****",
     password: "****",
    })

//User enter details so that they can be used for databse actions.
app.get("/FinalYearProject/Signintemp", function(req, res){
    res.render("form.ejs");
});



app.post("/FinalYearProject/Signintemp",urlencodedParser, function(req, res){
    const data = req.body;
    const username = data.uid;
    const email = data.mail;  
    const password = data.pwd;

    MyApp.UID = data.uid;
    MyApp.Email = email;
    MyApp.password = password;
    
    res.cookie("uidUser",username);
    res.cookie("email",email);

con.connect(function (err) {
    var sql = "SELECT * FROM users WHERE uidUsers= '"+username+"' AND emailUsers= '"+email+"' ;";
    con.query(sql,function(err, row){
        if(row && row.length){
             var hash = row[0].pwdUsers;
             hash = hash.replace(/^\$2y(.+)$/i, '$2a$1');
            bcrypt.compare(password , hash, function(err, result) {
                if(result == true){
                        continue;
                }else{
                    //Incorrect password return
                    res.render("formFail.ejs");
                }
            });
        }else{
            //Invalid credentails
                    res.render("formFail.ejs");
        }
                           res.redirect("https://jb1828.brighton.domains/FinalYearProject/");
          });
 }); 
});


//Truelayer redirect service which takes the users to the data api interface.
app.get("/FinalYearProject", (req, res) => {
    const username =  MyApp.UID;
    const email = MyApp.Email ;
    const password = MyApp.password;
    
       var rand = function() {
    return Math.random().toString(36).substr(2); // remove `0.`
    };

    var token = function() {
    return rand() + rand(); // to make it longer
    };

    const Token_ID = token();

    con.connect(function (err) {
    var sql = "SELECT * FROM personalinformation WHERE uidUsers= '"+username+"' AND emailUsers= '"+email+"';";
    con.query(sql,function(err, row){
        if(row && row.length){
            if(row[0].DataToken == null){
                var sql = "UPDATE personalinformation SET DataToken = '"+Token_ID+"' WHERE uidUsers= '"+username+"' AND emailUsers= '"+email+"' ;";
                con.query(sql); 
            }else{
                
            }
        }else{
            //Invalid credentails
                    res.send("Error:Can not find users");
        }
          const authURL = (`https://auth.truelayer.com/?response_type=code&client_id=ad92fn21-9496f0&nonce=349422181&scope=info%20accounts%20balance%20cards%20transactions%20direct_debits%20standing_orders%20offline_access&redirect_uri=https://jb1828.brighton.domains/FinalYearProject/truelayer-redirect&providers=uk-ob-all%20uk-oauth-all%20uk-cs-mock`);
           
              res.redirect(authURL);  

    });
 }); 
});



// Retrieve 'code' query-string param, exchange it for access token and hit data api
app.get("/FinalYearProject/truelayer-redirect", async (req, res) => {
      
    try {
    const code = req.query.code;
    const tokens = await client.exchangeCodeForToken(redirect_uri, code);
    
    const accounts = await DataAPIClient.getAccounts(tokens.access_token);
    var User_Account = JSON.stringify(accounts,null, 2);
    const obj = JSON.parse(User_Account);
    let size = accounts.results.length-1;
    const token = null; 

con.connect();

//   var sql = "SELECT * FROM personalinformation WHERE uidUsers= '"+req.cookies.uidUser+"' AND emailUsers= '"+req.cookies.email+"';";
//           con.query(sql,function(err, row){
//              row[0].DataToken
//     }); 
    
    
    function gettoken() {
        return new Promise(function(resolve, reject) {
            var sql = "SELECT * FROM personalinformation WHERE uidUsers= '"+req.cookies.uidUser+"' AND emailUsers= '"+req.cookies.email+"';";
            con.query(sql,function(err, row){
               if (err) return reject(err);
                 resolve(row[0].DataToken);
        });
    });
}

(async function(){
    let T = await gettoken();

    // account data
    for (let i = 0; i <= size ; i++) {
        const Account_id = obj.results[i].account_id;
        
        var sql = "SELECT * FROM accounts WHERE account_id= '"+Account_id+"' AND DataToken = '"+T+"';";
          con.query(sql,function(err, row){
            if(row && row.length){
            
            }else{
              const account_type = obj.results[i].account_type;
              const display_name = obj.results[i].display_name;
              const currency = obj.results[i].currency;
          
              const iban = obj.results[i].account_number.iban;
              const swift_bic = obj.results[i].account_number.swift_bic;
              const number = obj.results[i].account_number.number;
              const sort_code = obj.results[i].account_number.sort_code;
          
              const Bank_display_name = obj.results[i].provider.display_name;
              const provider_id = obj.results[i].provider.provider_id;
              const Num = obj.results[i].provider.number;
              const logo_uri = obj.results[i].provider.logo_uri;
                var sql = "INSERT into accounts (DataToken ,Account_id, account_type, display_name, currency, iban, swift_bic , Account_number, Bank_name ,provider_id) values ('"+T+"','"+Account_id+"','"+account_type+"','"+display_name+"','"+currency+"' ,'"+iban+"' ,'"+swift_bic+"' ,'"+number+"', '"+Bank_display_name+"', '"+provider_id+"');";
               // con.query(sql);
            }
          });
          continue;
    }

    //balance
    for (let x = 0; x <= size ; x++) {
        const balance = await DataAPIClient.getBalance(tokens.access_token, accounts.results[x].account_id);
        var User_balance = JSON.stringify(balance,null, 2);
        const Balance_obj = JSON.parse(User_balance);
        const Account_id = obj.results[x].account_id;
        
        
         var sql = "SELECT * FROM Balance WHERE account_id= '"+Account_id+"' AND DataToken = '"+T+"';";
          con.query(sql,function(err, row){
            if(row && row.length){
            
            }else{
               
                const Currency = Balance_obj.results[0].currency;
                const available = Balance_obj.results[0].available;
                const Current = Balance_obj.results[0].current;
                const overdraft = Balance_obj.results[0].overdraft; 
               
                var sql = "INSERT into Balance (DataToken,Account_id,currency, available, current, overdraft) values ('"+T+"','"+Account_id+"','"+Currency+"','"+available+"','"+Current+"','"+overdraft+"');";
              con.query(sql);
            }
          });
          continue;
    } 

    // transactions
    for (let x = 0; x <= size ; x++) {
        const transaction = await DataAPIClient.getTransactions(tokens.access_token, accounts.results[x].account_id);
        var User_transaction = JSON.stringify(transaction);
        const transaction_obj = JSON.parse(User_transaction);
        const Account_id = obj.results[x].account_id;

        for (let y = 0; y <= 100; y++) {   
            
        const transaction_id = transaction_obj.results[y].transaction_id;
            
        var sql = "SELECT * FROM transactions WHERE account_id= '"+Account_id+"' AND transaction_id= '"+transaction_id+"' AND DataToken = '"+T+"';";
          con.query(sql,function(err, row){
            if(row && row.length){
            
            }else{
                const account_amount = transaction_obj.results[y].running_balance.amount;
                const account_currency = transaction_obj.results[y].running_balance.currency;
                const description = transaction_obj.results[y].description;
                const transaction_amount = transaction_obj.results[y].amount;
                const transaction_category = transaction_obj.results[y].transaction_category;
                const transaction_type = transaction_obj.results[y].transaction_type;
                const timestamp = transaction_obj.results[y].timestamp;
               
                var sql = "INSERT into transactions (DataToken, Account_id, account_amount, account_currency, description, transaction_amount, transaction_category, transaction_id, transaction_type, DateOfTransaction) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?);";
              con.query(sql,[T, Account_id, account_amount, account_currency, description, transaction_amount, transaction_category, transaction_id, transaction_type, timestamp]);
            }
          });
        }
    } 
    
    // Pendingtransactions
    for (let x = 0; x <= size ; x++) {
        const PendingTransactions = await DataAPIClient.getPendingTransactions(tokens.access_token, accounts.results[x].account_id)
        const User_PendingTransactions= JSON.stringify(PendingTransactions,null, 2);
        const PendingTransactions_obj = JSON.parse(User_PendingTransactions);
        const Account_id = obj.results[x].account_id;
        let PTsize = PendingTransactions.results.length-1;

       for (let y = 0; y <= PTsize; y++) {   
            
        const Transaction_id = PendingTransactions_obj.results[y].transaction_id;

        var sql = "SELECT * FROM PendingTransactions WHERE account_id= '"+Account_id+"' AND transaction_id= '"+Transaction_id+"' AND DataToken = '"+T+"';";
         con.query(sql,function(err, row){
            if(row && row.length){
            
            }else{
              const MerchantName = PendingTransactions_obj.results[y].merchant_name;
              const Currency = PendingTransactions_obj.results[y].currency;
              const Description = PendingTransactions_obj.results[y].description;
              const Transaction_amount = PendingTransactions_obj.results[y].amount;
              const Transaction_category = PendingTransactions_obj.results[y].transaction_category;
              const Transaction_type = PendingTransactions_obj.results[y].transaction_type;
              const DateOfTransaction = PendingTransactions_obj.results[y].timestamp;
               
            var sql = "INSERT into PendingTransactions (DataToken, Account_id, merchant_name, currency, description, transaction_amount, transaction_category, transaction_id, transaction_type, DateOfTransaction) VALUES (?,?,?,?,?,?,?,?,?,?);";

              con.query(sql,[T, Account_id, MerchantName, Currency, Description, Transaction_amount, Transaction_category, Transaction_id, Transaction_type, DateOfTransaction]);
           }
          });
        }
    } 
    
    
    // Standing Orders
    for (let x = 0; x <= size ; x++) {
      const StandingOrders = await DataAPIClient.getStandingOrders(tokens.access_token, accounts.results[x].account_id);
      const User_StandingOrders= JSON.stringify(StandingOrders,null, 2);
      const StandingOrders_obj = JSON.parse(User_StandingOrders);
      let SOsize = StandingOrders.results.length-1;
      const Account_id = obj.results[x].account_id;

        for (let y = 0; y <= SOsize; y++) {   
            
        const Provider_account_id = StandingOrders_obj.results[y].meta.provider_account_id;
            
        var sql = "SELECT * FROM StandingOrders WHERE account_id= '"+Account_id+"' AND provider_account_id= '"+Provider_account_id+"' AND DataToken = '"+T+"';";
          con.query(sql,function(err, row){
            if(row && row.length){
            
            }else{
            const Currency = StandingOrders_obj.results[y].currency;
            const Final_payment_amount = StandingOrders_obj.results[y].final_payment_amount;
            const Final_payment_date = StandingOrders_obj.results[y].final_payment_date;
            const First_payment_amount = StandingOrders_obj.results[y].first_payment_amount;
            const First_payment_date = StandingOrders_obj.results[y].first_payment_date;
            const Frequency = StandingOrders_obj.results[y].frequency;
            const Next_payment_amount = StandingOrders_obj.results[y].next_payment_amount;
            const Next_payment_date = StandingOrders_obj.results[y].next_payment_date;
            const Reference = StandingOrders_obj.results[y].reference;
            const Status = StandingOrders_obj.results[y].status;

            var sql = "INSERT into StandingOrders (DataToken, Account_id, provider_account_id, currency, final_payment_amount, final_payment_date, first_payment_amount, first_payment_date, frequency, next_payment_amount, next_payment_date, reference, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            con.query(sql,[T, Account_id, Provider_account_id, Currency, Final_payment_amount, Final_payment_date, First_payment_amount, First_payment_date, Frequency, Next_payment_amount, Next_payment_date, Reference, Reference, Status]);
            }
          });
        }
    } 
    
  
  
  //DirectDebits
  for (let x = 0; x <= size ; x++) {
      
      const DirectDebits = await DataAPIClient.getDirectDebits(tokens.access_token, accounts.results[x].account_id);
      const User_DirectDebits= JSON.stringify(DirectDebits,null, 2);
      const DirectDebits_obj = JSON.parse(User_DirectDebits);
      
      let DBsize = DirectDebits.results.length-1;
      const Account_id = obj.results[x].account_id;

        for (let y = 0; y <= DBsize; y++) {   
            
        const direct_debit_id = DirectDebits_obj.results[y].direct_debit_id;
            
       var sql = "SELECT * FROM DirectDebt WHERE account_id= '"+Account_id+"' AND direct_debit_id= '"+direct_debit_id+"' AND DataToken = '"+T+"';";
          con.query(sql,function(err, row){
            if(row && row.length){
            
            }else{
                
        const name = DirectDebits_obj.results[y].name;
        const status = DirectDebits_obj.results[y].status;
        const previous_payment_amount = DirectDebits_obj.results[y].previous_payment_amount;
        const currency = DirectDebits_obj.results[y].currency;
        const previous_payment_timestamp = DirectDebits_obj.results[y].previous_payment_timestamp;
        const provider_account_id = DirectDebits_obj.results[y].meta.provider_account_id;
        const provider_mandate_identification = DirectDebits_obj.results[y].meta.provider_mandate_identification;
            

          var sql = "INSERT into DirectDebt (DataToken, Account_id, direct_debit_id, name, status, previous_payment_amount, currency, previous_payment_timestamp, provider_account_id, provider_mandate_identification) VALUES (?,?,?,?,?,?,?,?,?,?)";
            con.query(sql,[T, Account_id, direct_debit_id, name, status, previous_payment_amount, currency,previous_payment_timestamp, provider_account_id, provider_mandate_identification]);
            
            }
          });
        }
    }
      
        
    
    
})();  
 
        
    res.clearCookie("uidUser");
    res.clearCookie("email");
    
   res.redirect("https://jb1828.brighton.domains/FinalYearProject/display");

    }catch (error){
        res.send("ERROR: 404"); //Not good
    }   
});



app.get("/FinalYearProject/display", (req, res)=>{
     
    res.render("Complete.ejs");
});




app.listen();


