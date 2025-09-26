<?php 
    include('../incld/verify.php');
    include('../admin/check_auth.php');
    include('../incld/header.php');
    include('../incld/top_menu.php');
    include('../admin/side_menu.php');
    include('../incld/dbconn.php');
    $dtset  = $conn->query("select * from users;");
    $users = json_decode(json_encode($dtset->fetch_all(MYSQLI_ASSOC)));
    $dtset  = $conn->query("select * from prgroup;");
    $pgrps = json_decode(json_encode($dtset->fetch_all(MYSQLI_ASSOC)));
    $conn->close();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Purchasing Groups</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Purch Group</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div><!-- /.content-header -->
    <div class="container">
        <div class="row">
            <div class="col-md-12">

            </div><!-- /.col-md-col12 -->
        </div><!-- /.row -->
    </div><!-- /.container -->

    <div class="card">
        <div class="card-header">
        </div>
        <!-- /.card-header -->
        <div class="card-body">
    <table id="dtbl" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Group</th> 
            <th>Group Name</th>
            <th>Email ID</th>
            <th>Contact</th>
            <th>Mobile No</th>
            <th>Extension</th>
            <th>User ID</th>
        </tr>
    </thead>
    <?php 
        foreach($pgrps as $pgrp) { 
    ?>
            <tr>
                <td><?php echo $pgrp->ekgrp; ?> <input type="hidden" name="ekgrp" value="<?php echo $pgrp->ekgrp ;?>"></td>
                <td><?php echo $pgrp->eknam; ?> </td>
                <td><?php echo $pgrp->email; ?> </td>
                <td><?php echo $pgrp->ektel; ?> </td>
                <td><?php echo $pgrp->phone; ?> </td>
                <td><?php echo $pgrp->extno; ?> </td>
                <td>
                    <select class="form-control" name="user_id" onchange="setGUser(this);">
                        <option value=""></option>
                            <?php 
                                foreach($users as $user) {
                                    if($user->role_id == '2') {
                            ?>
                                <option value="<?php echo $user->user_id;?>" <?php if($user->user_id == $pgrp->user_id) {echo 'selected';}?>>
                            <?php 
                                echo $user->user_id;
                            ?>
                        </option>
                            <?php }
                                }
                            
                            ?>
                    </select>
                </td>
             </tr>
    <?php 
            } 
    ?>
</table>
</div>
    </div>
</div>


<?php
    include('../incld/jslib.php');
    include('../incld/footer.php');
?>