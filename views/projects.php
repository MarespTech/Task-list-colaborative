<div class="content container">
    <h2>Projects</h2>
    
    <div class="row" id="projects">
        <?php
        $query_projects = "select * from project where id_team=$team and disable_flag != 1";
        $result_projects = mysqli_query($conn, $query_projects);
        if (mysqli_num_rows($result_projects) > 0){
            while($rows_projects = mysqli_fetch_assoc($result_projects)){
                $id       = $rows_projects['id_project'];
                $name     = $rows_projects['name_project'];
                $date     = strtotime($rows_projects['date_project']);
                $complete = $rows_projects['complete'];
        ?>
            <div class="col-sm-4">
                <div class="project">
                <span id="delete-project" data-id="<?php echo $id; ?>" class="red-x" data-toggle="tooltip" title="Delete project">X</span>
                    <h3><?php echo $name;
                               if ($complete == 1)
                               echo "<span class=\"btn btn-success btn-circle\"><i class=\"fas fa-check\"></i><span>";
                        ?>
                    </h3>
                    <div class="info-project">
                        <p><?php echo date("m-d-Y", $date );?></p> <a class="btn btn-primary more-project" data-toggle="tooltip" title="See more" id="<?php echo $id;?>"><i class="fas fa-angle-double-right"></i></a>
                    </div>
                </div>
            </div>
        
        <?php
            }
        ?>
    </div>
    <h2 class="col-sm-12" style="text-align: center;">New Project ? Click <span id="showModalform" style="font-size: 30px; color: blue; cursor:pointer">here</span> to create it</h2> 
        <?php
        }
        else{
        ?>
    </div>
    <h2 style="text-align: center;">No Projects, click <span id="showModalform" style="font-size: 30px; color: blue; cursor:pointer">here</span> to create one</h2>
        <?php
        }
        ?>
    
</div>

<div class="modalform" id="project">
    <div class="new-project square-form">
    <span class="close">&times;</span>
        <div id="projectM">
            <input type="text" name="nameP" id="nameP" placeholder="Project name"><br>
            <input type="date" name="date" id="dateP" placeholder="Date" ><br>
            <textarea name="description" id="descriptionP" cols="37" rows="5" placeholder="Project Description"></textarea>
   
            <input class="btn btn-primary" type="submit" value="Create" name="add_project" id="add_project">
        </div>
    </div>
</div>