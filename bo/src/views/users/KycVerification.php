<?php

use inc\Raise;
$this->mainTitle = 'User Management';
$this->subTitle  = 'KYC Verificaton';

?>

<style type="text/css">

  .select2-container--default .select2-selection--multiple{
     padding: 3px 10px !important;
   }
  
  .div_float{
     float: right;
     margin-right: 10px;
  }

@media(max-width:767px){
    .div_float {
        
        padding: 5px;
        margin-right: 0px;
    }
    .full_width{
     width: 100%;
   }
} 
.form-control{
  height: 40px;
}

</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="row">
                        <div class="row col-md-12 col-xs-12">
                            <form id="btc_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Kyc/Index/">
                                  <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom" onchange="selfrom();">
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date" onchange="selto();">
                                  </div>
                                  <div class="form-group col-sm-2 input-group">
                                        <input type="text" class="form-control" id="username" name="username" value="" placeholder="Username">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="$('#username').val('');">x</span>
                                        </div>
                                  </div>
                                  
                                  <div class="form-group col-sm-2">
                                        <select type="option" name="status" class="form-control custom-select" id="status">
                                            <option value="">Status</option>
                                            <option value="1">Pending</option>
                                            <option value="2">Approved</option>
                                            <option value="3">Rejected</option>
                                        </select>
                                  </div>
                                  
                            </form>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                           
                                <div class="col-md-5 col-lg-5">
                                  <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Raise::t('app','yesterday_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Raise::t('app','today_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','7days_txt')?></a> </span>
                                      
                                </div>
                                <div class="col-md-7 col-lg-7">

                                      <input type="submit" class="btn btn-success col-md-4 col-lg-3 div_float" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                                </div>
                        </div>
                      
                    </div>

                  
                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Request ID</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                     <!--  <th class="sorting_disabled" rowspan="1" colspan="1">Fullname</th> -->
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Passport</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Identity Card</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Request Time</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Remarks</th>
                                      <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    if(!empty($data['data'])){
                                       foreach($data['data'] as $key => $val):
                                ?>
                                    <tr role="row" class="odd">
                                      <td ><a class="badge outline-badge-primary" onclick="openKycModal(<?=$val['id']?>)"><?=$val['id']?></a></span></td>
                                      <td><?=$val['username']?></td>
                                      <!-- <td><?=ucwords($val['fullname'])?></td> -->
                                      <td><?=$val['passport']?></td>
                                      <td><?=$val['id_img']?></td>
                                      <td><?=$val['time']?></td>
                                      <td><?=$val['status']?></td>
                                      <td><?=$val['remarks']?></td>
                                      <td><?=$val['action']?></td>
                                    </tr>
                                <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="8" class="text-center">No Data Found</td></tr>';
                                  }   
                                ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="kyc_pag" method="post" action="<?=BASEURL;?>Kyc/Index/">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$pagination;?>
                                    </ul>
                                </div>
                                <input name="sub" id="subs" value="" style="display: none;">
                            </form>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>

</div>

<div class="modal fade" id="remarksModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>
                Enter Remarks</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" id="statusUpdate">
            <div class="card-block"> 
                <div class="row m-b-30 form-group">
                   <div class="col-lg-12 col-xl-12">
                     <label>Remarks</label>     
                     <textarea name="update-remarks" class="form-control" id="update-remarks" placeholder="Remarks"> </textarea>  
                     <input type="hidden" name="update-status-id" id="update-status-id">
                     <input type="hidden" name="update-type" id="update-type">                                   
                   </div>
                </div>                                                                      
            </div>
           </form> 
        </div>
        <div class="modal-footer">
               <button type="button" class="btn btn-primary mr-3" onclick="updateRemarks()">Update</button> 
               <button type="button" class="btn btn-default" data-dismiss="modal"><?=Raise::t('announcement','can')?></button>
            </div>
      </div>
    </div>
</div>

<div class="modal fade" id="kycModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>&nbsp;&nbsp;
                 KYC Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="card-block"> 
            <div class="table-responsive mb-4 mt-4">
              <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                <div class="row">
                  <div class="col-sm-12" style="overflow: auto;">
                    <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                      <thead>
                      </thead>
                      <tbody id="table_list_box"></tbody>
                    </table>
                  </div>
                </div>
                
              </div>
            </div>                                                                        
          </div>
        
        </div>
        <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal"><?=Raise::t('announcement','can')?></button>
            </div>
      </div>
    </div>
</div>


<?php

$error_dash = Raise::t('app','error_dash');
$success    = Raise::t('app','suucess_txt');
$okay       = Raise::t('app','okay_btn'); 
$load1      = Raise::t('app','load1_txt');
$load2      = Raise::t('app','load2_txt');
?>

<script>
 
$(function () { 

  $('#accordionExample').find('li a').attr("data-active","false");
  $('#userMenu').attr("data-active","true");
  $('#userNav').addClass('show');
  $('#userKyc').addClass('active');

  $('#datefrom').val("<?=$datefrom;?>");
  $('#dateto').val("<?=$dateto;?>");
  $('#username').val("<?=$username;?>");
  $('#status').val("<?=$status;?>");
  
  
});

function pageHistory(datefrom,dateto,username,status,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    
    $('#kyc_pag').submit();
}
       

$( function() {
  var f1 = flatpickr(document.getElementById('datefrom'),{
    dateFormat:"d-m-Y",
  });
  var f2 = flatpickr(document.getElementById('dateto'),{
    dateFormat:"d-m-Y",
  });

  $('#search').click(function(){
      $('#btc_form').submit();
  })
});

function actionReq(id,type){

  $('#update-status-id').val(id);
  $('#update-type').val(type);
  $('#update-remarks').val("");
  $('#remarksModal').modal('show');
         
}

function updateRemarks(){

  var id = $('#update-status-id').val();
  var type = $('#update-type').val();
  var remark = $('#update-remarks').val();

    $.post('<?=BASEURL;?>Kyc/KycVerify',{'id':id,'type':type,'remark':remark},function(response){
          newResp = JSON.parse(response);
          if(newResp['status'] == 'success'){
              
              swal({
                 title: "<?= $success;?>",
                 text: newResp['response'],
                 type: "success",
                 showCancelButton: false,
                 confirmButtonClass: 'btn-success waves-effect waves-light',
                 confirmButtonText: '<?=$okay;?>',
                 }).then(function(isConfirm) {
                      location.reload();
                 });
           }else{
                loadingoverlay('error',"<?=$error_dash;?>",newResp['response']);  
           }
      }); 
     return false;
}
function openKycModal(id){
 
  var postdata = [];
  postdata.push({'name':'request_id','value':id});

 
  $.ajax({
        url: "<?=BASEURL?>Kyc/LoadKycDetails",
        type: "post",
        data: postdata ,
        success: function (response) {
          
          $("#table_list_box").html(response.html.table);
                 
          $('#kycModal').modal('show');
        } 
    });
}




</script>
