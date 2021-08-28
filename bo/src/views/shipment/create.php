<?php 
use inc\Raise;
$this->mainTitle = 'Game';
$this->subTitle  = 'Create Game';
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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Game/Index/">Game</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);">Create Game</a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">
                                           <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Name*</label>
                                                   <input type="text"  name="name" id="name"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Code*</label>
                                                   <input type="text"  name="code" id="code"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Vendor*</label>
                                                   <input type="text"  name="vendor" id="vendor"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Order No*</label>
                                                   <input type="text"  name="orderNo" id="orderNo"  class="form-control" onkeyup="checkNumber(event)">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Type*</label>
                                                      <select class="form-control custom-select" name="type" id="type">
                                                         <option value="0">Normal Game</option>
                                                         <option value="1">Hot Game</option>
                                                            
                                                      </select> 
                                                </div>
                                            </div>
                                           
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Image*</label>
                                                   <input type="file" name="filename"  class="form-control" id="filename" >
                                                </div>
                                            </div>
                                            
                                          
                                            <div class="col-md-12  text-center" style="margin-top: 2%;">
                                                
                                                <button class="btn btn-primary proceedToPayment" id="save" type="button"><?=Raise::t('announcement','submit')?></button>
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

$error_dash = 'Error';
$success    = Raise::t('app','suucess_txt');
$okay       = Raise::t('app','okay_btn'); 

?>

<script type="text/javascript">

$('#accordionExample').find('li a').attr("data-active","false");
$('#gamelist').attr("data-active","true");

     
$('#save').click(function(){
   
 data = new FormData();
 data.append('name', $('#name').val());
 data.append('code', $('#code').val());
 data.append('vendor', $('#vendor').val());
 data.append('orderNo', $('#orderNo').val());
 data.append('type', $('#type').val());

 data.append('filename', $('#filename')[0].files[0]);
  loadingoverlay('info',"<?=Raise::t('announcement','load1_txt');?>","<?=Raise::t('announcement','load2_txt');?>");
 $.ajax({
        url: '<?=BASEURL;?>Game/Add/', 
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
                     openSuccess(newResp['response'],'<?=BASEURL;?>Game/Index/')  
                }
                     else
                     {
                         loadingoverlay('error','Error',newResp['response']);
                     }
          return false;
        }
    }); 
});

function checkNumber(event){ 
    var e=$(event.target);
    $(e).val($(e).val().replace(/\D/, ''));
  }

</script>
