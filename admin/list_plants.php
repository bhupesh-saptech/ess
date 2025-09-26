<?php
    require '../incld/verify.php';
    require '../admin/check_auth.php';
    require '../incld/header.php';
    require '../admin/top_menu.php';
    require '../admin/side_menu.php';
    require '../admin/dashboard.php';
    require '../incld/autoload.php';
    $conn = new Model\Conn();
    $query = "select * from plants";
    $param = array();
    $items = $conn->execQuery($query,$param);
    $query = "select * from usr_data where role_nm = ?";
    $param = array('stloc');
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
                    <th>Plant</th> 
                    <th>Description</th>
                    <th>Email ID</th>
                    <th>Contact</th>
                    <th>Mobile No</th>
                    <th>Extension</th>
                    <th>Auth</th>
                </tr>
            </thead>
            <?php 
                foreach($items as $item) { 
            ?>
            <tr>
                <td><?php echo $item->objky; ?> <input type="hidden" name="werks" value="<?php echo $item->objky;?>"></td>
                <td><?php echo $item->objnm; ?> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td> </td>
                <td><a href="list_aobj.php?objty=PLNT&objky=<?php echo $item->objky;?>"><img class="img-fluid rounded mx-auto d-block" style="width:25px;height:25px" src="../assets/dist/img/auth.png"></a></td>
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