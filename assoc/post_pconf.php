<?php
    require '../incld/verify.php';
    require '../pconf/check_auth.php';
    require '../vendor/autoload.php';
    require '../incld/autoload.php';
    use GuzzleHttp\Client;
    $util = new Model\Util();
    $rqst = json_decode(json_encode($_POST));

    $client = new Client([
        "base_uri" => "https://prd.pmipl.in:44300",
        "auth" => ['kpab', 'Phoenix!2024']
    ]);

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
    $resp = $client->post("/sap/bc/bsp/sap/zprd_conf/zprd_updt.html", [ "body"      => $json,
                                                                        "headers"   => $hdrs]);
    $data = str_replace([ "\r", "\n"], "", $resp->getBody()->getContents());
    $query = "update zpch set zresp = ? where objky = ?";
    $param = array($data,$rqst->objky);
    $item  = $util->execQuery($query,$param,1);
    $data  = json_decode($data);
    if($data->status == "Success") {
        $query = "update zpch set zflag = 'S'  where objky = ?";
        $param = array($rqst->objky);
        $item  = $util->execQuery($query,$param,1);
    }
    $data = json_decode(json_encode($data));
    require '../incld/header.php';
    require '../incld/top_menu.php';
    // require '../pconf/side_menu.php';
    // require '../pconf/dashboard.php';
?>
<div class="card">
    <div class="card-header">
    </div>
    <div class="card=body">
        <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            <input type="hidden" id="objky" value="<?php echo "{$rqst->objky}"?>">
                        Status
                        </td>
                        <td>
                            <?php echo "{$data->status}";?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Confirmation
                        </td>
                        <td>
                            <?php if(isset($data->pconf)) {
                                foreach($data->pconf as $pcnf) {
                                        echo "{$pcnf->aufnr}_{$pcnf->vornr}_{$pcnf->pconf}_{$pcnf->pcntr} <br>";
                                      
                                }
                            } ?>
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Goods Movements
                        </td>
                        <td>
                            <?php if(isset($data->gdmvt)) {
                                foreach($data->gdmvt as $gmvt) {
                                    echo "{$gmvt->aufnr}_{$gmvt->vornr}_{$gmvt->mblnr}_{$gmvt->mjahr} <br>"; 
                                }
                            } ?>
                            
                        </td>
                    </tr>
                    <tr>
                        <td>
                        Messages
                        </td>
                        <td>
                            <?php if(isset($data->mesgs)) {
                                    foreach($data->mesgs as $mesg) {
                                        echo "{$mesg->mesgs} <br>"; 
                                    }
                                } ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="button" class="btn btn-primary float-right" onclick="goBack();">Back</button>
                        </td>
                    </tr>
                </table>
            <div class="col-sm-2">
            </div>
        </div>
    </div>
</div>
</div>
<?php 
    include '../incld/jslib.php';
?>
<script>
    function goBack() {
        let urlky = '../pconf/disp_pconf.php?objky=';
        let objky = $('#objky').val();
        window.location.href = urlky + objky;
    }
</script>
<?php
    include '../incld/footer.php';
?>