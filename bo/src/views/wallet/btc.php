<?php

use inc\Raise;
$this->mainTitle = Raise::t('wallet','tittle');
$this->subTitle  = Raise::t('wallet','btcwallet'); 
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
                            <form id="btc_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Wallet/Btc/">
                              
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
                                        <select type="option" name="creditType" class="form-control custom-select" id="creditType">
                                            <option value=""><?=Raise::t('wallet','credit_type')?></option>
                                            <?php
                                                foreach ($this->creditType as $key1 => $value1) {
                                                  echo '<option value="'.$key1.'">'.$value1.'</option>';
                                                }
                                            ?>
                                        </select>
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <select type="option" name="txn_type" class="form-control custom-select" id="txn_type">
                                            <option value=""><?=Raise::t('wallet','txn_type')?></option>
                                            <?php
                                                foreach ($this->transactionArray as $key1 => $value1) {
                                                  echo '<option value="'.$key1.'">'.$value1.'</option>';
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
                                  <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','export')?></a> </span>
                                      
                                </div>
                                <div class="col-md-7 col-lg-7">

                                      <input type="submit" class="btn btn-success col-md-4 col-lg-3 div_float" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                                </div>
                           
                        </div>
                      
                    </div>
                    <div class="col-lg-5">
                     
                      <div class="badge outline-badge-primary col-12 text-center pull-left" style="padding: 10px"><?=Raise::t('wallet','total_amt')?>: <?=number_format($sum,$wallet_decimal_limits['btc'])?></div>
                    </div>

                  
                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                               <thead>
                                  <tr role="row">
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="User Name"><?=Raise::t('wallet','username')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Credit Type"><?=Raise::t('wallet','credit_type')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Transaction Type"><?=Raise::t('wallet','txn_type')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Amount"><?=Raise::t('wallet','amount')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Before Balance"><?=Raise::t('wallet','before_bal')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="After Balance"><?=Raise::t('wallet','after_bal')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Transaction Date"><?=Raise::t('wallet','txn_date')?></th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1" aria-label="Remarks"><?=Raise::t('wallet','remarks')?></th></tr>
                                </thead>
                                <tbody>
                                <?php 
                                  if(!empty($data['data'])){
                                   foreach($data['data'] as $key => $val):?>
                                    <tr role="row" class="odd">
                                      <td><?=$val['username']?></td>
                                      <td><?=$val['credit_type']?></td>
                                      <td><?=$val['txn_type']?></td>
                                      <td><?=number_format($val['value'],$wallet_decimal_limits['btc'])?></td>
                                      <td><?=number_format($val['before_bal'],$wallet_decimal_limits['btc'])?></td>
                                      <td><?=number_format($val['after_bal'],$wallet_decimal_limits['btc'])?></td>
                                      <td><?=$val['txn_date']?></td>
                                      <td><?=$val['remarks']?></td>
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
                            <form class="col-md-12" id="btc_pagination" method="post" action="<?=BASEURL;?>wallet/Btc">
                                <div class="dataTables_paginate paging_simple_numbers">
                                    <ul class="pagination">
                                      <?=$pagination;?>
                                    </ul>
                                </div>
                                <input name="sub" id="subs" value="" style="display: none;">
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
  $('#walletHisMenu').attr("data-active","true");
  $('#wallethistoryNav').addClass('show');
  $('#btcWal').addClass('active');

  $('#datefrom').val("<?=$datefrom;?>");
  $('#dateto').val("<?=$dateto;?>");
  $('#username').val("<?=$username;?>");
  $('#creditType').val("<?=$creditType;?>");
  $('#txn_type').val("<?=$txn_type;?>");

});

function pageHistory(datefrom,dateto,username,trans,credit,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('.pagination').append('<input name="txn_type" value="'+trans+'" style="display:none;">');
    $('.pagination').append('<input name="creditType" value="'+credit+'" style="display:none;">');
    $('#btc_pagination').submit();
}

function exportReport(){
        var txn_type    = '<?=$txn_type;?>';
        var creditType   = '<?=$creditType;?>';
        var datefrom = '<?=$datefrom;?>';
        var dateto   = '<?=$dateto;?>';
        var username = '<?=$username;?>';
        
        loadingoverlay("info","Loading...","Please Wait..");
        $.post('<?=BASEURL?>export/Btc',{'txn_type':txn_type,'creditType':creditType,'datefrom':datefrom,'dateto':dateto,'username':username,'coin_code':'btc'},
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
        $('#btc_form').submit();
    })
  });


</script>
