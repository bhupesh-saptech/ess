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

    $query = "select * from supplier limit 50";
    $items = $conn->execQuery($query,$param);

    $query = "select * from usr_data";
    $users = $conn->execQuery($query,$param);
  
    $query = "select * from pur_grps";
    $pgrps = $conn->execQuery($query,$param);

?>
<div class="card">
    <div class="card-header">
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <table id="dtbl" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Supplier</th>    
                    <th>Supplier Name</th>
                    <th>Country</th>
                    <th>Region</th>
                    <th>City</th>
                    <th>Pin Code</th>
                    <th>Type</th>
                    <th>Pur.Group</th>
                    <th>Auth</th>
                </tr>
            </thead>
            <?php 
                if(isset($items)) {
                    foreach($items as $item) {
                        $item->lifnr = ltrim($item->lifnr,'0');
            ?>
                <tr>
                    <td><?php echo $item->objky; ?><input type="hidden" name="lifnr" value="<?php echo $item->supp_id ;?>"></td>
                    <td><?php echo $item->objnm; ?></td>      
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
                                    <?php echo $pgrp->ekgrp ."-". $pgrp->objnm;?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                    <td><a href="list_aobj.php?objty=supl&objky=<?php echo $item->objky;?>"><img class="img-fluid rounded mx-auto d-block" style="width:25px;height:25px" src="../assets/dist/img/auth.png"></a></td>
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