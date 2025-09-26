<?php
    require '../incld/verify.php';
    require '../admin/check_auth.php';
    require '../incld/header.php';
    require '../admin/top_menu.php';
    require '../admin/side_menu.php';
    require '../admin/dashboard.php';
    require '../incld/autoload.php';
    $conn = new Model\Conn();
    $query = "select * from usr_role";
    $param = array();
    $items = $conn->execQuery($query,$param);
    

?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="dtbl" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Role_ID</th> 
                    <th>Role Name</th>
                    <th>Home Page</th>
                    <th>Contact</th>
                    <th>Mobile No</th>
                    <th>Auth</th>
                </tr>
            </thead>
            <?php 
                foreach($items as $item) { 
            ?>
            <tr>
                <td><?php echo $item->role_id; ?> <input type="hidden" name="role_id" value="<?php echo $item->role_id ;?>"></td>
                <td><?php echo $item->role_nm; ?> </td>
                <td><?php echo $item->home_pg; ?> </td>
                <td><?php echo "";             ?> </td>
                <td><?php echo "";             ?> </td>
                <td><a href="list_users.php?role_nm=<?php echo $item->role_nm;?>"><img class="img-fluid rounded mx-auto d-block" style="width:25px;height:25px" src="../assets/dist/img/auth.png"></a></td>
            </tr>
            <?php 
                    } 
            ?>
        </table>
    </div>
</div>

<?php
    require '../incld/jslib.php';
    require '../incld/footer.php';
?>