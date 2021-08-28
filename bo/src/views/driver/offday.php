<?php
  use inc\Raise;
  $this->mainTitle = 'Driver Management';
  $this->subTitle  = 'Off Days';
?>
<style type="text/css">
	#leaderTable_length,#leaderTable_filter{
       display: none;
    }
    h6{
    	font-weight: bold;
    	color: blue;
    }
    .table > thead > tr > th {
      text-align: center;
    }
</style>
<link rel="stylesheet" href="<?=WEB_PATH?>plugins/font-icons/fontawesome/css/regular.css">

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
            	 <div class="widget-content widget-content-area">
            	 	<div class="row col-md-12 col-xs-12">
            	 		
            	 	       <div class=" col-sm-12 col-md-6 col-xl-3 form-group">
            	 	 	       <select type="option" name="driverId" class="form-control custom-select" id="driverId">
                            <option value="">Select Driver</option>
                              <?php
                                  foreach ($driver as $val) {
                                    echo '<option value="'.$val['driver_id'].'">'.ucwords($val['name']).'</option>';
                                  }
                              ?>
                                
                          </select>
                       </div>
                        <div class=" col-sm-12 col-md-6 col-xl-3 form-group">
                         <select type="option" name="day" class="form-control custom-select" id="day">
                            <option value="">Select Day</option>
                            <option value="Sun">Sunday</option>
                            <option value="Mon">Monday</option>
                            <option value="Tue">Tuesday</option>
                            <option value="Wed">Wednesday</option>
                            <option value="Thu">Thursday</option>
                            <option value="Fri">Friday</option>
                            <option value="Sat">Saturday</option>
                          </select>
                       </div>
                       <div class="col-sm-12 col-md-4 col-xl-3 form-group">
                        <button class="btn btn-outline-primary col-12" onclick="setOffDay()">
                            <div class="icon-container">
                                <i data-feather="user-check" style="margin-right: 10px"></i><span class="icon-name">Set Off Day</span>
                            </div>
                        </button>
                    </div>
                </div>

                  <div class="table-responsive mb-4 mt-4">
                      <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                    <th class="sorting_disabled" rowspan="1" colspan="1" >Driver</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Off Day</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                  </tr>
                                </thead>
                                <tbody class="text-center">
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=ucwords($val['name'])?></td>
                                           <td><?=ucwords($val['day'])?></td>
                                           <td><?=$val['action']?></td>
                                       </tr>
                                <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="3" class="text-center">No Data Found</td></tr>';
                                  }   
                                ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Leave/OffDays/">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$pagination?>
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

    <div class="modal fade" id="myModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>&nbsp;&nbsp;
                 Edit Off Day</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form id="formBlockDate" class="form-horizontal" role="form">
              <div class="card-block"> 

                <div class="row m-b-30 form-group">
                  <div class="col-lg-12 col-xl-12">
                    <label>Off Day *</label>      
                    <select type="option" name="dayEdit" class="form-control custom-select" id="dayEdit">
                            <option value="">Select Day</option>
                            <option value="Sun">Sunday</option>
                            <option value="Mon">Monday</option>
                            <option value="Tue">Tuesday</option>
                            <option value="Wed">Wednesday</option>
                            <option value="Thu">Thursday</option>
                            <option value="Fri">Friday</option>
                            <option value="Sat">Saturday</option>
                          </select> 
                    <input type="hidden" value="" id="driverOff" name="driverOff">      
                  </div>
                </div>
                
              </div>
                
          
              <div class="modal-footer">
                <button type="button" id="saveOffDay" class="btn btn-primary mr-3"><?=Raise::t('announcement','save_change')?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=Raise::t('announcement','can')?></button>
              </div>
          </form>  
        </div>
      </div>
    </div>
  </div>

    <script src="<?=WEB_PATH?>plugins/font-icons/feather/feather.min.js"></script>
    <script type="text/javascript">
        feather.replace();
    </script>

 <script>

    $(document).ready(function() {

        $('#accordionExample').find('li a').attr("data-active","false");
        $('#userMenu').attr("data-active","true");
        $('#userNav').addClass('show');
        $('#offDay').addClass('active');
    } );
    
     $('#saveOffDay').click(function(){

        var driverId = $('#driverOff').val();
        var dayEdit =  $('#dayEdit').val();

        $.post('<?=BASEURL;?>Leave/SetOffDay/',{'driver':driverId,'day':dayEdit},function(response){
            newResp = JSON.parse(response);
            if(newResp['status']=='success')
            {
                openSuccess(newResp['response']);
            }
            else
            {
               loadingoverlay('error','Error',newResp['response']);
            }
             return false;
        });
         return false; 
    });


    function setOffDay(type){
   
    	var driver = $('#driverId').val();
      var day = $('#day').val();

    	$.post('<?= BASEURL ?>Leave/SetOffDay/',{driver:driver,'day':day},function(response){ 
    
          newResp = JSON.parse(response);
            
            if(newResp['status'] == 'success'){
                openSuccess(newResp['response'])
            }else{ 
                loadingoverlay('error','Error',newResp['response']);
            }
        });
        return false;
    }

    function showModal(id) {

        $.post('<?= BASEURL ?>Leave/getEditOffDay/',{'id':id},function(response){
           
           $('#dayEdit').val(response.off_day);
           $('#driverOff').val(id);
           
           $('#myModal').modal('show');
        });
    }

    function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Off Day !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Leave/DeleteOffday/',{getId:val},function(response){ 
    
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        openSuccess(newResp['response'])
                    }else{ 
                        loadingoverlay('error','Error',newResp['response']);
                    }
                });
                return false;
            }
        })
  }
   

    function pageHistory(page){
       $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
       $('#user_pagination').submit();
    }
</script>
