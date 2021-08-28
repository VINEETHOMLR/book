<?php

 use inc\Raise;

 $this->mainTitle = 'User Management';
 $this->subTitle  = 'Account'; 

 
 $lastloginTime =!empty($user['last_login_time'])? date("d-m-Y H:i:s",$user['last_login_time']):"-" ;

 $servicesArray = $_SESSION['INF_privilages'];
 $servicesArray = explode(",", $servicesArray[0]);
 $servicesArray = array_filter($servicesArray);

 $role = $_SESSION['INF_role'];

?>

<link href="<?=WEB_PATH?>assets/css/components/tabs-accordian/custom-tabs.css" rel="stylesheet" type="text/css" />

<style type="text/css">
    .wallet-type{
        padding: 10px;
    }

    .breadcrumb-two .breadcrumb li a::before {
       content: none;
    }
    .breadcrumb-two{
        top: 0;
        position: absolute;
        left: 0;
    }
    .vertical-line-pill .nav-pills {
        width: 100%;
    }
    .dot{
        background: rgb(25, 185, 211);
        color: rgb(231, 81, 90);
        height: 12px;
        width: 12px;
        left: 0px;
        top: 0px;
        border-width: 0px;
        border-color: rgb(255, 255, 255);
        border-radius: 12px;
        float: left;
        margin: 5px;
    }
    .pwdDiv{
        margin-bottom: 30px;
        margin-left: 1px;
    }
    @media only screen and (min-width: 800px) {
      .padding30{
        padding-left: 30px;
       }
    }
    @media only screen and (min-width: 1000px) {
        .pwdDiv{
          margin: 0px 70px;
       }
    }
    .swal2-loading{
        background: none !important;
    }
    #depositTable_length,#depositTable_filter{
       display: none;
    }
    #withdrawTable_length,#withdrawTable_filter{
       display: none;
    }
    .table > thead > tr > th {
      text-align: center;
    }
    .thumbnail{
        width: 250px;
        height: 200px;
        float: left;
      }

  .btn-upload {
    background-color: #eee;
    border-left: none;
    height: 44px;
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
          <div class="row layout-top-spacing">
            <div class="user-profile layout-spacing col-12">
                <div class="widget-content widget-content-area">
                     <div class="d-flex">
                          <div class="dot"></div> <h4><b> <?=ucwords($user['data']['username'])?></b></h4>
                      </div>
                      <div class="user-info-list padding30">

                          <div class="col-sm-12 col-md-12 row">
                              
                              <div class="col-md-3 col-sm-12">
                                <label class="marginLeft"><?= Raise::t('subadmin','name_text'); ?></label> : <label><?=ucwords($user['data']['fullname']);?></label>
                              </div>
                              <div class="col-md-3 col-sm-12">
                                <label><?= Raise::t('user','user_status'); ?></label> : <label><?=($user['data']['status']==1) ? 'Login' : 'Blocked';?></label>
                              </div>
                              
                              <div class="col-md-3 col-sm-12">
                               
                                <label><?= Raise::t('subadmin','lastseen_text'); ?></label> : <label><?=$lastloginTime;?></label>
                              </div>

                          </div>                                    
                      </div>
                </div>
          </div>
                <div class="col-lg-12 col-12 layout-spacing">
                    <div class="widget-content widget-content-area vertical-line-pill">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="<?=BASEURL;?>Users/Index/">User
                               list</a></li>
                              <li class="breadcrumb-item active"><a href="javascript:void(0);">Account Details</a></li>
                          </ol>
                        </nav>
                        
                        <br><br>
                        
                         <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                
                            </div>
                        </div>
                        <div class="row mb-4 mt-3">

                            <div class="col-sm-2 col-12">
                                <div class="nav flex-column nav-pills mb-sm-0 mb-3 text-center mx-auto" id="v-line-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active mb-3" id="v-line-pills-home-tab" data-toggle="pill" href="#v-line-pills-home" role="tab" aria-controls="v-line-pills-home" aria-selected="true">Profile</a>
                                   
                                    <a class="nav-link mb-3  text-center" id="v-line-pills-profile-tab" data-toggle="pill" href="#v-line-pills-profile" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">Update Password</a>
                                 
                                    <a class="nav-link mb-3  text-center" data-toggle="pill" href="#HisTab" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">View History</a> 
									
									<a class="nav-link mb-3  text-center" data-toggle="pill" href="#WalletTab" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">Wallet Details</a>
                                  <a class="nav-link mb-3  text-center" data-toggle="pill" href="#walletmagmnt" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">Wallet Management</a>
                                    
                                </div>
                            </div>

                            <div class="col-sm-9 col-12">
                                <div class="tab-content" id="v-line-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-line-pills-home" role="tabpanel" aria-labelledby="v-line-pills-home-tab">

                                      <div class="widget-content widget-content-area">
                                        <div class="row col-12"><h4>Upload Profile Image</h4></div>
                                        <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <div class="input-group">
                                                    <input type="text" class="form-control" readonly placeholder="Select profile image to upload">
                                                    <label class="input-group-btn">
                                                      <span class="btn btn-upload">
                                                        <i class="fa fa-upload"></i> <input type="file" id="pic" style="display: none;">
                                                      </span>
                                                    </label>

                                                  </div>
                                                </div>
                                                 <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-6">
                                                      <div class="form-group" id="gallery" style="padding-left: 10px;">
                                                        <?php if($user['info']['profile_pic']){?>
                                                            <img src="<?=FrontEnd;?>web/upload/profile/<?=$user['info']['profile_pic']?>" class="img-responsive editImg1 thumbnail" alt="your image">
                                                         <?php } else{ ?>
															<label>Please upload profile image</label>
                                                            <!-- <img src="" class="img-responsive editImg1 thumbnail" alt="your image">-->
                                                         <?php } ?>
                                                      </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                      </div>

                                      <br>

                                      <div class="widget-content widget-content-area">
                                         
                                        <form id="formValidation" class="form-horizontal" role="form">
                                            <div class="row col-12"><h4>Update Profile</h4></div>
                                            <div class="row col-md-12">
                                                <input type="hidden" name="editId" value="<?=$user['data']['id']?>">
                                                <div class="form-group col-md-6">
                                                    <div class="col-md-12">
                                                        <label><?=Raise::t('app','full_name');?></label>
                                                        <input type="text"  name="fullName" id="fullName" value="<?=ucwords($user['data']['fullname']);?>" class="form-control" placeholder="<?=Raise::t('app','full_name');?>">       
                                                              
                                                    </div>
                                                </div> 
                                                <div class="form-group col-md-6">
                                                    <div class="col-md-12">
                                                        <label><?=Raise::t('app','username');?></label>
                                                        <input type="text" name="userName"  class="form-control" id="userName" value="<?=ucwords($user['data']['username']);?>" placeholder="<?=Raise::t('app','username');?>">
                                                    </div>
                                                </div>
                                            </div>
                                   
                                            <div class="col-md-12  text-center">
                                                <button class="btn btn-primary col-md-4 col-12 proceedToUpdate" type="button">Update Details</button>
                                            </div>

                                        </form>

                                      </div>
                                      <br>

                                      <div class="widget-content widget-content-area">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>Enable/Disable For Deposit</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <?php $isDeposit = $user['info']['is_deposit_allowed'];?>
                                                <div class="form-group col-md-12">
                                                    <?php if($isDeposit == 1){ ?>
                                                        <label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchDeposit('<?=$userid?>','<?=$isDeposit?>');"></span></label>
                                                    <?php } else { ?>
                                                        <label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchDeposit('<?=$userid?>','<?=$isDeposit?>');"></span></label>
                                                    <?php } ?>
                                                        
                                                </div>
                                            </div>
                                        </div>
                                      </div>

                                      <br>

                                      <div class="widget-content widget-content-area">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>Enable/Disable For Withdrawal</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <?php $isWithdraw = $user['info']['is_withdrawal_allowed'];?>
                                                <div class="form-group col-md-12">
                                                    <?php if($isWithdraw == 1){ ?>
                                                        <label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchWithdraw('<?=$userid?>','<?=$isWithdraw?>');"></span></label>
                                                       <?php } else { ?>
                                                        <label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchWithdraw('<?=$userid?>','<?=$isWithdraw?>');"></span></label>
                                                    <?php } ?>
                                                        
                                                </div>
                                            </div>
                                        </div>
                                      </div>

                                     <br>

                                      <div class="widget-content widget-content-area">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>Enable/Disable For Swap</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <?php $isSwap = $user['info']['is_swap_allowed'];?>
                                                <div class="form-group col-md-12">
                                                    <?php if($isSwap == 1){ ?>
                                                        <label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchSwap('<?=$userid?>','<?=$isSwap?>');"></span></label>
                                                     <?php } else { ?>
                                                        <label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchSwap('<?=$userid?>','<?=$isSwap?>');"></span></label>
                                                        <?php } ?>
                                                        
                                                </div>
                                            </div>
                                        </div>
                                      </div>

                                      <br>

                                      <div class="widget-content widget-content-area">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h4>Enable/Disable For Financial</h4>
                                            </div>
                                            <div class="col-md-6">
                                                <?php $isFinc = $user['info']['is_financial_allowed'];?>
                                                <div class="form-group col-md-12">
                                                     <?php if($isFinc == 1){ ?>
                                                        <label class="switch s-primary mb-0"><input type="checkbox" checked=""><span class="slider round" onclick="switchFinancial('<?=$userid?>','<?=$isFinc?>');"></span></label>
                                                        <?php } else { ?>
                                                             <label class="switch s-primary mb-0"><input type="checkbox"><span class="slider round" onclick="switchFinancial('<?=$userid?>','<?=$isFinc?>');"></span></label>
                                                        <?php } ?>
                                                        
                                                </div>
                                            </div>
                                        </div>
                                      </div>

                                   </div>


                                    <div class="tab-pane fade" id="v-line-pills-profile" role="tabpanel" aria-labelledby="v-line-pills-profile-tab">

                                        <div class='panel panel-default row'>
                                          <form method="post" id="pwdForm">
                                            <div class='panel-body statbox widget box box-shadow row col-sm-12 col-md-12 col-xl-12 pwdDiv'>
                                               
                                                <div class="col-md-12 col-xl-6">
                                                    <div class="widget-header">
                                                        <div class="row" style="margin-bottom: 20px;">
                                                            <div class="col-xl-12 col-md-12 col-sm-12 col-12 text-center">
                                                                <h4>Primary Password</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='row col-md-12'>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px"><?=Raise::t('subadmin','new_pass');?> <span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-12 col-md-12 col-sm-12 col-12" id="show_hide_password">
                                                                        <input type="password" name="newpass"  class="form-control input-sm" id="newpass" placeholder="<?=Raise::t('subadmin','new_pass');?>">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                            </div>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px"><?=Raise::t('subadmin','con_pass');?> <span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-12 col-md-12 col-sm-12 col-12" id="show_hide_password1">
                                                                        <input type="password" name="confpass"  class="form-control input-sm " id="confpass" placeholder="<?=Raise::t('subadmin','con_pass');?>">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                        
                                                            </div>
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-12 col-xl-6">
                                                
                                                    <div class="widget-header">
                                                        <div class="row" style="margin-bottom: 20px;">
                                                            <div class="col-xl-12 col-md-12 col-sm-12 col-12 text-center">
                                                                <h4>Transaction Password</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='row col-md-12'>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px"><?=Raise::t('subadmin','new_pass');?> <span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-12 col-md-12 col-sm-12 col-12" id="show_hide_password3">
                                                                        <input type="password" name="newtrans"  class="form-control input-sm" id="newtrans" placeholder="<?=Raise::t('subadmin','new_pass');?>">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                            </div>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px"><?=Raise::t('subadmin','con_pass');?> <span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-12 col-md-12 col-sm-12 col-12" id="show_hide_password4">
                                                                        <input type="password" name="contrans"  class="form-control input-sm " id="contrans" placeholder="<?=Raise::t('subadmin','con_pass');?>">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>    
                                                            </div>
                                                    </div>
                                                </div>
                                            
                                                    <input type="hidden" name="editId" value="<?=$user['data']['id']?>">       
                                                
                                                    <div class="col-md-12 text-center">
                                                            <button class="btn btn-primary btn-sm  edit-text-shw  updatePass" id="updatePass" type="button"><span class="fa fa-save"></span>&nbsp;<?=Raise::t('subadmin','update_pass')?></button>
                                                    </div>
                                                

                                               </div>
                                             </form>
                                    
                                         </div>
                                    </div>

                                     <div class="tab-pane fade" id="HisTab" role="tabpanel" aria-labelledby="HisTab">
                                        <div class="row col-12">
                                            <div class="widget-content widget-content-area animated-underline-content col-12">
                                    
                                                <div class="row">
                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                         <h4>History</h4>
                                                    </div>
                                                </div>
                                                <div class="row col-12">
                                                    <div class="col-4"><label>Total Withdrawal</label> : <?=number_format($total['withdraw'],2)?></div>
                                                    <div class="col-4"><label>Total Deposit</label> : <?=number_format($total['deposit'],2)?></div>
                                                </div>
                                    
                                                <ul class="nav nav-tabs  mb-3" id="animateLine" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active" data-toggle="tab" href="#depositTab" role="tab" aria-controls="" aria-selected="true"><b>Deposit</b></a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link" data-toggle="tab" href="#withdrawTab" role="tab" aria-controls="" aria-selected="false"><b>Withdraw</b></a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content" id="animateLineContent-4">
                                                     <div class="tab-pane fade show active" id="depositTab" role="tabpanel" aria-labelledby="depositTab">
                                                           
                                                            <div class="table-responsive">
                                                                <table class="multi-table table table-hover" id="depositTable" style="width:100%">
                    
                                                                   <tbody class="text-center"></tbody>
                                                                </table>
                                                            </div>
                                                     </div>
                                                     <div class="tab-pane fade" id="withdrawTab" role="tabpanel" aria-labelledby="withdrawTab">
                                                            <div class="table-responsive">
                                                                <table class="multi-table table table-hover" id="withdrawTable" style="width:100%">
                    
                                                                   <tbody class="text-center"></tbody>
                                                                </table>
                                                            </div>
                                                     </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
<!----------------------------------------   Wallet history ---------------------------------------->
									<div class="tab-pane fade" id="WalletTab" role="tabpanel" aria-labelledby="WalletTab">
                                        <div class="row col-12">
                                            <div class="widget-content widget-content-area animated-underline-content col-12">
                                    
                                                <div class="row">
                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                         <h4>Wallet Details</h4>
                                                    </div>
                                                </div>
                                                

                                                <div class="tab-content" id="">
                                                     <div class="tab-pane fade show active" id="coinWalletTab" role="tabpanel" aria-labelledby="coinWalletTab">
                                                           
                                                            <div class="table-responsive">
                                                                <table class="multi-table table table-hover" id="coinWalletTable" style="width:100%">
                    
                                                                   <tbody class="text-center"></tbody>
                                                                </table>
                                                            </div>
                                                     </div>
                                                    
                                                </div>
                                            </div>
                                        </div>

                                    </div>									
<!---------------------------------   End Wallet History Tab ---------------------------------------->		

                                  <div class="tab-pane fade" id="walletmagmnt" role="tabpanel" aria-labelledby="walletTab">
                                          
                                          <div class="widget-content widget-content-area">
                                                <div class="row" style="margin-bottom: 20px;">
                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12 ">
                                                        <h4><b>Wallet Management</b></h4>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 col-xl-6">
                                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                           <h5><b>Wallet Info <i class="fa fa-refresh" onclick="refreshDiv()" aria-hidden="true" style="font-size: 20px;color:blue;margin-left: 20px;cursor: pointer;"></i></b></h5> 

                                                       </div>
                                                        <div id="appendData">
                                                        <?php
                                                            foreach ($walletArr as $key => $value) {
                                                               echo '<div class="row col-12">
                                                                        <div class="col-5" style="float:left;"><label>'.$key.'</label></div>
                                                                        <div class="" style="float:left;">-</div>
                                                                        <div class="col-5" style="float:left;"><label>'.$value.'</label></div>
                                                                     </div>';
                                                            }
                                                        ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 col-xl-6">
                                                        <h5><b>Credit/Debit Amount</b></h5>
                                                        <form method="post" id="walletForm"> 
                                                        <div class="form-group">
                                                           <label>Select Wallet</label><br>
                                                           <select class="form-control custom-select" name="walletType" id="walletType">
                                                                <?php
                                                                    foreach ($walletArr as $key => $value) {
                                                                       echo '<option value="'.$this->walletArray[$key].'">'.$key.'</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Credit/Debit</label><br>
                                                            <select class="form-control custom-select" name="creditType" id="creditType">
                                                               <option value="0">Credit</option>
                                                               <option value="1">Debit</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <input type="text" class="form-control" id="amount" name="amount" value="" placeholder="Enter Amount">
                                                            <input type="hidden" name="user" id="user" value="<?=$user['data']['id']?>">
                                                        </div>
                                                        <div class="form-group">
                                                             <label>Remarks </label><br>
                                                            <input type="text" class="form-control" id="remarks" name="remarks" value="" placeholder="Enter Remarks">
                                                            
                                                        </div>
                                                        <div class="col-md-12  text-center">
                                                            <button class="btn btn-primary col-6" id="subtransfer" onclick="transferAmt()" type="button">Submit</button>
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
            </div>
    </div>
</div>


<?php

$error_dash = Raise::t('app','error_dash');
$success    = Raise::t('app','suucess_txt');
$okay       = Raise::t('app','okay_btn'); 

?>

<script>

$( function() {
      $('#accordionExample').find('li a').attr("data-active","false");
      $('#userMenu').attr("data-active","true");
      $('#userNav').addClass('show');
      $('#userList').addClass('active');

  
});

$(function () {


      $(document).on('change', ':file', function() { 
        var input = $(this),
        numFiles = input.get(0).files ? input.get(0).files.length : 1,
        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
        input.trigger('fileselect', [numFiles, label]);
      });
  });

  $(document).ready( function() { 
      $(':file').on('fileselect', function(event, numFiles, label) {

        var input = $(this).parents('.input-group').find(':text'),
        log = numFiles > 1 ? numFiles + ' files selected' : label;

        if( input.length ) {
          input.val(log);
        } else {
          if( log ) {
            //alert(log);
          }
        }

      });


    });

  var imagesPreview = function(input, placeToInsertImagePreview,removeClass) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                

                reader.onload = function(event) {
                  //$('#galleryNew').attr('src', e.target.result);
                    $($.parseHTML('<img class="thumbnail '+removeClass+'">')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    }; 

    $('#pic').on('change', function() {
      $('.editImg1').remove();
        imagesPreview(this, 'div#gallery' ,'editImg1');
    });
   
    
    $('.proceedToUpdate').click(function(){
       
        postdata = $('#formValidation').serializeArray();

        $.post('<?=BASEURL;?>Users/UserEdit/',postdata,function(response){
            newResp = JSON.parse(response);
            if(newResp['status']=='success')
            {
                openSuccess(newResp['response'])
            }
            else
            {
               loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
            }
             return false;
        });
         return false;
    });

    $('.proceedToDelete').click(function(){

        var user = $('#editId').val();

        swal({
            title: '<?=Raise::t('user','conf_txt');?> ',
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn btn-rounded',
            cancelButtonClass: 'btn btn-rounded mr-3',
            confirmButtonText: '<?=Raise::t('user','delete_txt');?>'
        }).then((result) => {
            if (result.value) {
                $.post('<?=BASEURL;?>Users/DeleteUSer',{'uid':user},function(response){
                     
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        
                        openSuccess(newResp['response'],'<?=BASEURL;?>Users/Index/')
                    }else{
                        loadingoverlay("error","Error","Failed to Change");
                    }
                });
            }
        })

       return false;
    });

    $('#updatePass').click(function(){

        postdata = $('#pwdForm').serializeArray();
 
        $.post('<?= BASEURL ?>Users/Resetpass/',postdata,function(response){ 
    
          newResp = JSON.parse(response);
            
            if(newResp['status'] == 'success'){
                openSuccess(newResp['response'])
            }else{ 
                loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
            }
        });
        return false;
    });

 

   

function checkNumber(event){ 
    var e=$(event.target);
    $(e).val($(e).val().replace(/\D/, ''));
}



function refreshDiv(){

    swal.showLoading()
    //location.reload();
    id=$('#user').val();
    $.post('<?=BASEURL;?>Users/RefreshWallet/',{id:id},function(response){ 
         $('#appendData').html(response);
         swal.close();
     });

}

function transferAmt(){ 

    //$('#subtransfer').prop('disabled', true);
    postdata = $('#walletForm').serializeArray();

    $.post('<?=BASEURL;?>Users/TransferAmt/',postdata,function(response){ 

            newResp = JSON.parse(response);
            if(newResp['status']=='success')
            {
              swal({
                    title: "<?= $success;?>",
                    text: newResp['response'],
                    type: "success",
                    showCancelButton: false,
                    confirmButtonClass: 'btn-success waves-effect waves-light',
                    confirmButtonText: '<?=$okay;?>',
                    closeOnConfirm: false,
                    }).then(function(isConfirm) {
                        refreshDiv();
                        $('#amount').val('');
                        $('#remarks').val('');
                        $('#walletForm').find('select').prop("selectedIndex", 0);
                    });
            }
            else
            { 
               loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
            }
             $('#subtransfer').prop('disabled', false);
        return false;
    });
  return false;
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
    swal.showLoading()
    $.post('<?=BASEURL;?>Users/'+url,{'uid':id},function(response){

      location.reload();

    });
}

$(document).ready(function() {
    var id = "<?=$user['data']['id']?>";
    $('#depositTable').DataTable({
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_"
        },
        "stripeClasses": [],
        "ajax": {
          url: "<?= BASEURL . 'Users/DepositData' ?>",
          type: "post",
          data: {'id':id}
        },
        order: [[ 0, "desc" ]],
        columns: [
          {data: 'id', title: 'ID', orderable: false, visible: false},
          {title: 'Transaction date', data: 'time',orderable: false},
          {title: 'Receiver address', data: 'to_address',orderable: false},
          {title: 'Amount', data: 'amount', orderable: false},
          {title: 'Status', data: 'status', orderable: false},
        ],
        "pageLength": 10
    });
} );

$(document).ready(function() {
    var id = "<?=$user['data']['id']?>";
    $('#withdrawTable').DataTable({
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_"
        },
        "stripeClasses": [],
        "ajax": {
          url: "<?= BASEURL . 'Users/WithdrawData' ?>",
          type: "post",
          data: {'id':id}
        },
        order: [[ 0, "desc" ]],
        columns: [
          {data: 'id', title: 'ID', orderable: false, visible: false},
          {title: 'Apply date', data: 'time',orderable: false},
          {title: 'Amount', data: 'amount',orderable: false},
          {title: 'Receivable amount', data: 'receive', orderable: false},
          {title: 'Service charge', data: 'service_charge', orderable: false},
          {title: 'Remarks', data: 'remarks', orderable: false},
          {title: 'Approved by', data: 'approve', orderable: false},
          {title: 'Status', data: 'status', orderable: false},
        ],
        "pageLength": 10
    });
} );

$(document).ready(function() {
    var id = "<?=$user['data']['id']?>";
    $('#coinWalletTable').DataTable({
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_"
        },
        "stripeClasses": [],
        "ajax": {
          url: "<?= BASEURL . 'Users/CoinWalletbalance' ?>",
          type: "post",
          data: {'id':id}
        },
        order: [[ 0, "desc" ]],
        columns: [
          {data: 'id', title: 'ID', orderable: false, visible: false},
          {title: 'Coin Name', data: 'coin_name',orderable: false},
          {title: 'Coin Code', data: 'coin_code',orderable: false},
          {title: 'Value', data: 'value', orderable: false},
          {title: 'Wallet Balance', data: 'balance', orderable: false},
        ],
        "pageLength": 10
    });
} );

$(document).ready(function() {
      $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
          $('#show_hide_password input').attr('type', 'password');
          $('#show_hide_password i').addClass( "fa-eye-slash" );
          $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
          $('#show_hide_password input').attr('type', 'text');
          $('#show_hide_password i').removeClass( "fa-eye-slash" );
          $('#show_hide_password i').addClass( "fa-eye" );
        }
      });
      $("#show_hide_password1 a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password1 input').attr("type") == "text"){
          $('#show_hide_password1 input').attr('type', 'password');
          $('#show_hide_password1 i').addClass( "fa-eye-slash" );
          $('#show_hide_password1 i').removeClass( "fa-eye" );
        }else if($('#show_hide_password1 input').attr("type") == "password"){
          $('#show_hide_password1 input').attr('type', 'text');
          $('#show_hide_password1 i').removeClass( "fa-eye-slash" );
          $('#show_hide_password1 i').addClass( "fa-eye" );
        }
      });

      $("#show_hide_password3 a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password3 input').attr("type") == "text"){
          $('#show_hide_password3 input').attr('type', 'password');
          $('#show_hide_password3 i').addClass( "fa-eye-slash" );
          $('#show_hide_password3 i').removeClass( "fa-eye" );
        }else if($('#show_hide_password3 input').attr("type") == "password"){
          $('#show_hide_password3 input').attr('type', 'text');
          $('#show_hide_password3 i').removeClass( "fa-eye-slash" );
          $('#show_hide_password3 i').addClass( "fa-eye" );
        }
      });
      $("#show_hide_password4 a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password4 input').attr("type") == "text"){
          $('#show_hide_password4 input').attr('type', 'password');
          $('#show_hide_password4 i').addClass( "fa-eye-slash" );
          $('#show_hide_password4 i').removeClass( "fa-eye" );
        }else if($('#show_hide_password4 input').attr("type") == "password"){
          $('#show_hide_password4 input').attr('type', 'text');
          $('#show_hide_password4 i').removeClass( "fa-eye-slash" );
          $('#show_hide_password4 i').addClass( "fa-eye" );
        }
      });
})

function switchDeposit(userid,status){

    if(status == 1){
        changedTo = 0;
    }else{
        changedTo = 1;
    }

    $.post('<?=BASEURL;?>Users/IsAllowDeposit',{'uid':userid,'status':changedTo},function(response){

          newResp = JSON.parse(response);

          if(newResp['status'] == 'success'){
                openSuccess(newResp['response'])
            }else{ 
                loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
            }

    });

}

function switchWithdraw(userid,status){

    if(status == 1){
        changedTo = 0;
    }else{
        changedTo = 1;
    }

    $.post('<?=BASEURL;?>Users/IsAllowWithdraw',{'uid':userid,'status':changedTo},function(response){

      newResp = JSON.parse(response);
            
        if(newResp['status'] == 'success'){
            openSuccess(newResp['response'])
        }else{ 
            loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
        }

    });

}

function switchSwap(userid,status){

    if(status == 1){
        changedTo = 0;
    }else{
        changedTo = 1;
    }

    $.post('<?=BASEURL;?>Users/IsAllowSwap',{'uid':userid,'status':changedTo},function(response){

      newResp = JSON.parse(response);
            
        if(newResp['status'] == 'success'){
            openSuccess(newResp['response'])
        }else{ 
            loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
        }

    });

}

function switchFinancial(userid,status){

    if(status == 1){
        changedTo = 0;
    }else{
        changedTo = 1;
    }

    $.post('<?=BASEURL;?>Users/IsAllowFinancial',{'uid':userid,'status':changedTo},function(response){

      newResp = JSON.parse(response);
            
        if(newResp['status'] == 'success'){
            openSuccess(newResp['response'])
        }else{ 
            loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
        }

    });

}

$("#pic").change(function(){

    var userID = '<?=$user['data']['id']?>';
   
     data = new FormData();
    
     data.append('file', $('#pic').prop('files')[0]);
     data.append('user_id', userID);
     

     loadingoverlay('info',"<?=Raise::t('announcement','load1_txt');?>","<?=Raise::t('announcement','load2_txt');?>");
      $.ajax({
            url: '<?=BASEURL;?>Users/ImageUpload/', 
            dataType: 'text',  
            cache: false,
            contentType: false,
            processData: false,
            data: data,                         
            type: 'post',
            success: function(response){ 
            
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){
                openSuccess("Profile Image Updated Successfully");
                location.reload();
                }else{
                  loadingoverlay('error','Error',newResp['response']);
                }
             return false;
            }
        }); 
});

 
</script>

 
