<?php
    require '../incld/verify.php';
 //   require '../suppl/check_auth.php';
    require '../incld/autoload.php';
    $conn = new Model\Conn();
    if(isset($_GET['objty'])) {
        $rqst  = json_decode(json_encode($_GET));
        $query = "select * from obj_type where objty = ?";
        $param = array($rqst->objty);
        $objty = $conn->execQuery($query,$param,1);
        $query = "select objky,objnm from {$objty->table} where objky not in (select objky from usr_auth where user_id = ? and objty = ? )";
        $param = array($rqst->user_id,$rqst->objty);
        $items = $conn->execQuery($query,$param);
        echo json_encode($items);
        die();
    }
    $cntr = new Contr\UserContr();
    if(isset($_POST['setUser'])) {
        $rqst = json_decode(json_encode($_POST));
        $action = $_POST['setUser'];
        switch($action) {
            case 'add' :
                $item = $cntr->createUser($rqst);
                $_SESSION['status'] = 'User : '.$item->user_id. ' Created';
                $action = 'view';
                break;
            case 'mod' :
                $item = $cntr->modifyUser($rqst);
                $_SESSION['status'] = 'User : '.$item->user_id. ' Modified';
                $action = 'view';
                break;
            case 'view' :
                $action = 'mod';
                break;
        }
    } else {
        if(isset($_REQUEST['user_id'])) {
            $action = "view";
        } else {
            $action = "add";
        }
    }
    require '../incld/header.php';
    require '../admin/top_menu.php';
    require '../admin/side_menu.php';
    $conn = new Model\Conn();
    
    if(isset($_REQUEST['user_id'])) {
        if ($action == "") {
            $action = "view";
        }
        $query = "select * from usr_data where user_id = ?";
        $param = array($_REQUEST['user_id']);
        $user  = $conn->execQuery($query,$param,1);
        $query = "select * from obj_type where objty = ?";
        $param = array($user->objty);
        $objt  = $conn->execQuery($query,$param,1);
        $query = "select objky,objnm from {$objt->table}";
        $param = array();
        $items = $conn->execQuery($query,$param);
    } else {
        $user = new Contr\UserContr();
    }
    if(isset($_REQUEST['role_nm'])) {
        $user->role_nm = $_REQUEST['role_nm'];
    } 
    $query = "select * from usr_role";
    $param = array();
    $roles = $conn->execQuery($query,$param);

    require '../admin/form_user.php'; 
    require '../incld/jslib.php'; ?>
<script>
    $( document ).ready(function() {
        text = $('#action').val();
        switch(text) {
            case 'add':
                $("input").attr('readonly', false);
                $("select").prop('disabled', false);
                break;
            case 'mod' :
                $("input").attr('readonly', false);
                $("select").prop('disabled', false);
                $("input[name='user_id']").attr('readonly',true);
                break;
            case 'view':
                $("input").attr('readonly', true); 
                $("select").prop('disabled', true);
                break;
        }
    });
    function setObjty(obj) {
        debugger;
        let role_id = $(obj).val();
        let value   = $('#objty_val').val();
        options = $('#objty');
        
        options.empty();
        options.append("<option value=''>select OB type</option>")
        if(role_id == 1 || role_id == 2 || role_id == 6) {
            options.append("<option value='EMPL'>EMPL : Employee</option>");
        } else {
            options.append("<option value='PLNT'>PLNT : Plant</option>");
        }
        options.val(value);
    }
    function getObjects(obj,) {
        debugger;
        let objty = $(obj).val();
        let value = $('#objky_val').val();
        options = $('#objky');
        $.get(window.location.href, { objty: objty }, function(data) {
            data = JSON.parse(data);
            options.empty();
            options.append("<option value=''>Select a Object</option>");
            for (let i = 0; i < data.length; i++) {
                console.log("Object : "+ i + data[i].objky + data[i].objnm);
                options.append("<option value='"+data[i].objky+"'>"+data[i].objky + " : " + data[i].objnm+"</option>");
            }
        });
        options.val(value);
    }
</script>
<?php
    require '../incld/footer.php';
?>