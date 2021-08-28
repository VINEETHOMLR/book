<?php

use inc\Raise;
$this->mainTitle = 'Reports';
$this->subTitle = 'Daywise Report';
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
        margin-right: 20px;
    }
    .full_width{
     width: 100%;
   }
} 
.form-control{
  height: 40px;
}
  #table_length,#table_filter{
      display: none;
  }
  .table > thead > tr > th, .table > tbody > tr > td{
      text-align: center;
      border-radius: 4px;

transition: all 0.1s ease;
border-right: 1px solid #e0e6ed;
  }
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="row">
                        <div class="row col-md-12 col-xs-12">
                            <form id="daywise_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Report/Daywise/">
                                  
                                  
                                   <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" value="" name="datefrom" onchange="selfrom();">
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
                                  
                            </form>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                           
                                <div class="col-md-6 col-lg-5" style="margin-bottom: 6px">
                                  <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Raise::t('app','yesterday_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Raise::t('app','today_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','7days_txt')?></a> </span>
                                  <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('wallet','export')?></a> </span>
                                      
                                </div>
                                <div class="form-group col-md-6">

                                      <input type="submit" class="btn btn-success col-sm-12 col-md-6 pull-right col-xl-3 " id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                                </div>
                              
                           
                        </div>
                         <div class="col-md-12 col-xs-12 mt-2">
                           
                             
                             <div class="badge outline-badge-primary form-group col-12 col-md-5 p-3" style="text-align: left;">
                              <h6><b>Total Deposit</b></h6>
                                 <div class="col-md-6 col-12">Total BTC : <?=number_format($data['total']['depositBTC'],$wallet_decimal_limits['btc'])?></div>
                                 <div class="col-md-6 col-12">Total USDT : <?=number_format($data['total']['depositUSDT'],$wallet_decimal_limits['usdt'])?></div>
                                 <div class="col-md-6 col-12">Total ETH : <?=number_format($data['total']['depositETH'],$wallet_decimal_limits['usd'])?></div>
                                
                            </div>
                             
                             <div class="badge outline-badge-primary form-group col-12 col-md-5 p-3" style="text-align: left;">
                              <h6><b>Total Withdraw</b></h6>
                                 <div class="col-md-6 col-12">Total BTC : <?=number_format($data['total']['withdrawlBTC'],$wallet_decimal_limits['btc'])?></div>
                                 <div class="col-md-6 col-12">Total USDT : <?=number_format($data['total']['withdrawlUSDT'],$wallet_decimal_limits['usdt'])?></div>
                                 <div class="col-md-6 col-12">Total ETH : <?=number_format($data['total']['withdrawlETH'],$wallet_decimal_limits['usd'])?></div>
                                 


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
                                  <th class="sorting_disabled" rowspan="1" colspan="3">Deposit</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="4">Withdrawal</th>
                                  
                                  </tr>
                                   <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1"></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">BTC</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">USDT</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">ETH</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">BTC</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">USDT</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">ETH</th>
                                  
                                  
                                  </tr>
                                </thead>
                                <tbody>
                                <?php 
                                 if(!empty($data['data'])){
                                   foreach($data['data'] as $key => $val):
                                ?>
                                    <tr role="row" class="odd">
                                      <td><?=$val['username']?></td>
                                      <td><?=number_format($val['depositBTC'],$wallet_decimal_limits['btc'])?></td>
                                      <td><?=number_format($val['depositUSDT'],$wallet_decimal_limits['usdt'])?></td>
                                      <td><?=number_format($val['depositETH'],$wallet_decimal_limits['usd'])?></td>
                                      <td><?=number_format($val['withdrawlBTC'],$wallet_decimal_limits['btc'])?></td>
                                      <td><?=number_format($val['withdrawlUSDT'],$wallet_decimal_limits['usdt'])?></td>
                                      <td><?=number_format($val['withdrawlETH'],$wallet_decimal_limits['usd'])?></td>
                                      
                                      
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
                            <form class="col-md-12" id="dw_pagination" method="post" action="<?=BASEURL;?>Report/Daywise">
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
  $('#ReportMenu').attr("data-active","true");
  $('#reportNav').addClass('show');
  $('#daywiseNav').addClass('active');

  $('#datefrom').val("<?=$datefrom;?>");
  $('#dateto').val("<?=$dateto;?>");
  $('#username').val("<?=$username;?>");
  });

function pageHistory(datefrom,dateto,username,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#dw_pagination').submit();
}

function exportReport(){
  var datefrom = '<?=$datefrom;?>';
  var dateto   = '<?=$dateto;?>';
  var username = '<?=$username;?>';
 
  loadingoverlay("info","Loading...","Please Wait..");
  $.post('<?=BASEURL?>export/Daywise',{'datefrom':datefrom,'dateto':dateto,'username':username},
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
      $('#daywise_form').submit();
  });
});
</script>
