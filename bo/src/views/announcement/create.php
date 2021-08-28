<?php 
use inc\Raise;
$this->mainTitle = '  Announcement';
$this->subTitle  = 'Create Announcement';
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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Announcement/Index/">Announcement</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);">Create Announcement</a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label><?=Raise::t('announcement','Language')?>*</label>
                                                      <select class="form-control custom-select" name="language" id="language" onchange="getCategory(this.options[this.selectedIndex].value)">
                                                         <option value=""><?=Raise::t('announcement','Language_text');?></option>
                                                            <?php  foreach ($LanguageArray as $language) {
                                                                echo '<option value="'.$language['id'].'">'.ucwords($language['lang_name']).'</option>';
                                                            } ?>
                                                      </select> 
                                                </div>
                                            </div>
                                            
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <label><?=Raise::t('announcement','title')?>*</label>
                                                   <input type="text"  name="title" id="title"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Message*</label>
                                                   <textarea id="message" name="message" class="form-control" rows="4" cols="50"></textarea>
                                                </div>
                                            </div>
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                   <label><?=Raise::t('announcement','pdf_upload')?> (PDF,Image) *</label>
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

$error_dash = Raise::t('app','error_dash');
$success    = Raise::t('app','suucess_txt');
$okay       = Raise::t('app','okay_btn'); 

?>

<script type="text/javascript">

$('#accordionExample').find('li a').attr("data-active","false");
$('#announcement').attr("data-active","true");

     
$('#save').click(function(){
   
 data = new FormData();
 data.append('language', $('#language').val());
 data.append('title', $('#title').val());
 data.append('message', $('#message').val());

 data.append('filename', $('#filename')[0].files[0]);
  loadingoverlay('info',"<?=Raise::t('announcement','load1_txt');?>","<?=Raise::t('announcement','load2_txt');?>");
 $.ajax({
        url: '<?=BASEURL;?>Announcement/Add/', 
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
                     openSuccess(newResp['response'],'<?=BASEURL;?>Announcement/Index/')  
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
