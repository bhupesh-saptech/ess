<?php
    require '../incld/verify.php';
    require '../incld/autoload.php';
    $util = new Model\Util();
    $cntr = new Contr\ZpciContr();
    if(isset($_GET['readCap'])) {
        $rqst = json_decode(json_encode($_GET));
        $rqst->wkday = date("N",strtotime($rqst->budat));
        $rqst->wkday = str_pad($rqst->wkday,3,'0',STR_PAD_LEFT);
        $rqst->objky = "{$rqst->werks}{$rqst->arbpl}{$rqst->wkday}{$rqst->tprog}";
        $query = "select * from shift where tprog = ?";
        $param = array($rqst->tprog);
        $shft  = $util->execQuery($query,$param,1);
        $query = "select * from capacity where objky = ?";
        $param = array($rqst->objky);
        $item  = $util->execQuery($query,$param,1);
        
        if(!isset($item)) {
            $item = new stdClass();
            $item->zicap = 0;
        }
        $item->begzt = $shft->begzt;
        $item->endzt = $shft->endzt;
        echo json_encode($item);
        die();
    }
    $_SESSION['pref_id'] = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if(isset($_POST['action'])) {
        $rqst = json_decode(json_encode($_POST));
        
        switch($rqst->action) {
            case 'addConf' :
                $rqst->objky = "{$rqst->werks}{$rqst->arbpl}". date("Ymd",strtotime($rqst->budat)). "{$rqst->tprog}";
                $query = "select * from zpch where objky = ?";
                $param = array($rqst->objky);
                $item  = $util->execQuery($query,$param,1);
                if(!isset($item)) {
                    $cntr->createZpch($rqst);
                    $url = "../pconf/disp_pconf.php?objky={$rqst->objky}";
                    echo "<script>window.open('$url', '_blank');</script>";
                } else {
                    $_SESSION['status'] = "Confirmation Key - {$item->objky} already exists";
                }
                break;
            case 'modConf' :
                $query = "update zpch 
                             set zacap = ?,
                                 zunam = ?,
                                 zrmks = ?
                           where objky = ?";
                $param = array($rqst->zacap,$rqst->zunam,$rqst->zrmks,$rqst->objky);
                $item  = $util->execQuery($query,$param,1);
                $_SESSION['status'] = "Confirmation Key - {$rqst->objky} updated Successfully";     
                break;
            case 'delConf' :
                $query = "delete from zpch where objky = ?";
                $param = array($rqst->objky);
                $item  = $util->execQuery($query,$param,1);
                $_SESSION['status'] = "Confirmation Key - {$rqst->objky} deleted Successfully"; 
                break;
        }
    }

    
    require '../pconf/check_auth.php';
    require '../incld/header.php';
    require '../incld/top_menu.php';
    $sess = json_decode(json_encode($_SESSION));
    date_default_timezone_set('Asia/Kolkata');
    if(isset($_POST['filter'])) {
        $rqst = $rqst = json_decode(json_encode($_POST));
        $dt_from = $rqst->dt_from;
        $dt_upto = $rqst->dt_upto;
        $pc_stat = $rqst->pc_stat;
    } else {
        $dt_from = date('Y-m-01'); //$sess->from_dt;
        $dt_upto = date('Y-m-d'); 
        $pc_stat = 'A';
    }
    if(isset($_GET['arbpl'])) {
        $rqst = json_decode(json_encode($_GET)); 
    } 
    $query = "select * from users where user_id = ?";
    $param = array($sess->user_id);
    $auth  = $util->execQuery($query,$param,1);

    $query = "select * from yusr";
    $param = array();
    $users = $util->execQuery($query,$param);

    $query = "select * from shift";
    $param = array();
    $shift = $util->execQuery($query,$param);

    $query = "select * from wcenter where werks = ? and arbpl = ?";
    $param = array($rqst->werks,$rqst->arbpl);
    $wctr  = $util->execQuery($query,$param,1);  
    switch($pc_stat) {
        case 'A' :
            $query = "select * from zpch where werks = ? and arbpl = ? and budat between ? and ? order by budat desc";
            $param = array($rqst->werks,$rqst->arbpl,$dt_from,$dt_upto);
            break;
        default  :
            $query = "select * from zpch where werks = ? and arbpl = ? and budat between ? and ? and zflag = ? order by budat desc";
            $param = array($rqst->werks,$rqst->arbpl,$dt_from,$dt_upto,$pc_stat);
            break;
    }
    
    $items = $util->execQuery($query,$param);


   
?>
<div class="card">
    <div class="card-header">
        <?php require '../incld/messages.php'; ?>
        <div>
            <form method="post"> 
                <table class="table table-bordered table-striped" style="width:100%">
                    <tr class="bg-dark">
                        <td style="width:50%">
                            <h3>Confirmation Filter </h3>  
                        </td>
                        <td style="width:15%">
                            <label for="" class="form-label mt-2">From Date</label>
                            <input type="date" class="form-control" name="dt_from" value="<?php echo "{$dt_from}"; ?>">
                        </td>
                        <td style="width:15%">
                            <label for="" class="form-label mt-2">Upto Date</label>
                            <input type="date" class="form-control" name="dt_upto" value="<?php echo "{$dt_upto}"; ?>">
                        </td>
                        <td style="width:30%">
                            <label for="" class="form-label mt-2">Confirm Status</label>
                            <div class="input-group mb-3">
                                <select name="pc_stat" class="form-control" >
                                    <option value=""  <?php if($pc_stat == "" ) {echo "selected" ;} ?>>Select Status</option>
                                    <option value="A" <?php if($pc_stat == "A") {echo "selected" ;} ?>>All Items  </option>
                                    <option value="P" <?php if($pc_stat == "P") {echo "selected" ;} ?>>Partial   </option>
                                    <option value="C" <?php if($pc_stat == "C") {echo "selected" ;} ?>>Complete   </option>
                                    <option value="S" <?php if($pc_stat == "S") {echo "selected" ;} ?>>Success   </option>
                                    <option value="F" <?php if($pc_stat == "F") {echo "selected" ;} ?>>Failed    </option>
                                    
                                </select>
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" name="filter" value="filter">
                                        <i class="fa fa-filter"></i>
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <div class="card-body">
        <form method="post" autocomplete="off" onkeydown="return preventEnter(event);" onsubmit="chkForm(this,event);">
            <table class="table table-bordered table-stripped" >
                <tr>
                    <td style="width:20%;">
                        <div class="row">
                            <div class="col-sm-4">
                                <label class="form-label">Plant</label>
                                <input type="text" name="werks" class="form-control" value="<?php echo "{$wctr->werks}"; ?>" readonly>
                            </div>
                            <div class="col-sm-8">
                                <label class="form-label">WorkCenter</label>
                                <input type="text" name="arbpl" class="form-control" value="<?php echo "{$wctr->arbpl}"; ?>" readonly>
                            </div>
                        </div>
                        <label class="form-label">Work Center Desc</label>
                        <input type="text" name="ktext" class="form-control" value="<?php echo "{$wctr->objnm}"; ?>" readonly>
                    </td>
                    <td style="width:20%;">
                        <label class="form-label">Confirm Dt</label>
                        <input type="date" name="budat" class="form-control" value="<?php echo date('Y-m-d') ;?>" onfocus="this.max = new Date().toISOString().split('T')[0]" required>
                        <label class="form-label">Shift</label>
                        <select name="tprog" class="form-control" onchange="getShift(this);" required >
                            <option value="">select Shift</option>
                            <?php foreach($shift as $shft) { ?>
                                <option value="<?php echo "{$shft->tprog}";?>" ><?php echo "{$shft->tprog}_{$shft->ktext}";?></option>
                            <?php } ?>
                        </select>
                    </td>
                    <td style="width:15%;">
                        <label class="form-label">Start Time</label>
                        <input type="text" name="begzt" class="form-control" value="" readonly>
                         <label class="form-label">End Time</label>
                        <input type="text" name="endzt" class="form-control" value="" readonly>
                    </td>
                
                    <td style="width:15%;">
                        <label class="form-label">Inst.Cap</label>
                        <input type="text" name="zicap" class="form-control text-right" value="" readonly>
                         <label class="form-label">Add.Cap</label>
                        <input type="text" name="zacap" class="form-control text-right" value="0" <?php if($auth->suser == 0 ) { echo 'readonly'; }?> >
                    </td>

                    <td style="width:20%;">
                        <label class="form-label">User</label>
                        <select name="zunam" class="form-control select2" required >
                            <option value="">select user</option>
                            <?php foreach($users as $user) { ?>
                                <option value="<?php echo "{$user->user_nm}";?>"><?php echo "{$user->user_nm}";?></option>
                            <?php } ?>
                        </select>
                         <label class="form-label">Remarks</label>
                        <input type="text" name="zrmks" class="form-control" value="" >
                    </td>
                    <td style="width:5%;">
                        <lable for="" class="form-label">Action</label>
                        <button type="submit" name="action" value="addConf" class="btn btn-primary">
                            <i class="fa fa-plus"></i>
                        </button>
                    </td>
                </tr>
            </table>
        </form>
        <table class="table table-bordered table-stripped" id="dtbl">
            <thead>
                <tr>
                    <th style="width:03%;">Status</th>
                    <th style="width:12%;">Confirmation Key</th>
                    <th style="width:03%;">Plant</th>
                    <th style="width:05%;">WCenter</th>
                    <th style="width:10%;">Description</th>
                    <th style="width:08%;">Confirm Dt</th>
                    <th style="width:05%;">Shift</th>
                    <th style="width:08%;">Install Cap<br>Extra Cap</th>
                    <th style="width:08%;">Total Cap<br>Utilized Cap</th>
                    <th style="width:15%;">Prod User<br>Remarks</th>
                    <th style="width:03%;">Edit</th>
                    <th style="width:03%;">Delete</th>
                    
                </tr>
            </thead>
            </tbody>
            <?php if(isset($items)) {
                    foreach($items as $item) {
                        $item->ztcap = $item->zicap + $item->zacap; ?>
                        <td class="text-center"><a href="javascript:void(0)" onclick="newTab('<?php echo 'disp_pclog.php?objky='.$item->objky; ?>');" ><i class='fas fa-traffic-light' style='font-size:20px;  <?php switch($item->zflag) {
                                                                                                                
                                                                                                                case 'C' : echo 'color:orange';break;
                                                                                                                case 'F' : echo 'color:red'   ;break;
                                                                                                                case 'P' : echo 'color:blue'  ;break;
                                                                                                                case 'S' : echo 'color:green' ;break;
                                                                                                                default  : echo ''            ;break;
                                                                                                            }
                                                                                                        ?>'></i></a></td>
                        <td class="text-left"  ><a href="javascript:void(0)" onclick="newTab('<?php echo 'disp_pconf.php?objky='.$item->objky; ?>');" ><?php echo "{$item->objky}"; ?></a></td>
                        <td class="text-left"  ><?php echo "{$item->werks}"; ?> </td>
                        <td class="text-left"  ><?php echo "{$item->arbpl}"; ?> </td>
                        <td class="text-left"  ><?php echo "{$wctr->objnm}"; ?> </td>
                        <td class="text-left"  ><?php echo "{$item->budat}"; ?> </td>
                        <td class="text-left"  ><?php echo "{$item->tprog}"; ?> </td>
                        <td class="text-right" ><input type="number" name="zicap" class="form-control text-right-align" value="<?php echo "{$item->zicap}"; ?>" readonly>
                                                <input type="number" name="wacap" class="form-control text-right-align" value="<?php echo "{$item->zacap}"; ?>" readonly onblur="updtCap(this);" > </td>
                        <td class="text-right" ><input type="number" name="ztcap" class="form-control text-right-align" value="<?php echo "{$item->ztcap}"; ?>" readonly>
                                                <input type="number" name="zucap" class="form-control text-right-align" value="<?php echo "{$item->zucap}"; ?>" readonly></td>
                        <td>
                            <select name="zuser" class="form-control" required disabled onchange="updtUser(this);">
                                <option value="">select user</option>
                                <?php foreach($users as $user) { ?>
                                    <option value="<?php echo "{$user->user_nm}";?>" <?php if($user->user_nm == $item->zunam) {echo 'selected' ; }?>><?php echo "{$user->user_nm}";?></option>
                                <?php } ?>
                            </select>
                            <input type="text" name="zrmks" class="form-control" value="<?php echo "{$item->zrmks}"; ?>" onblur="updtRmks(this);" readonly>
                        </td>
                        <td class="text-center">
                            <form method="post">
                                <input type="hidden" name="objky" value="<?php echo "{$item->objky}"; ?>">
                                <input type="hidden" name="werks" value="<?php echo "{$item->werks}"; ?>">
                                <input type="hidden" name="arbpl" value="<?php echo "{$item->arbpl}"; ?>">
                                <input type="hidden" name="zacap" value="<?php echo "{$item->zacap}"; ?>">
                                <input type="hidden" name="zunam" value="<?php echo "{$item->zunam}"; ?>">
                                <input type="hidden" name="zrmks" value="<?php echo "{$item->zrmks}"; ?>">
                                <button type="button" name="action" class="btn " value="modConf" onclick="btnToggle(this,event);"; style="font-size:0.8em" <?php if($item->zflag == 'S') { echo 'disabled'; }?>>
                                    <i class="fas fa-edit text-primary"> </i>
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <form method="post">
                                <input type="hidden" name="objky" value="<?php echo "{$item->objky}"; ?>">
                                <button type="submit" name="action" value="delConf" class="btn" style="font-size:0.8em" onclick="return confirm('Are you sure you want to delete this record?');" <?php if($item->zflag == 'S') { echo 'disabled'; }?>>
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                
            <?php }
            }?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../incld/jslib.php'; ?>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $("#dtbl").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#dtbl_wrapper .col-md-6:eq(0)');
    });

    function MyLoad() {
    }
    function getShift(obj) {
        let rowno = $(obj).closest('tr'); // ✅ Find the correct row
        let werks = rowno.find('input[name="werks"]').val();
        let arbpl = rowno.find('input[name="arbpl"]').val();
        let budat = rowno.find('input[name="budat"]').val();
        let tprog = $(obj).val();
        $.get(window.location.href, { werks: werks,arbpl:arbpl,budat:budat,tprog:tprog, readCap: true }, function(data) {
            console.log(data);
             var oData = JSON.parse(data);
             rowno.find('input[name="begzt"]').val(oData.begzt);
             rowno.find('input[name="endzt"]').val(oData.endzt);
             rowno.find('input[name="zicap"]').val(oData.zicap).trigger('change');
        });
    }
    function preventEnter(e) {
        
      if (e.key === "Enter") {
        debugger;
        e.preventDefault(); // Prevent form submission
        return false;
      }
    }
    function chkForm(obj,event) {
        let form = $(obj);
        let icap = form.find("input[name='zicap']").val();
        let acap = form.find('input[name="zacap"]').val();
        let rmks = form.find('input[name="zrmks"]').val();
        let tcap = Number(icap) + Number(acap);
 
        if(Number(tcap) <= 0) {
            alert('Total Capacity Can not be Zero');
            event.preventDefault();
        } 
        if(Number(acap) > 0 && rmks == '') {
            alert('Please enter Remarks for OverTime');
            event.preventDefault();
        } 
    }
    function btnToggle(obj,event) {
        if (obj.type === "button") {
             cur_row = $(obj).closest('tr');
             cur_row.find('input[name="wacap"]').attr('readonly', false);
             cur_row.find('input[name="zrmks"]').attr('readonly', false);
             cur_row.find('select[name="zuser"]').attr('disabled', false);
             obj.type = "submit"; // ✅ Change to submit
             event.preventDefault();
            obj.innerHTML = '<i class="fa fa-save"></i>';
        } 
    }
    function updtCap(obj){
        debugger;
        cur_row = $(obj).closest('tr');
        zicap = cur_row.find('input[name="zicap"]').val();
        wacap = cur_row.find('input[name="wacap"]').val();
        ztcap = Number(zicap) + Number(wacap);
        cur_row.find('input[name="ztcap"]').val(ztcap);
        cur_row.find('input[name="zacap"]').val(wacap);
    }
    function updtUser(obj){
        cur_row = $(obj).closest('tr');
        zuser = cur_row.find('select[name="zuser"]').val();
        cur_row.find('input[name="zunam"]').val(zuser);
    }
    function updtRmks(obj){
        cur_row = $(obj).closest('tr');
        zrmks = cur_row.find('input[name="zrmks"]').val();
        cur_row.find('input[name="zrmks"]').val(zrmks);
    }
    
    function newTab(url) {
        window.open(url,'_blank');
    }
</script>
<?php include '../incld/footer.php';?>