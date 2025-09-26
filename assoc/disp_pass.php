<?php
    include('../incld/verify.php');
    include('../qinsp/check_auth.php');
    require '../incld/autoload.php';
    $cntr = new Contr\VehPassContr();
    $conn = new Model\Conn();
    if(isset($_POST['submit'])) {
        $rqst = json_decode(json_encode($_POST));
        $rqst->user_id = $_SESSION['user_id'];
        switch($rqst->submit) {
            case 'accept' : 
                $rqst->zzins = 'A';
                $rqst->pstatus = 'QA';
                $cntr->updtVPStatus($rqst);

                $query = "update veh_pass set zzins = ? and zcomm = ? where pass_id= ?";
                $param = array($rqst->zzins,$rqst->zcomm,$rqst->pass_id);
                $item  = $conn->execQuery($query,$param,1);
                
                $query = "update veh_item set zflag = 'Q' where pass_id= ?";
                $param = array($rqst->pass_id);
                $item  = $conn->execQuery($query,$param,1);

                $_SESSION['status'] = "Vehicle Accepted";
                break;
            case 'reject' :
                $rqst->zzins = 'R';
                $rqst->pstatus = 'QR';
                $cntr->updtVPStatus($rqst);

                $query = "update veh_pass set zzins = ? and zcomm = ? where pass_id= ?";
                $param = array($rqst->zzins,$rqst->zcomm,$rqst->pass_id);
                $item  = $conn->execQuery($query,$param,1);
                $_SESSION['status'] = "Vehicle Rejected";
                break;
            case 'review' :
                $rqst->zzins = 'X';
                $rqst->pstatus = 'QX';
                $cntr->updtVPStatus($rqst);
                
                $query = "update veh_pass set zzins = ? and zcomm = ? where pass_id= ?";
                $param = array($rqst->zzins,$rqst->zcomm,$rqst->pass_id);
                $item  = $conn->execQuery($query,$param,1);
                $_SESSION['status'] = "Vehicle put for Review";
                break;
        }
        if(isset($_SESSION['pref_id'])) {
            $page_id = $_SESSION['pref_id'];
            header("location:".$page_id);
        }
    }
    if(isset($_REQUEST['pass_id'])) {
        $pass_id = $_REQUEST['pass_id'];
    } else {
        $pass_id = "";
    }
    $query = "select * from veh_data where pass_id = ?";
    $param = array($pass_id);
    $item  = $conn->execQuery($query,$param,1);
    $item->lifnr = (int)$item->lifnr;
    $query = "select * from veh_item where pass_id = ?";
    $param = array($pass_id);
    $items = $conn->execQuery($query,$param);
    if(!isset($items)) {
        $item->cstat = 'XX';
        $_SESSION['status'] = 'There are no invoices in this Vehicle';
    }

    include('../incld/header.php');
?>        
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <!-- left column -->
            </div>
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-9">
                                <h3 class="card-title">Vehicle Gate Pass Details</h3>
                            </div>
                        </div>
                    </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php include('../incld/messages.php'); ?>
                            <form method="POST"  >
                                <div class="modal-footer">

                                    <?php   if(isset($_SESSION['user_ty'])) {
                                                $user_ty = $_SESSION['user_ty'];
                                            } else {
                                                $user_ty = 'user';
                                            }
                                            switch($user_ty) {
                                                case 'user' :
                                                    switch($item->cstat) {
                                                        case 'VA' :
                                    ?>   
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" >Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" >Reject</button> 
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" >Review</button> 
                                    <?php
                                                            break;
                                                        case 'QA' :
                                    ?>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" disabled>Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" >Reject</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" >Review</button> 
                                    <?php
                                                        break;
                                                        case 'QR' :
                                    ?>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" >Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" disabled >Reject</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" disabled>Review</button> 
                                    <?php
                                                        break;
                                                        case 'QX' :
                                    ?>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" disabled>Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" disabled>Reject</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" disabled>Review</button>
                                    <?php
                                                            break;
                                                        default :
                                    ?>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" disabled >Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" disabled >Reject</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" disabled >Review</button>  
                                    <?php
                                                            break;
                                                    }
                                                    break;
                                                case 'manager' :
                                                    switch($item->cstat) {
                                                        case 'VA' :
                                    ?>   
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" >Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" >Reject</button> 
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" >Review</button> 
                                    <?php
                                                            break;
                                                        case 'QA' :
                                    ?>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" disabled>Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" >Reject</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" disabled>Review</button> 
                                    <?php
                                                        break;
                                                        case 'QR' :
                                    ?>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" >Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" disabled >Reject</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" disabled>Review</button> 
                                    <?php
                                                        break;
                                                        case 'QX' :
                                    ?>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" >Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" >Reject</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" disabled>Review</button>
                                    <?php
                                                            break;
                                                        default :
                                    ?>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-success"  value="accept" disabled >Accept</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-danger"   value="reject" disabled >Reject</button>
                                                            <button type="submit" name="submit" class="btn btn-secondary btn-warning"  value="review" disabled >Review</button>  
                                    <?php
                                                            break;
                                                    }
                                                    break;
                                            }
                                        ?>
                                </div>
                                <div class="form-group">
                                    <label for="">Inspection Remarks</label>
                                    <input type="text" name="zcomm" class="form-control" value="<?php echo $item->zcomm; ?>"  >
                                </div>
                                <div class="form-group">
                                    <label for="">Usage Decision</label>
                                    <input type="text" name="zzins" class="form-control <?php echo $item->vccol; ?>" value="<?php echo $item->idesc; ?>"  readonly >
                                </div>
                                <div class="form-group">
                                    <label for="">Vehicle No</label>
                                    <input type="text" name="zvehn"   class="form-control bg-primary" value="<?php echo $item->zvehn; ?>"   readonly >
                                </div>
                                <div class="form-group">
                                    <label for="">Supplier</label>
                                    <input type="text" name="lifnr"  class="form-control " value="<?php echo "{$item->lifnr}_{$item->sname}"; ?>"   readonly >
                                </div>
                                <div class="form-group">
                                    <label for="">Vehicle Pass</label>
                                    <input type="text" name="pass_id" class="form-control" value="<?php echo $item->pass_id; ?>" readonly >
                                </div>
                                <div class="form-group">
                                    <label for="">Gate Pass</label>
                                    <input type="text" name="zvpno" class="form-control"   value="<?php echo $item->zvpno; ?>"    readonly >
                                </div>
                                <div class="form-group">
                                    <label for="">Transporter</label>
                                    <input type="text" name="ztnam"   class="form-control" value="<?php echo $item->ztnam; ?>"   readonly >
                                </div>
                                <div class="form-group">
                                    <label for="">Driver Name</label>
                                    <input type="text" name="zdnam" class="form-control" value="<?php echo $item->zdnam;?>"    readonly >
                                </div>
                                <div class="form-group">
                                    <label for="">Driver Phone</label>
                                    <input type="text" name="zmbno"   class="form-control" value="<?php echo $item->zmbno; ?>"   readonly >
                                </div>

                                <div class="form-group">
                                    <label for="">Shipment</label>
                                    <input type="text" name="tknum" class="form-control"   value="<?php echo $item->tknum; ?>"    readonly >
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
    include('../incld/jslib.php');
    include('../incld/footer.php');
?>
