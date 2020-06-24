<?php
    $today = new Datetime();
    $id = $_GET['info'];
    $query = "Select * from project where id_project = $id";
    $result = mysqli_query($conn, $query);
    if ($result){
        while($rows = mysqli_fetch_assoc($result)){
            $name        = $rows['name_project'];
            $date        = new Datetime($rows['date_project']);
            $date        = $date->format("m/d/Y");
            $description = $rows['description_project'];
            $complete    = $rows['complete'];
        }
    }
?>

<div class="content container">
    <div class="flex-container">
        <h2 data-id="<?php echo $_GET["info"];?>" class="project-name"><?php echo $name;
                                                                             if ($complete == 1)
                                                                                echo "<span class=\"btn btn-success btn-circle\"><i class=\"fas fa-check\"></i><span>";
                                                                        ?></h2>
        <h4 class="project-date">Finish date: <?php if ($complete == 1)
                                                        echo "<span class=\"complete\"> $date </span>";
                                                    else if($today->format("m/d/Y") < $date)
                                                        echo "<span > $date </span>";
                                                    else
                                                        echo "<span class=\"blink warning\"> $date </span>";?>
        </h4>
        <span class="btn btn-warning" data-toogle="tooltip" title="Edit" id="showModalform"><i id="add-project" class="fas fa-pen"></i></span>
        <span class="btn btn-success" data-toogle="tooltip" title="Complete" id="completeProject" data-id="<?php echo $_GET["info"];?>"><i class="fas fa-check"></i></span>
    </div>
    <div class="row description">
        <div class="col-sm-12">
            <p class="project-descript"><?php echo $description;?></p>
        </div>
    </div>

    <div class="row">
       <h3 class="col-sm-1" style="margin: 0 0 7px 0;">Tasks</h3>
       <div class="col-sm-2">
           <span id="showModalform" data-toggle="tooltip" title="Add task" class="btn btn-danger" style="margin: 0 0 10px 0;"><i id="add-task" class="fas fa-plus"></i> </span>
       </div> 
       
    </div>
    
    <div class="row" id="tasks">
    <?php  
    $query = "select task.id_task, task.description, task.date, task.urgency, task.complete, user.name, user.last_name from task inner join user on user.id_user = task.id_person_assign where task.id_project = $id order by task.date ASC";
    $result = mysqli_query($conn, $query);
    if ($result){
        if (mysqli_num_rows($result) > 0){
            while ($rows = mysqli_fetch_assoc($result)){
                $id_task     = $rows['id_task']; 
                $description = $rows['description'];
                $full_name   = $rows['name']." ".$rows['last_name'];
                $date        = new Datetime($rows['date']);
                $dateStr     = $date->format("m/d/Y");
    ?>
            <div class="row" id="<?php echo $id_task;?>">
                <p class="col-sm-3"><?php echo $description?></p>
                <p class="col-sm-3">Assign to: <?php echo $full_name;?></p>
                <p class="col-sm-2" id="<?php echo $date->format("Y/m/d"); ?>">Finish date: <?php if ($today < $date)
                                                                echo $dateStr;
                                                            else{
                                                                if ($rows['complete'] == 1)
                                                                    echo "<span class=\"complete\"> $dateStr";
                                                                else
                                                                    echo "<span class=\"blink warning\"> $dateStr";
                                                            }?>
                </p>
                <?php if ($rows['complete'] == 1){
                    echo "<p class=\"complete col-sm-2\">This task is complete.</p>"; 
                } 
                else if ($rows['urgency'] == 1){
                    echo "<p class=\"blink warning col-sm-2\">This task is urgent !</p>";   
                }
                else{
                    echo "<p class=\"col-sm-2\">This task is not urgent.</p>";
                }
                ?>
                <div class="col-sm-2" style="margin-bottom: 15px">
                    <span class="btn btn-warning" data-toogle="tooltip" title="Edit" id="edit"><i class="fas fa-pen"></i></span>
                    <span class="btn btn-success" data-toogle="tooltip" title="Complete" id="complete"><i class="fas fa-check"></i></span>
                </div>
            </div>
            <?php
            }
        }
        else{
            ?>
            <h2 style="text-align: center;">No tasks</h2>
    <?php
        }
    }
    ?>   
    </div>
</div>

<div class="modalform" id="project">
    <div class="new-project square-form">
    <span class="close">&times;</span>
        <div id="projectM">
            <input type="text" name="project" id="name-project" placeholder="Project name"><br>
            <input type="date" name="date" id="date-project" placeholder="Date" ><br>
            <textarea name="description" id="description-project" cols="37" rows="5" placeholder="Project Description"></textarea>
   
            <a id="delete-project" data-id="<?php echo $_GET['info']?>" data-toggle="tooltip" title="Delete Project" class="btn btn-danger" ><i class="fas fa-trash-alt"></i></a>
            <input class="btn btn-primary" data-id="<?php echo $_GET['info']?>" type="submit" value="Edit project" name="edit_project" id="edit_project">
        </div>
    </div>
</div>

<div class="modalform" id="task">
    <div class="new-task square-form">
    <span class="close">&times;</span>
        <div id="task">
            <input type="text" name="task" id="new-task" placeholder="Task" style="width: 300px"><br>
            <input type="date" name="date" id="date-task" placeholder="Date" style="width: 300px"><br>
            <label for="person">Person Assign: </label>
            <select name="person" id="person-assign">
                <option value="0" disabled selected><- Assign person -></option>
                <?php
                    $query  = "Select * from user where id_team = $team";
                    $result = mysqli_query($conn, $query);
                    if ($result){
                        while ($rows = mysqli_fetch_assoc($result)){
                ?>
                <option value="<?php echo $rows['id_user'];?>"><?php echo $rows['name'] . " " . $rows['last_name'] ?></option>
                <?php
                        }
                    } 
                ?>
            </select><br>
           <input type="radio" id="no-urgent" name="urgent" value="0"><label for="no-urgent">No urgent</label>
           <input type="radio" id="urgent" name="urgent" value="1"><label for="urgent">Urgent</label><br>
                
            <input class="btn btn-primary" type="submit" value="Add Task" name="add_task" id="add_task">
        </div>
    </div>
</div>

