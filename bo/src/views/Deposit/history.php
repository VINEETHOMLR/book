<?php

use inc\Raise;
$this->mainTitle = 'Deposit History';
global $wallet_decimal_limits;

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
                       <form id="deposit_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Deposit/Index/">
                        <div class="row col-md-12 col-xs-12">
                           
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
                                   <select type="option" name="coin" class="form-control custom-select" id="coin">
                                            <option value="">Select Coin</option>
                                            <?php
                                                foreach ($coinArray as $coins) {
                                                  echo '<option value="'.$coins['id'].'">'.$coins['coin_name'].'</option>';
                                                }
                                            ?>
                                        </select>
                                 
                                </div>
                                   <div class="form-group col-sm-2">
                                        <select type="option" name="status" class="form-control custom-select" id="status">
                                            <option value=""><?=Raise::t('wallet','select_status')?></option>
                                            <?php
                                                foreach ($this->statusArray as $key1 => $value1) {
                                                  echo '<option value="'.$key1.'">'.$value1.'</option>';
                                                }
                                            ?>
                                        </select>
                                  </div>
                            
                        </div>
                      </form>


                        <div class="row col-md-12 col-lg-12">
                           
                                <div class="col-md-5 col-lg-5">
                                  <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Raise::t('app','yesterday_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Raise::t('app','today_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','7days_txt')?></a> </span>
                                  <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('wallet','export')?></a> </span>
                                      
                                </div>
                                <div class="col-md-7 col-lg-7 mt-2 mb-2">

                                      <input type="submit" class="btn btn-success col-md-4 col-lg-3 div_float" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                                </div>
                           
                        </div>
                        <div class="row col-md-12 col-lg-12">
                          <div class="col-md-4 col-xl-4 col-sm-12">
                            <div class="badge outline-badge-primary  mr-3 mb-1 col-12 p-3">Total BTC :<?=number_format($data['total']['btc'],$wallet_decimal_limits['btc'])?></div>
                            
                          </div>    
                          <div class="col-md-4 col-xl-4 col-sm-12">
                            <div class="badge outline-badge-primary  mr-3 mb-1 col-12 p-3">Total USDT :<?=number_format($data['total']['usdt'],$wallet_decimal_limits['usdt'])?></div>
                            
                          </div>
                          <div class="col-md-4 col-xl-4 col-sm-12">
                            <div class="badge outline-badge-primary  mr-3 mb-1 col-12 p-3">Total ETH :<?=number_format($data['total']['eth'],$wallet_decimal_limits['usd'])?></div>
                            
                          </div>     
                                                         
                        </div>
                      
                    </div>

                  
                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                  
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Receiver Address</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Coin Type</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Amount</th>
                                  <!-- <th class="sorting_disabled" rowspan="1" colspan="1">Transaction Hash</th> -->
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Transaction Date</th>
                                   <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php 
                                  if(!empty($data['data'])){
                                   foreach($data['data'] as $key => $val):
                                    $coinCode = $val['coin_code'] == "eth" ? "usd" : $val['coin_code'] ;?>
                                    <tr role="row" class="odd">
                                      <td><?=$val['username']?></td>
                                      <td><?=$val['to_address']?></td>
                                      <td><?=$val['coin_name']?></td>
                                      <td><?=number_format($val['amount'],$wallet_decimal_limits[$coinCode])?></td>
                                      <td><?=$val['create_time']?></td>
                                      <td><?=$val['status']?></td>
                                    </tr>
                                <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="6" class="text-center">No Data Found</td></tr>';
                                  }   
                                ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="deposit_pagination" method="post" action="<?=BASEURL;?>Deposit/Index/">
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
  $('#TranshistoryMenu').attr("data-active","true");
  $('#transhistoryNav').addClass('show');
  $('#deposithis').addClass('active');
  
  $('#datefrom').val("<?=$datefrom;?>");
  $('#dateto').val("<?=$dateto;?>");
  $('#username').val("<?=$username;?>");
  $('#status').val("<?=$status;?>");
  $('#coin').val("<?=$coin;?>");

  
 });

function pageHistory(datefrom,dateto,coin,status,username,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="coin" value="'+coin+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('#deposit_pagination').submit();
}

function exportReport(){
  var datefrom = '<?=$datefrom;?>';
  var dateto   = '<?=$dateto;?>';
  var coin='<?=$coin;?>';
  var username='<?=$username;?>';
  var status='<?=$status;?>';

  loadingoverlay("info","Loading...","Please Wait..");
  $.post('<?=BASEURL?>export/Deposit',{'datefrom':datefrom,'dateto':dateto,'status':status,'coin':coin,'username':username},
      function(response){
          hideoverlay();
      newResp = JSON.parse(response);
      if(newResp['status'] == 'success')
      {        
        openSuccess(newResp['response']);
      }else{
          loadingoverlay("error","Error","Please Try Again");
      }

  });
  return false;
        
}
        

$( function() {
  var f1 = flatpickr(document.getElementById('datefrom'),{
    dateFormat:"d-m-Y",
  });
  var f2 = flatpickr(document.getElementById('dateto'),{
    dateFormat:"d-m-Y",
  });
  $('#search').click(function(){
      $('#deposit_form').submit();
  })
});


</script>
