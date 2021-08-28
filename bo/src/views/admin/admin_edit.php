<?php 
use inc\Raise;
$this->mainTitle = 'Admin Management';
$this->subTitle  = 'Account Details';
?>
<link href="<?=WEB_PATH?>assets/css/components/tabs-accordian/custom-tabs.css" rel="stylesheet" type="text/css" />
<style type="text/css">
	.vertical-line-pill .nav-pills {
		width: 100%;
	}
	.form-control{
       height: 40px;
    }
.breadcrumb-two{
	top: 0px;
	left: 0px;
	position: absolute;
}
.breadcrumb-two .breadcrumb li a::before {
    content: none;
  }
.t-group-item {
    color: #fff;
    background-color: #888ea8;
    border-color: transparent;
    box-shadow: 0 1px 15px 1px rgba(52, 40, 104, 0.15);
    border: 1px solid #e0e6ed;
    padding: 10px 12px;
}
.table > thead > tr > th {
  text-align: center;
 }

 @media only screen and (min-width: 1200px) {

  .pwdLabel{
     text-align: right;
  }
}
.dot{
    background: #bae7ff;
    border: 2px solid #2196f3;
    padding: 4px;
    border-radius: 50%;
    margin-right: 5px;
    display: inline-block;
  }
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
          <div class="row layout-top-spacing">
                <div class="col-lg-12 col-12 layout-spacing">
                    <div class="widget-content widget-content-area vertical-line-pill">
                    	<nav class="breadcrumb-two" aria-label="breadcrumb">
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="<?=BASEURL;?>Admin/Index/">Admin list</a></li>
                              <li class="breadcrumb-item active"><a href="javascript:void(0);">Account Details</a></li>
                          </ol>
                      </nav>
                      <br><br>
                         <div class="row">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <h4> <?=ucwords($info['username'])?></h4>
                            </div>
                        </div>
                        <div class="row mb-4 mt-3">
                            <div class="col-sm-2 col-12">
                                <div class="nav flex-column nav-pills mb-sm-0 mb-3 text-center mx-auto" id="v-line-pills-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active mb-3" id="v-line-pills-home-tab" data-toggle="pill" href="#v-line-pills-home" role="tab" aria-controls="v-line-pills-home" aria-selected="true">Profile</a>
                                    <a class="nav-link mb-3  text-center" id="v-line-pills-profile-tab" data-toggle="pill" href="#v-line-pills-profile" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">Update Password</a>
                                    <a class="nav-link mb-3  text-center" id="v-line-pills-messages-tab" data-toggle="pill" href="#v-line-pills-messages" role="tab" aria-controls="v-line-pills-messages" aria-selected="false">Login Info</a>
                                    <a class="nav-link  text-center" id="v-line-pills-settings-tab" data-toggle="pill" href="#v-line-pills-settings" role="tab" aria-controls="v-line-pills-settings" aria-selected="false">Account Activity</a>
                                </div>
                            </div>

                            <div class="col-sm-9 col-12">
                                <div class="tab-content" id="v-line-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-line-pills-home" role="tabpanel" aria-labelledby="v-line-pills-home-tab">

                                        <div class="row" style="padding-top: 20px;">
                                               <div class="col-12">
                                                    <form role="form" id="UpdateAdmin" method="post">   
                                                        <div class="table-responsive">
                                                            <table class="table table-striped table-bordered">
                                                                <thead>
                                                                    <th colspan="4" class="text-center">
                                                                       <p class="col-md-12 viewbal text-center"><span class="show_bal col-md-2"><?=$info['name']?></span></p>
                                                                       <a href="#" class="text-primary"><?=Raise::t('subadmin','accinfo_text')?></a>
                                                                       <?=$statusField?>
                                                                    </th>
                                                                </thead>
                                                                <tbody class="responseEdit">
                                                                    <tr> <input type="hidden" name="admin_id" value="<?=$info['id']?>">
                                                                         <td><?=Raise::t('subadmin','name_text')?> * :</td>
                                                                         <td style="width: 25%"><span class="hide_text_edit" style="display:none;"><?=$info['name']?></span>
                                                                             <input type="text" class="edit-text-shw form-control" id="admin_name" name="admin_name" value="<?=$info['name']?>"></td>
                                                                         <td><?=Raise::t('subadmin','user_text')?> * :</td>
                                                                         <td style="width: 25%"><span class="hide_text_edit" style="display:none;"><?=$info['username']?></span>
                                                                             <input type="text" class="edit-text-shw form-control" id="admin_user_name" name="admin_user_name" value="<?=$info['username']?>"></td>
            
                                                                    </tr>
                                                                    <tr>
                                                                        <td><?=Raise::t('subadmin','email_txt')?>:</td>
                                                                        <td style="width: 25%">
                                                                             <input type="text" class="form-control" id="adminEmail" name="adminEmail" value="<?=$info['email']?>"></td>
                                                                        <td><?=Raise::t('subadmin','created_text')?> :</td>
                                                                        <td style="width: 25%">
                                                                              <span><?=date('d-m-Y H:i:s',$info['createtime'])?></span></label>
                                                                    </tr>
                        
                                                                </tbody>
                                                            </table>
                                                            <table class="table table-striped table-bordered">
                                                                <tbody>
                                                                    <tr>
                                                                        <td colspan="4" class="t-group-item text-white"><span class="fa fa-cog"></span>&nbsp;<?=$headPrivilege?></td>
                                                                    </tr>
                                                                    <tr>
                                                                            <?=$privilege?>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        <div class="col-md-12 text-center">
                                                            <button class="btn btn-primary btn-sm pull-right edit-text-shw col-md-2 saveedit" id="saveMemberedit" type="button"><span class="fa fa-save"></span>&nbsp;<?=Raise::t('subadmin','saveedit_text')?></button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                                               
                                        </div>


                                        <div class="tab-pane fade" id="v-line-pills-profile" role="tabpanel" aria-labelledby="v-line-pills-profile-tab">

                                            <div class='panel panel-default'>
                                                <div class='panel-body statbox widget box box-shadow col-sm-12 col-md-12'>
                                                	<div class="widget-header">
                                                        <div class="row" style="margin-bottom: 20px;">
                                                            <div class="col-xl-12 col-md-12 col-sm-12 col-12 text-center">
                                                                <h4>Change Password</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='row col-md-12'>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-5 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px"><?=Raise::t('subadmin','new_pass');?> <span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-6 col-md-12 col-sm-12 col-12" id="show_hide_password">
                                                                        <input type="password" name="userTransPassword"  class="form-control input-sm" id="newpass" placeholder="<?=Raise::t('subadmin','new_pass');?>">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                    
                                                            </div>
                                                            <div class="form-group row col-md-12">
                                                                    <div class="col-xl-5 col-md-12 col-sm-12 col-12 pwdLabel">
                                                                        <label style="margin-top: 10px"><?=Raise::t('subadmin','con_pass');?> <span style="color:red;">*</span> :</label>
                                                                    </div>
                                                                    <div class="input-group form-group col-xl-6 col-md-12 col-sm-12 col-12" id="show_hide_password1">
                                            	                        <input type="password" name="userTransConfirmPassword"  class="form-control input-sm " id="conpass" placeholder="<?=Raise::t('subadmin','con_pass');?>">
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text" id="basic-addon6"><a href=""><i class="fa fa-eye-slash" aria-hidden="true" style="color: black"></i></a></span>
                                                                        </div>
                                                                    </div>
                                                                        
                                                            </div>
                                                        <div class="col-md-12 text-center">
                                                            <button class="btn btn-primary btn-sm pull-right edit-text-shw  updatePass" id="updatePass" type="button"><span class="fa fa-save"></span>&nbsp;<?=Raise::t('subadmin','update_pass')?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="tab-pane fade" id="v-line-pills-messages" role="tabpanel" aria-labelledby="v-line-pills-messages-tab">
                                            <div class="table-responsive mb-4 mt-4">
                                                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                                               <thead>
                                                                  <tr role="row">
                                                                      <th class="sorting_disabled" rowspan="1" colspan="1" ><?= Raise::t('subadmin','number_text'); ?></th>
                                                                      <th class="sorting_disabled" rowspan="1" colspan="1"><?= Raise::t('subadmin','ip_text'); ?></th>
                                                                      <th class="sorting_disabled" rowspan="1" colspan="1"><?= Raise::t('subadmin','logintime_text'); ?></th>
                                                                      <th class="sorting_disabled" rowspan="1" colspan="1"><?= Raise::t('subadmin','logouttime_text'); ?></th>
                                                                      <th class="sorting_disabled" rowspan="1" colspan="1"><?= Raise::t('subadmin','logouttype_text'); ?></th>
                                                                  </tr>
                                                               </thead>
                                                               <tbody class="LoginResponse text-center">
                                                                    <?=$this->actionLoginData()?>
                                                               </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                              
                                        <div class="tab-pane fade " id="v-line-pills-settings" role="tabpanel" aria-labelledby="v-line-pills-settings-tab">
                                            <div class="table-responsive mb-4 mt-4">
                                                <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                                               <thead>
                                                                  <tr role="row">
                                                                      <th class="sorting_disabled" rowspan="1" colspan="1" ><?= Raise::t('subadmin','number_text'); ?></th>
                                                                      <th class="sorting_disabled" rowspan="1" colspan="1"><?= Raise::t('subadmin','loginip_text'); ?></th>
                                                                      <th class="sorting_disabled" rowspan="1" colspan="1"><?= Raise::t('subadmin','time_text'); ?></th>
                                                                      <th class="sorting_disabled" rowspan="1" colspan="1"><?= Raise::t('subadmin','activity_text'); ?></th>
                                                                  </tr>
                                                               </thead>
                                                               <tbody class="activityResponse text-center">
                                                                    <?=$this->actionActivityLog()?>
                                                               </tbody>
                                                            </table>
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
    </div>

<?php

$error_dash = Raise::t('app','error_dash');
$success    = Raise::t('app','suucess_txt');
$okay       = Raise::t('app','okay_btn'); 

?>
 <script type="text/javascript">
 	
 	$('#v-line-pills-home').on('change','#CheckBoxAll',function(){
 
        $(".checkEditmem").prop('checked', $(this).prop("checked"));
    });

    $('#v-line-pills-home').on('click','#saveMemberedit',function(){
      
      postdata = $('#UpdateAdmin').serializeArray();
      postdata.push({'name':'saveeditdata','value':true});
     
        $.post('<?=BASEURL;?>Admin/Editinformation/',postdata,function(response){
             newResp = JSON.parse(response);
            if(newResp['status'] == 'success')
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

    function switchStatus(id,status){
        if(status == 1){
          changedToStatus = 0 ;
        }else{
          changedToStatus = 1 ;
        }

        $.post('<?=BASEURL;?>Admin/UpdateStatus/',{'id':id,'status':changedToStatus},function(response){
           if(response){
              openSuccess('<?=Raise::t('appointment','S4')?>')
          }

        });
    }

    $('#updatePass').click(function(){

      var newp = $('#newpass').val();
      var con  = $('#conpass').val();
      var user = "<?=base64_decode($_GET['admin'])?>";
 
        $.post('<?= BASEURL ?>Admin/resetpass/',{'newp':newp,'con':con,'user':user},function(response){ 
    
             newResp = JSON.parse(response);
            
                 if(newResp['status'] == 'success')
                 {
                     openSuccess(newResp['response'])
                 }else{ 
                     loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
                 }
        });
      return false;
    });
 </script>

<script>
    $('#accordionExample').find('li a').attr("data-active","false");
    $('#adminMenu').attr("data-active","true");
    $('#adminNav').addClass('show');
    $('#adminList').addClass('active');

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
function pageHistory(page){
   var admin = "<?=$_GET['admin']?>";
   $.post('<?= BASEURL ?>Admin/ActivityLog/',{'admin':admin,'page':page},function(response){ 

      $('.activityResponse').html(response);
   }) 
}
function pageLogin(page){
   var admin = "<?=$_GET['admin']?>";
   $.post('<?= BASEURL ?>Admin/LoginData/',{'admin':admin,'page':page},function(response){ 

      $('.LoginResponse').html(response);
   }) 
}
</script>