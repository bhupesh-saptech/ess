<?php 
    include('../incld/verify.php');
    include('../admin/check_auth.php');
    include('../incld/header.php');
    include('../incld/top_menu.php');
    include('../admin/side_menu.php');
    include('../incld/dbconn.php');
    
    $dtset = $conn->query("select * from supplier limit 50");
    $items = json_decode(json_encode($dtset->fetch_all(MYSQLI_ASSOC)));
    
    $dtset = $conn->query("select * from users");
    $users = json_decode(json_encode($dtset->fetch_all(MYSQLI_ASSOC)));

    $dtset = $conn->query("select * from prgroup");
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
            <h1 class="m-0">Vendor Master</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Vendor Master</li>
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
            <th>Vendor </th>    
            <th>Vendor Name</th>
            <th>Country</th>
            <th>Region</th>
            <th>City</th>
            <th>Pin Code</th>
            <th>Type</th>
            <th>Pur.Group</th>
            <th>UserID</th>
        </tr>
    </thead>
    <?php 
        if(isset($items)) {
            foreach($items as $item) {
                $item->suppl = $item->lifnr;
                $item->lifnr = ltrim($item->lifnr,'0');
    ?>
             <tr>
                 <td><?php echo $item->lifnr; ?> <input type="hidden" name="lifnr" value="<?php echo $item->suppl ;?>"></td>
                 <td><?php echo $item->name1; ?></td>      
                 <td><?php echo $item->land1; ?></td>      
                 <td><?php echo $item->regio; ?></td>      
                 <td><?php echo $item->ort01; ?></td>      
                 <td><?php echo $item->pstlz; ?></td>      
                 <td><?php echo $item->ktokk; ?></td>   
                 <td>
                        <select class="form-control" name="ekgrp"  onchange="setSGroup(this);" >
                            <option value=""></option>
                            <?php 
                                foreach($pgrps as $pgrp) {
                            ?>
                                <option value="<?php echo $pgrp->ekgrp;?>" <?php if($pgrp->ekgrp == $item->ekgrp) {echo 'selected';}?>>
                                    <?php echo $pgrp->ekgrp ."-". $pgrp->eknam;?>
                                </option>
                            <?php } ?>
                        </select>
                </td>
                 <td>
                    <select class="form-control" name="user_id"  onchange="setSUser(this);" >
                        <option value=""></option>
                        <?php 
                            foreach($users as $user) {
                                if($user->role_id == '6') {
                        ?>
                            <option value="<?php echo $user->user_id;?>" <?php if($user->user_id == $item->user_id) {echo 'selected';}?>>
                                <?php echo $user->user_id;?>
                            </option>
                        <?php } 
                            } ?>
                    </select>
                </td>
             </tr>
    <?php 
            } 
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