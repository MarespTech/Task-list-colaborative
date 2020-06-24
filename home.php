<?php 
    require_once 'app/config.php';
    require_once 'includes/head.php';
    global $conn;
?>
<body>
    <?php if (isset($_SESSION['user_log'])){
        $userlog = $_SESSION['user'];
        $team = $_SESSION['team'];
        $id_user = $_SESSION['id_user'];

        if($_SESSION['user_log'] == 1){ ?>
        <div class="bar">
            <div class="dropdown"><p>Welcome <?php echo $userlog;?> <i class="fas fa-chevron-circle-down"></i></p>
                <div class="dropdown-content">
                    <nav class="sidebar">
                        <a href="home.php">Home</a>
                        <a href="home.php?page=projects">Projects</a>
                        <a href="home.php?page=team">Team</a>
                        <a href="index.php?logout=true">Exit</a>
                    </nav>
                </div>
            </div> 
        </div>
        <?php
            if(isset($_GET['page'])){
                $page = $_GET['page'];
                if ($page == "team")
                    require_once "views/team.php";
                else if ($page == "projects")
                    require_once "views/projects.php";
                else if ($page == "more")
                    require_once "views/project-info.php";
                else{
                    require_once "views/tasks.php";
                }
            }
            else{
                require_once "views/tasks.php";
            }
        ?>
    <?php 
        } 
    
    } 

    ?>
    

    

    <script src="<?PHP echo JS;?>main.js"></script>
</body>
</html>