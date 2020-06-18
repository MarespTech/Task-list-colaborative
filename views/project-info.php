<?php
    $today = new Datetime();
    $id = $_GET['info'];
    $query = "Select * from project where id_project = $id";
    $result = mysqli_query($conn, $query);
    if ($result){
        while($rows = mysqli_fetch_assoc($result)){
            $name        = $rows['name_project'];
            $date        = new Datetime($rows['date_project']);
            $date        = $date->format("m-d-Y");
            $description = $rows['description_project'];
        }
    }
?>

<div class="content container">
    <div class="flex-container">
        <h2><?php echo $name;?></h2>
        <h4>Finish date: <?php echo $date;?></h4>
    </div>
    <div class="row description">
        <div class="col-sm-12">
            <p><?php echo $description;?></p>
        </div>
    </div>

    <div class="row">
       <h3 class="col-sm-1" style="margin: 0;">Tasks</h3>
       <div class="col-sm-6">
           <span id="showModalform" data-toggle="tooltip" title="Add task"><i class="fas fa-plus btn btn-danger" ></i> </span>
       </div> 
       
    </div>
    
    <div class="row tasks">
    <?php  
    $query = "select task.description, task.date, task.urgency, user.name, user.last_name from task inner join user on user.id_user = task.id_person_assign where task.id_project = $id order by task.date ASC";
    $result = mysqli_query($conn, $query);
    if ($result){
        if (mysqli_num_rows($result) > 0){
            while ($rows = mysqli_fetch_assoc($result)){
                $description = $rows['description'];
                $full_name   = $rows['name']." ".$rows['last_name'];
                $date        = new Datetime($rows['date']);
                $dateStr     = $date->format("m-d-Y");
    ?>
                <div class="col-sm-3"><p><?php echo $description?></p></div>
                <div class="col-sm-3"><p>Assign to: <?php echo $full_name;?></p></div>
                <div class="col-sm-3"><p>Finish date: <?php if ($today < $date)
                                                                echo $dateStr;
                                                            else
                                                                echo "<span class=\"blink warning\"> $dateStr";?>
                                    </p></div>
                <div class="col-sm-2">
                <?php if ($rows['urgency'] == 1){
                    echo "<p class=\"blink warning\">This task is urgent !</p>";   
                }
                else{
                    echo "<p>This task is not urgent.</p>";
                }
                ?>
                </div>
                <div class="col-sm-1">
                    <span class="btn btn-warning" data-toogle="tooltip" title="Edit" id="edit"><i class="fas fa-pen"></i></span>
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

<div class="modalform">
    
    <div class="new-project square-form">
    <span class="close">&times;</span>
        <div>
            <input type="text" name="name" id="nameP" placeholder="name" ><br>
            <textarea name="description" id="descriptionP" placeholder="description" cols="22" rows="10"></textarea><br>
            <input type="date" name="date" id="dateP" placeholder="Date" ><br>
                
            <input class="btn btn-primary" type="submit" value="Add Project" name="add_project" id="add_project">
        </div>
    </div>
</div>