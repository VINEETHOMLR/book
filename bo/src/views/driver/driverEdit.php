<?php

use inc\Raise;
$this->mainTitle = 'Driver Management';
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
    #upcomingTripsTable_length,#upcomingTripsTable_filter{
       display: none;
    }
     #hisTable_length,#hisTable_filter{
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
  }
  .hide{
    display:none;
  }
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
          <div class="row layout-top-spacing">
            <div class="user-profile layout-spacing col-12">
                <div class="widget-content widget-content-area">
                     <!-- <div class="d-flex">
                          <div class="dot"></div> <h4><b> <?=ucwords($user['info']['icnumber'])?></b></h4>
                      </div> -->
                      <div class="user-info-list padding30">

                          <div class="col-sm-12 col-md-12 row">
                              
                              <div class="col-md-3 col-sm-12">
                                <label class="marginLeft">Name</label> : <label><?=ucwords($user['data']['driver_name']);?></label>
                              </div>
                              <div class="col-md-3 col-sm-12">
                                <label class="marginLeft">Phone</label> : <label><?=$user['data']['phone'];?></label>
                              </div>
                              <div class="col-md-3 col-sm-12">
                               
                                <label>Vehicle Number</label> : <label><?=$user['data']['vehicle_number'];?></label>
                              </div>

                          </div>                                    
                      </div>
                </div>
          </div>
                <div class="col-lg-12 col-12 layout-spacing">
                    <div class="widget-content widget-content-area vertical-line-pill">
                        <nav class="breadcrumb-two" aria-label="breadcrumb">
                          <ol class="breadcrumb">
                              <li class="breadcrumb-item"><a href="<?=BASEURL;?>Driver/Index/">Driver
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
                                   
                                    <a class="nav-link mb-3  text-center" id="v-line-pills-profile-tab" data-toggle="pill" href="#v-line-pills-profile" role="tab" aria-controls="v-line-pills-profile" aria-selected="true">Update Password</a>

                                     <!-- <a class="nav-link mb-3  text-center" data-toggle="pill" href="#tripsTab" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">Upcoming Trips</a> 

                                     <a class="nav-link mb-3  text-center" data-toggle="pill" href="#HisTab" role="tab" aria-controls="v-line-pills-profile" aria-selected="false">View History</a>  -->
                                                                      
                                </div>
                            </div>

                            <div class="col-sm-9 col-12">
                                <div class="tab-content" id="v-line-pills-tabContent">
                                    <div class="tab-pane fade show active" id="v-line-pills-home" role="tabpanel" aria-labelledby="v-line-pills-home-tab">

                                    

                                      

                                      <div class="widget-content widget-content-area">
                                         
                                        <form id="formValidation" class="form-horizontal" role="form">
                                            <div class="row col-12"><h4>Update Profile</h4></div>
                                            <div class="row col-md-12">
                                                <input type="hidden" name="editId" value="<?=$user['data']['id']?>">
                                                
                                                <div class="form-group col-md-6">
                                                    <div class="col-md-12">
                                                        <label>Driver name</label>
                                                        <input type="text" name="driver_name"  class="form-control isSpclChar" id="driver_name" value="<?=$user['data']['driver_name'];?>" placeholder="Driver name">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <div class="col-md-12">
                                                        <label>Vehicle Number</label>
                                                        <input type="text"  name="vehicle_number" id="vehicle_number"  class="form-control" value="<?=$user['data']['vehicle_number'];?>" placeholder="Vehicle Number">
                                                    </div>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <div class="col-md-12">
                                                        <label>Phone</label>
                                                        <input type="text"  name="phone" id="phone"  class="form-control" value="<?=$user['data']['phone'];?>" placeholder="Phone number">
                                                    </div>
                                                </div>
                                            </div>
                                             
                                           
                                             
                                          
                                                 
                                            <div class="col-md-12  text-center">
                                                <button class="btn btn-primary col-md-4 col-12 proceedToUpdate" type="button">Update Details</button>
                                            </div>

                                        </form>

                                      </div>
                                      <br>

                                   </div>


                                    <div class="tab-pane fade " id="v-line-pills-profile" role="tabpanel" aria-labelledby="v-line-pills-profile-tab">

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
                                            
                                                    <input type="hidden" name="editId" value="<?=$user['data']['id']?>">       
                                                
                                                    <div class="col-md-12 text-center">
                                                            <button class="btn btn-primary btn-sm  edit-text-shw  updatePass" id="updatePass" type="button"><span class="fa fa-save"></span>&nbsp;<?=Raise::t('subadmin','update_pass')?></button>
                                                    </div>
                                                

                                               </div>
                                             </form>
                                    
                                         </div>
                                    </div>

                                    <!--  <div class="tab-pane fade" id="tripsTab" role="tabpanel" aria-labelledby="tripsTab">
                                        <div class="row col-12">
                                            <div class="widget-content widget-content-area animated-underline-content col-12">
                                    
                                                <div class="row">
                                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                                         <h4>Trip List</h4>
                                                    </div>
                                                </div>
                                                           
                                                    <div class="table-responsive">
                                                        <table class="multi-table table table-hover" id="upcomingTripsTable" style="width:100%">
            
                                                           <tbody class="text-center"></tbody>
                                                        </table>
                                                    </div>
                                            </div>
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
                                                    <div class="col-4"><label>Total Paid</label> : <?=number_format($total['paid'],2)?></div>
                                                    <div class="col-4"><label>Total Not Paid</label> : <?=number_format($total['notpaid'],2)?></div>
                                                    <div class="col-4"><label>Total Number Of Transfer</label> : <?=$total['nooftransfer']?></div>
                                                </div>
                                                           
                                                    <div class="table-responsive">
                                                        <table class="multi-table table table-hover" id="hisTable" style="width:100%">
            
                                                           <tbody class="text-center"></tbody>
                                                        </table>
                                                    </div>
                                                    
                                            </div>
                                        </div>

                                    </div> -->

		
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

      var f1 = flatpickr(document.getElementById('dob'),{dateFormat:"d-m-Y",maxDate: "31.12.2002"});

  
});

$(document).ready(function() {
    var id = "<?=$user['data']['id']?>";
    /*$('#upcomingTripsTable').DataTable({
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_"
        },
        "stripeClasses": [],
        "ajax": {
          url: "<?= BASEURL . 'Driver/UpcomingTripsData' ?>",
          type: "post",
          data: {'id':id}
        },
        order: [[ 0, "desc" ]],
        columns: [
          {data: 'id', title: 'JOB ID', orderable: false, visible: true},
          {title: 'Trip Date', data: 'date',orderable: false},
          {title: 'Driver', data: 'driver',orderable: false},
           {title: 'Vehicle', data: 'vehicle',orderable: false},
           {title: 'Transfer Driver', data: 'driverT',orderable: false},
           {title: 'Transfer Vehicle', data: 'vehicleT',orderable: false},
           {title: 'Customer', data: 'customer',orderable: false},
           {title: 'Customer Type', data: 'type',orderable: false},
           {title: 'Pick Up', data: 'pick_off', orderable: false},
           {title: 'drop Off', data: 'drop_off', orderable: false},
          {title: 'Amount', data: 'amount', orderable: false},
          {title: 'Payment Status', data: 'payStatus', orderable: false},
          {title: 'Status', data: 'status', orderable: false},
        ],
        "pageLength": 10
    });*/
} );

$(document).ready(function() {
    var id = "<?=$user['data']['id']?>";
    /*$('#hisTable').DataTable({
        "oLanguage": {
            "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
            "sInfo": "Showing page _PAGE_ of _PAGES_"
        },
        "stripeClasses": [],
        "ajax": {
          url: "<?= BASEURL . 'Driver/TripHisData' ?>",
          type: "post",
          data: {'id':id}
        },
        order: [[ 0, "desc" ]],
        columns: [
          {data: 'id', title: 'JOB ID', orderable: false, visible: true},
          {title: 'Trip Date', data: 'date',orderable: false},
          {title: 'Driver', data: 'driver',orderable: false},
           {title: 'Vehicle', data: 'vehicle',orderable: false},
           {title: 'Transfer Driver', data: 'driverT',orderable: false},
           {title: 'Transfer Vehicle', data: 'vehicleT',orderable: false},
           {title: 'Customer', data: 'customer',orderable: false},
           {title: 'Customer Type', data: 'type',orderable: false},
           {title: 'Pick Up', data: 'pick_off', orderable: false},
           {title: 'drop Off', data: 'drop_off', orderable: false},
          {title: 'Amount', data: 'amount', orderable: false},
          {title: 'Payment Status', data: 'payStatus', orderable: false},
          {title: 'Status', data: 'status', orderable: false},
        ],
        "pageLength": 10
    });*/
} );

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



    $('#ic_driving_license').on('change', function() {
      $('.editImg2').remove();
        imagesPreview(this, 'div#gallery2' ,'editImg2');
    });
   
    
    $('.proceedToUpdate').click(function(){
       
        postdata = $('#formValidation').serializeArray();

        loadingoverlay('info',"Please wait..","loading...");

        $.post('<?=BASEURL;?>Driver/DriverEdit/',postdata,function(response){
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
 
        $.post('<?= BASEURL ?>Driver/Resetpass/',postdata,function(response){ 
    
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


$("#pic").change(function(){

    var userID = '<?=$user['data']['id']?>';
   
     data = new FormData();
    
     data.append('file', $('#pic').prop('files')[0]);
     data.append('user_id', userID);
     data.append('type', '1');
     

     loadingoverlay('info',"<?=Raise::t('announcement','load1_txt');?>","<?=Raise::t('announcement','load2_txt');?>");
      $.ajax({
            url: '<?=BASEURL;?>Driver/ImageUpload/', 
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


$("#ic_driving_license").change(function(){

    var userID = '<?=$user['data']['id']?>';
   
     data = new FormData();
    
     data.append('file', $('#ic_driving_license').prop('files')[0]);
     data.append('user_id', userID);
     data.append('type', '2');
     

     loadingoverlay('info',"<?=Raise::t('announcement','load1_txt');?>","<?=Raise::t('announcement','load2_txt');?>");
      $.ajax({
            url: '<?=BASEURL;?>Driver/ImageUpload/', 
            dataType: 'text',  
            cache: false,
            contentType: false,
            processData: false,
            data: data,                         
            type: 'post',
            success: function(response){ 
            
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){
                openSuccess("Driving License Image Updated Successfully");
                location.reload();
                }else{
                  loadingoverlay('error','Error',newResp['response']);
                }
             return false;
            }
        }); 
});



$('.isSpclChar').keyup(function() {
    var $th = $(this);
    $th.val( $th.val().replace(/[^a-zA-Z0-9]/g,'') );
});

 
</script>

 
