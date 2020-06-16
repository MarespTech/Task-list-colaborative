<?php
session_start();

require_once 'dbConnect.php';
global $conn;

//Register new user
if(isset($_POST['register'])){
    //Get values with POST Method
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $team = $_POST['team'];
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
    //Validate if the team exist, if it doesn't exist, create it
    $query = "select * from team where name_team like '%$team%'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0){
        $query = "Insert into team(name_team) values('$team')";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
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
                header("Location: http://localhost/Tasklist/home.php");
            }
            else
                header("Location: http://localhost/Tasklist/index.php?error=wrong_data");
        }
    }
    else
        header("Location: http://localhost/Tasklist/index.php?error=no_user");

}