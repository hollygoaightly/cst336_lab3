<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up Page</title>
        <link href="css/style.css" rel="stylesheet" type="text/css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </head>
    <body>
        <form id="signupForm" action="welcome.html">
            <h1>Sign Up</h1>
            First Name: <input type="text" name="fName"><br/><br/>
            Last Name: <input type="text" name="lName"><br/><br/>
            <p>
                Gender: <input type="radio" name="gender" value="m">Male<br/>
                <input type="radio" name="gender" value="f">Female<br/>
            </p>
            Zip Code: <input type="text" id="zip" name="zip"><br/><br/>
            City:     <span id="city"></span><br/><br/>
            Latitude: <span id="latitude"></span><br/><br/>
            Longitude:<span id="longitude"></span><br/></br/><br/>
            
            State: 
            <select id="state" name="state">
                <option>Select One</option>
                <option value="ca">California</option>
                <option value="ny">New York</option>
                <option value="tx">Texas</option>
            </select><br/><br/>
            
            Select a County: <select id="county"></select><br/><br/>
            
            Desired Username: <input type="text" id="username" name="username"><br/>
                              <span id="usernameError" class="error"></span><br/><br/>
            Password:         <input type="password" id="password" name="password"><br/>
                              <span id="passwordError" class="error"></span><br/><br/>
            Password Again:   <input type="password" id="passwordAgain"><br/>
                              <span id="passwordAgainError" class="error"></span><br/><br/>
            <input type="submit" value="Sign Up!">
        </form>
        <script>
            // global var
            var usernameAvailable = false;
            var passwordValid = false;
            
            // display city from API after typing a zip code
            $("#zip").on("change", async function(){
               let zipCode = $("#zip").val();
               let url = `https://cst336.herokuapp.com/projects/api/cityInfoAPI.php?zip=${zipCode}`;
               let response = await fetch(url);
               let data = await response.json();
               //console.log(data);
               $("#city").html(data.city);
               $("#latitude").html(data.latitude);
               $("#longitude").html(data.longitude);
            });
            
            // state
            $("#state").on("change", async function(){
               let state = $("#state").val();
               let url = `https://cst336.herokuapp.com/projects/api/countyListAPI.php?state=${state}`;
               let response = await fetch(url);
               let data = await response.json();
               //console.log(data);
               // reset county options
               $("#county").html("<option>Select one</option>")
               for(let i = 0; i < data.length; i++){
                   $("#county").append(`<option> ${data[i].county}</option>`)
               }
            });
            
            // username
            $("#username").on("change", async function(){
               let username = $("#username").val();
               let url = `https://cst336.herokuapp.com/projects/api/usernamesAPI.php?username=${username}`;
               let response = await fetch(url);
               let data = await response.json();
               if(data.available){
                   $("#usernameError").html("Username available!");
                   $("#usernameError").css("color", "green");
                   usernameAvailable = true;
               }
               else{
                   $("#usernameError").html("Username not available!");
                   $("#usernameError").css("color", "red");
                   usernameAvailable = false;
               }
            });
            
            // password
            $("#password").on("change", async function(){
               let password = $("#password").val();
               if(password.length < 6){
                   $("#passwordError").html("Password must be at least 6 characters long!");
                   $("#passwordError").css("color", "red");
                   passwordValid = false;
               }
               else{
                   $("#passwordError").html("");
                   passwordValid = true;
               }
            });
            
            // submit
            $("#signupForm").on("submit", function(event){
               // abort form submit
               if(!isFormValid()){
                   event.preventDefault();
               }
            });
            
            // validation
           function isFormValid(){
                isValid = true;
                if(!usernameAvailable || !passwordValid){
                    isValid = false;
                }
                if(!passwordValid){
                    isValid = false;
                }
                if($("#username").val().length == 0){
                    isValid = false;
                    $("#usernameError").html("Username is required!");
                }
                if($("#password").val().length == 0){
                    isValid = false;
                    $("#passwordError").html("Password is required!");
                }
                if($("#password").val() != $("#passwordAgain").val()){
                    isValid = false;
                    $("#passwordAgainError").html("Password missmatch!");
                }
                return isValid;
            }
            
        </script>
    </body>
</html>