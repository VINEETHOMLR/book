<?php 
use inc\Raise;
$this->mainTitle = '  Coin';
$this->subTitle  = 'Create Coin';
global $wallet_groups_array;
?>

<style type="text/css">
  .breadcrumb-two .breadcrumb li a::before {
    content: none;
  }
  .form-control{
    height: 50px;
  }
  
@media only screen and (max-width: 900px) {
  .info-icon {
     margin-top: -8px !important;
     margin-left: 32%;
  }
}
.card-body{
  width: 70%;margin: 0 auto;
}
 @media only screen and (min-width: 600px) and (max-width: 1000px)  {
  .card-body{
    width: 100%;margin: 25px;
  }
}
.breadcrumb-two .breadcrumb li a::before {
    content: none;
  }

  .breadcrumb-two{
        top: 0;
        position: absolute;
        left: 0;
    }

</style>
<link href="<?=WEB_PATH?>assets/css/users/user-profile.css" rel="stylesheet" type="text/css" />

<div id="content" class="main-content" id="info">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div class="statbox widget box box-shadow">
                  <nav class="breadcrumb-two" aria-label="breadcrumb">
                      <ol class="breadcrumb">
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Coin/Index/">Coin</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);">Create Coin</a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">
                                            
                                            
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <label><?=Raise::t('coin','title')?>*</label>
                                                   <input type="text"  name="title" id="title"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label><?=Raise::t('coin','coin_code')?>*</label>
                                                   <input type="text"  name="coin_code" id="coin_code"  class="form-control">
                                                </div>
                                            </div>
											<div class="col-md-6">
                                                <div class="form-group">
                                                   <label><?=Raise::t('coin','coin_value')?>*</label>
                                                   <input type="text"  name="coin_value" id="coin_value"  class="form-control">
                                                </div>
                                            </div>
											<div class="col-md-6">
                                                <div class="form-group">
                                                   <label><?=Raise::t('coin','coin_transfer_value')?>*</label>
                                                   <input type="text"  name="coin_transfer_value" id="coin_transfer_value"  class="form-control">
                                                </div>
                                            </div>
											<div class="col-md-6">
                                                <div class="form-group">
                                                   <label><?=Raise::t('coin','master_address')?>*</label>
                                                   <input type="text"  name="master_address" id="master_address"  class="form-control">
                                                </div>
                                            </div>
											<div class="col-md-6">
                                                <div class="form-group">
                                                   <label><?=Raise::t('coin','wallet_group')?>*</label>
                                                    <select class="form-control custom-select" id="wallet_group" name="wallet_group">
													 <?php
														foreach ($wallet_groups_array as $key => $wallet_groups_array1) {
															echo '<option value="'.$key.'">'.$wallet_groups_array1.'</option>';
														}
													?>
													</select>
                                                </div>
                                            </div>
											
                                            
                                          
                                            <div class="col-md-12  text-center" style="margin-top: 2%;">
                                                
                                                <button class="btn btn-primary proceedToPayment" id="save" type="button"><?=Raise::t('coin','submit')?></button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    
                </div>
            </div>
        </div>
</div>
<?php

$error_dash = Raise::t('app','error_dash');
$success    = Raise::t('app','suucess_txt');
$okay       = Raise::t('app','okay_btn'); 

?>

<script type="text/javascript">

$('#accordionExample').find('li a').attr("data-active","false");
$('#coinlist').attr("data-active","true");

     
$('#save').click(function(){
   
 data = new FormData();
 data.append('title', $('#title').val());
 data.append('coin_code', $('#coin_code').val());
 data.append('value', $('#coin_value').val());
 data.append('transfer_out_value', $('#coin_transfer_value').val());
 data.append('master_address', $('#master_address').val());
 data.append('wallet_group', $('#wallet_group').val());


  loadingoverlay('info',"<?=Raise::t('coin','load1_txt');?>","<?=Raise::t('coin','load2_txt');?>");
 $.ajax({
        url: '<?=BASEURL;?>Coin/Add/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success')
                     {
                     openSuccess(newResp['response'],'<?=BASEURL;?>Coin/Index/')  
                }
                     else
                     {
                         loadingoverlay('error','Error',newResp['response']);
                     }
          return false;
        }
    }); 
});

</script>
