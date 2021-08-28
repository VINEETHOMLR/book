<?php 
use inc\Raise;
$this->mainTitle = 'Driver Management';
$this->subTitle  = 'Create Driver';
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
  .thumbnail{
        width: 250px;
        height: 200px;
        float: left;
      }

  .btn-upload {
    background-color: #eee;
    border-left: none;
    height: 50px;
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
                          <li class="breadcrumb-item"><a href="<?=BASEURL;?>Driver/Index/">Driver</a></li>
                          <li class="breadcrumb-item active"><a href="javascript:void(0);">Create Driver</a></li>
                      </ol>
                  </nav><br>
                    <form id="formValidation" class="form-horizontal" role="form">
                            <div class="info">
                                <div class="row">
                                    <div class="col-md-11 mt-4 mb-4">

                                        <div class="row">
                                        
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Driver Name *</label>
                                                   <input type="text"  name="driver_name" id="driver_name"  class="form-control isSpclChar">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Vehicle Number *</label>
                                                   <input type="text"  name="vehicle_number" id="vehicle_number"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Phone *</label>
                                                   <input type="text"  name="phone" id="phone"  class="form-control" onkeyup="checkNumber(event)">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Password *</label>
                                                   <input type="password"  name="password" id="password"  class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                   <label>Confirm Password *</label>
                                                   <input type="password"  name="cpassword" id="cpassword"  class="form-control">
                                                </div>
                                            </div>
                                            
                                          
                                            <div class="col-md-12  text-center" style="margin-top: 2%;">
                                                
                                                <button class="btn btn-primary " id="save" type="button"><?=Raise::t('announcement','submit')?></button>
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

$(function () {

      var f1 = flatpickr(document.getElementById('dob'),{dateFormat:"d-m-Y",maxDate: "31.12.2002"});

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

    $('#ic_driving_license_pic').on('change', function() {
      $('.editImg2').remove();
        imagesPreview(this, 'div#gallery2' ,'editImg2');
    });

     
$('#save').click(function(){
  
       data = new FormData();
       data.append('driver_name', $('#driver_name').val());
       data.append('vehicle_number', $('#vehicle_number').val());
       data.append('phone', $('#phone').val());
       data.append('status', '1');
       data.append('password', $('#password').val());
       data.append('cpassword', $('#cpassword').val());

       loadingoverlay('info',"<?=Raise::t('announcement','load1_txt');?>","<?=Raise::t('announcement','load2_txt');?>");
       $.ajax({
              url: '<?=BASEURL;?>Driver/Add/', 
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
                           openSuccess(newResp['response'],'<?=BASEURL;?>Driver/Index/')  
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
$('.isSpclChar').keyup(function() {
    var $th = $(this);
    $th.val( $th.val().replace(/[^a-zA-Z0-9]/g,'') );
});

</script>
