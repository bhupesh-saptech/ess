<?php 
    require '../incld/verify.php';
    require '../admin/check_auth.php';
    require '../incld/header.php';
    require '../admin/top_menu.php';
    require '../admin/side_menu.php';
    require '../admin/dashboard.php';
    require '../incld/autoload.php';
  
    $conn = new Model\Conn();
    $param = array();

    $query = "select * from staff limit 50";
    $items = $conn->execQuery($query,$param);

    $query = "select * from usr_data";
    $users = $conn->execQuery($query,$param);
  

?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="dtbl" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Staff No</th>    
                    <th>Staff Name</th>
                    <th>Site Id</th>
                    <th>User Id</th>
                    <th>Card Id</th>
                    <th>Last Visit</th>
                    <th>Status</th>
                </tr>
            </thead>
            <?php 
                if(isset($items)) {
                    foreach($items as $item) {
            ?>
                <tr>
                    <td><?php echo $item->staff_no; ?><input type="hidden" name="lifnr" value="<?php echo $item->staff_id ;?>"></td>
                    <td><?php echo $item->staff_name; ?></td>      
                    <td><?php echo $item->site_id; ?></td>      
                    <td><?php echo $item->user_id; ?></td>      
                    <td><?php echo $item->card_id; ?></td>      
                    <td><?php echo $item->last_visit; ?></td>      
                    <td><?php echo $item->status; ?></td>   
                     <td><a href="list_aobj.php?objty=supl&objky=<?php echo $item->staff_id;?>"><img class="img-fluid rounded mx-auto d-block" style="width:25px;height:25px" src="../assets/dist/img/auth.png"></a></td>
                </tr>
                <?php 
                        } 
                    }
                ?>
        </table>
    </div>
</div>
<?php
    include('../incld/jslib.php');
    include('../incld/footer.php');
?>