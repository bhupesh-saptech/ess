<?php
    $util = new Model\Util();
    $sess = json_decode(json_encode($_SESSION));

    $query = "select a.date_id,
                            sum(if(a.in_time is not null,1,0 )) as cnt_attend,
                            sum(if(a.date_id > curdate(),0,case a.wkday when 1 then 0 when 7 then 0 else if(a.in_time is null,1,0     ) end)) as cnt_absent,
                            sum(if(a.in_time > '10:00:00',1,0)) as cnt_late, 
                            b.emp_name,b.nfc_card 
                       from time_sheet as a inner join staff as b on b.staff_id = a.staff_id where month(date_id) = month(?) group by a.date_id"; 
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
        <div class="text-center" style="width:50%;height:50%">
            <canvas id="chart1" ></canvas>
        </div>
        <form method="get   "><input type="date" name="pdate" value="<?php echo $rqst->pdate ?>"><input type="submit" name="getData" ></form>
        <table id="list_pass" class="table table-bordered table-striped">
        <thead>
            <tr class="bg-primary">
                <th style="width:02%">Q</th>
                <th style="width:10%">Date</th>
                <th style="width:10%">Present</th>
                <th style="width:10%">Absent</th>
                <th style="width:10%">Late</th>
                <th style="width:10%">Leave</th>
                <th style="width:10%">WFH</th>
                <th style="width:04%">Log</th>
            </tr>
        </thead>
        <?php 
            if(isset($items)) {
                foreach($items as $item) { 
        ?>
                    <tr>
                        <td class="text-center"><a href="javascript:void(0);" onclick="newTab('<?php echo '../assoc/view_staff.php?staff_id='.$item->staff_id;?>');"><i class="fas fa-qrcode"></i></a></td>
                        <td><?php echo $item->date_id; ?></a></td>
                        <td class="text-right"><?php echo $item->cnt_attend;   ?></td>
                        <td class="text-right"><?php echo $item->cnt_absent;  ?></td>
                        <td class="text-right"><?php echo $item->cnt_late;   ?></td>  
                        <td></td>
                        <td></td>
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
    const ctx = document.getElementById('chart1');
        createChart('Data','bar');
        function createChart(chartData,type) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    borderWidth: 1
                },{
                    label: '# of Votes',
                    data: [25, 22, 8, 5, 4, 3],
                    borderWidth: 1
                }]
                },
                options: {
                scales: {
                    y: {
                    beginAtZero: true
                    }
                }
                }
            });
        }
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