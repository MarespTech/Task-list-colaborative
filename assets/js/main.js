document.addEventListener("DOMContentLoaded", function(){
    
    var password = document.querySelector("#password");
    var register_button = document.querySelector("#register")
    var error = document.querySelector(".error");

    //Patterns to validate password
    var lowerkeysPat = /[a-z]/g;
    var upperkeysPat = /[A-Z]/g;
    var numbersPat = /[0-9]/g;

    //Validate security of password
    password.onkeyup = function(){
        var password_value = password.value;
        if (password_value.length < 6){ //First Level
            error.textContent = "Password too short";
            error.style.color = "red";
            register_button.setAttribute("disabled", true);
        }
        else { //Second Level
            register_button.removeAttribute("disabled");
            if (password_value.match(upperkeysPat)){  //Third Level
                
                if (password_value.match(numbersPat)){ //Fourth Level
                    error.textContent = "Password stronger";
                    error.style.color = "green";
                }
                else { 
                    error.textContent = "Password strong";
                    error.style.color = "orange";
                }
            }
            error.textContent = "Password OK";
            error.style.color = "yellow";
        }
        error.style.display = "inline";
    }

});