<?php require_once 'app/config.php';
      require_once 'includes/head.php';
?>

<div class="signup square-form">
    <div>
        <span style="font-size:12px;"> * Required field</span>
        <form action="<?php echo URL;?>/app/functions.php" method="post">
        <span> * </span><label for="username">Username: </label>
            <input type="text" name="username" id="username" placeholder="Username" required>
            <p style="margin-left:10px; font-size:14px; font-weight:bold; color: red; display:inline;">
            <?php 
                if(isset($_GET['error'])){
                    if ($_GET['error'] == "user_exist"){
                        echo "User already exist.";
                    }
                }
            ?>
            </p><br>
            <span> * </span><label for="password">Password: </label>
            <input type="password" name="password" id="password" placeholder="Password" required><p class="error"style="margin-left:10px; font-size:14px; display:none;"></p><br>

            <label for="team_code" class="space-label-10">Enter with: </label>
            <select name="team_code" id="team_code">
                <option value="newTeam">New team</option>
                <option value="code">Team code</option>
            </select><br>
            
            <span> * </span><label for="team" class="space-label-40">Team:</label>
            <input type="text" name="team" id="team" placeholder="New team" required>
            <p style="margin-left:10px; font-size:14px; font-weight:bold; color: red; display:inline;">
            <?php 
                if(isset($_GET['error'])){
                    if ($_GET['error'] == "team_exist"){
                        echo "Team already exist.";
                    }
                    else if ($_GET['error'] == "no_code"){
                        echo "Code is not correct.";
                    }
                }
            ?>
            </p><br>
            
            <span> * </span><label for="name" class="space-label-40">Name:</label>
            <input type="text" name="name" id="name" placeholder="Name" required><br>
            
            <span> * </span><label for="last_name">Last name: </label>
            <input type="text" name="last_name" id="last_name" placeholder="Last name" required><br>
            
            <label for="email" class="space-label-40">E-mail: </label>
            <input type="email" name="email" id="email" placeholder="E-mail"><br>
            
            <div class="row">
                <div class="col-sm-6">
                    <a href="index.php" data-toggle="tooltip" title="Return" data-placement="bottom" id="return"><i class="fas fa-arrow-circle-left"></i></a>
                </div>
                <div class="col sm-6">
                    <input class="btn btn-primary" type="submit" value="Register" name="register" id="register">
                </div>
            </div>
            
        </form>

        <?php 
            if(isset($_GET['success'])){
                if ($_GET['success'] == "false")
                    echo "<p style=\"margin-left:10px; font-size:14px; font-weight:bold; display:inline; color: red;\"> We can't sign up you now, try again later.";
            }
        ?>
        </p>

    </div>
</div>


    <script src="<?PHP echo JS;?>main.js"></script>
</body>
</html>