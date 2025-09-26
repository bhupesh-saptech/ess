   function setSUser(obj) {
        var user_id = $(obj).val();
        var supp_id = $(obj).closest('tr').find('input[type=hidden]').val();
        $.post('../api/post_data.php',{'lifnr':supp_id,'user_id':user_id,'action':'setSUser'},function(data){console.log(data);});
    }
    function setSGroup(obj) {
        var supp_id = $(obj).closest('tr').find('input[type=hidden]').val();
        var pur_grp = $(obj).val();
        $.post('../api/post_data.php',{'lifnr':supp_id,'ekgrp':pur_grp,'action':'setSGroup'},function(data){console.log(data);});    
    }
   function setGUser(obj) {
        var user_id = $(obj).val();
        var pur_grp = $(obj).closest('tr').find('input[type=hidden]').val();
         $.post('../api/post_data.php',{'ekgrp':pur_grp,'user_id':user_id,'action':'setGUser'},function(data){console.log(data);});
    }
    function setVendor(obj) {
        var lifnr = $(obj).val();
        $.post('../api/post_data.php',{'lifnr':lifnr,'action':'setVendor'},function(data){window.location = '../supplier/index.php';});
        
    }
    function closeWindow() {
        debugger;
        let wnd = open(location, '_self');
        wnd.close();
    }
    
    function openWindow(obj)
    {
        var pass_id = $(obj).val();
    	url='https://agilesaptech.com/scp/supplier/qrcd_pass.php?pass_id=' + pass_id;
    	window.open(url,"_blank","height=250,width=900, resizable=yes,left=300,top=300");
    }

  $(function () {
    $("#dtbl").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#dtbl_wrapper .col-md-6:eq(0)');
    
    function closeWindow() {
        window.close();
    }
    
    
    
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });