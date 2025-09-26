<?php
    require '../incld/verify.php';
    require '../pconf/check_auth.php';
    require '../vendor/autoload.php';
    require '../incld/autoload.php';
    use GuzzleHttp\Client;
    $util = new Model\Util();
    $rqst = json_decode(json_encode($_GET));

    $query = "select * from zpch where objky = ?";
    $param = array($rqst->objky);
    $pcnf  = $util->execQuery($query,$param,1);
    $data  = json_decode($pcnf->zresp);
    require '../incld/header.php';
    require '../incld/top_menu.php';
?>
<div class="card">
    <div class="card-header">
        <?php require '../incld/messages.php'; ?>
    </div>
    <div class="card=body">
        <div class="row">
            <div class="col-sm-2">
            </div>
            <div class="col-sm-8">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            Status
                        </td>
                        <td class="<?php switch($data->status) {
                            case  'Success' : echo 'bg-green';break;
                            case  'Failed'  : echo 'bg-red'  ;break;
                        } ?>">
                            <?php echo "{$data->status}";?>
                        </td>
                        <td>
                            <label class="form-label">Confirmation Key</label>
                        </td>
                        <td>
                            <input type="text" id="objky" class="form-control" value="<?php echo "{$rqst->objky}"?>" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary float-right" onclick="window.close();">Close</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width:20%">Order No</th>
                                    <th style="width:10%">Operation</th>
                                    <th style="width:10%">MsgType</th>
                                    <th style="width:60%">Message Text</th>
                                </tr>
                                <?php foreach($data->orders as $item) { ?>
                                    <tr>
                                        <td><?php echo "{$item->aufnr}" ; ?></td>
                                        <td><?php echo "{$item->vornr}" ; ?></td>
                                        <td><?php echo "{$item->msgty}" ; ?></td>
                                        <td><?php echo "{$item->mtext}" ; ?></td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
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