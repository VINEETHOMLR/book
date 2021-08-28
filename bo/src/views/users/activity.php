<?php

use inc\Raise;
$this->mainTitle = 'User Management';
$this->subTitle  = 'User Activity Log'; 

?>

<style type="text/css">
  
  .dataTables_filter{
    display: none;
  }
  .m-t-40{
    margin-top: 0px !important;
  }
  .dataTable{ text-align: center; }

.form-control{
       height: 42px;
}
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="row">
                        <div class="row col-md-12 col-xs-12">
                            <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Users/Activity/">
                                  <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom" onchange="selfrom();">
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date" onchange="selto();">
                                  </div>
                                 <!--  <div class="form-group col-sm-2 input-group">
                                        <input type="text" class="form-control" id="userID" name="userID" value="" placeholder="<?=Raise::t('app','user_ID')?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="ClearID();">x</span>
                                        </div>
                                  </div> -->
                                  <div class="form-group col-sm-2 input-group">
                                        <input type="text" class="form-control" id="fullname" name="fullname" value="" placeholder="<?=Raise::t('app','fullname')?>">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="CleanText();">x</span>
                                        </div>
                                  </div>
                                  <div class="form-group col-sm-2">
                                       <input type="submit" class="btn btn-success col-12 " id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                                  </div>
                            </form>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                           
                                <div class="col-md-5 col-lg-5">
                                  <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Raise::t('app','yesterday_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Raise::t('app','today_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','7days_txt')?></a> </span>
                                      
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
                                    <th class="sorting_disabled" rowspan="1" colspan="1" ><?=Raise::t('app','user_ID')?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('app','fullname')?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Activity</th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Time</th>
                                  </tr>
                                </thead>
                                <tbody class="text-center">
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['id']?></td>
                                           <td><?=$val['fname']?></td>
                                           <td><?=$val['activity']?></td>
                                           <td><?=$val['time']?></td>
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Users/Activity/">
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
      $('#userActivity').addClass('active');

      var f1 = flatpickr(document.getElementById('datefrom'));
      var f2 = flatpickr(document.getElementById('dateto'));

        $('#userID').val("<?=$userID;?>");
        $('#datefrom').val("<?=$datefrom;?>");
        $('#dateto').val("<?=$dateto;?>");
        $('#fullname').val("<?=$fullname;?>");
});

function CleanText(){ 
  $('#fullname').val("");
}
function ClearID(){ 
  $('#userID').val("");
}

function pageHistory(datefrom,dateto,userId,fullname,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="userID" value="'+userId+'" style="display:none;">');
    $('.pagination').append('<input name="fullname" value="'+fullname+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#user_pagination').submit();
}

</script>
