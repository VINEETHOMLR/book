<?php 
use inc\Raise;
$this->mainTitle = Raise::t('announcement','page_title');
$this->subTitle  = '';

 $servicesArray = $_SESSION['INF_privilages'];
 $servicesArray = explode(",", $servicesArray[0]);
 $servicesArray = array_filter($servicesArray);

 $role = $_SESSION['INF_role'];
?>

<style type="text/css">
  
 .dataTables_filter{
    display: none;
  }
  .m-t-40{
    margin-top: 0px !important;
  }
  .dataTable{ text-align: center; }

  .div_float{
     float: right;
     margin-right: 30px;
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
       height: 42px;
}
.select{
  padding-top: 9px;
}
 

</style>

<div id="content" class="main-content">
  <div class="layout-px-spacing">
    <div class="row layout-top-spacing">
      <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
        <div class="widget-content widget-content-area br-6">
          <div class="row">
            <div class="row col-md-12 col-xs-12">
              <form id="AnnounceForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Announcement/index/">
                <div class="form-group col-sm-2 ">
                  <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom" onchange="selfrom();">
                </div>
                <div class="form-group col-sm-2">
                  <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date" onchange="selto();">
                </div>
                <div class="form-group col-sm-2">
                  <select type="option" name="language" class="form-control select" id="language">
                    <option value=""><?=Raise::t('announcement','Language_text')?></option>
                      <?php  foreach($LanguageArray as $language) {
                        echo '<option value="'.$language['id'].'">'.ucwords($language['lang_name']).'</option>';
                      } ?> 
                  </select>
                </div>
                <div class="form-group col-sm-2">
                  <select type="option" name="status" class="form-control select" id="status">
                    <option value=""><?=Raise::t('announcement','status')?></option>
                    <option value="0"><?=Raise::t('announcement','publish')?></option>
                    <option value="1"><?=Raise::t('announcement','hidden')?></option>
                  </select>
                </div>
                <div class="form-group col-sm-2">
                    <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                </div>
              </form>
            </div>

            <div class="row col-md-12 col-lg-12">
                           
              <div class="col-md-5 col-lg-5">
                <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Raise::t('app','yesterday_txt')?></a> </span>
                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Raise::t('app','today_txt')?></a> </span>
                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','7days_txt')?></a> </span>
                                 
              </div>
              <div class="col-md-7 col-lg-7">
                              
                <a href="<?=BASEURL;?>Announcement/Create/" class="full_width div_float">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;<?=Raise::t('announcement','ann_add')?></button>
                </a>
                                    
              </div>
                           
            </div>
                             
                                           
          </div>

          <div class="widget-content widget-content-area">

              <div class="table-responsive mb-4 mt-4">
                    <div class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                          <div class="col-sm-12">
                              <table id="dt" class="table table-hover dataTable no-footer" style="width: 100%;" role="grid" aria-describedby="dt_info">
                                <thead>
                                  <tr role="row">
                                    <th class="sorting_disabled" rowspan="1" colspan="1" >Date</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Title</th>
                                   <th class="sorting_disabled" rowspan="1" colspan="1">Message</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Language</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">File</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['datetime']?></td>
                                           <td><?=$val['title']?></td>
                                           <td><?=$val['message']?></td>
                                           <td><?=ucwords($val['language'])?></td>
                                           <td><?=$val['filename']?></td>
                                           <td><?=$val['status']?></td>
                                           <td><?=$val['action']?></td>
                                       </tr>
                              <?php endforeach;
                                  }else{
                                     echo '<tr><td colspan="7" class="text-center">No Data Found</td></tr>';
                                  }   
                              ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row text-center">
                            <form class="col-md-12" id="anno_pagination" method="post" action="<?=BASEURL;?>Announcement/Index/">
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
  </div>

  <div class="modal fade" id="myModal" role="dialog" tabindex="-1" role="dialog" aria-labelledby="mypassLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" style="font-size: 17px;font-weight: 600;"><span class="fa fa" style="color: #1caf9a"></span>&nbsp;&nbsp;
                 <?=Raise::t('announcement','ann_edit')?></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="card-block">     
            <div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label><?=Raise::t('announcement','title')?></label>      
                    <input type="text" name="annTitle" id="annTitle" value="" class="form-control">
                </div>
            </div>  
            <div class="row m-b-30 form-group">
              <div class="col-lg-12 col-xl-12">
                <label><?=Raise::t('announcement','status')?></label>      
                <select class="form-control custom-select" id="choice" name="choice">
                  <option  value="0"><?=Raise::t('announcement','publish')?></option>
                  <option value="1"><?=Raise::t('announcement','hidden')?></option>
                </select> 
                <input type="hidden" value="" id="announcement_id" name="announcement_id">      
                <input type="hidden" value="" id="onchange_Lang_id">      
              </div>
            </div>
            <div class="row m-b-30 form-group">
              <div class="col-lg-12 col-xl-12">
                <label><?=Raise::t('announcement','Language')?></label> 
                <select class="form-control custom-select" id="language_id" name="language_id">
                      
                  <?php  foreach($LanguageArray as $language) {
                    echo '<option value="'.$language['id'].'">'.ucwords($language['lang_name']).'</option>';
                  } ?> 
                </select>
              </div>  
                                                   
            </div>

            <div class="row m-b-30 form-group">
              <div class="col-lg-12 col-xl-12">

                  <label>Message</label><br>
                  <textarea id="message" name="message" class="form-control" rows="4" cols="50"></textarea>
              </div>
            </div>
                          
               
            <div class="row m-b-30 form-group">
              <div class="col-lg-12 col-xl-12">

                  <label><?=Raise::t('announcement','pdf_upload')?> (PDF,Image) *</label><br>
                  <input type="file" name="filename"  class="" id="filename" >
              </div>
            </div>

          </div>
            
      
          <div class="modal-footer">
            <button type="button" id="save" class="btn btn-primary mr-3"><?=Raise::t('announcement','save_change')?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=Raise::t('announcement','can')?></button>
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

    $(document).ready(function() {

         
        $('#status').val("<?=$status;?>");
        $('#datefrom').val("<?=$datefrom;?>");
        $('#dateto').val("<?=$dateto;?>");
        $('#language').val("<?=$lan;?>");
        var status   = "<?=$status;?>"; 
        var datefrom = "<?=$datefrom;?>";
        var dateto   = "<?=$dateto;?>";
        var lan   = "<?=$lan;?>";

        var f1 = flatpickr(document.getElementById('datefrom'),{dateFormat:"d-m-Y",});
        var f2 = flatpickr(document.getElementById('dateto'),{dateFormat:"d-m-Y",});
    });
    
    function showModal(id) {

        $.post('<?= BASEURL ?>Announcement/getEdit/',{'AnnId':id},function(response){

            lan_id = $('#btn'+id).attr("data-lang");

           $('#annTitle').val(response.title);
           $('#choice').val(response.status);
           $('#message').val(response.message);
           $('#announcement_id').val(id);
           $('#language_id').val(lan_id);
           $('#onchange_Lang_id').val(lan_id);
           $('#myModal').modal('show');
        });
    }

 $('#save').click(function(){

    data = new FormData();
    data.append('title', $('#annTitle').val());
    data.append('filename', $('#filename')[0].files[0]);
    data.append('status', $('#choice').val());
    data.append('language', $('#language_id').val());
    data.append('message', $('#message').val());
    data.append('id', $('#announcement_id').val());
    
    loadingoverlay('info',"Please wait..","loading...");
    
    $.ajax({
        url: '<?=BASEURL;?>Announcement/Add/', 
        dataType: 'text',  
        cache: false,
        contentType: false,
        processData: false,
        data: data,                         
        type: 'post',
        success: function(response){ 
        // alert(response);
            newResp = JSON.parse(response);
            if(newResp['status'] == 'success'){
               openSuccess(newResp['response'])  
            }else{
               loadingoverlay('error','Error',newResp['response']);
            }
              return false;
        }
    }); 
    return false;
});

    $('#search').click(function(){
        $('#AnnounceForm').submit();
    })

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#announcement').attr("data-active","true");

 

  function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Announcement !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Announcement/Delete/',{getId:val},function(response){ 
    
                    newResp = JSON.parse(response);
                    if(newResp['status'] == 'success'){
                        openSuccess(newResp['response'])
                    }else{ 
                        loadingoverlay('error','<?=$error_dash;?>',newResp['response']);
                    }
                });
                return false;
            }
        })
  }

  function pageHistory(datefrom,dateto,status,language,page){
    $('.pagination').append('<input name="datefrom" value="'+datefrom+'" style="display:none;">');
    $('.pagination').append('<input name="dateto" value="'+dateto+'" style="display:none;">');
    $('.pagination').append('<input name="language" value="'+language+'" style="display:none;">');
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#anno_pagination').submit();
  }
</script>

       



