document.addEventListener("DOMContentLoaded", function(){
    
    var password = document.querySelector("#password");
    var register_button = document.querySelector("#register")
    var error = document.querySelector(".error");
    var option_team = document.querySelector("#team_code");
    var input_team = document.querySelector("#team")
    var id_task = 0;
    

    //**********************************************************************  Changes into code or new team  ***********************************************************************//
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
    
    //**********************************************************************  Patterns to validate password  ***********************************************************************//
    var lowerkeysPat = /[a-z]/g;
    var upperkeysPat = /[A-Z]/g;
    var numbersPat = /[0-9]/g;
    
    //********************************************************************** Validate security of password  ***********************************************************************//
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

    //**********************************************************************       Show modal form          ***********************************************************************//
    var modal = document.querySelectorAll(".modalform"),
        clickView = document.querySelectorAll("#showModalform");
    if (clickView != null){
        for (cv of clickView){
            cv.onclick = function(){
                var childModal = this.firstElementChild;
                if (childModal != null)
                    var modaltype = childModal.id;
                else
                    var modaltype = "add-project";
                if (modaltype == "add-project"){
                   modal[0].style.display = "block";
                }else{
                    modal[1].style.display = "block";
                }

                
            }
        }
        
    }
      
    //**********************************************************************  Adding Events ***********************************************************************//
      // Close Modal
      var span = document.getElementsByClassName("close");
      if (span != null){
        for (sp of span){
           sp.onclick = function() {
            modal[0].style.display = "none";
            modal[1].style.display = "none";
            addModalView();
            cleanModal();
        } 
        }
        
      }
      

    // Ajax to add and show new projects
    var add_project = document.querySelector("#add_project");
    if (add_project != null)
        add_project.addEventListener("click", addProject);
  
    //Ajax to delete a project
    var delete_project = document.querySelectorAll("#delete-project");
    for(links of delete_project){
        links.addEventListener("click", function(){
            id = this.getAttribute("data-id");
            deleteProject(id);
        });
    }

    //Button to edit project
    var edit_project = document.querySelectorAll("#edit_project");
    for (edit of edit_project){
        edit.addEventListener("click", function(){
            id = this.getAttribute("data-id");
            editProject(id);
        });
    }

    //Button to complete project
    var complete_project = document.querySelectorAll("#completeProject");
    for (comp of complete_project){
        comp.addEventListener("click", completeProject);
    }
    
    
    //See more information about the project you click
    var button_information = document.querySelectorAll(".more-project");
    if (button_information != null){
        for (btn of button_information){
            btn.addEventListener("click", function(){
                var id = this.id;
                console.log(id);
                window.location.replace("home.php?page=more&info="+id);
            });
        }
    }
    
    //Tasks
    // Ajax to add and show new tasks
    var add_task = document.querySelector("#add_task");
    if (add_task != null)
        add_task.addEventListener("click", addTask, true);

    //Button to edit task
    edit_task = document.querySelectorAll("#edit");
    for (x = 0; x < edit_task.length; x++){
        edit_task[x].addEventListener("click", editViewModal);
    }
    //Button to complete task
    complete_task = document.querySelectorAll("#complete");
    for (x = 0; x < complete_task.length; x++){
        complete_task[x].addEventListener("click", completeTask);
    }


    /*** FUNCTIONS ***/
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

                    modal[0].style.display = "none";
                }
            };
            xmlhttp.open("GET", "app/functions.php?project=add&name="+name+"&description="+description+"&date="+date, true); //Send data
            xmlhttp.send();
        }
    }
    function editProject(id){
        var name        = document.querySelector("#name-project").value,
            date        = document.querySelector("#date-project").value,
            description = document.querySelector("#description-project").value;

            newdate          = new Date(date),
            month            = newdate.getMonth() + 1,
            day              = newdate.getDate() + 1; //I don't know why but it returns me one day less than what I select 

        month = month <= 9 ? "0"+ month : month;
        day = day <= 9 ? "0"+ day : day;
        var strDate =  month +"/"+ day +"/"+ newdate.getFullYear();  

        url = "app/functions.php?project=edit&id="+id;
            //Edit information
            if (name != "")
                url += "&name="+name;
           if (date != "")
               url += "&date="+date;
           if (description != "")
                url += "&description="+description;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){
                if (name != "")
                    document.querySelector(".project-name").textContent = name;
                if (date != "")
                    document.querySelector(".project-date").textContent = strDate;
                if (description != "")
                    document.querySelector(".project-descript").textContent = description;

                modal[0].style.display = "none";
            }
        };
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
    function deleteProject(id){
        var opc = confirm("Do you really want to delete this project ?");
        if (opc == true){
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){ //Create column for new project before to send info to database.
                    var project = document.getElementById(id);
                    if (project != null)
                        project.parentElement.parentElement.parentElement.remove();
                    else
                        window.location.href = "home.php?page=projects";
                }
            };
            xmlhttp.open("GET", "app/functions.php?project=delete&id="+id, true); //Send data
            xmlhttp.send();
        }
    }
    function completeProject(){
        id      = this.getAttribute("data-id");
        var opc = confirm("Do you really want to complete this task ?");
        if (opc == true){
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){ //Create column for new project before to send info to database.
                    document.querySelector(".project-date span").setAttribute("class", "complete");
                    console.log(this.responseText)
                }
            };
            xmlhttp.open("GET", "app/functions.php?project=complete&id_project="+id, true); //Send data
            xmlhttp.send();
        }
    }
    function addTask(){
        var id_project       = document.querySelector(".project-name").getAttribute("data-id"),
            task             = document.querySelector("#new-task").value,
            id_person_assign = document.querySelector("#person-assign").value,
            persons          = document.querySelector("#person-assign"),
            person           = persons.options[persons.selectedIndex].text,
            urgent           = document.getElementsByName('urgent'),
            date             = document.querySelector("#date-task").value,
            newdate          = new Date(date),
            month            = newdate.getMonth() + 1,
            day              = newdate.getDate();

        for (x = 0; x<urgent.length; x++){
            if (urgent[x].checked){
                var urgent_val = urgent[x].value;
            }
        }
        month = month < 9 ? "0"+ month : month;
        day = day < 9 ? "0"+ day : day;
        var strDate =  month +"/"+ day +"/"+ newdate.getFullYear();  
        if (task.length > 0 && date.length > 0){ //If there's data in name and date do the request
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){ //Create column for new project before to send info to database. 
                    var id          = this.responseText,
                        row         = document.querySelector("#tasks")
                        div_task    = document.createElement("div"),
                        description = document.createElement("p"),
                        assign      = document.createElement("p"),
                        finish_date = document.createElement("p"),
                        urgency     = document.createElement("p"),
                        edit_div    = document.createElement("div"),
                        button      = document.createElement("span"),
                        icon        = document.createElement("i");
                
                    //Set classes and information to new elements
                    div_task.setAttribute("class", "row");
                    div_task.setAttribute("id", id);
                    description.setAttribute("class", "col-sm-3");
                    description.textContent = task;
                    assign.setAttribute("class", "col-sm-3");
                    assign.textContent = "Assign to: "+person;
                    finish_date.setAttribute("class", "col-sm-2");
                    finish_date.textContent = "Finish date: "+ strDate;
                    if (urgent_val == 1){
                        urgency.setAttribute("class", "col-sm-2 blink warning");
                        urgency.textContent = "This task is urgent !";
                    }
                    else{
                        urgency.setAttribute("class", "col-sm-2");
                        urgency.textContent = "This task is not urgent.";
                    }
                    edit_div.setAttribute("class", "col-sm-2");
                    edit_div.setAttribute("style", "margin-bottom: 15px");
                    button.setAttribute("class", "btn btn-warning");
                    button.setAttribute("data-toggle", "tooltip");
                    button.setAttribute("title", "Edit");
                    button.setAttribute("id", "edit");
                    button.addEventListener("click", editViewModal)
                    icon.setAttribute("class", "fas fa-pen")


                    //Insert new project into div.row
                    row.appendChild(div_task);
                    div_task.appendChild(description);
                    div_task.appendChild(assign);
                    div_task.appendChild(finish_date);
                    div_task.appendChild(urgency);
                    div_task.appendChild(edit_div);
                    edit_div.appendChild(button);
                    button.appendChild(icon);

                    button      = document.createElement("span"),
                    icon        = document.createElement("i");
                    button.addEventListener("click", completeTask);
                    button.setAttribute("class", "btn btn-success")
                    icon.setAttribute("class", "fas fa-check")
                    edit_div.appendChild(button);
                    button.appendChild(icon);
                    
                    cleanModal()
                    modal[1].style.display = "none";
                }
            };
            xmlhttp.open("GET", "app/functions.php?task=add&name="+task+"&personAssign="+id_person_assign+"&date="+date+"&urgent="+urgent_val+"&id_project="+id_project, true); //Send data
            xmlhttp.send();
        }
    }
    function editTask(id_task){
        var task             = document.querySelector("#new-task").value,
            id_person_assign = document.querySelector("#person-assign").value,
            persons          = document.querySelector("#person-assign"),
            person           = persons.options[persons.selectedIndex].text,
            urgent           = document.getElementsByName('urgent'),
            date             = document.querySelector("#date-task").value,
            urgent_val       = "";
            newdate          = new Date(date),
            month            = newdate.getMonth() + 1,
            day              = newdate.getDate() + 1; //I don't know why but it returns me one day less than what I select, So I add 1
            url = "";

        for (x = 0; x<urgent.length; x++){
            if (urgent[x].checked){
                urgent_val = urgent[x].value;
            }
        }

        month = month < 9 ? "0"+ month : month;
        day = day < 9 ? "0"+ day : day;
        var strDate =  month +"/"+ day +"/"+ newdate.getFullYear();  

        url = "app/functions.php?task=edit&id_task="+id_task;
                //Edit information
                if (task != "")
                    url += "&taskname="+task;
                if (id_person_assign != 0)
                    url += "&personAssign="+id_person_assign;
               if (date != "")
                   url += "&date="+date;
               if (urgent_val != "")
                    url += "&urgent="+urgent_val;
               
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function(){
            if (this.readyState == 4 && this.status == 200){ //Create column for new project before to send info to database. 
                var taskRow = document.getElementById(id_task),
                    data = taskRow.querySelectorAll("p");
                
                    //Edit information
                if (task != "")
                    data[0].textContent = task;
                if (id_person_assign != 0)
                    data[1].textContent = "Assign to: "+person;
               if (date != "")
                   data[2].textContent = "Finish date: " + strDate;
               if (urgent_val != ""){
                    if (urgent_val == 1){
                        data[3].setAttribute("class", "col-sm-2 blink warning");
                        data[3].textContent = "This task is urgent !";
                    }
                    else{
                        data[3].setAttribute("class", "col-sm-2");
                        data[3].textContent = "This task is not urgent";
                    }
               }
               console.log(this.responseText);
               
                addModalView();
                cleanModal();
                modal[1].style.display = "none";
            }
            
            
            
        };
        xmlhttp.open("GET", url , true); //Send data
        xmlhttp.send();
    }
    function deleteTask(id_task){
        var opc = confirm("Do you really want to delete this task ?");
        if (opc == true){
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){ //Create column for new project before to send info to database.
                    var task = document.getElementById(id_task);
                    task.remove();
                }
            };
            xmlhttp.open("GET", "app/functions.php?task=delete&id_task="+id_task, true); //Send data
            xmlhttp.send();

            addModalView();
            cleanModal();
            modal[1].style.display = "none";
        }
    }
    function completeTask(){
        id_task    = this.parentElement.parentElement.id;
        var opc = confirm("Do you really want to complete this task ?");
        if (opc == true){
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200){ //Create column for new project before to send info to database.
                    var task        = document.getElementById(id_task);
                        urgent_task = task.querySelectorAll("p");

                    urgent_task[3].setAttribute("class", "col-sm-2 complete");
                }
            };
            xmlhttp.open("GET", "app/functions.php?task=complete&id_task="+id_task, true); //Send data
            xmlhttp.send();
        }
         
    }
    function editViewModal(){
        modal[0].style.display = "block";
            id_task    = this.parentElement.parentElement.id;
        var id_project = document.querySelector(".flex-container h2").getAttribute("id");
            
        update = document.querySelector("#add_task");
        //Change button of add task to update edit
        update.setAttribute("name", "edit_task");
        update.setAttribute("value", "Edit Task");
        update.setAttribute("class", "btn btn-warning");
        update.removeEventListener("click", addTask, true);
        update.addEventListener("click", function(){
            editTask(id_task);
        }, true);

        //Add button to delete task
        var delete_task = document.createElement("a");
        delete_task.setAttribute("id", "delete-task");
        delete_task.setAttribute("data-toggle", "tooltip");
        delete_task.setAttribute("title", "Delete Task");
        delete_task.setAttribute("class", "btn btn-danger");
        delete_task.setAttribute("style", " margin-top: 5px;");
        delete_task.addEventListener("click", function(){
            deleteTask(id_task);
        });
        update.parentElement.appendChild(delete_task);

        var icon_delete = document.createElement("i");
        icon_delete.setAttribute("class", "fas fa-trash-alt");
        delete_task.appendChild(icon_delete);
    }
    function addModalView(){
        update = document.querySelector("#add_task");
        if (update.getAttribute("name") == "edit_task"){
            update.setAttribute("name", "add_task");
            update.setAttribute("class", "btn btn-primary");
            update.setAttribute("value", "Add Task");
            update.removeEventListener("click", function(){
                editTask(id_task);
            }, true);
            update.addEventListener("click", addTask, true);
        }
        delete_task = document.querySelector("#delete-task");
        if (delete_task != null){
            delete_task.remove();
        }
    }
    function cleanModal(){
        document.querySelector(".project-name").getAttribute("id"),
        document.querySelector("#new-task").value      = ""
        document.querySelector("#date-task").value     = ""
    }
    //Tooltip Bootstrap
    $('[data-toggle="tooltip"]').tooltip();

});

