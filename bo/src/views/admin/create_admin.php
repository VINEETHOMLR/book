
<?php 
use inc\Raise;
$this->mainTitle = 'Admin Management';
$this->subTitle  = 'Create Admin';
?>

<style type="text/css">
  .breadcrumb-two .breadcrumb li a::before {
    content: none;
  }
  .form-control{
    height: 40px;
  }
  .t-group-item {
    color: #fff;
    background-color: #888ea8;
    border-color: transparent;
    box-shadow: 0 1px 15px 1px rgba(52, 40, 104, 0.15);
    border: 1px solid #e0e6ed;
    padding: 10px 12px;
}
  .info-icon {
    border-radius: 50%;
    background: #888ea8;
    display: inline-block;
    padding: 15px;
    margin-bottom: 20px;
    margin-top:-90px;
    margin-left: 42%;
}
.info-icon svg {
    width: 50px;
    height: 50px;
    stroke-width: 1px;
    color: #d3d3d3;
}
svg {
    overflow: hidden;
    vertical-align: middle;
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

</style>

<div id="content" class="main-content">
    <div class="layout-px-spacing">
          <div class="row layout-top-spacing">

              <div class="col-12">
                  <div class="card">
                      <nav class="breadcrumb-two" aria-label="breadcrumb">
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="<?=BASEURL;?>Admin/Index/">Admin list</a></li>
                              <li class="breadcrumb-item active"><a href="javascript:void(0);">Create Admin</a></li>
                          </ol>
                      </nav>
                      <div class="card-body">
                          <div class="row" >
                              <form role="form" id="regiseterNewAdmin" method="post" class="col-md-12">
                                  <div class="panel-body row">
                                    <div class="col-md-5 col-sm-12 statbox widget box box-shadow">
                                        <div class="col-sm-12 col-md-12">
                                            <label><?=Raise::t('subadmin','name_text')?><span style="color:red;">*</span></label>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-12">
                                                    <input type="text" id="Fullname" name="name" class="form-control" placeholder="<?=Raise::t('subadmin','enter_username')?>">
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <label>Username<span style="color:red;">*</span></label>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-12">
                                            
                                                    <input type="text"  name="username" class="form-control input-sm" placeholder="<?=Raise::t('subadmin','enter_user')?>">
                                        </div>


                                        <div class="col-sm-12 col-md-12">
                                           <label><?=Raise::t('user','user_reg_pass')?><span style="color:red;">*</span></label>
                                        </div>
                                        <div class="input-group form-group col-md-12" id="show_hide_password">
                                            <input type="password"  name="password" class="form-control input-sm" placeholder="<?=Raise::t('subadmin','enter_pwd')?>">
                                            <div class="input-group-append">
                                                   <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                            </div>
                                        </div>
                                        
                                        <div class="col-sm-12 col-md-12">
                                          <label><?=Raise::t('user','user_reg_Conpass')?><span style="color:red;">*</span></label>
                                        </div>
                                        <div class="input-group form-group col-12" id="show_hide_password1">
                                            	<input type="password"  name="ConfirmPassword" class="form-control input-sm" placeholder="<?=Raise::t('subadmin','enter_confPwd')?>">
                                              <div class="input-group-append">
                                                   <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                              </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <label><?=Raise::t('subadmin','email_txt')?></label>
                                        </div>
                                        <div class="form-group col-md-12">
                                            	    <input type="text" name="email" id="email" class="form-control input-sm" placeholder="<?=Raise::t('subadmin','enter_email')?>">
                                        </div>
                                        <!-- <div class="col-sm-12 col-md-12">
                                          <label><?=Raise::t('subadmin','Mobile_txt')?></label>
                                        </div>
                                        <div class="form-group col-md-12">
                                            	    <input type="text" name="mobile" id="Mobile" class="form-control input-sm" placeholder="<?=Raise::t('subadmin','enter_mobile')?>">
                                        </div> -->
                                  </div>  
                                  <div class="col-1"></div>
                                  <div class="col-md-5 col-sm-12 statbox widget box box-shadow">

                                      <div id="" class="col-xl-12 col-lg-12 layout-spacing">
                            
                                          <div class="t-group-item ">
                                               <span class="fa fa-cog"></span>&nbsp;<?=Raise::t('subadmin','selservice_dash')?>
                                          </div>
                                          <div class="info-icon">
                                               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                          </div>
                                          <div class="row col-12">
                                              <div class="n-chk col-12">
                                                  <label class="new-control new-checkbox checkbox-danger" style="color:#515365;">
                                                    <input type="checkbox" class="new-control-input" id="checkallmem">
                                                    <span class="new-control-indicator"></span>&nbsp;<?=Raise::t('app','all_txt')?>
                                                  </label>
                                              </div>
                                              <br>
                                              <?php
                                                  foreach ($ServicesAr as $key => $memval) {
                                              ?>
                                                        <div class="n-chk col-md-4" style="margin-top: 5px;float: left;">
                                                            <label class="new-control new-checkbox checkbox-info" style="color:#515365;">
                                                               <input type="checkbox" name="servicearr[]" class="checkmem pull-left new-control-input"  value="<?=$memval['id'];?>">
                                                               <span class="new-control-indicator"></span>&nbsp;<?=$memval['group_name'];?>
                                                            </label>
                                                        </div>
                                              <?php  } ?>
                                          </div>
                               
                                     </div>
                                  </div>              
                            </div>
                        </div><br><br>
                      </form>
                        <div class="panel-footer">
                            <div class="form-actions text-center">
                                    <button type="button" class="btn btn-info proceedToCreate col-12 col-md-3">&nbsp;<?=Raise::t('subadmin','create_txt')?></button>
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
    $('#accordionExample').find('li a').attr("data-active","false");
    $('#adminMenu').attr("data-active","true");
    $('#adminNav').addClass('show');
    $('#adminList').addClass('active');


$("#checkallmem").change(function () {
    $(".checkmem").prop('checked', $(this).prop("checked"));
});

    $(function () {

        $('.proceedToCreate').click(function(){
            postdata = $('#regiseterNewAdmin').serializeArray();
           
            var checked=$('#regiseterNewAdmin input[type=checkbox]:checked').length; 
            if(checked==0) {
               postdata.push({'name':'servicearr','value':""});
            }
            $.post('<?=BASEURL;?>Admin/CreateAdmin/',postdata,function(response){  
                newResp = JSON.parse(response);
                if(newResp['status']=='success'){
                  
                  openSuccess(newResp['response'],'<?=BASEURL;?>Admin/Index/') 
               
                }else{
                    loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
                }
            
               return false;
            });
        });

    });

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
    })
</script>