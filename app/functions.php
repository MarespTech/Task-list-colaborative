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
    }while($code == "");
    return $code;
}

//Register new user
if(isset($_POST['register'])){
    //Get values with POST Method
    $username  = strip_tags($_POST['username']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name      = strip_tags($_POST['name']);
    $last_name =  strip_tags($_POST['last_name']);
    $id_team   = 0;
    if(isset($_POST['email'])){
        $email =  strip_tags($_POST['email']);
    }  
    else
        $email = "No E-mail";

    //Validate if the username isn't already exist
    $query = "select * from user where username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if (mysqli_num_rows($result) > 0){
        header("Location: http://localhost/Tasklist/register.php?error=user_exist"); //Return error: user already exist
    }
    else{
    //Check if the user is singing up with code or new team
    if(isset($_POST['team'])){
        //If the user signs up with new team, check if the new team is already exist
        $team = strip_tags($_POST['team']);
        $query = "select * from team where name_team = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $team);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) > 0){
            header("Location: http://localhost/Tasklist/register.php?error=team_exist"); //Return error: user already exist
        }
        else{
            $code = generateCode();
            $query = "Insert into team(name_team, code_team) values(?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ss", $team, $code );
            if (!$stmt->execute()) {
                echo "Error: " . $query . "<br>" . mysqli_error($conn);
            }
            else
                $id_team = $conn->insert_id;
            
        }
    }
    else{ //If the user sings up with code, Check if the code is correct
        $code = strip_tags($_POST['code']);
        $query = "select * from team where code_team = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        if (mysqli_num_rows($result) == 0){
            header("Location: http://localhost/Tasklist/register.php?error=no_code"); //Return error: Code is not correct
        }
        else{
            while($rows = mysqli_fetch_assoc($result))
                $id_team = $rows['id_team'];
        }
    }
    //Insert new user into user table
    $query = "INSERT INTO `user` (`username`, `password`, `name`, `last_name`,`email`, `id_team`) VALUES(?, ?, ?, ?, ? ,?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssss", $username, $password, $name, $last_name, $email, $id_team);
    if (!$stmt->execute()){  
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
        //header("Location: http://localhost/Tasklist/register.php?success=false");
    }
    else{
        $stmt->close();
        header("Location: http://localhost/Tasklist/index.php?signup=success");
    }
        
    }
}
//Log in
if (isset($_POST['login'])){
    //Get username and password
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "Select * from user where username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

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
        $query = "insert into project (name_project, description_project,date_project,id_team) values(?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi",$name,$description,$date,$team);
        if (!$stmt->execute()){
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
        if (isset($_GET['name'])){
            $query .= " name_project = ? where id_project = $id";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_GET['name']);
            $stmt->execute();
        }
        if (isset($_GET['date'])){
            $query .= " date_project = ? where id_project = $id";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_GET['date']);
            $stmt->execute();
        }
        if (isset($_GET['description'])){
            $query .= " description_project = ? where id_project = $id";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_GET['description']);
            $stmt->execute();
        }
        $stmt->close();
    }
    else if ($_GET['project'] == "delete"){
        $id = $_GET['id'];
        $query_task  = "Delete from task where id_project = $id";
        $result_task = mysqli_query($conn, $query_task);
        if (!$result_task)
            echo "Error: " . $query_task . "<br>" . mysqli_error($conn);
        else{
            $query_project = "Delete from project where id_project = $id";
            $result_project = mysqli_query($conn, $query_project);
            if (!$result_project)
                echo "Error: " . $query_project . "<br>" . mysqli_error($conn);
            else
                echo "Project delete";
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
        $team             = $_SESSION['team'];

        $query = "insert into task (description, date, id_person_assign, urgency, id_team, id_project) values(?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssiiii", $task, $date, $id_person_assign, $urgent, $team, $id_project);
        if (!$stmt->execute()){
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
        if (isset($_GET['taskname'])){
            $query .= " description = ? where id_project = $id_task";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_GET['taskname']);
            $stmt->execute();
        }
        if (isset($_GET['personAssign'])){
            $query .= " id_person_assign = ? where id_project = $id_task";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $_GET['personAssign']);
            $stmt->execute();
        }
        if (isset($_GET['date'])){
            $query .= " date = ? where id_project = $id_task";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $_GET['date']);
            $stmt->execute();
        }
        if (isset($_GET['urgent'])){
            $query .= " urgency = ? where id_project = $id_task";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $_GET['urgent']);
            $stmt->execute();
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