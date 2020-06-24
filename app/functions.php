<?php
session_start();

require_once 'dbConnect.php';
global $conn;

function generateCode() {
    global $conn;
    do{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $code = '';
    for ($i = 0; $i < 5; $i++) {
        $code .= $characters[rand(0, $charactersLength - 1)];
    }

    //Validate that code doesn't exist
    $query = "select * from team where code_team = '$code'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0)
        $code = "";
    else
        echo $code;

    }while($code == "");
    return $code;
}

//Register new user
if(isset($_POST['register'])){
    //Get values with POST Method
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    if(isset($_POST['email']))
        $email = $_POST['email'];
    else
        $email = "No E-mail";

    //Validate if the username isn't already exist
    $query = "select * from user where username = '$username'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0){
        header("Location: http://localhost/Tasklist/register.php?error=user_exist"); //Return error: user already exist
    }
    else{
    //Check if the user is singing up with code or new team
    if(isset($_POST['team'])){
        //If the user signs up with new team, check if the new team is already exist
        $team = $_POST['team'];
        $query = "select * from team where name_team = '$team'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) > 0){
            header("Location: http://localhost/Tasklist/register.php?error=team_exist"); //Return error: user already exist
        }
        else{
            $code = generateCode();
            echo "El codigo es $code";
            $query = "Insert into team(name_team, code_team) values('$team', '$code')";
            $result = mysqli_query($conn, $query);
            if (!$result) {
                echo "Error: " . $query . "<br>" . mysqli_error($conn);
            }
        }
    }
    else{ //If the user sings up with code, Check if the code is correct
        $code = $_POST['code'];
        $query = "select * from team where code_team = '$code'";
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) == 0){
            header("Location: http://localhost/Tasklist/register.php?error=no_code"); //Return error: Code is not correct
        }
        else{
            while($rows = mysqli_fetch_assoc($result))
                $team = $rows['name_team'];
        }
    }
    //Insert new user into user table
    $query = "INSERT INTO `user` (`username`, `password`, `name`, `last_name`,`email`, `id_team`) select '$username', '$password', '$name', '$last_name', '$email' ,id_team from team where name_team like '%$team%'";
    $result = mysqli_query($conn, $query);
    if (!$result)  
        header("Location: http://localhost/Tasklist/register.php?success=false");
    else
        header("Location: http://localhost/Tasklist/index.php?signup=success");
    }
}

//Log in
if (isset($_POST['login'])){
    //Get username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "Select * from user where username = '$username'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)){
            $password_db = $row['password'];

            if (password_verify($password, $password_db)){
                $_SESSION["user_log"] = 1;
                $_SESSION["user"] = $username;
                $_SESSION['id_user'] = $row['id_user'];
                $_SESSION["team"] = $row['id_team'];
                header("Location: http://localhost/Tasklist/home.php");
            }
            else
                header("Location: http://localhost/Tasklist/index.php?error=wrong_data");
        }
    }
    else
        header("Location: http://localhost/Tasklist/index.php?error=no_user");

}

//Add, edit and Delete projects
if (isset($_GET['project'])){
    if ($_GET['project'] == "add"){
        $name = $_GET['name'];
        $description = $_GET['description'];
        $date = $_GET['date'];
        $team = $_SESSION['team'];
        $query = "insert into project (name_project, description_project,date_project,id_team) values('$name', '$description', '$date', $team)";
        $result = mysqli_query($conn, $query);
        if (!$result){
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
        else{
            $last_id = mysqli_insert_id($conn);
            echo $last_id;
        }
    }
    else if ($_GET['project'] == "edit"){
        $id     = $_GET['id'];
        $query  = "update project set  ";
        if (isset($_GET['name']))
            $query .= " name_project = '". $_GET['name'] . "',";
        if (isset($_GET['date']))
            $query .= " date_project = '". $_GET['date'] . "',";
        if (isset($_GET['description']))
            $query .= " description_project = '". $_GET['description'] . "',";

        $query = substr_replace($query, "", -1);
        $query .= " where id_project = $id";
        $result = mysqli_query($conn, $query);
        if (!$result){
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
        else{
            echo "Project updated";
        }
    }
    else if ($_GET['project'] == "delete"){
        $id = $_GET['id'];
        $query = "delete from project where id_project = $id";
        $result = mysqli_query($conn, $query);
        if (!$result){
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
        else{
            $last_id = mysqli_insert_id($conn);
            echo $last_id;
        }
    }
    else if ($_GET['project'] == "complete"){
        $id_project    = $_GET['id_project'];
        $query  = "update project set complete = 1 where id_project = $id_project";
        $result = mysqli_query($conn, $query);
        if ($result){
            echo "done";
        } 
    }
}

//Add, edit and delete tasks
if (isset($_GET['task'])){
    if ($_GET['task'] == "add"){
        $task             = $_GET['name'];
        $id_person_assign = $_GET['personAssign'];
        $date             = $_GET['date'];
        $urgent           = $_GET['urgent'];
        $id_project       = $_GET['id_project'];
        $team = $_SESSION['team'];

        $query = "insert into task (description, date, id_person_assign, urgency, id_team, id_project) values('$task', '$date', '$id_person_assign', '$urgent', '$team', '$id_project')";
        $result = mysqli_query($conn, $query);
        if (!$result){
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
        else{
            $last_id = mysqli_insert_id($conn);
            echo $last_id;
        }
    }
    else if ($_GET['task'] == "edit"){
        $id_task          = $_GET['id_task'];

        $query  = "update task set  ";
        if (isset($_GET['taskname']))
            $query .= " description = '". $_GET['taskname'] . "',";
        if (isset($_GET['personAssign']))
            $query .= " id_person_assign = '". $_GET['personAssign'] . "',";
        if (isset($_GET['date']))
            $query .= " date = '". $_GET['date'] . "',";
        if (isset($_GET['urgent']))
            $query .= " urgency = '". $_GET['urgent'] . "',";

        $query = substr_replace($query, "", -1);
        $query .= " where id_task = $id_task";
        $result = mysqli_query($conn, $query);
        if (!$result){
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
        else{
            echo "Task updated";
        }
    }
    else if ($_GET['task'] == "delete"){
        $id_task    = $_GET['id_task'];
        $query  = "delete from task where id_task = $id_task";
        $result = mysqli_query($conn, $query);
        if ($result){
            echo "done";
        } 
    }
    else if ($_GET['task'] == "complete"){
        $id_task    = $_GET['id_task'];
        $query  = "update task set complete = 1 where id_task = $id_task";
        $result = mysqli_query($conn, $query);
        if ($result){
            echo "done";
        } 
    }
        
}


?>