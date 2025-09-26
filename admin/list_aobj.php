<?php
    require '../incld/verify.php';
    require '../admin/check_auth.php';
    require '../incld/header.php';
    require '../admin/top_menu.php';
    require '../admin/side_menu.php';
    require '../admin/dashboard.php';
    require '../incld/autoload.php';
    require_once '../incld/functions.php';
    $conn = new Model\Conn();
    if(isset($_POST['action'])) {
        $rqst = json_decode(json_encode($_POST));
        switch($rqst->action) {
            case 'add' :
                $query = "insert ignore into usr_auth ( user_id,
                                                        objty,
                                                        objky,
                                                        objnm)
                                                values( ?, ?, ?,?)";
                $param = array( $rqst->user_id,
                                $rqst->objty,
                                $rqst->objky,
                                $rqst->objnm );
                $conn->execQuery($query,$param);
                $_SESSION['status'] = " User Assignment {$rqst->user_id} added ";
                break;
            case 'del':
                $query = "delete from usr_auth where user_id = ?
                                                 and objty = ?
                                                 and objky = ?";
                $param = array( $rqst->user_id,
                                $rqst->objty,
                                $rqst->objky);
                $conn->execQuery($query,$param);
                $_SESSION['status'] = " User Assignment {$rqst->user_id} deleted ";
                break;
        }
    }
    if(isset($_GET['objty'])) {
        $rqst = json_decode(json_encode($_GET));
        $query = "select * from obj_type where objty = ?"; 
        $param = array($rqst->objty);
        $objt  = $conn->execQuery($query,$param,1);
        
        $query = "select a.*,b.objty,b.objky,b.objnm from usr_data as a left outer join usr_auth as b on b.user_id = a.user_id left outer join obj_type as c on c.objty = b.objty where b.objty = ? and b.objky = ?";
        $param = array($rqst->objty,$rqst->objky);
        $items = $conn->execQuery($query,$param);
        if(isset($items)) { 
            $item  = $items[0];
        } else {
            $query = "select objky,objnm from {$objt->table} where objky = {$rqst->objky}";
            $param = array();
            $item  = $conn->execQuery($query,$param,1);
        }
    }
    $query = "select * from users where user_id not in (select user_id from usr_auth where objty = ? and objky = ? )";
    $param = array($rqst->objty,$rqst->objky);
    $users = $conn->execQuery($query,$param);
?>
<div class="card">
    <div class="card-header">
        <div class=row">
            <div class="col-md-12">
                <?php include('../incld/messages.php'); ?>
            </div>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form method="POST">
            <table class="table table-bordered">
                <tr>
                    <td class="col-sm-1">
                        <label for="" class="form-label">Object Type</label>
                        <input type="text" name="objty" class="form-control" value="<?php echo $objt->objty;?>" readonly>
                    </td>
                    <td class="col-sm-2">
                        <label for="" class="form-label">Object Name</label>
                        <input type="text"   name="objnm" class="form-control" value="<?php echo $objt->objnm;?>" readonly>
                    </td>
                    <td class="col-sm-2">
                        <label for="" class="form-label">Object Value</label>
                        <input type="text"   name="objky" class="form-control" value="<?php echo $item->objky;?>" readonly></td>
                    <td class="col-sm-2">
                        <label for="" class="form-label">Object Desc</label>
                        <input type="text"   name="objnm" class="form-control" value="<?php echo $item->objnm;?>"                             readonly></td>
                    <td class="col-sm-2">
                        <label for="" class="form-label">User ID</label>
                        <select class="form-control" name="user_id" required>
                            <option value="">Select User</option>
                            <?php foreach($users as $user) { ?>
                                <option value="<?php echo $user->user_id;?>"><?php echo "{$user->user_id} : {$user->user_nm}";?></option>
                            <?php }?>
                        </select>
                    </td>
                    <td class="col-sm-1">
                        <label for="" class="form-label">User Role</label>
                        <input  type="text"   name="role_nm" class="form-control"    value="" readonly>
                    </td>
                    <td class="col-sm-1 text-center">
                        <label for="" class="form-label">Action</label>
                        <button type="submit" name="action" class="btn btn-primary" value="add">
                            <i class="fa fa-plus"></i>
                        </button>
                    </td>
                </tr>
            </table>
        </form>
        <table id="dtbl" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th class="col-sm-1">BO Type</th>
                    <th class="col-sm-1">BO Title </th>
                    <th class="col-sm-2">BO Value</th>
                    <th class="col-sm-2">BO Name </th>
                    <th class="col-sm-3" >UserID</th>
                    <th class="col-sm-1">User Role</th>
                    <th class="col-sm-1">Action</th> 
                </tr>
            </thead>
            <tbody>
        <?php 
            if(isset($items)) {
                foreach($items as $item) { ?>
                    <tr>
                        <td><?php echo $objt->objty;  ?></td>
                        <td><?php echo $objt->objnm;  ?></td>
                        <td><?php echo $item->objky;  ?></td>
                        <td><?php echo $item->objnm;  ?></td>
                        <td><?php echo "{$item->user_id} : {$item->user_nm}";  ?></td>
                        <td><?php echo $item->role_nm;  ?></td>
                        <td>
                            <form method="post">
                                <input  type="hidden" name="user_id" value="<?php echo $item->user_id;  ?>">
                                <input  type="hidden" name="objty"   value="<?php echo $item->objty;  ?>">
                                <input  type="hidden" name="objky"   value="<?php echo $item->objky;  ?>">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <button type="button" name="action" value="mod" class="btn btn-warning"    >
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="submit" name="action" value="del" class="btn btn-danger ml-2" onclick="return confirm('Are you sure you want to delete Challan?');"   >
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </td>
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
    function setRole(obj) {
        let user = $(obj).val();
    }
</script>
<?php
    require '../incld/footer.php';
?>