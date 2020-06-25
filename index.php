<?php require_once 'app/config.php';
      require_once 'includes/head.php';
?>
<?php
    if(isset($_GET['logout'])){
        if ($_GET['logout'] == "true"){
            session_unset();
            session_destroy();
        }

    }
?>

<div class="login square-form">
    <div>
        <p class="error">
            <?php 
                if(isset($_GET['error'])){
                    if ($_GET['error'] == "wrong_data")
                        echo "User or password are incorrect.";
                    else if ($_GET['error'] == "no_user")
                        echo "User doesn't exist.";
                }
            ?>
        </p>
        <form action="<?php echo URL;?>/app/functions.php" method="post">
            <label for="username">Username: </label>
            <input type="text" name="username"> <br>
            <label for="password">Password: </label>
            <input type="password" name="password"> <br>
            <input class="btn btn-primary" type="submit" value="login" name="login" id="login">
        </form>
        <p style="text-align: center;">No account ? <a href="register.php" style="color: blue; text-decoration: none;" id="register_link">Sign up </a> now</p>
    </div>
</div>

<?php
    if(isset($_GET['signup'])){
        if ($_GET['signup'] == "success"){
?>
            <div class="modalform" id="messageModal" style="display: block;">
                <div class="message-modal square-form">
                    <span class="close">&times;</span>
                    <h2>Sign up Success !</h2>
                    <p>Your account is already activate.</p>
                </div>
            </div>
<?php
        }
    }
?>
    <script src="<?PHP echo JS;?>main.js"></script>
</body>
</html>