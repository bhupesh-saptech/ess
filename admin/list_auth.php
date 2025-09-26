<?php
    require '../incld/autoload.php';
    $conn = new Model\Conn();
    if(isset($_GET['objty'])) {
        $rqst  = json_decode(json_encode($_GET));
        $query = "select * from obj_type where objty = ?";
        $param = array($rqst->objty);
        $objty = $conn->execQuery($query,$param,1);
        $query = "select objky,objnm from {$objty->table} where objky not in (select objky from usr_auth where user_id = ? and objty = ? )";
        $param = array($rqst->user_id,$rqst->objty);
        $items = $conn->execQuery($query,$param);
        echo json_encode($items);
        die();
    }
    if(isset($_POST['action'])) {
        $rqst = json_decode(json_encode($_POST));
        switch($rqst->action) {
            case 'getObj' :
                $query = "select * from obj_type where objty = ?";
                $param = array($rqst->objty);
                $objty = $conn->execQuery($query,$param,1);
                $query = "select objky,objnm from {$objty->table} ";
                $param = array();
                $items = $conn->execQuery($query,$param,1);
                echo json_encode($items);
                die();
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
                                $rqst->objky );
                $conn->execQuery($query,$param);
                $_SESSION['status'] = " User Assignment {$rqst->user_id} deleted ";
                break;
        }

    }
    require '../incld/verify.php';
    require '../admin/check_auth.php';
    require '../incld/header.php';
    require '../admin/top_menu.php';
    require '../admin/side_menu.php';
    require '../admin/dashboard.php';
    
    $rqst = json_decode(json_encode($_GET));
    $query = "select a.*,b.objty,b.objky,b.objnm as title,c.objnm  from usr_data as a left outer join usr_auth as b on b.user_id = a.user_id left outer join obj_type as c on c.objty = b.objty where a.user_id = ? order by a.user_id,b.objty,b.objky";
    $param = array($rqst->user_id);
    $items = $conn->execQuery($query,$param);
    foreach($items as $i => $item) {
        if(!empty($item->objty)) {
            $query = "select * from obj_type where objty = ? "; 
            $param = array($item->objty);
            $objty  = $conn->execQuery($query,$param,1);
        } 
    }
    
    $query = "select * from obj_type where oauth = 1";
    $param = array();
    $objts = $conn->execQuery($query,$param);
?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class=row">
            <div class="col-md-12">
                <?php include('../incld/messages.php'); ?>
            </div>
        </div>
        <form method="POST">
            <table class="table table-bordered">
                <tr>
                    <td class="col-sm-1">
                        <label for="" class="form-label">User ID</label>
                        <input type="text" name="user_id" class="form-control" value="<?php echo $item->user_id;?>" readonly></td>
                    <td class="col-sm-2">
                    <label for="" class="form-label">User Name</label>
                        <input type="text" name="user_nm" class="form-control" value="<?php echo $item->user_nm;?>" readonly></td>
                    <td class="col-sm-1">
                        <label for="" class="form-label">User Role</label>
                        <input type="text" name="role_nm" class="form-control" value="<?php echo $item->role_nm;?>" readonly></td>
                    <td class="col-sm-2">
                        <label for="" class="form-label">Object Type</label>
                        <select  name="objty" class="form-control" onchange="getObjects(this);" required>
                            <option value="">select Object</option>
                            <?php foreach($objts as $objty) { ?>
                                <option value="<?php echo "{$objty->objty}"; ?>" ><?php echo "{$objty->objty} : {$objty->objnm}"; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td class="col-sm-2">
                        <label for="" class="form-label">Object Value</label>
                        <select name="objky" class="form-control" id="objky" onchange="setObjnm(this);">
                        </select>
                        
                    <td class="col-sm-2">
                        <label for="" class="form-label">Object Description</label>
                        <input type="text" name="objnm" class="form-control" id="objnm" value="" readonly>
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
                    <th class="col-sm-1">UserID</th>
                    <th class="col-sm-2">UserName</th>
                    <th class="col-sm-1">UserRole</th>
                    <th class="col-sm-1">BO Type</th>
                    <th class="col-sm-2">BO Name</th>
                    <th class="col-sm-2">BO Value</th>
                    <th class="col-sm-2">BO Title</th>
                    <th class="col-sm-1">Action</th>
                </tr>
            </thead>
            <tbody>
        <?php 
            if(isset($items)) {
                foreach($items as $item) { 
                    if(!empty($item->objty)) { ?>
                        <tr>
                            <td><a href="disp_user.php?user_id=<?php echo $item->user_id;?>"><?php echo $item->user_id;?></a></td>
                            <td><?php echo $item->user_nm;  ?></td>
                            <td><?php echo $item->role_nm;  ?></td>
                            <td><?php echo $item->objty;    ?></td>
                            <td><?php echo $item->objnm;    ?></td>
                            <td><?php echo $item->objky;    ?></td>
                            <td><?php echo $item->title;    ?></td>
                            <td>
                                <form method="post">
                                    <input  type="hidden" name="user_id" value="<?php echo $item->user_id;  ?>">
                                    <input  type="hidden" name="objty" value="<?php echo $item->objty;  ?>">
                                    <input  type="hidden" name="objky" value="<?php echo $item->objky;  ?>">
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
        <?php       }
                }
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
    function getObjects(obj) {
        objty = $(obj).val();
        options = $('#objky');
        $.get(window.location.href, { objty: objty }, function(data) {
            data = JSON.parse(data);
            options.empty();
            options.append("<option value=''>Select a Object</option>");
            for (let i = 0; i < data.length; i++) {
                console.log("Object : "+ i + data[i].objky + data[i].objnm);
                options.append("<option value='"+data[i].objky+"'>"+data[i].objky + " : " + data[i].objnm+"</option>");
            }
        });
    }
    function setObjnm(obj) {
        let objnm = $('#objnm');
        let otext = $('#objky option:selected').text().split(":")[1];
        objnm.val(otext);
    }
</script>
<?php
    require '../incld/footer.php';
?>