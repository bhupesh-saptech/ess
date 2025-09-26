<?php

    require '../incld/autoload.php';
    $util = new Model\Util();
    require '../incld/verify.php';
    require '../pconf/check_auth.php';
    require '../incld/header.php';
    require '../incld/top_menu.php';
    // require '../pconf/side_menu.php';
    // require '../pconf/dashboard.php';
    
    $sess = json_decode(json_encode($_SESSION));
    
    $query = "select * from wcenter as a left outer join usr_auth as b on b.objky = a.objky where a.werks = ? and b.user_id = ?";
    $param = array($sess->plnt_id,$sess->user_id);
    $items = $util->execQuery($query,$param);
?>
<div class="card">
    <div class="card-header">
        <?php require '../incld/messages.php'; ?>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-stripped" >
            <tr>
        <?php   if(!isset($items)) {
                $j = 4;
                $i = 0; 
                foreach($items as $item) {
                    $i = $i + 1; 
        ?>        
                    <td class="text-center"><a href="<?php echo "../pconf/list_pconf.php?werks={$item->werks}&arbpl={$item->arbpl}"; ?>" ><img src="<?php echo "../assets/dist/img/wc_{$item->arbpl}.jpeg"; ?>" style="height:150px;width:250px;"><br><?php echo "{$item->arbpl}_{$item->objnm}";?></a></td>
        <?php       if($i % $j == 0) { ?>
                        </tr>
                        <tr>
        <?php       }
                }
            }?>
        </table>
    </div>
</div>

<?php include '../incld/jslib.php'; ?>
<script>
    $(document).ready(function() {
        $("#dtbl").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#dtbl_wrapper .col-md-6:eq(0)');
    });
    function MyLoad() {
    }
</script>
<?php include '../incld/footer.php';?>