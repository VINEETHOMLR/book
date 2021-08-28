<?php

use inc\Raise;
$this->mainTitle = 'Report';
$this->subTitle  = 'Consolidate Report';
global $wallet_decimal_limits;
$limits = $wallet_decimal_limits;
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
                        <div class="row col-md-12 col-xs-12">
                            <form id="consolidate_form" class="row col-md-12" method="post" action="<?=BASEURL;?>Report/Consolidate/">
                                  <div class="form-group col-sm-3 input-group">
                                        <input type="text" class="form-control" id="username" name="username" value="" placeholder="Username">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="$('#username').val('');">x</span>
                                        </div>
                                  </div>
                                  <div class="form-group col-12 col-md-4 col-xl-3">
                                    <input type="submit" class="btn btn-success col-12 div_float" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                                  </div>

                            </form>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                           
                             
                           
                        </div>
                        <div class="row col-md-12 col-lg-12">
                          <div class="col-md-12 col-lg-12">
                            <div class="badge outline-badge-primary col-md-5 col-xl-3 mr-3 mb-1 col-12 p-3">BTC Wallet : <?=number_format($data['total']['btc_wallet'],$limits['btc'])?></div>
                            <div class="badge outline-badge-primary col-md-5 col-xl-3 mr-3 mb-1 col-12 p-3">USDT Wallet : <?=number_format($data['total']['usdt_wallet'],$limits['usdt'])?></div>
                            <div class="badge outline-badge-primary col-md-5 col-xl-3 mr-3 mb-1 col-12 p-3">ETH Wallet : <?=number_format($data['total']['eth_wallet'],$limits['usd'])?></div>

                             <span class="badge badge-success datebuttons ml-5" onClick="exportReport()"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('wallet','export')?></a> </span>
                            
                          </div>                                
                        </div>
                      
                    </div>

                  
                    <div class="table-responsive mb-4 mt-4" style="overflow-x: scroll !important;">
                        <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                          <div class="row">
                            <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row"><th class="sorting_disabled" rowspan="1" colspan="1">Username</th>
                                  
                                  <th class="sorting_disabled" rowspan="1" colspan="1">BTC Wallet</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">USDT Wallet</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">ETH Wallet</th>
                                  
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php foreach($data['data'] as $key => $val):?>
                                    <tr role="row" class="odd">
                                      <td><?=$val['username']?></td>
                                      
                                      <td><?=number_format($val['btc_wallet'],$limits['btc'])?></td>
                                      <td><?=number_format($val['usdt_wallet'],$limits['usdt'])?></td>
                                      <td><?=number_format($val['eth_wallet'],$limits['usd'])?></td>
                                      
                                    </tr>
                                  <?php endforeach;?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="consolidate_pagination" method="post" action="<?=BASEURL;?>Report/Consolidate">
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
  $('#consolidate').addClass('active');
  $('#username').val("<?=$username;?>");

  
 });

  
function pageHistory(username,page){
    $('.pagination').append('<input name="username" value="'+username+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#consolidate_pagination').submit();
}

function exportReport(){
  var username = '<?=$username;?>';
  
  loadingoverlay("info","Loading...","Please Wait..");
  $.post('<?=BASEURL?>export/Consolidate',{'username':username},
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
  $('#search').click(function(){
      $('#consolidate_form').submit();
  })
});


</script>
