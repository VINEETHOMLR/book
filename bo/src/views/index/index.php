<?php
/**
 * View File for IndexController
 * @author 
 */

use inc\Raise;

$this->mainTitle = Raise::t('app','dashboard'); 
$this->subTitle  = ''; 
global $wallet_decimal_limits;

?>
<link href="<?=WEB_PATH?>assets/css/dashboard/dash_1.css" rel="stylesheet" type="text/css">
<link href="<?=WEB_PATH?>assets/css/dashboard/dash_2.css" rel="stylesheet" type="text/css">
<link href="<?=WEB_PATH?>assets/css/components/cards/card.css" rel="stylesheet" type="text/css" />
<style>
    .widget{
        padding: 20px;
    }
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">

                <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-one" style="min-height: 150px">
                            <div class="widget-heading">
                                <h6 class="" style="margin-bottom: 25px">Total Withdrawal</h6>
                            </div>
                    
                            <div class="w-chart row" style="min-height: 85px">
                                <div class="col-md-4 col-12 form-group">
                                    <div class="w-chart-section" style="width: 100%;word-wrap: break-word;">
                                        <p class="w-title">BTC</p>
                                        <p class="w-stats"><?=number_format($data['totalWithdraw']['btc'],$wallet_decimal_limits['btc']);?></p>  
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 form-group">
                                    <div class="w-chart-section" style="width: 100%;word-wrap: break-word;">
                                        <p class="w-title">USDT </p>
                                        <p class="w-stats"><?=number_format($data['totalWithdraw']['usdt'],$wallet_decimal_limits['usdt']);?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="w-chart-section" style="width: 100%;word-wrap: break-word;">
                                        <p class="w-title">ETH </p>
                                        <p class="w-stats"><?=number_format($data['totalWithdraw']['eth'],$wallet_decimal_limits['btc']);?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                        <div class="widget widget-one" style="min-height: 150px">
                            <div class="widget-heading">
                                <h6 class="" style="margin-bottom: 25px">Total Deposit</h6>
                            </div>
                            <div class="w-chart row" style="min-height: 85px">
                                <div class="col-md-4 col-12 form-group">
                                    <div class="w-chart-section" style="width: 100%;word-wrap: break-word;">
                                        <p class="w-title">BTC</p>
                                        <p class="w-stats"><?=number_format($data['totalDeposit']['btc'],$wallet_decimal_limits['btc']);?></p>  
                                    </div>
                                </div>
                                <div class="col-md-4 col-12 form-group">
                                    <div class="w-chart-section" style="width: 100%;word-wrap: break-word;">
                                        <p class="w-title">USDT </p>
                                        <p class="w-stats"><?=number_format($data['totalDeposit']['usdt'],$wallet_decimal_limits['usdt']);?></p>
                                    </div>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="w-chart-section" style="width: 100%;word-wrap: break-word;">
                                        <p class="w-title">ETH </p>
                                        <p class="w-stats"><?=number_format($data['totalDeposit']['eth'],$wallet_decimal_limits['btc']);?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 widget-heading">
                        <h5>Master Wallet Balance <i class="fa fa-refresh" style="color:blue;cursor:pointer;" onclick="masterWallet(1)" title="Refresh"></i></h5>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value inv-balance btc-bal">0.00000000</h6>
                                        <p class="">BTC</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class="value inv-balance eth-bal">0.00000000</h6>
                                        <p class="">ETH</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class=""><?=$data['btc_address']?></h6>
                                        <p class="">BTC Master Address</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-12 layout-spacing">
                        <div class="widget widget-card-four">
                            <div class="widget-content">
                                <div class="w-content">
                                    <div class="w-info">
                                        <h6 class=""><?=$data['eth_address']?></h6>
                                        <p class="">ETH Master Address</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one">
                        <div class="widget-heading">
                            <h5 class="">Users</h5>
                        </div>

                        <div class="widget-content">
                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="avatar avatar-xl">
                                                <span class="avatar-title rounded-circle">TU</span>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h4>Total Users</h4>
                                        </div>
                                    </div>
                                    <div class="t-rate rate-inc">
                                        <p><span><?=number_format($users['total'])?></span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg></p>
                                    </div>
                                </div>
                            </div>

                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                        <div class="t-icon">
                                            <div class="avatar avatar-xl">
                                                <span class="avatar-title rounded-circle">AU</span>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h4>Active Users</h4>
                                        </div>

                                    </div>
                                    <div class="t-rate rate-inc">
                                        <p><span><?=number_format($users['active'])?></span> </span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg></p>
                                    </div>
                                </div>
                            </div>

                            <div class="transactions-list">
                                <div class="t-item">
                                    <div class="t-company-name">
                                       <div class="t-icon">
                                            <div class="avatar avatar-xl">
                                                <span class="avatar-title rounded-circle">BU</span>
                                            </div>
                                        </div>
                                        <div class="t-name">
                                            <h4>Inactive Users</h4>
                                        </div>

                                    </div>
                                    <div class="t-rate rate-dec">
                                        <p><span><?=number_format($users['inactive'])?></span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

               <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 layout-spacing">
                    
                    <div class="widget widget-table-two">

                        <div class="widget-heading">
                            <h5 class="">Wallet Balance</h5>
                        </div>

                        <div class="widget-content">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                            <tr role="row" class="odd">
                                                <td><div class="td-content customer-name">BTC Wallet</div></td>
                                                <td><div class="td-content pricing"><?=number_format($data['walletBalance']['btc_wallet'],$wallet_decimal_limits['btc']);?></div></td>
                                            </tr>
                                            <tr role="row" class="even">
                                                <td><div class="td-content customer-name">USDT Wallet</div></td>
                                                <td><div class="td-content pricing"><?=number_format($data['walletBalance']['usdt_wallet'],$wallet_decimal_limits['usdt']);?></div></td>
                                            </tr>
                                           <tr role="row" class="odd">
                                                <td><div class="td-content customer-name">ETH Wallet</div></td>
                                                <td><div class="td-content pricing"><?=number_format($data['walletBalance']['eth_wallet'],$wallet_decimal_limits['usdt']);?></div></td>
                                            </tr>
                                            
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        <!-- </div> -->
    </div>
</div>        
       
<script>
    $('#dashboard').attr("data-active","true");
    
    masterWallet(0);

    function masterWallet(type){
        if(type==1){ //refresh
           loadingoverlay('info',"Please wait","loading...");
        }
        $.post('<?=BASEURL?>index/masterWallet',{},function(response){
            hideoverlay();
            newResp = JSON.parse(response);
            resp = newResp['response'];
            $('.eth-bal').html(resp['eth']);
            $('.btc-bal').html(resp['btc']);
        });
        return false;
    }

</script>