<?php

use inc\Raise;
$this->mainTitle = 'Driver Management';
$this->subTitle  = 'Driver List'; 

 $servicesArray = $_SESSION['INF_privilages'];
 $servicesArray = explode(",", $servicesArray[0]);
 $servicesArray = array_filter($servicesArray);

 $role = $_SESSION['INF_role'];
//$this->typeArr = array(1=>Raise::t('user','free_txt'),2=>Raise::t('user','normal_txt'));
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
                            <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Driver/Index/">
                                 
                                  <div class="form-group col-sm-2 input-group">
                                        <input type="text" class="form-control" id="driver_name" name="driver_name" value="" placeholder="<?=Raise::t('app','drivername')?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="$('#driver_name').val('');">x</span>
                                        </div>
                                  </div>


                                  <div class="form-group col-sm-2 input-group">
                                        <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" value="" placeholder="Vehicle Number">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="$('#vehicle_number').val('');">x</span>
                                        </div>
                                  </div>


                                  <div class="form-group col-sm-2">
                                    <select type="option" name="driverstatus" class="form-control select" id="driverstatus">
                                      <option value="">Driver Status</option>
                                      <option value="1">Active</option>
                                      <option value="2">Inactive</option>
                                    </select>
                                  </div>
                                  <div class="form-group col-sm-2 input-group">
                                        <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                                  </div>
                                
                            </form>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                           
                                <div class="col-md-5 col-lg-5">                                  
                                </div>
                                <div class="col-md-7 col-lg-7">
                              
                                  <a href="<?=BASEURL;?>Driver/Create/" class="full_width div_float">
                                    <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Add Driver</button>
                                  </a>
                                    
                                </div>
                        </div>
                      
                    </div>

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                  <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('app','drivername')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Vehicle Number</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Contact Number</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('app','create_time')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                  
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['name']?></td>
                                           <td><?=$val['vehicle_number']?></td>
                                           <td><?=$val['phone']?></td>
                                           <td><?=$val['status']?></td>
                                           <td><?=$val['created_at']?></td>
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Driver/Index/">
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

<script>
 
$(function () { 

      $('#accordionExample').find('li a').attr("data-active","false");
      $('#userMenu').attr("data-active","true");
      $('#userNav').addClass('show');
      $('#userList').addClass('active');

        $('#driverstatus').val("<?=$status;?>");
        $('#driver_name').val("<?=$username;?>");
        $('#vehicle_number').val("<?=$vehicle_number;?>");
        
});

function pageHistory(status,driver_name,vehicle_number,page){
    $('.pagination').append('<input name="driver_name" value="'+driver_name+'" style="display:none;">');
    $('.pagination').append('<input name="vehicle_number" value="'+vehicle_number+'" style="display:none;">');
    $('.pagination').append('<input name="driverstatus" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#user_pagination').submit();
}

$( function() {

    var f1 = flatpickr(document.getElementById('datefrom'),{
      dateFormat:"d-m-Y",
    });
    var f2 = flatpickr(document.getElementById('dateto'),{
      dateFormat:"d-m-Y",
    });
  
    $('#search').click(function(){
        $('#userForm').submit();
    })
});

function CleanText(){ 
  $('#driver_name').val("");
}
function ClearID(){ 
  $('#userID').val("");
}

function DeleteDriver(id){




        swal({
            title: 'Are you sure want to delete this driver?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-rounded',
            cancelButtonClass: 'btn btn-rounded mr-3',
            confirmButtonText: '<?=Raise::t('user','delete_txt');?>'
        }).then((result) => {
            if (result.value) {
                $.post('<?=BASEURL;?>Driver/DeleteDriver',{'uid':id},function(response){
                     
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        
                        openSuccess(newResp['response'],'<?=BASEURL;?>Driver/Index/')
                    }else{
                        loadingoverlay("error","Error","Failed to Change");
                    }
                });
            }
        })

       return false;

}

/*$('.proceedToDelete').click(function(){

        var user = $('#editId').val();

        swal({
            title: '<?=Raise::t('user','conf_txt');?> ',
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-rounded',
            cancelButtonClass: 'btn btn-rounded mr-3',
            confirmButtonText: '<?=Raise::t('user','delete_txt');?>'
        }).then((result) => {
            if (result.value) {
                $.post('<?=BASEURL;?>Users/DeleteUSer',{'uid':user},function(response){
                     
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        
                        openSuccess(newResp['response'],'<?=BASEURL;?>Users/Index/')
                    }else{
                        loadingoverlay("error","Error","Failed to Change");
                    }
                });
            }
        })

       return false;
    });*/

// function switchStatus(id,status){
//   if(status == 1){
//     var swClass ='';
//     changedToStatus = 0 ;
//     var url = 'BlockUSer';
//   }else{
//     var swClass ='';
//     changedToStatus = 1 ;
//     var url = 'UnBlockUSer';
//   }

//   $.post('<?=BASEURL;?>Driver/'+url,{'uid':id},function(response){

//       newResp = JSON.parse(response);
//       openSuccess(newResp['response'])
//   });

// }


function switchStatus(id,status){
        if(status == 1){
          changedToStatus = 2 ;
        }else{
          changedToStatus = 1 ;
        }

        $.post('<?=BASEURL;?>Driver/UpdateStatus/',{'id':id,'status':changedToStatus},function(response){
          
          var newResp =JSON.parse(response);
          if(response){
              openSuccess(newResp.response);
          }
        });
  }


</script>
