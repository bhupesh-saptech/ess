<?php
    require '../incld/verify.php';
    require '../pconf/check_auth.php';
    require '../vendor/autoload.php';
    require '../incld/autoload.php';
    date_default_timezone_set('Asia/Kolkata');
    use GuzzleHttp\Client;
    $util = new Model\Util();
    $cntr = new Contr\ZpciContr();
    $sapc = new Contr\SAPContr();

    $sess = json_decode(json_encode($_SESSION));

    // $client = new Client([
    //     "base_uri" => "https://prd.pmipl.in:44301",
    //     "auth" => ['SAPSUPPORT', 'Phoenix2025#']
    // ]);

    $client = new Client([
        "base_uri" => "https://prd.pmipl.in:44300",
        "auth" => ['kpab', 'Phoenix!2024']
    ]);

    if(isset($_GET['getOrder'])) {
        $rqst = json_decode(json_encode($_GET));
        $rqst->im_werks = substr($rqst->objky,0,4);
        $rqst->im_arbpl = substr($rqst->objky,4,6);
        $rqst->im_aufnr = str_pad($rqst->im_aufnr, 12, "0", STR_PAD_LEFT);
        $query =  [ 'im_werks' => $rqst->im_werks,
                    'im_arbpl' => $rqst->im_arbpl,
                    'im_aufnr' => $rqst->im_aufnr ];
        $headr =  [ 'Accept' => 'application/json'];
        try {
            $resp = $client->request('GET',"/sap/bc/bsp/sap/zprd_conf/read_afpo.html", ["query" => $query,"header" => $headr]);  
            echo  $resp->getBody()->getContents();
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                echo "Error Response: " . $e->getResponse()->getBody();
            } else {
                echo "Request Error: " . $e->getMessage();
            }
        }
        die();
    }
    if(isset($_GET['chkOrder'])) {
        $rqst = json_decode(json_encode($_GET));
        $rqst->im_aufnr = str_pad($rqst->im_aufnr, 12, "0", STR_PAD_LEFT);
        $util->writeLog(json_encode($rqst));
        $query = "select * from zpci where objky = ? and aufnr = ?";
        $param = array($rqst->im_objky,$rqst->im_aufnr);
        $order = $util->execQuery($query,$param,1);
        $data = new stdClass();
        if(isset($order)) {
            $data->exists = true;
        } else {
            $data->exists = false;
        }
        echo (json_encode($data));
        die();
    }

    if(isset($_POST['action'])) {
        $rqst = json_decode(json_encode($_POST));
        switch($rqst->action) { 
            case 'addSAP' :
                $query = "select * from zpch where objky = ?";
                $param = array($rqst->objky);
                $item  = $util->execQuery($query,$param,1);
                if($item->zflag != 'S') {
                    $query = "update zpch set zresp = ? where objky = ?";
                    $param = array($data,$rqst->objky);
                    $item  = $util->execQuery($query,$param,1);
                    $data  = json_decode($data);
                    if($data->status == "Success") {
                        $query = "update zpch set zflag = 'S'  where objky = ?";
                        $param = array($rqst->objky);
                        $item  = $util->execQuery($query,$param,1);
                    } else {
                        $query = "update zpch set zflag = 'F'  where objky = ?";
                        $param = array($rqst->objky);
                        $item  = $util->execQuery($query,$param,1);
                    }
                    $data = json_decode(json_encode($data));
                    $query = "select * from zpci where objky = ?";
                    $param = array($rqst->objky);
                    $items  = $util->execQuery($query,$param);
                    $data = new stdClass();
                    $data->pconf = $item;
                    $data->items = $items;
                    $json = json_encode($data);
                    $hdrs = ['Content-Type' => 'application/json'];
                    $resp = $client->post("/sap/bc/bsp/sap/zprd_conf/zprd_updt.html", [ "body"      => $json,
                                                                                        "headers"   => $hdrs]);
                    $data = str_replace([ "\r", "\n"], "", $resp->getBody()->getContents());
                    $_SESSION['status'] = "{$rqst->objky} - Posting : {$data->status}";
                    header("Location: ../pconf/disp_pclog.php?objky=".$rqst->objky);
                } else {
                    $_SESSION['status'] = "{$rqst->objky} - already posted successfully";
                }
                break;
            case 'modOrder' :
                $item = $cntr->modifyZpci($rqst);
                $query = "select IFNULL(sum(    zot00 +
                                                zdt01 +
                                                zdt02 +
                                                zdt03 +
                                                zdt04 +
                                                zdt05 +
                                                zdt06 +
                                                zdt07 +
                                                zdt08 ),0) as zucap 
                                from  zpci 
                                where  objky = ?";
                $param = array($rqst->objky);
                $item  = $util->execQuery($query,$param,1);
                if(!isset($item)) {
                    $item = new stdClass();
                    $item->zucap = 0;
                }
                $query = "update zpch set zucap = {$item->zucap},
                                          zbcap = zicap + zacap - {$item->zucap} 
                                    where objky = ?";
                $param = array($rqst->objky);
                $item  = $util->execQuery($query,$param,1);
                $_SESSION['status'] = "{$rqst->aufnr}_{$rqst->vornr} - updated successfully";
                break;
                case 'addOrder' :
                    $rqst->aufnr = str_pad($rqst->aufnr, 12, "0", STR_PAD_LEFT);
                    $item = $cntr->createZpci($rqst);
                    $query = "select * from zpch where objky = ?";
                    $param = array($rqst->objky);
                    $item  = $util->execQuery($query,$param,1);
                    $query = "select * from zpci where objky = ?";
                    $param = array($rqst->objky);
                    $items  = $util->execQuery($query,$param);
                    $data = new stdClass();
                    $data->pconf = $item;
                    $data->items = $items;
                    $json = json_encode($data);
                    
                    $hdrs = ['Content-Type' => 'application/json'];
                    $resp = $client->post("/sap/bc/bsp/sap/zprd_conf/updt_prod.html", [ "body"      => $json,
                                                                                        "headers"   => $hdrs]);
                    $data = str_replace([ "\r", "\n"], "", $resp->getBody()->getContents());
                    
                    
                //    $ordr = $sapc->readOrder($client,$rqst);
                //     if(!is_null($ordr)) {
                //         $ordr = json_decode($ordr);
                //         foreach($ordr as $ord) {
                //             if ($ord->psmng > 0 ) {
                //                 
                //                 $query = "select IFNULL(sum(    zot00 +
                //                                                 zdt01 +
                //                                                 zdt02 +
                //                                                 zdt03 +
                //                                                 zdt04 +
                //                                                 zdt05 +
                //                                                 zdt06 +
                //                                                 zdt07 +
                //                                                 zdt08 ),0) as zucap 
                //                                 from  zpci 
                //                                 where  objky = ?";
                //                 $param = array($rqst->objky);
                //                 $item  = $util->execQuery($query,$param,1);
                //                 if(!isset($item)) {
                //                     $item = new stdClass();
                //                     $item->zucap = 0;
                //                 }
                //                 $query = "select * from zpch where objky = ?";
                //                 $param = array($rqst->objky);
                //                 $pcnf  = $util->execQuery($query,$param,1);
                //                 $pcnf->zucap = $item->zucap;
                //                 $pcnf->zbcap = $pcnf->zicap + $pcnf->zacap - $item->zucap;
                //                 if($pcnf->zbcap == 0 ) {
                //                     $pcnf->zflag = 'C';
                //                 } else {
                //                     $pcnf->zflag = 'P';
                //                 }
                //                 $query = "update zpch set zucap = ?,
                //                                         zbcap = ?,
                //                                         zflag = ? 
                //                                     where objky = ?";
                //                 $param = array($pcnf->zucap,$pcnf->zbcap,$pcnf->zflag,$rqst->objky);
                //                 $item  = $util->execQuery($query,$param,1);
                //                 $_SESSION['status'] = "{$rqst->aufnr}_{$rqst->vornr} - added successfully";
                //             } else {
                //                 $_SESSION['status'] = "Open Qty ZERO - {$rqst->aufnr}";
                //             }
                //         }
                //     }
                    break;
            case 'delOrder' :
                $query = "delete from zpci where objky = :objky
                                             and aufnr = :aufnr
                                             and vornr = :vornr
                                             and ztype = :ztype";
                
                $param = array( ':objky' => $rqst->objky,
                                ':aufnr' => $rqst->aufnr,
                                ':vornr' => $rqst->vornr,
                                ':ztype' => $rqst->ztype );
                $util->execQuery($query,$param);
                $query = "select IFNULL(sum(   zot00 +
                                                zdt01 +
                                                zdt02 +
                                                zdt03 +
                                                zdt04 +
                                                zdt05 +
                                                zdt06 +
                                                zdt07 +
                                                zdt08 ),0) as zucap 
                                from  zpci 
                                where  objky = ?";
                $param = array($rqst->objky);
                $item  = $util->execQuery($query,$param,1);
                
                $query = "update zpch set zucap = {$item->zucap},
                                          zbcap = zicap + zacap - {$item->zucap} 
                                    where objky = ?";
                $param = array($rqst->objky);
                $item  = $util->execQuery($query,$param,1);
                break;
        }
    }
    require '../incld/header.php';
    require '../incld/top_menu.php';
    // require '../pconf/side_menu.php';
    // require '../pconf/dashboard.php';
    if(isset($_REQUEST['objky'])) {
        $rqst = json_decode(json_encode($_REQUEST));
        $query = "select * from zpch where objky = ?";
        $param = array($rqst->objky);
        $pcnf  = $util->execQuery($query,$param,1);
        $pcnf->ztcap = $pcnf->zicap + $pcnf->zacap;
        $pcnf->zbcap = $pcnf->ztcap - $pcnf->zucap;
        $pcnf->etime = new DateTime("{$pcnf->budat} {$pcnf->endzt}");
        $pcnf->ctime = new DateTime();
        $util->writeLog(json_encode($pcnf));
    }
    $query = "select * from yusr";
    $param = array();
    $users = $util->execQuery($query,$param);

    $query = "select * from shift";
    $param = array();
    $shift = $util->execQuery($query,$param);

    $query = "select * from zpci where objky = :objky";                            
    $param = array( ':objky' => $pcnf->objky);
    $items = $util->execQuery($query,$param);

    $query = "select * from users where user_id = ?";                            
    $param = array( $sess->user_id);
    $user  = $util->execQuery($query,$param,1);
    require '../pconf/form_pconf.php';
?>



<?php include '../incld/jslib.php'; ?>
<script>
    function MyLoad() {
        var cols = [];
        const inpt = document.getElementsByTagName("input");

        for (let index=0;index < inpt.length; index++) {
            if (inpt[index].name.startsWith('zdt') || inpt[index].name.startsWith('zot') || inpt[index].name.startsWith('zbc')) { 
                cols.push(inpt[index]);
            }
        }
        for (let index=0;index < cols.length; index++) {
            let col = cols[index].name;
            colTotal(col);
            cols[index].addEventListener('change', function() {
                colTotal(col); // ✅ Uses the correct `col`
            });
        }
        form = $('tr[name="neword"]');
            for (let i=0;i < form.length; i++) {
                let inp = [];
                let row = $(form[i]);
                let ord = row.find('input');
                for (let j=0;j < ord.length; j++) {
                if (ord[j].name.startsWith('zdt') || ord[j].name.startsWith('zot') ) { 
                        inp.push(ord[j]);
                }
            }
        
            rows =[];
            orders = $('tr[name="orders"]');
            rows.push(form);
            rows.push(orders);
        
            for (let i=0;i < rows.length; i++) {
                let data = [];
                let row = $(rows[i]);
                let ord = row.find('input');
                for (let j=0;j < ord.length; j++) {
                    if (ord[j].name.startsWith('zdt') || ord[j].name.startsWith('zot') ) { 
                            data.push(ord[j]);
                    }
                }
                
                for (let k=0;k < data.length; k++) {
                    data[k].addEventListener('blur', function() {
                        rowTotal(row,data);
                    });
                }
                
            }
        }
    }
    function setMaxValue(obj) {
        val = $(obj).val();
        if(val=="") {
            $(obj).attr('max',$('#blcap_total').val());
        }
    }
    function colTotal(col) {
        var cols = document.getElementsByName(col);
        var ls_sum = 0;
        for (let index=0;index < cols.length; index++) {
            var ls_base = Number(cols[index].value) || 0;
            ls_sum += ls_base;
        }
        if(col == 'zbc00') {
            $("#"+ col + "_total").val(ls_sum).trigger('change');
        } else {
            $("#"+ col + "_total").val(ls_sum); 
        }   
    }
    function rowTotal(row,data) {
        var ls_sum = 0;
        for (let index=0;index < data.length; index++) {
            var ls_base = (Number(data[index].value) || 0);
            ls_sum += ls_base;
        }
        row.find('input[name="zbc00"]').val(ls_sum).trigger('change');
        colTotal('zbc00');
        

    }
    function getShift(obj) {
        let tprog = $(obj).val();
        let rowno = $(obj).closest('tr'); // ✅ Find the correct row

        $.get(window.location.href, { tprog: tprog, action: 'getShift' }, function(data) {
            try {
                if (!data || data.trim() === "") {
                    throw new Error("Empty response from server");
                }
                var oData = JSON.parse(data);

                // ✅ Check if inputs exist before assigning values
                if (rowno.find('input[name="begzt"]').length) {
                    rowno.find('input[name="begzt"]').val(oData.begzt || "");
                } else {
                    console.warn("Input field 'begzt' not found in row", rowno);
                }

                if (rowno.find('input[name="endzt"]').length) {
                    rowno.find('input[name="endzt"]').val(oData.endzt || "");
                } else {
                    console.warn("Input field 'endzt' not found in row", rowno);
                }

                if (rowno.find('input[name="einzt"]').length) {
                    rowno.find('input[name="einzt"]').val(oData.einzt).trigger('change');
                } else {
                    console.warn("Input field 'einzt' not found in row", rowno);
                }

            } catch (e) {
                console.error("Invalid JSON response", e, "Server Response:", data);
            }
        });
    }
    function btnToggle(obj,event) {
        if (obj.type === "button") {
             cur_row = $(obj).closest('tr');
             cur_row.find('input[name="lmnga"]').attr('readonly', false);
             cur_row.find('input[name="zot00"]').attr('readonly', false);
             cur_row.find('input[name="zdt01"]').attr('readonly', false);
             cur_row.find('input[name="zdt02"]').attr('readonly', false);
             cur_row.find('input[name="zdt03"]').attr('readonly', false);
             cur_row.find('input[name="zdt04"]').attr('readonly', false);
             cur_row.find('input[name="zdt05"]').attr('readonly', false);
             cur_row.find('input[name="zdt06"]').attr('readonly', false);
             cur_row.find('input[name="zdt07"]').attr('readonly', false);
             cur_row.find('input[name="zdt08"]').attr('readonly', false);
             cur_row.find('input[name="zrmks"]').attr('readonly', false);
             cur_row.find('input[name="zcalc"]').attr('readonly', false);
             obj.type = "submit"; // ✅ Change to submit
             event.preventDefault();
            obj.innerHTML = '<i class="fa fa-save"></i>';
        } 
    }
    function calcBalance(obj) {
        icap = $('#einzt').val();
        ucap = $('#zbc00_total').val();
        $('#blcap_total').val(icap-ucap).trigger('change');
    }
    function calcOTime(obj) {
        // debugger;
        let base_row = $(obj).closest('tr');
        let oper_qty = $(obj).val();
        let cyl_time = base_row.find("input[name='zcytm']").val();
        let opr_time = Math.ceil(oper_qty * cyl_time);
        base_row.find("input[name='zot00']").val(opr_time).trigger('blur');
    }
    function getOrder(obj) {
        let base_row = $(obj).closest('tr');
        let im_objky = base_row.find("input[name='objky']").val();
        let im_aufnr = base_row.find("input[name='aufnr']").val();
        if(im_aufnr != '') {
            $.get(window.location.href,{im_objky : im_objky, im_aufnr : im_aufnr, getOrder:true}, function(data) {
                data = JSON.parse(data);
                if (Array.isArray(data) && data.length === 0)  {
                    base_row.find("input").prop('readonly',true);
                    base_row.find("input[name='aufnr']").prop('readonly',false);
                   
                } else {
                    base_row.find("input").prop('readonly',false);
                    base_row.find("input[name='vornr']").prop('readonly',true);
                    base_row.find("input[name='steus']").prop('readonly',true);
                    base_row.find("input[name='ltxa1']").prop('readonly',true);
                    base_row.find("input[name='gstrp']").prop('readonly',true);
                    base_row.find("input[name='psmng']").prop('readonly',true);
                    base_row.find("input[name='zcytm']").prop('readonly',true);
                    data.forEach(function(item) {
                        base_row.find("input[name='matnr']").val(item.matnr);
                        base_row.find("input[name='maktx']").val(item.maktx);
                        base_row.find("input[name='vornr']").val(item.vornr);
                        base_row.find("input[name='steus']").val(item.steus);
                        base_row.find("input[name='ltxa1']").val(item.ltxa1);
                        base_row.find("input[name='gstrp']").val(item.gstrp);
                        base_row.find("input[name='psmng']").val(item.blqty);
                        if (item.zcalc == 1 ) {
                            base_row.find("input[name='zcalc']").prop('checked',true);
                        } else {
                            base_row.find("input[name='zcalc']").prop('checked',false);
                        }
                        base_row.find("input[name='zcytm']").val((item.vgw01/60).toFixed(2));
                    });
                }
            });
        } else {
            base_row.find("input").prop('readonly',true);
            base_row.find("input[name='aufnr']").prop('readonly',true);
        }
        let im_vornr = base_row.find("input[name='vornr']").val();
        $.get(window.location.href,{im_objky : im_objky, im_aufnr : im_aufnr, im_vornr : im_vornr,chkOrder:true}, function(data) {
            data = JSON.parse(data);
            console.log(data);
            if(data.exists) {
                let psmng = base_row.find("input[name='psmng']").val();
                base_row.find("input[name='lmnga']").val(0);
                base_row.find("input").prop('readonly',true);
                base_row.find("input[name='aufnr']").prop('readonly',false);
                if ( psmng > 0 ) {
                    base_row.find("input[name='zot00']").prop('readonly',false);
                } else {
                    base_row.find("input").prop('readonly',true);
                    base_row.find("input[name='aufnr']").prop('readonly',false);
                }
            }
        });
    }
    function chkForm(obj,event) {
        let form = $(obj);
        let pord = form.find("input[name='aufnr']").val();
        let mins = form.find("input[name='zbc00']").val();
        let cqty = form.find('input[name="lmnga"]').val();
        let oqty = form.find('input[name="psmng"]').val();
        let pdat = form.find('input[name="gstrp"]').val();
        let bqty = $('#blcap_total').val();
        let bdat = $('#budat').val();
        if(pord == '' ) {
            alert('Production Order can not be blank');
            event.preventDefault();
        }
        if(pord == 0 ) {
            alert('Production Order can not be zero');
            event.preventDefault();
        }
        if(Number(bqty) < 0 ) {
            alert('Capacity of Work Center exceeded');
            event.preventDefault();
        }
        if(Number(mins) <= 0) {
            alert('Utilized Mins can not be zero');
            event.preventDefault();
        }
        if(Number(cqty) > Number(oqty)) {
            alert('Confirm Qty is more than Open Qty');
            event.preventDefault();
        }
        if(Number(bqty) < 0 ) {
            alert('Capacity of Work Center exceeded');
            event.preventDefault();
        }
        // if(pdat > bdat) {
        //     alert('Order Date can not greater than Confirmation Date');
        //     event.preventDefault();
        // }
        
    }

    function preventEnter(e) {
        
      if (e.key === "Enter") {
        debugger;
        e.preventDefault(); // Prevent form submission
        return false;
      }
    }
    function chkQty(obj)  {

      let crow = $(obj).closest('tr');
      let lmnga = crow.find('input[name="lmnga"]').val();
      let psmng = crow.find('input[name="psmng"]').val();
      if(lmnga > psmng) {
        alert('Confirmation Quantity can not be more than Open Qty');
        crow.find('input[name="zot00"]').prop('readonly',true);
        crow.find('input[name="zdt01"]').prop('readonly',true);
        crow.find('input[name="zdt02"]').prop('readonly',true);
        crow.find('input[name="zdt03"]').prop('readonly',true);
        crow.find('input[name="zdt04"]').prop('readonly',true);
        crow.find('input[name="zdt05"]').prop('readonly',true);
        crow.find('input[name="zdt06"]').prop('readonly',true);
        crow.find('input[name="zdt07"]').prop('readonly',true);
        crow.find('input[name="zdt08"]').prop('readonly',true);
      }
    }   
   
</script>
<?php include '../incld/footer.php';?>