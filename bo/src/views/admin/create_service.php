
<?php
 use inc\Raise;
  $this->mainTitle = 'Admin Management';
  $this->subTitle  = 'Service Group';

  if(!empty($servicesedit)){
  	$style1="display: none";
  	$style="";
  }else{
    $style="display: none";
    $style1="";
  }

  if($servicesedit != ''){
      $service_select_arr = explode(',',$servicesedit);
      $button=Raise::t('servicegroup', 'update'); 
  }else{
      $service_select_arr = array();
      $button=Raise::t('servicegroup', 'create_text');
  }
?>

<style type="text/css">
  .dot{
    background: #bae7ff;
    border: 2px solid #2196f3;
    padding: 4px;
    border-radius: 50%;
    margin-right: 5px;
    display: inline-block;
  }
  .table > tbody > tr > td {
    color: #515365;
  }

  .breadcrumb-two .breadcrumb li a::before {
    content: none;
  }

  table td:nth-of-type(2) {width:60%; text-align: left;}
</style>


<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
        
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing" id="ServiceListDiv" style="<?=$style1;?>">
                <div class="widget-content widget-content-area br-6">

                    <button class="btn btn-outline-primary mb-2 float-right" id="createServBtn">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>

                      <?= Raise::t('servicegroup','create_text'); ?>
                        
                      </button>
                  
                    <div class="table-responsive mb-4 mt-4">
                        <table id="dt" class="table table-hover" style="width:100%">
                            
                        </table>
                    </div>
                </div>
            </div>


             <div class="col-12" id="CreateServiceDiv" style="<?=$style;?>">
                    <div class="col-12">
                        <div class="card"> 
                            <nav class="breadcrumb-two" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                  <li class="breadcrumb-item"><a href="<?=BASEURL;?>Service/Index/">Service list</a></li>
                                  <li class="breadcrumb-item active"><a href="javascript:void(0);">Services</a></li>
                                </ol>
                            </nav>
                             <div class="card-body">
                              <div class="col-md-12"></div>
                               <div class="row" style="padding-top: 20px; margin-left: 5px;" >
                                 <form role="form" id="createPrivilegeGrp" class="col-md-12">
                                   <div class="panel-body">
                                     <div class="form-body table-responsive">
                                      <table class="table table-bordered">
                                        <tr class="servicenameDiv has-feedback">
                                          <td ><label style="color:#515365;"><?= Raise::t('servicegroup', 'servicename_text'); ?> *</label></td>
                                          <td><div class="col-md-4"><input type="text" id="state-success" name="servicename" data-divName='servicenameDiv' class="form-control servicename input-sm" value="<?=$servicenameedit;?>">
                                           <span class="text-danger errorMsg servicenameMsg"></span></div>

                                          <input type="hidden" name="servegrpid" id="servegrpid" value="<?=$servicegrpinfoid;?>">
                                         </td>
                                        </tr>
                      
                                        <tr style="background: #d6e0e3;margin-top: 12px;padding: 6px;">
                                          <td colspan="2" style="padding-top: 5px;font-weight: bold;padding-left: 18px;"><span class="fa fa-cog"></span>&nbsp;<?= Raise::t('servicegroup', 'selservice_dash'); ?></td>
                                        </tr>
                       
                                        <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkmem" onclick="checkList('checkmem')">
                                                    <span class="new-control-indicator"></span>&nbsp;<?= Raise::t('app', 'admin_mang'); ?>
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                
                                                foreach ($servicesArray as $key => $memval) {
                                                    if( $key==1 || $key==2 ) { 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                       
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkmem" name="servicearr[]" class="checkmem"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                        <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkUser" onclick="checkList('checkUser')">
                                                    <span class="new-control-indicator"></span>&nbsp;User Management
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                foreach ($servicesArray as $key => $memval) {
                                                    if( $key==3 || $key==4 || $key==7) { 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                       
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkUser" name="servicearr[]"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                          <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkwalletshis" onclick="checkList('checkwalletshis')">
                                                    <span class="new-control-indicator"></span>&nbsp; Wallet History
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                foreach ($servicesArray as $key => $memval) {
                                                    if ( $key==16|| $key==17 || $key==18 || $key==19){ 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkwalletshis" name="servicearr[]"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                         <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkTranshis" onclick="checkList('checkTranshis')">
                                                    <span class="new-control-indicator"></span>&nbsp;Transacton History
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                foreach ($servicesArray as $key => $memval) {
                                                    if ( $key==8|| $key==9 || $key==11 ){ 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkTranshis" name="servicearr[]"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                          <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkReport" onclick="checkList('checkReport')">
                                                    <span class="new-control-indicator"></span>&nbsp;Report
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                foreach ($servicesArray as $key => $memval) {
                                                    if ( in_array($key, range(14,15)) ){ 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkReport" name="servicearr[]"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                        <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkAnno" onclick="checkList('checkAnno')">
                                                    <span class="new-control-indicator"></span>&nbsp;Announcement
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                foreach ($servicesArray as $key => $memval) {
                                                    if( $key==5) { 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                       
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkAnno" name="servicearr[]"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                        <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkTick" onclick="checkList('checkTick')">
                                                    <span class="new-control-indicator"></span>&nbsp;Ticketing System
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                foreach ($servicesArray as $key => $memval) {
                                                    if( $key==6) { 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                       
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkTick" name="servicearr[]"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                         <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkGame" onclick="checkList('checkGame')">
                                                    <span class="new-control-indicator"></span>&nbsp;Game List
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                foreach ($servicesArray as $key => $memval) {
                                                    if( $key==12) { 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                       
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkGame" name="servicearr[]"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                         <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkCoin" onclick="checkList('checkCoin')">
                                                    <span class="new-control-indicator"></span>&nbsp;Coin
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                foreach ($servicesArray as $key => $memval) {
                                                    if( $key==13) { 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                       
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkCoin" name="servicearr[]"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                        <tr>
                                           <td style="width: 20%">
                                             <h6>
                                              <div class="n-chk">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkExport" onclick="checkList('checkExport')">
                                                    <span class="new-control-indicator"></span>&nbsp;Export
                                                  </label>
                                              </div>
                                            </h6>
                                           </td>
                                           <td>
                                               <?php
                                                foreach ($servicesArray as $key => $memval) {
                                                    if( $key==10) { 
                                                      $checked= (in_array($key, $service_select_arr)) ? 'checked' : ''; 
                                       
                                                ?>
                                                <div class="n-chk col-md-4" style="margin-top: 5px;float:left;">
                                                  <label class="new-control new-checkbox checkbox-success" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input checkExport" name="servicearr[]"  value="<?=$key;?>" <?=$checked;?>>
                                                    <span class="new-control-indicator"></span>&nbsp;<?=$memval;?>
                                                  </label>

                                                </div>
                                                <?php } } ?>

                                            </td>
                                        </tr>
                                       
                                      </table>
                                    </div>
                                </div>
                                <div class="panel-footer text-center">
                                    <div class="form-actions">
                                        <button type="button" class="btn btn-primary proceedToCreate pull-right">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>&nbsp;<?=$button;?></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>  
                </div>
            </div>
        </div>

    </div>
  </div>


<?php
$error_dash = Raise::t('app','error_dash');
$success    = Raise::t('app','suucess_txt');
$okay       = Raise::t('app','okay_btn'); 
?>

<script type="text/javascript">

	  $(function () {

      $('#dt').DataTable({

        searching: false,
        ajax: {
                url: "<?= BASEURL . 'Service/GetServiceList' ?>",
                type: "post"
            },
            columns: [
            {data: 'id', title: 'id', visible:false},
            {title: '<?= Raise::t('servicegroup','ser_dash'); ?>', data: 'group_name' , orderable: false},
            {title: '<?= Raise::t('servicegroup','service_txt'); ?>', data: 'service', orderable: false},
            {title: '<?= Raise::t('app','action_text'); ?>', data: 'action', orderable: false},
            ],
            "pageLength": 50
      });

      $('.dataTables_paginate').hide();
      $('.dataTables_length').hide();
      $('.dataTables_info').hide();
      
      setInterval(function(){ $('.dataTables_empty').html("<?=Raise::t('app','noData_txt')?>"); }, 100);
    });

    $('#createServBtn').click(function(){
      $('#CreateServiceDiv').show();
      $('#ServiceListDiv').hide();
    });
    

$('.proceedToCreate').click(function(){

      postdata = $('#createPrivilegeGrp').serializeArray();
      postdata.push({'name':'newPrivilege','value':true});
   
    $.post('<?=BASEURL;?>Service/AddService',postdata,function(response){
        newResp = JSON.parse(response);
        if(newResp['status'] == 'success')
        {   
            openSuccess(newResp['response'],'<?=BASEURL;?>Service/Index/') 
        }
        else
        {
           loadingoverlay('error','<?=$error_dash;?>',newResp['response']);

        }
        return false;
    });
    return false;
});

function checkList(val){ 

    if($('#'+val).prop('checked')) {
        $('.'+val+'').prop('checked', true);
    } else {
        $('.'+val+'').prop('checked', false);
    }
}

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#adminMenu').attr("data-active","true");
    $('#adminNav').addClass('show');
    $('#serviceNav').addClass('active');

</script>