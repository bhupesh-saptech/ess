<!-- Content Wrapper. Contains page content -->
<div >
    <!-- /.content-header -->
    <div class="card">
        <div class="card-body table-responsive">
            <?php require '../incld/messages.php'; ?>
            
            <form method="post"  autocomplete="off" onkeydown="return preventEnter(event);" >
                <table class="table table-bordered table-stripped" id="tblOrders">
                    <tr class="bg-dark">
                        <td colspan="2">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label class="form-label">WCenter</label>
                                    <input type="text" class="form-control" name="arbpl" value="<?php echo "{$pcnf->arbpl}";?>" readonly>
                                </div>
                                <div class="col-sm-8">
                                    <label class="form-label">Work Center Description</label>
                                    <input type="text" class="form-control " name="ktext" value="<?php echo "{$pcnf->ktext}";?>" readonly>
                                </div>
                            </div> 
                        </td>
                        <td colspan="5" class="text-center">
                            <h2>Production Confirmation for a Work Center</h2>
                        </td>
                        <td colspan="2">
                            <label class="form-label">Confirmation Key</label>
                            <input name="objky" class="form-control" value="<?php echo "{$pcnf->objky}";?>" readonly >
                        </td>
                        <td>
                             <button type="button" class="btn btn-primary float-right" onclick="window.close();">Close</button>
                        </td>
                    </tr>
                    <tr class="bg-dark">
                        <td rowspan="2" colspan="2">
                            <div class="row">
                                <div class="col-sm-12 text-center">
                                    <img src="<?php echo "../assets/dist/img/wc_{$pcnf->arbpl}.jpeg"; ?>" class="img-responsive" style="height:13em;">
                                </div>
                            </div>
                        </td>
                        <td style="width:10%">
                            <label class="form-label">Plant</label>
                            <input type="text" name="werks" value=<?php echo "{$pcnf->werks}"; ?> class="form-control">
                        </td>
                        <td style="width:10%">
                            <label class="form-label">Confirm Dt</label>
                            <input type="date" name="budat"  id="budat" class="form-control" value="<?php echo "{$pcnf->budat}"; ?>" readonly>
                        </td>
                        <td colspan="2" style="width:20%">
                            <label class="form-label">Shift</label>
                            <select name="tprog" class="form-control"  required disabled>
                                <option value="">select Shift</option>
                                <?php foreach($shift as $shft) { ?>
                                    <option value="<?php echo "{$shft->tprog}";?>" <?php if($pcnf->tprog == $shft->tprog) {echo "selected";} ?>><?php echo "{$shft->tprog}_{$shft->ktext}";?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td style="width:10%">
                            <label class="form-label">Start Time</label>
                            <input type="text" name="begzt"  class="form-control text-right-align" value="<?php echo "{$pcnf->begzt}";  ?>" readonly>  
                        </td>
                        <td style="width:10%">
                            <label class="form-label">End Time</label>
                            <input type="text" name="endzt"  class="form-control text-right-align" value="<?php echo "{$pcnf->endzt}"; ?>" readonly>  
                        </td>
                        <td style="width:10%">
                            <label class="form-label">Total Cap</label>
                            <input type="number" name="ztcap" id="einzt"  class="form-control text-right-align" onchange="calcBalance(this);" value="<?php echo "{$pcnf->ztcap}"; ?>" readonly> 
                        </td>
                        <td class='text-center' style="width:5%">
                            <input type="hidden"  name="objky"   value="<?php echo "{$pcnf->objky}"  ?>">
                            <label class="form-label">=>SAP </label>
                            <?php if($pcnf->zbcap == 0 ) { 
                                    $user_ty = $user->user_ty;  
                                    $util->writeLog($user_ty)  ; 
                                    if(($user_ty == 'manager' || $user_ty == 'supervisor') && $pcnf->zflag != 'S' ) { ?>
                                        <button type="submit" name="action" value="addSAP" class="btn btn-primary" <?php if(($pcnf->budat >= date('Y-m-d', strtotime('-3 days')) && $user->suser != 1 ) || ($user->suser != 1  && $pcnf->ctime < $pcnf->etime ))  {echo 'disabled';}?>>
                                           
                                            <i class="fa fa-paper-plane"></i>
                                        </button>  
                            <?php   }
                                } ?>
                        </td>
                    </tr>
                    <tr class="bg-secondary" >
                        <td>
                            <label class="form-label">Installed Cap</label>
                            <input type="number"  name="zicap" class="form-control text-right-align"  value="<?php echo "{$pcnf->zicap}"; ?>" readonly>
                            <label class="form-label">Additional Cap</label>
                            <input type="number"  id="zacap" class="form-control text-right-align" value="<?php echo "{$pcnf->zacap}"; ?>" readonly>
                            
                        </td>
                        <td>
                            <label class="form-label">Conf. Qty</label>
                            <input type="number"  name="zcq00" class="form-control text-right-align"  value="" readonly>
                            <label class="form-label">Oper Time</label>
                            <input type="number"  id="zot00_total" class="form-control text-right-align" value="" readonly>
                        </td>
                        <td>
                            <label class="form-label">Add.Setup</label>
                            <input type="number"  id="zdt01_total"  class="form-control text-right-align" value="" readonly>
                            <label class="form-label">No Manp </label>
                            <input type="number"  id="zdt05_total" class="form-control text-right-align" value="" readonly>
                        </td>
                        <td>
                            <label class="form-label">Tool Break</label>
                            <input type="number"  id="zdt02_total" class="form-control text-right-align" value="" readonly>
                            <label class="form-label">Trials</label>
                            <input type="number" name="xdt06_total" id="zdt06_total" class="form-control text-right-align" value="" readonly>
                        </td>
                        <td>
                            <label class="form-label">M/C Break</label>
                            <input type="number"  id="zdt03_total" class="form-control text-right-align" value="" readonly>
                            <label class="form-label">No Sch</label>
                            <input type="number"  id="zdt07_total" class="form-control text-right-align" value="" readonly>
                        </td>
                        <td>
                            <label class="form-label">Mat N/A</label>
                            <input type="number"  id="zdt04_total" class="form-control text-right-align" value="" readonly>
                            <label class="form-label">Others</label>
                            <input type="number"  id="zdt08_total" class="form-control text-right-align" value="" readonly>
                        </td>
                        <td>
                            <label class="form-label">Util.Cap</label>
                            <input type="number" id="zbc00_total" class="form-control text-right-align" onchange="calcBalance(this);" value="0" >
                            <label class="form-label">Bal Cap</label>
                            <input type="number" id="blcap_total" value="0" class="form-control text-right-align"  readonly> 
                        </td>
                        <td>
                            
                        </td>
                    </tr>
                </table>
            </form>
            <form method="post" autocomplete="off" onsubmit="chkForm(this,event);" onkeydown="return preventEnter(event);">
                <table class="table table-bordered table-stripped">          
                    <?php if($pcnf->zbcap > 0 ) { ?> 
                    <tr class="bg-info" name="neword">
                        <td colspan="2">  
                            <input type="hidden" name="objky"   value="<?php echo "{$pcnf->objky}"  ?>">
                            <input type="hidden" name="meinh"   value="<?php echo "NOS"  ?>">
                            <input type="hidden" name="steus"   value="<?php echo ""  ?>">
                            <div class="row">
                                <div class="col-sm-5">
                                    <label class="form-label">Oper/Item</label>
                                    <input type="text" name="gstrp" class="form-control text-right-align" value="" readonly>
                                </div>
                                <div class="col-sm-7">
                                    <label class="form-label">Order No</label>
                                    <input type="text" name="aufnr" class="form-control text-right-align " value="" onchange="getOrder(this);">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <input type="text" name="vornr" class="form-control text-right-align mt-2" value="" readonly>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="ltxa1" class="form-control  mt-2" value="" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">
                                    <input type="text" name="matnr" class="form-control text-right-align mt-2" value="" readonly>
                                </div>
                                <div class="col-sm-7">
                                    <input type="text" name="maktx" class="form-control mt-2" value="" readonly>
                                </div>
                            </div>
                        </td>
                        <td>
                            <label class="form-label">Open Qty</label>
                            <input type="number" name="psmng" class="form-control text-right-align" value="" readonly>
                            <label class="form-label">Cycle Time</label>
                            <input type="number" name="zcytm" class="form-control text-right-align" value="" readonly>

                        </td>
                        <td>
                            <label >Conf. Qty</label>
                            <input type="number" name="lmnga"  class="form-control text-right-align " value="" onchange="calcOTime(this);" readonly> 
                            <label >OperTime</label>
                            <input type="number" name="zot00" class="form-control text-right-align "  onfocus="setMaxValue(this);" value="" readonly> 
                        </td>
                        <td>
                            <label class="form-label">Add.Setup</label>
                            <input type="number" name="zdt01" class="form-control text-right-align" onfocus="setMaxValue(this);" value="" readonly>
                            <label class="form-label">No Manp </label>
                            <input type="number" name="zdt05" class="form-control text-right-align" onfocus="setMaxValue(this);" value="" readonly>
                        </td>
                        <td>
                            <label class="form-label">Tool Break</label>
                            <input type="number" name="zdt02" class="form-control text-right-align" onfocus="setMaxValue(this);" value="" readonly>
                            <label class="form-label">Trials</label>
                            <input type="number" name="zdt06" class="form-control text-right-align" onfocus="setMaxValue(this);" value="" readonly>
                        </td>
                        <td>
                            <label class="form-label">M/C Break</label>
                            <input type="number" name="zdt03" class="form-control text-right-align" onfocus="setMaxValue(this);" value="" readonly>
                            <label class="form-label">No Schedule</label>
                            <input type="number" name="zdt07" class="form-control text-right-align" onfocus="setMaxValue(this);" value="" readonly>
                        </td>
                        <td>
                            <label class="form-label">Mat N/A</label>
                            <input type="number" name="zdt04" class="form-control text-right-align" onfocus="setMaxValue(this);" value="" readonly>
                            <label class="form-label">Others</label>
                            <input type="number" name="zdt08" class="form-control text-right-align" onfocus="setMaxValue(this);" value="" readonly>
                        </td>
                        <td>
                            <label class="form-label">Utilize Cap</label>
                            <input type="number" name="zbc00" class="form-control text-right-align" value="" readonly>
                            <label class="form-label">Remarks</label>
                            <input type="text"   name="zrmks" class="form-control text-right-align" value="" readonly>
                        </td>
                        <td>
                            <button type="submit" name="action" class="btn btn-primary float-right" value="addOrder" <?php if($pcnf->budat < date('Y-m-d', strtotime('-3 days')) && $user->suser != 1 )  {echo 'disabled';}?>>
                            <i class="fa fa-plus"></i>
                            </button>
                              <input type="checkbox" name="zcalc" class="form-control" value="0" readonly>
                        </td>
                    </tr>
                </table>
            </form>
        <?php } ?>
        <?php 
            if(isset($items)) {
                foreach($items as $item) { 
                    $item->total =  $item->zot00 + 
                                    $item->zdt01 + 
                                    $item->zdt02 + 
                                    $item->zdt03 + 
                                    $item->zdt04 + 
                                    $item->zdt05 + 
                                    $item->zdt06 + 
                                    $item->zdt07 + 
                                    $item->zdt08 ;?>
            <form method="post" autocomplete="off" onkeydown="return preventEnter(event);">
                <table class="table table-bordered table-stripped">
                    <tr class="bg-info" name="orders">
                        <td colspan="2" class="floating-label floating">
                            <input type="hidden" name="objky" value="<?php echo "{$pcnf->objky}";?>">
                            <input type="hidden" name="werks" value="<?php echo "{$pcnf->werks}";?>">
                            <input type="hidden" name="arbpl" value="<?php echo "{$pcnf->arbpl}";?>">
                            <input type="hidden" name="budat" value="<?php echo "{$pcnf->budat}";?>">
                            <input type="hidden" name="tprog" value="<?php echo "{$pcnf->tprog}";?>">
                            
                            <div class="row">
                                <div class="col-sm-5 floating-label floating">
                                    <input type="hidden" name="ztype" value="<?php echo "{$item->ztype}";?>">
                                    <!-- <input type="hidden" name="zcalc" value="<?php echo "{$item->zcalc}";?>"> -->
                                    <input type="text" name="gstrp" class="form-control floating text-right-align " value="<?php echo "{$item->gstrp}";?>" readonly>
                                    <label >Oper/Item</label>
                                </div>
                                <div class="col-sm-7 floating-label floating" >
                                    <input type="text" name="aufnr" class="form-control floating text-right-align " value="<?php echo "{$item->aufnr}";?>" readonly>
                                     <label >Production Order</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5 floating-label floating">
                                    <input type="text" name="vornr" class="form-control floating text-right-align mt-2" value="<?php echo "{$item->vornr}";?>" readonly>
                                    <label >Operation</label>
                                </div>
                                <div class="col-sm-7 floating-label floating">
                                    <input type="text" name="ltxa1" class="form-control  floating mt-2" value="<?php echo "{$item->ltxa1}";?>" readonly>
                                    <label >Operation Desc</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5 floating-label floating">
                                    <input type="text" name="matnr" class="form-control floating text-right-align mt-2" value="<?php echo "{$item->matnr}";?>" readonly>
                                    <label >Material</label>
                                </div>
                                <div class="col-sm-7 floating-label floating">
                                    <input type="text" name="maktx" class="form-control floating" value="<?php echo "{$item->maktx}";?>" readonly>
                                    <label >Description</label>
                                </div>
                            </div>
                        </td>
                        <td class="floating-label floating">
                            <input type="number" name="psmng" class="form-control floating text-right-align "  value="<?php echo "{$item->psmng}";?>" readonly>
                            <label >Open Qty</label>
                            <input type="number" name="zcytm" class="form-control floating text-right-align " value="<?php echo "{$item->zcytm}";?>" readonly>
                            <label >Cycle Time</label>
                        </td>
                        <td class="floating-label floating">
                            
                            <input type="number" name="lmnga" class="form-control floating text-right-align" value="<?php echo "{$item->lmnga}";?>" onchange="calcOTime(this);" readonly>
                            <label class="form-label">Conf. Qty</label>
                            <input type="number" name="zot00" class="form-control floating text-right-align" value="<?php echo "{$item->zot00}";?>" readonly>
                            <label class="form-label">OperTime</label>
                        </td>
                        <td class="floating-label floating">
                            <input type="number" name="zdt01" class="form-control floating text-right-align" value="<?php echo "{$item->zdt01}";?>" readonly>
                            <label class="form-label">Add.Setup</label>
                            <input type="number" name="zdt05" class="form-control floating text-right-align " value="<?php echo "{$item->zdt05}";?>" readonly>
                            <label class="form-label">No Manp </label>
                        </td>
                        <td class="floating-label floating">
                            <input type="number" name="zdt02" class="form-control floating text-right-align" value="<?php echo "{$item->zdt02}";?>" readonly>
                            <label class="form-label">Tool Break</label>
                            <input type="number" name="zdt06" class="form-control floating text-right-align " value="<?php echo "{$item->zdt06}";?>" readonly>
                            <label class="form-label">Trials</label>
                        </td>
                        <td class="floating-label floating">
                            <input type="number" name="zdt03" class="form-control floating text-right-align" value="<?php echo "{$item->zdt03}";?>" readonly>
                            <label class="form-label">M/C Break</label>
                            <input type="number" name="zdt07" class="form-control floating text-right-align " value="<?php echo "{$item->zdt07}";?>" readonly>
                            <label class="form-label">No Schedule</label>
                        </td>
                        <td class="floating-label floating">
                            <input type="number" name="zdt04" class="form-control floating text-right-align" value="<?php echo "{$item->zdt04}";?>" readonly>
                            <label class="form-label">Mat N/A</label>
                            <input type="number" name="zdt08" class="form-control floating text-right-align " value="<?php echo "{$item->zdt08}";?>" readonly>
                            <label class="form-label">Others</label>
                        </td>
                        <td class="floating-label floating">
                            <input type="number" name="zbc00" class="form-control floating text-right-align" value="<?php echo "{$item->total}"; ?>" readonly>
                            <label class="form-label">Util Capacity</label>
                            <input type="text"   name="zrmks" class="form-control floating text-right-align" value="<?php echo "{$item->zrmks}"; ?>" readonly>
                            <label class="form-label">Remarks</label>
                        </td>
                        <td class="text-center floating-label floating">
                            <button type="button" name="action" class="btn btn-warning mt-1" value="modOrder" onclick="btnToggle(this,event);"; style="font-size:0.8em" <?php if($pcnf->zflag == 'S') { echo 'disabled'; }?>>
                                <i class="fa fa-edit"></i>
                            </button>
                            <button type="submit" name="action" value="delOrder" class="btn btn-danger  mt-2" style="font-size:0.8em" onclick="return confirm('Are you sure you want to delete this record?');" <?php if($pcnf->zflag == 'S') { echo 'disabled'; }?>>
                                <i class="fa fa-trash"></i>
                            </button>
                            <input type="checkbox"   name="zcalc" class="form-control " value="<?php echo "{$item->zcalc}"; ?>" readonly>
                        </td>
                    </tr>
                </table>
            </form>
        <?php 
                }
            }

        ?>
        </div>
    </div>
</div>