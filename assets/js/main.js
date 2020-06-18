document.addEventListener("DOMContentLoaded", function(){
    
    var password = document.querySelector("#password");
    var register_button = document.querySelector("#register")
    var error = document.querySelector(".error");
    var option_team = document.querySelector("#team_code");
    var input_team = document.querySelector("#team")
    

    //Changes into code or new team
    if (option_team != null){
        var option_val = option_team.value;
        option_team.addEventListener("change", function(){
            option_val = option_team.value;
    
            if (option_val == "code"){
                input_team.setAttribute("name", "code");
                input_team.setAttribute("placeholder", "Team code");
            }
            else if (option_val == "newTeam"){
                input_team.setAttribute("name", "team");
                input_team.setAttribute("placeholder", "New team");
            }
        })
    }
    
    //Patterns to validate password
    var lowerkeysPat = /[a-z]/g;
    var upperkeysPat = /[A-Z]/g;
    var numbersPat = /[0-9]/g;

    //Validate security of password
    if (password != null){
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
    }

    //Show modal form
    var modal = document.querySelector(".modalform"),
        clickView = document.querySelector("#showModalform");
    clickView.onclick = function(){
        modal.style.display = "block";
      }
      
      // Get the <span> element that closes the modal
      var span = document.getElementsByClassName("close")[0];
      // When the user clicks on <span> (x), close the modal
      span.onclick = function() {
        modal.style.display = "none";
      }

    
    // Ajax to add and show new projects
    var add_project = document.querySelector("#add_project");
    add_project.addEventListener("click", addProject);

    function addProject(){
        var name        = document.querySelector("#nameP").value,
            description = document.querySelector("#descriptionP").value,
            date        = document.querySelector("#dateP").value,
            newdate     = new Date(date),
            month       = newdate.getMonth() + 1,
            day         = newdate.getDay();

        month = month < 9 ? "0"+ month : month;
        day = day < 9 ? "0"+ day : day;
        var strDate =  month +"-"+ day +"-"+ newdate.getFullYear();  
            

        if (name.length > 0 && date.length > 0){ //If there's data in name and date do the request
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){ //Create column for new project before to send info to database.
                    //document.getElementById("ajaxinfo").innerHTML = this.responseText;

                    var id      = this.responseText,
                        row     = document.querySelector("#projects")
                        col     = document.createElement("div"),
                        project = document.createElement("div"),
                        delet   = document.createElement("span"),
                        title   = document.createElement("h3"),
                        info    = document.createElement("div"),
                        date_p  = document.createElement("p"),
                        button  = document.createElement("a"),
                        icon    = document.createElement("i");
                    
                        console.log(id);
                
                    //Set classes and information to new elements
                    col.setAttribute("class", "col-sm-4");
                    col.setAttribute("id", id)
                    project.setAttribute("class", "project");
                    delet.setAttribute("class", "red-x");
                    delet.setAttribute("data-toggle", "tooltip");
                    delet.setAttribute("title", "Delete project");
                    delet.textContent = "X";
                    delet.addEventListener("click", function(){
                        var idn = this.parentElement.parentElement.id;
                        deleteProject(idn);
                    });
                    title.textContent = name;
                    info.setAttribute("class", "info-project");
                    date_p.textContent = strDate;
                    button.setAttribute("class", "btn btn-primary");
                    button.setAttribute("data-toggle", "tooltip");
                    button.setAttribute("title", "See more");
                    button.setAttribute("class", "btn btn-primary more-project");
                    button.addEventListener("click", function(){
                        var id = this.parentElement.parentElement.parentElement.id;
                        window.location.replace("home.php?page=more&info="+id);
                    });
                    icon.setAttribute("class", "fas fa-angle-double-right");

                    //Insert new project into div.row
                    row.appendChild(col);
                    col.appendChild(project);
                    project.appendChild(delet);
                    project.appendChild(title);
                    project.appendChild(info);
                    info.appendChild(date_p);
                    info.appendChild(button);
                    button.appendChild(icon);

                    modal.style.display = "none";
                }
            };
            xmlhttp.open("GET", "app/functions.php?project=add&name="+name+"&description="+description+"&date="+date, true); //Send data
            xmlhttp.send();
        }
    }

    //Ajax to delete a project
    var delete_project = document.querySelectorAll("#delete-project");
    for(links of delete_project){
        links.addEventListener("click", function(){
            var id = this.parentElement.parentElement.id;
            deleteProject(id);
        });
    }

    function deleteProject(id){    
        var opc = confirm("Do you really want to delete this project ?")
        if (opc == true){
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){ //Create column for new project before to send info to database.
                    var project = document.getElementById(id);
                    project.remove();
                }
            };
            xmlhttp.open("GET", "app/functions.php?project=delete&id="+id, true); //Send data
            xmlhttp.send();
        }
    }

    //See more information about the project you click
    var button_information = document.querySelectorAll(".more-project");
    if (button_information != null){
        for (btn of button_information){
            btn.addEventListener("click", function(){
                var id = this.parentElement.parentElement.parentElement.id;
                window.location.replace("home.php?page=more&info="+id);
            });
        }
    }
    
    
    //Tooltip Bootstrap
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
});

