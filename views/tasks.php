
<div class="content container">
    <div class="row">
       <h3 class="col-sm-6" style="margin: 0 0 30px;">Next tasks</h3>
    </div>
    
    <div class="row tasks">
    <?php 
    $today  = new Datetime(); 
    $query  = "select task.id_project, task.description, task.date, task.urgency, project.name_project from task inner join project on project.id_project = task.id_project where task.id_person_assign = $id_user and task.complete = 0 and disable_flag != 1 order by task.date ASC";
    $result = mysqli_query($conn, $query);
    if ($result){
        if(mysqli_num_rows($result) > 0){
            while ($rows = mysqli_fetch_assoc($result)){
                $id_project  = $rows['id_project'];
                $description = $rows['description'];
                $project     = $rows['name_project'];
                $date        = new Datetime($rows['date']);
                $dateStr     = $date->format("m-d-Y");
    ?>
            <div class="row task">
                <div class="col-sm-3"><p>Task: <?php echo $description?></p></div>
                <div class="col-sm-3"><p>Project: <?php echo $project;?></p></div>
                <div class="col-sm-3"><p>Finish date: <?php if ($today < $date)
                                                                echo $dateStr;
                                                            else
                                                                echo "<span class=\"blink warning\"> $dateStr";?>
                                      </p></div>
                <div class="col-sm-2">
                <?php if ($rows['urgency'] == 1){
                    echo "<p class=\"blink warning\">This task is urgent!</p>";   
                }
                else{
                    echo "<p>This task is not urgent.</p>";
                }
                ?>
                </div>
                <div class="col-sm-1">
                    <a class="btn btn-primary more-project" data-toggle="tooltip" title="See project" id="<?php echo $id_project;?>"><i class="fas fa-angle-double-right"></i></a>
                </div>
            </div>
    <?php
            }
        }
        else{
    ?>
            <h2 style="text-align: center;">No tasks pending.</h2>
    <?php
        }
    }
    ?>   
    </div>
</div>