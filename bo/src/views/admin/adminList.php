

<?php 
use inc\Raise;
$this->mainTitle = 'Admin Management';
$this->subTitle  = 'Admin List';

$cLoginTime = isset($adminData['current_logintime'])? date("d-m-Y H:i:s",$adminData['current_logintime']):"" ;
$lastloginTime = !empty($adminData['last_logintime'])? date("d-m-Y H:i:s",$adminData['last_logintime']):"" ;

?>

<style type="text/css">
  
.table > thead > tr > th {
  text-align: center;
  }
.table-controls {
    padding: 0;
    margin: 0;
    list-style: none;
}
.table-controls > li {
    display: inline-block;
    margin: 0 2px;
    line-height: 1;
}
@media only screen and (min-width: 1200px) {

  .marginLeft{
     margin-left: 30%;
  }

  .addbtn{
    position: absolute;
    right: 15px;
  }
}

@media only screen and (max-width: 1200px) {
  .addbtn{
    margin-left: 10px;
  }
}

.form-control{
  height: 40px;
}
.datebuttonsDiv{
  margin-top: -40px;
}
.status{
  padding-top: 9px;
}

</style>
<link href="<?=WEB_PATH?>assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />

 <div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
          <div class="user-profile layout-spacing col-12">
                <div class="widget-content widget-content-area">
                     <div class="d-flex justify-content-between">
                          <h3 class=""><?=ucwords($adminData['username']);?></h3>
                          <a href="<?=BASEURL?>Admin/Profile/?admin=<?= base64_encode($adminData['id']) ?>" class="mt-2 edit-profile"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-3"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg></a>
                      </div>
                      <div class="user-info-list">

                          <div class="col-12 row">
                              <div class="col-md-6 col-sm-12">
                                <label class="marginLeft"><?= Raise::t('subadmin','user_text'); ?></label> : <label><?=ucwords($adminData['username']);?></label>
                              </div>
                              <div class="col-md-6 col-sm-12">
                                <label><?= Raise::t('subadmin','name_text'); ?></label> : <label><?=ucwords($adminData['name']);?></label>
                              </div>
                              <div class="col-md-6 col-sm-12">
                                <label class="marginLeft"><?= Raise::t('subadmin','logintime_text'); ?></label> : <label><?=$cLoginTime;?></label>
                              </div>
                              <div class="col-md-6 col-sm-12">
                                <label><?= Raise::t('subadmin','lastseen_text'); ?></label> : <label><?=$lastloginTime;?></label>
                              </div>
                                       
                          </div>                                    
                      </div>
                </div>
          </div>

          <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
              <div class="statbox widget box box-shadow">
                  <div class="widget-header">
                        <div class="row">
                             <div class="col-xl-10 col-md-8 col-sm-12 col-12">
                               <h5><b>Sub Admin List</b></h5>
                             </div>
                             <a href="<?=BASEURL?>Admin/Add/"><button class="btn btn-outline-info addbtn mb-2" >
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>Add New Admin</button></a>                 
                        </div>
                        <br>
                        
                        <form class="row col-md-12" method="post" action="<?=BASEURL;?>Admin/Index/">
                              <div class="form-group col-sm-12 col-md-4 col-lg-2">
                                  <input type="text" id="datefrom" class="form-control" placeholder="Login From" name="datefrom" onchange="selfrom();">
                              </div>
                              <div class="form-group col-sm-12 col-md-4 col-lg-2">
                                  <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="Login To" onchange="selto();">
                              </div>
                              <div class="form-group col-sm-12 col-md-4 col-lg-2 input-group">
                                  <input type="text" class="form-control" id="username" name="username" value="" placeholder="<?=Raise::t('app','username')?>">
                                  <div class="input-group-append">
                                     <span class="form-control input-group-text" style="cursor: pointer;" onclick="CleanText();">x</span>
                                  </div>
                              </div>
                              <div class="form-group col-sm-12 col-md-4 col-lg-2">
                                  <select class="form-control status" name="status" id="status">
                                      <option value="">Status</option>
                                      <option value="1">Active</option>
                                      <option value="0">Blocked</option>
                                  </select>
                              </div>
                              <div class=" form-group col-sm-12 col-md-4 col-lg-2 pull-right">
                                  <button class="btn btn-primary col-12 filtered-list-search" type="submit">
                                  <svg style="margin-right: 10px" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg><?=Raise::t('app','search_txt')?></button>
                              </div>
                              <div class="form-group col-md-12 col-xs-12 datebuttonsDiv">
      
                                  <span class="datebuttons"><a class="date-yesterday text-primary"><?=Raise::t('app','yesterday_txt')?></a></span>&nbsp;&nbsp;<span class="datebuttons"><a class="date-today text-primary"><?=Raise::t('app','today_txt')?></a></span>&nbsp;&nbsp;<span class="datebuttons"><a class="date-seven text-primary"><?=Raise::t('app','7days_txt')?></a></span>
     
                              </div>
                        </form>
                </div>
                <br>
                <div class="widget-content widget-content-area">
                     <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                    <th class="sorting_disabled" rowspan="1" colspan="1" ><?= Raise::t('subadmin','user_text'); ?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('app','full_name')?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('subadmin','email_txt')?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('subadmin','login_txt')?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('subadmin','lastseen_text')?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('appointment','status')?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('app','action_text')?></th>
                                </thead>
                                <tbody class="text-center">
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['username']?></td>
                                           <td><?=$val['name']?></td>
                                           <td><?=$val['email']?></td>
                                           <td><?=$val['login']?></td>
                                           <td><?=$val['lastSeen']?></td>
                                           <td><?=$val['Status']?></td>
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Admin/Index/">
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

<script>
    $('#accordionExample').find('li a').attr("data-active","false");
    $('#adminMenu').attr("data-active","true");
    $('#adminNav').addClass('show');
    $('#adminList').addClass('active');

    $('#status').val("<?=$status;?>");
    $('#datefrom').val("<?=$datefrom;?>");
    $('#dateto').val("<?=$dateto;?>");
    $('#username').val("<?=$username;?>");
</script>

<script> 


    $(function () {

        document.getElementById('datefrom').flatpickr();
        document.getElementById('dateto').flatpickr();

    });

  function switchStatus(id,status){
        if(status == 1){
          changedToStatus = 0 ;
        }else{
          changedToStatus = 1 ;
        }

        $.post('<?=BASEURL;?>Admin/UpdateStatus/',{'id':id,'status':changedToStatus},function(response){
           if(response){
              openSuccess('<?=Raise::t('appointment','S4')?>')
          }
        });
  }

  function CleanText(){ 
     $('#username').val("");
  }

  function DeleteAdmin(id){

     const swalWithBootstrapButtons = swal.mixin({
         confirmButtonClass: 'btn btn-success btn-rounded',
         cancelButtonClass: 'btn btn-danger btn-rounded mr-3',
         buttonsStyling: false,
      }) 
     
      swalWithBootstrapButtons({
         title: 'Are you sure?',
         text: "You won't be able to revert this!",
         type: 'warning',
         showCancelButton: true,
         confirmButtonText: 'Yes, delete it!',
         cancelButtonText: 'No, cancel!',
         reverseButtons: true,
         padding: '2em'
       }).then(function(result) {
            if (result.value) {
              $.post('<?=BASEURL;?>Admin/Delete/',{'id':id},function(response){

                swalWithBootstrapButtons(
                  'Deleted!',
                  'Admin has been deleted.',
                  'success'
                )
                location.reload();
              })

            } else if (
            result.dismiss === swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons(
                    'Cancelled',
                    'Admin Details Not Deleted',
                    'error'
            )}
        })
    }

function pageHistory(datefrom,dateto,status,username,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#user_pagination').submit();
}

</script>
