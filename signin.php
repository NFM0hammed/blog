<?php

    session_start();

    if(isset($_SESSION["user"])) {

        header("Location: index.php");

        exit();

    }

    $title = "دخول";

    include "init.php";

?>

<!-- Start sign in form -->
<div class="container">
    <div class="form sign-in">
        <h1 class="signup-signin">Login</h1>
        <input class="signin_email"    type="text"     placeholder="Email" />
        <input class="signin_password" type="password" placeholder="Password" />
        <input class="signin_submit"   type="submit"   id="register" value="Sign in" />
    </div>
</div>

<script>
    /*
        Sign in Ajax
    */
    let signin_email    =  document.querySelector(".sign-in .signin_email"),
        signin_password =  document.querySelector(".sign-in .signin_password"),
        signin_submit   =  document.querySelector(".sign-in .signin_submit"),
        emptyEmail      =  document.createElement("div"),
        emptyPassword   =  document.createElement("div"),
        failedLogin     =  document.createElement("div");


    // Create request Ajax
    let request = new XMLHttpRequest();

    signin_submit.addEventListener("click", () => {

        request.onreadystatechange = function() {

            if(this.readyState === 4 && this.status === 200) {

                if(signin_email.value == "") {

                    emptyEmail.style.display = "block";
            
                    emptyEmail.className = "error";
            
                    emptyEmail.textContent = "The email is empty !";
            
                    signin_email.before(emptyEmail);
                    
                } else {
            
                    emptyEmail.style.display = "none";
            
                }
            
                if(signin_password.value == "") {
            
                    emptyPassword.style.display = "block";
            
                    emptyPassword.className = "error";
            
                    emptyPassword.textContent = "The password is empty !";
            
                    signin_password.before(emptyPassword);
                    
                } else {
            
                    emptyPassword.style.display = "none";
            
                }

                if(signin_email.value != "" && signin_password.value != "") {

                    if(this.responseText == 1) {
        
                        // Redirect to home page
                        window.location.replace("index.php");

                    } else {
        
                        failedLogin.style.display = "block";

                        failedLogin.className = "error";

                        failedLogin.textContent = "Username or password isn't correct !";

                        signin_email.before(failedLogin);
        
                    }

                } else {

                    failedLogin.style.display = "none";

                }

            }

        }

        // Make a request
        request.open("POST", "ajax/signin.php", true);

        request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Send the request
        request.send(`email=${signin_email.value}&password=${signin_password.value}`);

    });
    
</script>