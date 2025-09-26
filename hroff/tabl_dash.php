<?php
    $util = new Model\Util();
    $sess = json_decode(json_encode($_SESSION));
    if(isset($rqst->option)){
        switch($rqst->option) {
            case ''  :
                 $query = "select a.staff_id,a.date_id,a.in_time,if(a.in_time = a.out_time,null,a.out_time) as out_time,a.duration, b.emp_name,b.nfc_card from time_sheet as a inner join staff as b on b.staff_id = a.staff_id where date_id = ?"; 
                 break;
            case '01':
                $query = "select a.staff_id,a.date_id,a.in_time,if(a.in_time = a.out_time,null,a.out_time) as out_time,a.duration, b.emp_name,b.nfc_card from time_sheet as a inner join staff as b on b.staff_id = a.staff_id where date_id = ?"; 
                break;
            case '02':
                $query = "select a.staff_id,a.date_id,a.in_time,if(a.in_time = a.out_time,null,a.out_time) as out_time,a.duration, b.emp_name,b.nfc_card from time_sheet as a inner join staff as b on b.staff_id = a.staff_id where date_id = ? and in_time is not null"; 
                break;
            case '03':
                $query = "select a.staff_id,a.date_id,a.in_time,if(a.in_time = a.out_time,null,a.out_time) as out_time,a.duration, b.emp_name,b.nfc_card from time_sheet as a inner join staff as b on b.staff_id = a.staff_id where date_id = ? and in_time is null"; 
                break;
            default  :
                $query = "select a.staff_id,a.date_id,a.in_time,if(a.in_time = a.out_time,null,a.out_time) as out_time,a.duration, b.emp_name,b.nfc_card from time_sheet as a inner join staff as b on b.staff_id = a.staff_id where date_id = ? and in_time > '10:00:00'"; 
                break;
        }
    } else {
        $query = "select a.staff_id,a.date_id,a.in_time,if(a.in_time = a.out_time,null,a.out_time) as out_time,a.duration, b.emp_name,b.nfc_card from time_sheet as a inner join staff as b on b.staff_id = a.staff_id where date_id = ?"; 
    }
    
    $param = array($rqst->pdate);
    $items = $util->execQuery($query,$param);
    $_SESSION['pref_id'] = 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
?>
<div class="card ">
    <div class="card-header">
        <?php include('../incld/messages.php'); ?>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <form method="get"><input type="hidden" name="option" value="<? echo "$rqst->option" ;?>"><input type="date" name="pdate" value="<?php echo $rqst->pdate ?>"><input type="submit" name="getData" ></form>
        <table id="list_pass" class="table table-bordered table-striped">
        <thead>
            <tr class="bg-primary">
                <th style="width:02%">Q</th>
                <th style="width:10%">EmpID</th>
                <th style="width:20%">Emp Name </th>
                <th style="width:10%">NFC Card</th>
                <th style="width:10%">Punch Date </th>
                <th style="width:10%">CKIN Time</th>
                <th style="width:10%">COUT Time</th>
                <th style="width:05%">Duration</th>
                <th style="width:04%">Log</th>
            </tr>
        </thead>
        <?php 
            if(isset($items)) {
                foreach($items as $item) { 
        ?>
                    <tr>
                        <td class="text-center"><a href="javascript:void(0);" onclick="newTab('<?php echo '../assoc/view_staff.php?staff_id='.$item->staff_id;?>');"><i class="fas fa-qrcode"></i></a></td>
                        <td><?php echo $item->staff_id; ?></a></td>
                        <td><?php echo $item->emp_name;  ?></td>
                        <td><?php echo $item->nfc_card;  ?></td>
                        <td><?php echo $item->date_id;   ?></td>
                        <td><?php echo $item->in_time;   ?></td>
                        <td><?php echo $item->out_time;  ?></td>
                        <td><?php echo $item->duration;   ?></td>  
                        <td></td>
                    </tr>
        <?php   }
            } 
        ?>
        </table>
    </div>
</div>

<?php
    include('../incld/jslib.php'); ?>
    <script>
        $(function () {
            $("#list_pass").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,"pageLength":30,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#list_pass_wrapper .col-md-6:eq(0)');

        });
        function newTab(url) {
            window.open(url,'_blank');
        }
    </script>
<?php
    include('../incld/footer.php');
?>