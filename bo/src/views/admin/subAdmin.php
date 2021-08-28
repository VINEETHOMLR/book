

<?php 
use inc\Raise;
$this->mainTitle = 'Admin Management';
$this->subTitle  = 'SubAdmin Activity Log';
?>

<style type="text/css">
  
.table > thead > tr > th {
  text-align: center;
  }

.form-control{
  height: 40px;
}
.datebuttonsDiv{
  margin-top: -40px;
}

</style>

 <div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

          <div id="tableHover" class="col-lg-12 col-12 layout-spacing">
              <div class="statbox widget box box-shadow">
                  <div class="widget-header">
                        <div class="row">
                             <div class="col-xl-10 col-md-8 col-sm-12 col-12">
                               <h5><b>SubAdmin Activity Log</b></h5>
                             </div>
                                       
                        </div>
                        <br>
                        
                        <form class="row col-md-12" method="post" action="<?=BASEURL;?>Admin/SubadminActivity/">
                              <div class="form-group col-sm-12 col-md-4 col-lg-2">
                                  <input type="text" id="datefrom" class="form-control" placeholder="Time From" name="datefrom" onchange="selfrom();">
                              </div>
                              <div class="form-group col-sm-12 col-md-4 col-lg-2">
                                  <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="Time To" onchange="selto();">
                              </div>
                              <div class="form-group col-sm-12 col-md-4 col-lg-2">
                                  <select name="username" id="username" class="form-control custom-select">
                                     <option value="">Select Admin</option>
                                     <?php 
                                        foreach ($list as $key => $value) {
                                           echo '<option value="'.$value['id'].'">'.$value['username'].'</option>';
                                        }
                                     ?>
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
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?=Raise::t('app','username')?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1"><?= Raise::t('subadmin','activity_text'); ?></th>
                                    <th class="sorting_disabled" rowspan="1" colspan="1">Time</th>
                                  </tr>
                                </thead>
                                <tbody class="text-center">
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['subAdmin']?></td>
                                           <td><?=$val['action']?></td>
                                           <td><?=$val['time']?></td>
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
                            <form class="col-md-12" id="user_pagination" method="post" action="<?=BASEURL;?>Admin/SubadminActivity/">
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
    $('#subAdminNav').addClass('active');

    $(function () {

        document.getElementById('datefrom').flatpickr();
        document.getElementById('dateto').flatpickr();

        $('#datefrom').val("<?=$datefrom;?>");
        $('#dateto').val("<?=$dateto;?>");
        $('#username').val("<?=$username;?>");
    });

    function pageHistory(datefrom,dateto,username,page){
        $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
        $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
        $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
        $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
        $('#user_pagination').submit();
    }
</script>

