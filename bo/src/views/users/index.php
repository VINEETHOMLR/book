<?php

use inc\Raise;
$this->mainTitle = 'User Management';
$this->subTitle  = 'User List'; 

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
                            <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Users/Index/">
                                  <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom" onchange="selfrom();">
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date" onchange="selto();">
                                  </div>
                                 
                                  <div class="form-group col-sm-2 input-group">
                                        <input type="text" class="form-control" id="username" name="username" value="" placeholder="<?=Raise::t('app','username')?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="CleanText();">x</span>
                                        </div>
                                  </div>
                                
                                  <div class="form-group col-sm-2">
                                        <select type="option" name="status" class="form-control select" id="status">
                                            <option value=""><?=Raise::t('user','user_status')?></option>
                                            <?php
                                                foreach ($this->userArr as $key => $value) {
                                                  echo '<option value="'.$key.'">'.$value.'</option>';
                                                }
                                            ?>
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

                    <div class="table-responsive mb-4 mt-4">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                  <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('app','username')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('app','full_name')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('user','user_status')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Email Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('app','create_time')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Last Seen</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['username']?></td>
                                           <td><?=$val['fullname']?></td>
                                           <td>
                                             <?php if( in_array(45, $servicesArray) || ($role==1)){ echo $val['userStatus'] ;}
                                                   else echo ($val['user_status']==1) ? 'Active' : 'Inactive'; ?>
                                           </td>
                                           <td><?=($val['email_verification_status']==1) ? 'Verified' : 'Not Verified'?></td>
                                           <td><?=$val['time']?></td>
                                           <td><?=$val['lasttime']?></td>
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Users/Index/">
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

        $('#status').val("<?=$status;?>");
        $('#datefrom').val("<?=$datefrom;?>");
        $('#dateto').val("<?=$dateto;?>");
        $('#username').val("<?=$username;?>");
        
});

function pageHistory(datefrom,dateto,status,username,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
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
  $('#username').val("");
}
function ClearID(){ 
  $('#userID').val("");
}

function switchStatus(id,status){
  if(status == 1){
    var swClass ='';
    changedToStatus = 0 ;
    var url = 'BlockUSer';
  }else{
    var swClass ='';
    changedToStatus = 1 ;
    var url = 'UnBlockUSer';
  }

  $.post('<?=BASEURL;?>Users/'+url,{'uid':id},function(response){

      newResp = JSON.parse(response);
      openSuccess(newResp['response'])
  });

}


</script>
