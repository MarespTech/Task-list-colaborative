<div class="content container">
    <div class="row">
        <?php 
        $query_team = "select * from team where id_team =".$team;
        $result_team = mysqli_query($conn, $query_team);
        if (mysqli_num_rows($result_team) > 0){
            while($rows_team = mysqli_fetch_assoc($result_team)){
                echo "<h2 style=\"display: inline;\">".$rows_team['name_team']."</h2><h4 style=\"display: inline; margin-left: 10px;\">Code Team: ".$rows_team['code_team']."<h4>";
            }
        }
        ?>
        
        <h3>Workers</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $query_workers = "Select id_user, name, last_name, email from user where id_team = ".$team;
                    $result_workers = mysqli_query($conn, $query_workers);
                    if (mysqli_num_rows($result_workers) > 0){
                        while ($rows_workers = mysqli_fetch_assoc($result_workers)){
                ?>
                <tr>
                    <td id="id_worker"><?php echo $rows_workers['id_user'];?></td>
                    <td id="name_worker"><?php echo $rows_workers['name']. " ".$rows_workers['last_name'];?></td>
                    <td id="email_worker"><?php echo $rows_workers['email'];?></td>
                </>
                <?php
                        }
                   }
                ?>
            </tbody>
        </table>
        
    </div>
</div>