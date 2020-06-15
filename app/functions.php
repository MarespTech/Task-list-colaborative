<?php
global $conn;

if(isset($_POST['register'])){
    //Get values with POST Method
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $team = $_POST['team'];
    $name = $_POST['name'];
    $last_name = $_POST['last_name'];
    if(isset($_POST['telephone']))
        $telephone = $_POST['telephone'];
    else
        $telephone = "";

    //Validate if the team exist, if it doesn't exist, create it
    $query = "select * from team where name_team like '%".$team."%'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 0){
        $query = "Insert into team(name_team) values(". $team .")";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "Error: " . $sql . "<br>" . $conn->error;
          } else {
            echo "New record created successfully";
          }
    }

}