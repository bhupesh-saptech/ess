<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">User Details </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">User Form</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div><!-- /.content-header -->
    <div class="card">
        <div class="row">
            <div class="col-md-2">
            </div>
            <div class="col-md-8">
                <div class="card-header">
                    <?php include('../incld/messages.php'); ?>
                </div>
            </div>
            <div class="col-md-2">
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Display User</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-6">
                    <form method="POST"  >
                        <table class="table table-bordered">
                            <tr>
                                <td class="col-sm-6">
                                    <input type="hidden" id="action" value="<?php echo $action; ?>">
                                    <label class="form-label" for="">User ID</label>
                                    <input type="text" name="user_id" class="form-control" value="<?php echo $user->user_id; ?>" readonly>
                                    
                                </td>
                                <td class="col-sm-6">
                                    <label class="form-label" for="">User Name</label>
                                    <input type="text" name="user_nm" class="form-control"  value="<?php echo $user->user_nm; ?>"  required >
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label" for="">Email ID</label>
                                    <input type="email" name="mail_id" class="form-control"  value="<?php echo $user->mail_id;?>" required>
                                </td>
                                <td>
                                    <label class="form-label" for="">Phone Number</label>
                                    <input type="text"  name="user_ph" class="form-control"  value="<?php echo $user->user_ph;?>" required>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label" for="">Password</label>
                                    <input type="password" name="pass_wd" class="form-control"  value="<?php echo $user->pass_wd; ?>" required>
                                </td>
                                <td>
                                    <label class="form-label" for="">User Role</label>
                                    <select class="form-control" name="role_id" id="roles_val" onchange="setObjty(this);" onclick="$(this).trigger('change');" required>
                                        <option value="">select user role  </option>
                                    <?php foreach($roles as $role) { ?>
                                        <option value="<?php echo $role->role_id ?>" <?php if($user->role_nm == $role->role_nm) { echo 'selected';} ?>><?php echo "{$role->role_id} : {$role->role_nm}";  ?></option>
                                    <?php }?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="hidden" id="objty_val" value="<?php echo "{$user->objty}"?>">
                                    <label class="form-label" for="">Object Type</label>
                                    <select  name="objty" id="objty" class="form-control" onchange="getObjects(this);" required>
                                        <option value="">select OB Type</option>
                                        <?php if($user->role_id == '1' || $user->role_id == '2' ||$user->role_id == '6' ) {?>
                                            <option value="EMPL" <?php if($user->objty == 'EMPL') {echo 'selected';} ?>>EMPL : Employee</option>
                                        <?php } else { ?>
                                            <option value="PLNT" <?php if($user->objty == 'PLNT') {echo 'selected';} ?>>PLNT : Plant</option>
                                        <?php }        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="hidden" id="objky_val" value="<?php echo "{$user->objky}"?>">
                                    <label class="form-label" for="">Object Value</label>
                                    <select class="form-control" name="objky" id="objky"  required>
                                        <option value="">select OB Value</option>
                                        <?php foreach($items as $item) { ?>
                                            <option value="<?php echo "{$item->objky}"; ?>" <?php if($item->objky == $user->objky) {echo 'selected';} ?>><?php echo "{$item->objky} : {$item->objnm}"; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label class="form-label" for="">User Type</label>
                                    <select name="user_ty" class="form-control" required> 
                                        <option value=""        <?php if($user->user_ty == ""       ) { echo 'selected';} ?>>select user type   </option>
                                        <option value="manager" <?php if($user->user_ty == "manager") { echo 'selected';} ?>>Manager            </option>
                                        <option value="user"    <?php if($user->user_ty == "user"   ) { echo 'selected';} ?>>User               </option>
                                    </select>
                                </td>
                                <td>
                                    <label class="form-label" for="">User Status</label>
                                    <select name="user_st" class="form-control" required> 
                                        <option value=""  <?php if($user->user_st == "" ) { echo 'selected';} ?>>select user status </option>
                                        <option value="1" <?php if($user->user_st == "1") { echo 'selected';} ?>>Active           </option>
                                        <option value="0" <?php if($user->user_st == "0") { echo 'selected';} ?>>Inactive         </option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <button type="button" name="btnClose" class="btn btn-danger  float-right ml-4" value="cls" onclick="window.close();">Close</button>
                                    <button type="submit" name="setUser"  class="btn btn-success float-right "  value="<?php    switch($action) { 
                                                                                                                                case 'add' : echo 'add' ; break; 
                                                                                                                                case 'mod' : echo 'mod' ; break;
                                                                                                                                case 'view': echo 'view'; break;
                                                                                                                            } ?>">
                                                                                                                <?php   switch($action) {
                                                                                                                            case 'add' : echo 'Create User'; break;
                                                                                                                            case 'mod' : echo 'Modify User'; break;
                                                                                                                            default    : echo 'Modify'     ; break;
                                                                                                                        } ?></button>
                    

                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div class="col-md-3">
                </div>
            </div>
        </div>
    </div>
</div>

