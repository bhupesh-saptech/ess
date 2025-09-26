<?php     
    require '../incld/autoload.php';
    $rqst = json_decode(json_encode($_GET));
    date_default_timezone_set('Asia/Kolkata');
    if(!isset($rqst->pdate)) {
       $rqst = new stdClass();
        $rqst->pdate = date('Y-m-d');
    }
    if(!isset($rqst->option)) {
        $rqst->option = "";
    }
    $dash  = new stdClass;
    $dash->cnt1 = 0;
    $dash->cnt2 = 0;
    $dash->cnt3 = 0;
    $dash->cnt4 = 0;
    $dash->cnt5 = 0;
    $dash->cnt6 = 0;
    $util  = new Model\Util();
    $param = array($rqst->pdate,$rqst->pdate,$rqst->pdate);
    $query = "select 'staff'   as param,
                     count(*) as count
                from staff
              union
              select 'Present'   as param,
                     count(*) as count
                from time_sheet where date_id = ? and in_time is not null
              union 
              select 'Absent'   as param,
                     count(*) as count
                from time_sheet where date_id = ? and in_time is null
              union
              select 'Late'   as param,
                     count(*) as count
                from time_sheet where date_id = ? and in_time > '10:00:00'";
    $items = $util->execQuery($query,$param);
    foreach($items as $item) {
      switch($item->param) {
          case 'staff'   : $dash->cnt1 = $item->count;break;
          case 'Present' : $dash->cnt2 = $item->count;break;
          case 'Absent'  : $dash->cnt3 = $item->count;break;
          case 'Late'    : $dash->cnt4 = $item->count;break;
      }
    }

?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Time Office Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">hroff</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <?php include('../incld/messages.php'); ?>
            </div>
        </div>
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3><?php echo $dash->cnt1; ?></h3>

                <p>Associates</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="<?php echo "index.php?option=01&pdate={$rqst->pdate}" ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo $dash->cnt2; ?></h3>

                <p>Present</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="<?php echo "index.php?option=02&pdate={$rqst->pdate}" ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3><?php echo $dash->cnt3; ?></h3>

                <p>Absent</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="<?php echo "index.php?option=03&pdate={$rqst->pdate}" ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-warning ">
              <div class="inner">
                <h3><?php echo $dash->cnt4; ?></h3>

                <p>Late</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="<?php echo "index.php?option=04&pdate={$rqst->pdate}" ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-success ">
              <div class="inner">
                <h3><?php echo $dash->cnt5; ?></h3>

                <p>Plants</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="list_plants.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-2 col-6">
            <!-- small box -->
            <div class="small-box bg-dark">
              <div class="inner">
                <h3><?php echo $dash->cnt6; ?></h3>

                <p>Locations</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="list_strloc.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>