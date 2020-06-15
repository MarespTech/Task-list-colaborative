<?php require_once 'app/config.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo CSS; ?>style.css">    
    <title>Register</title>
</head>
<body>

    <form action="<?php echo URL;?>/app/functions.php" method="post">
    <label for="username">Username </label>
    <input type="text" name="username" id="username" required><br>
    <label for="password">Password </label>
    <input type="password" name="password" id="password" required><p id="error" style="margin-left:10px; font-size:14px; font-weight:bold; display:none;">Holi</p><br>

    <label for="team">Team </label>
    <input type="text" name="team" id="team" required><br>
    <label for="name">Name </label>
    <input type="text" name="name" id="name" required><br>
    <label for="last_name">Last name </label>
    <input type="text" name="last_name" id="last_name" required><br>
    <label for="telephone">Telephone </label>
    <input type="text" name="telephone" id="telephone"><br>
    
    <input type="submit" value="Register" name="register" id="register">
    </form>

    <script src="<?PHP echo JS;?>main.js"></script>
</body>
</html>