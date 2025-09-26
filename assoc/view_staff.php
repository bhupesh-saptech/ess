<?php
    require '../incld/verify.php';
    require '../assoc/check_auth.php';
    require '../incld/autoload.php';
    $sess = json_decode(json_encode($_SESSION));
    $rqst = json_decode(json_encode($_GET));
    $util = new Model\Util();
    if ($sess->user_id == 'tmoff') {
        $cntr = new Contr\StaffContr();
        $cntr->loginTime($rqst);
        $_SESSION['status'] = 'Time Updated.';
    }
    $query = "select * from staff where staff_id = ?";
    $param = array($rqst->staff_id);
    $staff = $util->execQuery($query,$param,1);
    $query = "SELECT * FROM time_sheet WHERE staff_id = ? and YEAR(date_id) = YEAR(CURDATE()) AND MONTH(date_id) = MONTH(CURDATE())  order by date_id";
    $param = array($rqst->staff_id);
    $items = $util->execQuery($query,$param);
    
    include('../incld/header.php');
?>        
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-8">
                                <h3 class="card-title">Employee Details</h3>
                            </div>
                        </div>
                    </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php include('../incld/messages.php'); ?>
                        </div>
                    </div>
                    <table width="100%" class="table table-bordered table-striped">
                        <tr>
                            <td style="width:30%;"><?php echo $staff->staff_id ; ?></td>
                            <td style="width:30%;" colspan="3"><?php echo $staff->emp_name ; ?></td>
                            
                        </tr>
                            <td>Punch Date</td>
                            <td>In Time</td>
                            <td>Out Time</td>
                            <td>Duration</td>
                        <tr>
                        </tr>
                        <?php $wo = [1,7]; foreach($items as $item) { ?>
                        <tr>
                            <td class="<?php if( $item->date_id == date('Y-m-d')) { echo 'table-success'; }?>"><?php echo $item->date_id ; ?></td>
                            <td class="<?php if( in_array($item->wkday,$wo)) { echo 'table-danger'; }?>"><?php echo $item->in_time  ; ?></td>
                            <td class="<?php if( in_array($item->wkday,$wo)) { echo 'table-danger'; }?>"><?php echo $item->out_time  ; ?></td>
                            <td class="<?php if( in_array($item->wkday,$wo)) { echo 'table-danger'; }?>"><?php echo $item->duration  ; ?></td>
                        </tr>
                        <?php } ?>
                    </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    include('../incld/jslib.php');
    // include('../incld/footer.php');
?>
