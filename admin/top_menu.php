<?php     include('../incld/autoload.php'); 
    $sesn = json_decode(json_encode($_SESSION));
    $util = new Model\Util();

    $query = "select * from usr_data where user_id = ?";
    $param = array($sesn->user_id);
    $user  = $util->execQuery($query,$param,1);
    
    $query = "select * from obj_type where objty = 'SUPL'";
    $param = array();
    $objt  = $util->execQuery($query,$param,1);
    
    $query = "select * from usr_auth where objty = 'SUPL' and user_id = ? and objky not in (?)";
    $param = array($sesn->user_id,$user->objky);
    $items  = $util->execQuery($query,$param);

    $item = new stdClass();
    $item->user_id = $user->user_id;
    $item->objty   = 'SUPL';
    $item->objky   = $user->objky;

    if(isset($items)) {
        array_push($items,$item);
    } else {
        $items = [];
        array_push($items,$item);
    }
?>
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="<?php echo "{$user->home_pg}";?>" class="nav-link">
            Home
        </a>
      </li
    <li class="nav-item d-none d-sm-inline-block">
     <?php   
            if(isset($_SESSION['supp_id'])) {
                $supp_id = $_SESSION['supp_id'];
                if($supp_id = "") {
                    echo "<h3 style='color:red;'>No Supplier is assigned to this user yet</h3>";
                }
            }
    ?>
    </li>
    
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <div class="dropdown">
              <button class="btn btn-secondary dropdown-toggle" name="dropd" type="button" data-toggle="dropdown" aria-expanded="false">
                <?php if(isset($_SESSION['user_nm'])) {
                        echo $_SESSION['user_nm'];
                      } else {
                          echo 'Not Logged In';
                      }
                ?>
              </button>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#">User Profile</a>
                <a class="dropdown-item" href="#">Messages</a>
                <form method="POST" action="../admin/logout.php">
                    <button type="submit" name="logout" class="dropdown-item">Logout</button>
                </form>
              </div>
            </div>
        </li>
    </ul>
  </nav>
  <!-- /.navbar -->