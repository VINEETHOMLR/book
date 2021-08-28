<?php

use inc\Raise;
$this->mainTitle = 'Coinswap History';

global $wallet_decimal_limits;
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
</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
         <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                <div class="widget-content widget-content-area br-6">

                    <div class="row">
                        <div class="row col-md-12 col-xs-12">
                            <form id="coinswap_form" class="row col-md-12" method="post" action="<?=BASEURL;?>CoinSwap/Index/">
                             
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
                                  <div class="form-group col-sm-2">
                                       <select class="form-control" name="market" id="market">
                                           <option value="btc-usdt">BTC - USDT</option>
                                           <option value="eth-usdt">ETH - USDT</option>
                                           <option value="itt-usdt">ITT - USDT</option>
                                       </select>
                                  </div>
                                  
                            </form>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                           
                                <div class="col-md-5 col-lg-5">
                                  <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Raise::t('app','yesterday_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Raise::t('app','today_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','7days_txt')?></a> </span>
                                  <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','export')?></a> </span>
                                      
                                </div>
                                <div class="col-md-7 col-lg-7">

                                      <input type="submit" class="btn btn-success col-md-4 col-lg-3 div_float" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                                </div>
                           
                        </div>

                        <div class="row col-md-12 col-lg-12">
                            <div class="col-md-12 col-lg-12">
                                 <div class="badge outline-badge-primary col-md-3 col-xl-3 col-12 p-3">Total Order Amount: <?=number_format(floatval($data['total']['totOrder']),strlen(substr(strrchr(floatval($data['total']['totOrder']), "."), 1))) ?></div>
                                 <div class="badge outline-badge-primary col-md-3 col-xl-4 col-12 p-3">Total Executed Amount : <?= number_format($data['total']['execAmt'],$wallet_decimal_limits['btc']) ?></div>
                                 <div class="badge outline-badge-primary col-md-3 col-xl-3 col-12 p-3">Total Executed Value : <?= number_format($data['total']['execValue'],$wallet_decimal_limits['usdt']) ?></div>
                            </div>
                        </div>
                      
                    </div>
                   
                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                               <thead>
                                  <tr role="row">
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Date">Date</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name">Username</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Coinswap From">Swap From</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Coinswap To">Swap To</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Swap Out Amount">Side</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Swap Out Amount">Order Amount</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Swap Out Coin Price">Price(USDT)</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Swap Out Service Fee">Execute Amount(<?=$tableCode?>)</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Swap In Amount">Executed Value(USDT)</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Swap In Coin Price">Fee</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Swap In Service Fee">Execution Time</th>
                                       <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Date">Order ID</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php 
                                  if(!empty($data['data'])){
                                   foreach($data['data'] as $key => $val):?>
                                    <tr role="row" class="odd">
                                      <td><?=$val['date']?></td>
                                      <td><?=$val['username']?></td>
                                      <td><?=$val['coinswapfrom']?></td>
                                      <td><?=$val['coinswapto']?></td>
                                      <td><?=$val['coinSide']?></td>
                                      <td><?=floatval($val['swap_out_amout'])?></td>
                                      <td><?=number_format($val['swap_in_coin_price'],$wallet_decimal_limits['usdt'])?></td>
                                      <td><?=number_format($val['executed_amount'],$wallet_decimal_limits['btc'])?></td>
                                      <td><?=number_format($val['exceuted_value'],$wallet_decimal_limits['usdt'])?></td>
                                      <td><?=floatval($val['swap_in_service_fee'])?></td>
                                      <td><?=$val['exec_time']?></td>
                                      <td><?=$val['order_id']?></td>
                                    </tr>
                                <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="11" class="text-center">No Data Found</td></tr>';
                                  }   
                                ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="coinswap_pagination" method="post" action="<?=BASEURL;?>CoinSwap/Index/">
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
  $('#coinswaphis').addClass('active');
  
  $('#datefrom').val("<?=$datefrom;?>");
  $('#dateto').val("<?=$dateto;?>");
  $('#username').val("<?=$username;?>");
  $('#market').val("<?=$swapOption;?>");
});
  

function pageHistory(datefrom,dateto,username,swap,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="market" value="'+swap+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#coinswap_pagination').submit();
}

function exportReport(){
        
        var datefrom = '<?=$datefrom;?>';
        var dateto   = '<?=$dateto;?>';
        var username = '<?=$username;?>';
        var swapType = "<?=$swapOption;?>";
       
        loadingoverlay("info","Loading...","Please Wait..");
        $.post('<?=BASEURL?>export/CoinSwap',{'username':username,'datefrom':datefrom,'dateto':dateto,'swapType':swapType},
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
        $('#coinswap_form').submit();
    })
  });


</script>
