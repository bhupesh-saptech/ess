<?php
    require '../incld/verify.php';
    require '../admin/check_auth.php';
    require '../incld/header.php';
    require '../admin/top_menu.php';
    require '../admin/side_menu.php';
    require '../admin/dashboard.php';
    require '../incld/autoload.php';
    $conn = new Model\Conn();
    if(isset($_REQUEST['role_nm'])) {
        $role_nm = $_REQUEST['role_nm'];
        $query = "select * from usr_data where role_nm = ?";
        $param = array($role_nm);
        $items = $conn->execQuery($query,$param);

    } else {
        $role_nm = "suppl";
        $query = "select * from usr_data";
        $param = array();
        $items = $conn->execQuery($query,$param);
    }
?>
<div class="card">
    <div class="card-header">
        <?php
            if(isset($_SESSION['status'])) {
                echo "<h3 class='card-title'>".$_SESSION['status']."</h3>";
                unset($_SESSION['status']);
            }
        ?>
        <a href="javascript:void(0);" onclick="newTab('disp_user.php?role_nm=<?php echo $role_nm ?>')" class="btn btn-primary btn-sm float-right" >Add User</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="dtbl" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>UserID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Auth</th>
                </tr>
            </thead>
            <tbody>
        <?php 
            if(isset($items)) {
                foreach($items as $item) { ?>
                    <tr>
                        <td><a href="javascript:void(0);" onclick="newTab('<?php echo 'disp_user.php?user_id='.$item->user_id;?>');"><?php echo $item->user_id;?></a></td>
                        <td><?php echo $item->user_nm;  ?></td>
                        <td><?php echo $item->mail_id;  ?></td>
                        <td><?php echo $item->user_ph;  ?></td>
                        <td><?php echo $item->role_nm;  ?></td>
                        <td><?php echo $item->user_ty;  ?></td>
                        <td><?php if($item->user_st == 1 ) { echo 'Active';} else { echo 'InActive';}  ?>  </td>
                        <td><a href="list_auth.php?user_id=<?php echo $item->user_id;?>"><img class="img-fluid rounded mx-auto d-block" style="width:25px;height:25px" src="../assets/dist/img/auth.png"></a></td>
                    </tr>
        <?php   }
            } 
        ?>
            </tbody> 
        </table>
    </div>
</div>

<?php
    require '../incld/jslib.php';
?>
<script>
    function newTab(url) {
        window.open(url,'_blank');
    }
</script>
<?php 
    require '../incld/footer.php';
?>