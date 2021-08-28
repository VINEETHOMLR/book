<?php 
use inc\Raise;
$this->mainTitle = 'Shipment';
$this->subTitle  = '';

 $servicesArray = $_SESSION['INF_privilages'];
 $servicesArray = explode(",", $servicesArray[0]);
 $servicesArray = array_filter($servicesArray);

 $role = $_SESSION['INF_role'];
?>

<style type="text/css">
  
 .dataTables_filter{
    display: none;
  }
  .m-t-40{
    margin-top: 0px !important;
  }
  .dataTable{ text-align: center; }

  .div_float{
     float: right;
     margin-right: 30px;
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
       height: 42px;
}
.select{
  padding-top: 9px;
}
 

</style>

<div id="content" class="main-content">
  <div class="layout-px-spacing">
    <div class="row layout-top-spacing">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
          <div class="row">
            <div class="row col-md-12 col-xs-12">
              <form id="GameForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Shipment/index/">
                <!-- <div class="form-group col-sm-2 ">
                  <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom" onchange="selfrom();">
                </div>
                <div class="form-group col-sm-2">
                  <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date" onchange="selto();">
                </div> -->
                 <div class="form-group col-sm-2 input-group">
                      <input type="text" class="form-control" id="salesshipmentno" name="salesshipmentno" value="" placeholder="Shipment no">
                      <div class="input-group-append">
                          <span class="input-group-text" style="cursor: pointer;" onclick="$('#salesshipmentno').val('');">x</span>
                      </div>
                </div>
                 <!-- <div class="form-group col-sm-2">
                  <select type="option" name="delivery_status" class="form-control select" id="delivery_status">
                    <option value="">Delivery Status</option>
                    <option value="1">Delivered</option>
                    <option value="2">Not Delivered</option>
                  </select>
                </div> -->
                <div class="form-group col-sm-2">
                  <select type="option" name="status" class="form-control select" id="status">
                    <option value=""><?=Raise::t('announcement','status')?></option>
                    <option value="1">Active</option>
                    <option value="2">Inactive</option>
                  </select>
                </div>
                <div class="form-group col-sm-2">
                  <select type="option" name="assigned_driver_id" class="form-control select" id="assigned_driver_id">
                    <option value="">Driver</option>
                    <?php foreach($driverList as $k=>$v){?>
                      <option value="<?= $v['id']?>"><?= $v['driver_name']?></option>
                    <?php }?>  
                  </select>
                </div>


                <div class="form-group col-sm-2">
                    <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                </div>
              </form>
            </div>

            <div class="row col-md-12 col-lg-12">
                           
              <div class="col-md-5 col-lg-5">
               <!--  <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Raise::t('app','yesterday_txt')?></a> </span>
                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Raise::t('app','today_txt')?></a> </span>
                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','7days_txt')?></a> </span> -->
                                 
              </div>
              <div class="col-md-7 col-lg-7">
                              
                <!-- <a href="<?=BASEURL;?>Game/Create/" class="full_width div_float">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Create Game</button>
                </a> -->
                                    
              </div>
                           
            </div>
                             
                                           
          </div>

          <div class="widget-content widget-content-area">

              <div class="table-responsive mb-4 mt-4">
                    <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                          <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                    
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Shipment No.</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Sales Order No.</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Customer Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Customer Number</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Sales Person</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Contact Person</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Contact Number</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Ship to Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" >Address 1 </th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" >Address 2</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" >Driver Name / Vehicle</th>
                                  <!-- <th class="sorting_disabled" rowspan="1" colspan="1" >Assigned Date</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" >Delivery status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" >Delivery Details</th> -->
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['salesshipmentno']?></td>
                                           <td><?=$val['salesordernumber']?></td>
                                           <td><?=$val['customername']?></td>
                                           <td><?=$val['customernumber']?></td>
                                           <td><?=$val['salesperson']?></td>
                                           <td><?=$val['contactperson']?></td>
                                           <td><?=$val['contactnumber']?></td>
                                           <td><?=$val['shiptoname']?></td>
                                           <td><?=$val['shiptoaddress']?></td>
                                           <td><?=$val['shiptoaddress2']?></td>
                                           <td><?=$val['driver_name']?></td>
                                           <!-- <td><?=$val['assigned_date']?></td>
                                           <td><?=$val['delivery_status']?></td>
                                           <td><?=$val['delivery_details']?></td> -->
                                           <td><?=$val['status']?></td>
                                       </tr>
                              <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="15" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="game_pagination" method="post" action="<?=BASEURL;?>Shipment/Index/">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$pagination;?>
                                    </ul>
                                </div>
                            </form>
                          </div>
                        </div>
                </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="assignModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>&nbsp;&nbsp;
                 Assign Driver</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="card-block">     
            
            <div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label>Driver</label>      
                    <select class="form-control custom-select" name="assigned_driver_ids" id="assigned_driver_ids">
                    <option value="">Driver</option>
                    <?php foreach($driverList as $k=>$v){?>
                      <option value="<?= $v['id']?>"><?= $v['driver_name']?></option>
                    <?php }?>   
                          
                    </select> 
                </div>
            </div>
              
            <div class="row m-b-30 form-group">
                <input type="hidden" value="" id="id" name="id">      
            </div>
               
            

          </div>
            
      
          <div class="modal-footer">
            <button type="button" id="save" class="btn btn-primary mr-3"><?=Raise::t('announcement','save_change')?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=Raise::t('announcement','can')?></button>
          </div>
        </div>
      </div>
    </div>
  </div>



  <div class="modal fade " id="detailsModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true" >
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="max-width: 100% !important;">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>&nbsp;&nbsp;
                 Sales Line</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="card-block" id="details">     
            
            
               
            

          </div>
            
      
        <!--   <div class="modal-footer">
            <button type="button" id="save" class="btn btn-primary mr-3"><?=Raise::t('announcement','save_change')?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=Raise::t('announcement','can')?></button>
          </div> -->
        </div>
      </div>
    </div>
  </div>


    <div class="modal fade" id="deliveryModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>&nbsp;&nbsp;
                 Delivery Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="card-block" id="deliverydetails">     
            
            <!-- <table class='table' >
              
             <tr>
               <td>Image</td>
               <td><img src="http://127.0.0.1:801/spci_shipping/web/upload/weight/weight_1_1611756660.jpeg" style="width:100px;height:100px;"></td>
             </tr>

            </table> -->
            
               
            

          </div>
            
      
        <!--   <div class="modal-footer">
            <button type="button" id="save" class="btn btn-primary mr-3"><?=Raise::t('announcement','save_change')?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=Raise::t('announcement','can')?></button>
          </div> -->
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

    $(document).ready(function() {

         
        $('#status').val("<?=$status;?>");
        $('#salesshipmentno').val("<?=$salesshipmentno;?>");
        $('#delivery_status').val("<?=$delivery_status;?>");
        $('#assigned_driver_id').val("<?=$assigned_driver_id;?>");
        var status   = "<?=$status;?>"; 
        var salesshipmentno = "<?=$salesshipmentno;?>";
        var delivery_status   = "<?=$delivery_status;?>";
        var assigned_driver_id   = "<?=$assigned_driver_id;?>"; 
       

        var f1 = flatpickr(document.getElementById('datefrom'),{dateFormat:"d-m-Y",});
        var f2 = flatpickr(document.getElementById('dateto'),{dateFormat:"d-m-Y",});
    });
    
    function showModal(id) {

        $.post('<?= BASEURL ?>Game/getEdit/',{'gameid':id},function(response){

           $('#gname').val(response.name);
           $('#code').val(response.game_code);
           $('#game_id').val(id);
           $('#vendor').val(response.game_vendor);
           $('#orderNo').val(response.order_num);
           $('#gstatus').val(response.status);
           $('#gtype').val(response.is_hot_game);
           $('#myModal').modal('show');
        });
    }

 $('#save').click(function(){

    data = new FormData();
    data.append('shipment_id', $('#id').val());
    data.append('assigned_driver_ids', $('#assigned_driver_ids').val());
    
    loadingoverlay('info',"Please wait..","loading...");
    
    $.ajax({
        url: '<?=BASEURL;?>Shipment/Assigndriver/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 

         
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){
               openSuccess(newResp['response'])  
            }else{
               loadingoverlay('error','error',newResp['response']);
            }
              return false;
        }
    }); 
    return false;
});

    $('#search').click(function(){
        $('#GameForm').submit();
    })

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#shipmentlist').attr("data-active","true");

 

  function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Game !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Game/Delete/',{getId:val},function(response){ 
    
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        openSuccess(newResp['response'])
                    }else{ 
                        loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
                    }
                });
                return false;
            }
        })
  }

  function switchStatus(id,status){
        if(status == 1){
          changedToStatus = 2 ;
        }else{
          changedToStatus = 1 ;
        }

        $.post('<?=BASEURL;?>Shipment/ChangeStatus/',{'id':id,'status':changedToStatus},function(response){
          
          var newResp =JSON.parse(response);
          if(response){
              openSuccess(newResp.response);
          }
        });
  }

  function assignModal(id)
  {
   
 
          $('#id').val(id);
          $('#assignModal').modal('show');
    /*$.post('<?= BASEURL ?>Shipment/getDriverList/',{},function(response){

      
console.log(response);
          //$('#assigned_driver_id').html('<option>hai</option>');
          $('#assigned_driver_id').append('<option>Hello</option>');
          //$('#id').val(id);
          $('#assignModal').modal('show');


          
        });*/

  }

  function showDetails(id)
  {
      
     $.post('<?= BASEURL ?>Shipment/getSalesLines/',{'id':id},function(response){

      

          $('#details').html(response);
          $('#detailsModal').modal('show');


          
        });
     
  }


  function showModal(id)
  {

      
     // $('#deliveryModal').modal('show');

     //$('#deliveryModal').modal('show');
      
     $.post('<?= BASEURL ?>Shipment/getDeliveryDetails/',{'id':id},function(response){

      

           $('#deliverydetails').html(response);
           $('#deliveryModal').modal('show');


          
        });
     
  }



  function pageHistory(assigned_driver_id,status,salesshipmentno,delivery_status,page){
    $('.pagination').append('<input name="assigned_driver_id" value="'+assigned_driver_id+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="salesshipmentno" value="'+salesshipmentno+'" style="display:none;">');
    $('.pagination').append('<input name="delivery_status" value="'+delivery_status+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#game_pagination').submit();
  }
</script>

       



