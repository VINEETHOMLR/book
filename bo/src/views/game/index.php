<?php 
use inc\Raise;
$this->mainTitle = 'Game';
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
              <form id="GameForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Game/index/">
                <div class="form-group col-sm-2 ">
                  <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom" onchange="selfrom();">
                </div>
                <div class="form-group col-sm-2">
                  <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date" onchange="selto();">
                </div>
                 <div class="form-group col-sm-2 input-group">
                      <input type="text" class="form-control" id="name" name="name" value="" placeholder="Gamename">
                      <div class="input-group-append">
                          <span class="input-group-text" style="cursor: pointer;" onclick="$('#name').val('');">x</span>
                      </div>
                </div>
                 <div class="form-group col-sm-2">
                  <select type="option" name="type" class="form-control select" id="type">
                    <option value="">Type</option>
                    <option value="0">Normal Game</option>
                    <option value="1">Hot Game</option>
                  </select>
                </div>
                <div class="form-group col-sm-2">
                  <select type="option" name="status" class="form-control select" id="status">
                    <option value=""><?=Raise::t('announcement','status')?></option>
                    <option value="1">Enabled</option>
                    <option value="2">Disabled</option>
                  </select>
                </div>
                <div class="form-group col-sm-2">
                    <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
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
                              
                <a href="<?=BASEURL;?>Game/Create/" class="full_width div_float">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;Create Game</button>
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
                                    
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Name</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Code</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Vendor</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Order No</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Type</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" >Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" >Create Time</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" >Image</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=ucwords($val['name'])?></td>
                                           <td><?=$val['game_code']?></td>
                                           <td><?=$val['game_vendor']?></td>
                                           <td><?=$val['order_num']?></td>
                                           <td><?=$val['type']?></td>
                                           <td><?=$val['status']?></td>
                                           <td><?=$val['datetime']?></td>
                                           <td><?=$val['image']?></td>
                                           <td><?=$val['action']?></td>
                                       </tr>
                              <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="7" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="game_pagination" method="post" action="<?=BASEURL;?>Game/Index/">
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
                 Edit Game</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="card-block">     
            <div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label>Name</label>      
                    <input type="text" name="gname" id="gname" value="" class="form-control">
                </div>
            </div>
            <div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label>Code</label>      
                    <input type="text" name="code" id="code" value="" class="form-control">
                </div>
            </div>
            <div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label>Vendor</label>      
                    <input type="text" name="vendor" id="vendor" value="" class="form-control">
                </div>
            </div> 
            <div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label>Order No</label>      
                    <input type="text" name="orderNo" id="orderNo" value="" class="form-control">
                </div>
            </div> 
            <div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label>Type</label>      
                    <select class="form-control custom-select" name="gtype" id="gtype">
                       <option value="0">Normal Game</option>
                       <option value="1">Hot Game</option>
                          
                    </select> 
                </div>
            </div>
            <div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label>Status</label>      
                    <select class="form-control custom-select" name="gstatus" id="gstatus">
                       <option value="1">Enabled</option>
                       <option value="2">Disabled</option>
                          
                    </select> 
                </div>
            </div>  
            <div class="row m-b-30 form-group">
                <input type="hidden" value="" id="game_id" name="game_id">      
            </div>
               
            <div class="row m-b-30 form-group">
              <div class="col-lg-12 col-xl-12">

                  <label>Image</label><br>
                  <input type="file" name="filename"  class="" id="filename" >
              </div>
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
        $('#datefrom').val("<?=$datefrom;?>");
        $('#dateto').val("<?=$dateto;?>");
        $('#name').val("<?=$name;?>");
        $('#type').val("<?=$type;?>");
        var status   = "<?=$status;?>"; 
        var datefrom = "<?=$datefrom;?>";
        var dateto   = "<?=$dateto;?>";
        var name   = "<?=$name;?>"; 
        var type   = "<?=$type;?>"; 

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
    data.append('name', $('#gname').val());
    data.append('filename', $('#filename')[0].files[0]);
    data.append('status', $('#gstatus').val());
    data.append('code', $('#code').val());
    data.append('vendor', $('#vendor').val());
    data.append('type', $('#gtype').val());
    data.append('orderNo', $('#orderNo').val());
    data.append('id', $('#game_id').val());
    
    loadingoverlay('info',"Please wait..","loading...");
    
    $.ajax({
        url: '<?=BASEURL;?>Game/Add/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 
        // alert(response);
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
    $('#gamelist').attr("data-active","true");

 

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

  function pageHistory(datefrom,dateto,status,name,type,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="name" value="'+name+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="type" value="'+type+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#game_pagination').submit();
  }
</script>

       



