<?php 
use inc\Raise;
$this->mainTitle = 'Driver Management';
$this->subTitle  = 'Block Dates';


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
              <form id="BlockDateForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Leave/BlockDates/">
                
                <div class="form-group col-sm-2">
                  <select type="option" name="status" class="form-control select" id="status">
                    <option value="">Status</option>
                    <option value="1">Active</option>
                    <option value="2">Inactive</option>
                  </select>
                </div>
                <div class="form-group col-sm-2">
                    <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                </div>
              </form>
            </div>

            <div class="row col-md-12 col-lg-12">
                           
              <div class="col-md-5 col-lg-5">
                                 
              </div>
              <div class="col-md-7 col-lg-7">
                              
                <a href="javascript:void(0)" class="full_width div_float" onclick="createModal();">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add Block Dates</button>
                </a>
                                    
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
                                    
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Start Date</th>
                                     <th class="sorting_disabled" rowspan="1" colspan="1">End Date</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           
                                           <td><?=$val['start_date']?></td>
                                           <td><?=$val['end_date']?></td>
                                           <td><?=$val['status']?></td>
                                           <td><?=$val['action']?></td>
                                       </tr>
                              <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="4" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="BDates_pagination" method="post" action="<?=BASEURL;?>Leave/BlockDates/">
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

  <div class="modal fade" id="myModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>&nbsp;&nbsp;
                 Block Dates</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="formBlockDate" class="form-horizontal" role="form">
              <div class="card-block"> 
              
                <div class="row m-b-30 form-group">
                    <div class="col-lg-12 col-xl-12">
                        <label>Start Date *</label>      
                        <input type="text" id="startDate" class="form-control" placeholder="Start Date" name="startDate">
                    </div>
                </div>
                 <div class="row m-b-30 form-group">
                    <div class="col-lg-12 col-xl-12">
                        <label>End Date *</label>      
                        <input type="text" id="endDate" class="form-control" placeholder="End Date" name="endDate">
                    </div>
                </div>
                 
                <div class="row m-b-30 form-group">
                  <div class="col-lg-12 col-xl-12">
                    <label><?=Raise::t('announcement','status')?> *</label>      
                    <select class="form-control custom-select" id="bStatus" name="bStatus">
                      <option  value="1">Active</option>
                      <option value="2">Inactive</option>
                    </select> 
                    <input type="hidden" value="" id="blockId" name="blockId">      
                  </div>
                </div>
                
              </div>
                
          
              <div class="modal-footer">
                <button type="button" id="save" class="btn btn-primary mr-3"><?=Raise::t('announcement','save_change')?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=Raise::t('announcement','can')?></button>
              </div>
          </form>  
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

      $('#accordionExample').find('li a').attr("data-active","false");
      $('#userMenu').attr("data-active","true");
      $('#userNav').addClass('show');
      $('#blockDates').addClass('active');


        $('#status').val("<?=$status;?>");

        var f3 = flatpickr(document.getElementById('startDate'),{dateFormat:"d-m-Y",});
        var f4 = flatpickr(document.getElementById('endDate'),{dateFormat:"d-m-Y",});
    });
    
    function showModal(id) {

        $.post('<?= BASEURL ?>Leave/getEditBlockdate/',{'id':id},function(response){

           $('#startDate').val(response.from_date);
           $('#endDate').val(response.to_date);
           $('#bStatus').val(response.status);
           $('#blockId').val(id);
           
           $('#myModal').modal('show');
        });
    }

 $('#save').click(function(){

    var startDate = $('#startDate').val();
    var bStatus =  $('#bStatus').val();
    var endDate =  $('#endDate').val();
    var blockId =  $('#blockId').val();


    $.post('<?=BASEURL;?>Leave/AddBlockDate/',{'startDate':startDate,'bStatus':bStatus,'endDate':endDate,'blockId':blockId},function(response){
        newResp = JSON.parse(response);
        if(newResp['status']=='success')
        {
            openSuccess(newResp['response']);
        }
        else
        {
           loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
        }
         return false;
    });
     return false; 
});

    $('#search').click(function(){
        $('#BlockDateForm').submit();
    })
 

  function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Block Date !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Leave/DeleteBlockDate/',{getId:val},function(response){ 
    
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

  function pageHistory(status,page){
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#BDates_pagination').submit();
  }

  function createModal(){

    $('#blockId').val("");
     $("#formBlockDate").trigger("reset");

    $('#myModal').modal('show');

  }

  $('#startDate').click(function(){
       $('.flatpickr-calendar').css('z-index', 930000);
    });


</script>

       



