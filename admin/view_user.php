<?php
    include('../includes/verify.php');
    include('../admin/check_auth.php');
    include('../includes/dbconn.php');
    $item = new stdClass();
    if(isset($_POST['action'])) {
        if(isset($_POST['user_id'])) {
            $post = json_decode(json_encode($_POST));
            $dset = $conn->query("select * from users where user_id='$post->user_id'");
            $item = json_decode(json_encode($dset->fetch_assoc()));
            $item->action = $post->action;
        } else {
            $item->user_id    = '';
            $item->user_name  = '';
            $item->user_email = '';
            $item->user_phone = '';
            $item->user_role  = '';
            $item->user_lifnr = '';
            $item->password   = '';
        }
    } else {
        $item->action     = 'add';
        $item->user_id    = '';
        $item->user_name  = '';
        $item->user_email = '';
        $item->user_phone = '';
        $item->user_role  = '';
        $item->user_lifnr = '';
        $item->password   = '';
    }
    switch($item->action) {
        case 'add'   : 
            $item->btn_txt = 'Create User';
            break;
        case 'view'  :
            $item->btn_txt = 'Close';
        //    $item->btn_txt = 'Close';
            break;
        case 'edit'  :
            $item->btn_txt = 'Update User';
            break;
        case 'delete':
            $item->btn_txt = 'Delete User';
            break;
    }
    if( isset($_POST['crudat'])) {
        $item   = json_decode(json_encode($_POST));
        $action = $item->crudat;
        switch($action) {
            case 'add'   : 
                $sqls = "insert into users (user_id,
                                            user_name,
                                            user_email,
                                            user_phone,
                                            password,
                                            user_role,
                                            user_lifnr)
                                values (    '$item->user_id',
                                            '$item->user_name',
                                            '$item->user_email',
                                            '$item->user_phone',
                                            '$item->password',
                                            '$item->user_role',
                                            '$item->user_lifnr')";
                if($conn->query($sqls)) {
                    $_SESSION['status'] = 'User added successfully';
                } else {
                    $_SESSION['status'] = 'User registration failed';
                }
                break;
            case 'view'  :
                break;
            case 'edit'  :
                $sqls = "update users  set      user_name  = '$item->user_name',
                                                user_email = '$item->user_email',
                                                user_phone = '$item->user_phone',
                                                password   = '$item->password',
                                                user_role  = '$item->user_role',
                                                user_lifnr = '$item->user_lifnr'
                                        where   user_id    = '$item->user_id'";
                if($conn->query($sqls)) {
                    $_SESSION['status'] = 'User updated successfully';
                } else {
                    $_SESSION['status'] = 'User updation failed';
                }
                
                break;
            case 'delete':
                $sqls = "delete from users where user_id = '$item->user_id'";
                if($conn->query($sqls)) {
                    $_SESSION['status'] = 'User Deleted successfully';
                } else {
                    $_SESSION['status'] = 'User Deletion failed';
                }
                break;
            }
        $conn->close();
        header("Location:list_users.php");
        exit(0);
    }
    include('../includes/header.php');
    include('../includes/top_menu.php');
    include('../admin/left_menu.php');
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Registered User</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div><!-- /.content-header -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Registered Users</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <form method="POST"  >
                        <div class="form-group">
                            <label for="">User ID</label>
                            <input type="text" name="user_id" class="form-control" placeholder="User ID" value="<?php echo $item->user_id; ?>" required
                            <?php if($item->action != 'add') { echo 'readonly'; } ?> >
                        </div>
                        <div class="form-group">
                            <label for="">Name</label>
                            <input type="text" name="user_name" class="form-control" placeholder="Name" value="<?php echo $item->user_name; ?>">
                        </div>
                        <div class="form-group">
                            <label for="">Email ID</label>
                            <input type="email" name="user_email" class="form-control" placeholder="Email ID" value="<?php echo $item->user_email;?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Phone Number</label>
                            <input type="text" name="user_phone" class="form-control" placeholder="Phone Number" value="<?php echo $item->user_phone;?>" required>
                        </div>
                        <div class="form-group">
                            <label for="">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Password" value="<?php echo $item->password; ?>">
                        </div>
                        <div class="form-group">
                            <label for="">User Role</label>
                                
                            <select class="form-control" name="user_role" >
                                <option value="">select user role                                                           </option>
                                <option value='0' <?php if($item->user_role == '0') {echo 'selected';}?>>0 : Supplier       </option>
                                <option value='1' <?php if($item->user_role == '1') {echo 'selected';}?>>1 : Administrator  </option>
                                <option value='0' <?php if($item->user_role == '2') {echo 'selected';}?>>2 : Buyer          </option>
                                <option value='0' <?php if($item->user_role == '3') {echo 'selected';}?>>3 : Stores         </option>
                                <option value='0' <?php if($item->user_role == '4') {echo 'selected';}?>>4 : Inspection     </option>
                            </select>
                        </div>
                        <div class="form-group">
                        <input type="text" name="user_lifnr" class="form-control" value="">
                        </div>
                        <div class="modal-footer">
                            <a name="a_back" class="btn btn-secondary" href="list_users.php">Back</a>
                            <button type="submit" name="crudat" class="btn btn-secondary" value="<?php echo $item->action;?>"><?php echo $item->btn_txt;?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    include('../includes/footer.php');
?>