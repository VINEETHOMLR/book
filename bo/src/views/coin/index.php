<?php 
use inc\Raise;
$this->mainTitle = Raise::t('coin','page_title');
$this->subTitle  = '';
global $wallet_groups_array;
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
              <form id="CoinForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Coin/index/">
                <div class="form-group col-sm-2">
                  <input type="text" class="form-control"  id="coin_name_search" name="coin_name_search" value="" placeholder="Coin name" >
                </div>	
                <div class="form-group col-sm-2">
                  <select type="option" name="status" class="form-control select" id="status">
                    <option value=""><?=Raise::t('coin','status')?></option>
                    <option value="1"><?=Raise::t('coin','active')?></option>
                    <option value="0"><?=Raise::t('coin','not_active')?></option>
                  </select>
                </div>
				<div class="form-group col-sm-2">
                  <select type="option" name="wallet_group_search" class="form-control select" id="wallet_group_search">
                    <option value=""><?=Raise::t('coin','wallet_group')?></option>
                    <?php
                        foreach ($wallet_groups_array as $key => $wallet_groups_array1) {
                            echo '<option value="'.$key.'">'.$wallet_groups_array1.'</option>';
                        }
                    ?>
                  </select>
                </div>
                <div class="form-group col-sm-2">
                    <input type="submit" class="btn btn-success col-12" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                </div>
              </form>
            </div>

            <div class="row col-md-12 col-lg-12">
                           
              <div class="col-md-5 col-lg-5">
                <!--<span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Raise::t('app','yesterday_txt')?></a> </span>
                <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Raise::t('app','today_txt')?></a> </span>
                <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','7days_txt')?></a> </span>-->
                                 
              </div>
              <div class="col-md-7 col-lg-7">
                              
                <a href="<?=BASEURL;?>Coin/Create/" class="full_width div_float">
                  <button type="button" class="btn btn-outline-primary mb-2 col-md-12 col-sm-12"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>&nbsp;<?=Raise::t('coin','ann_add')?></button>
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
                                   
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Coin Name</th>
                                   <th class="sorting_disabled" rowspan="1" colspan="1">Coin Code		</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Value</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Transfer out value</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Status</th>
                                  <th class="sorting_disabled" rowspan="1" colspan="1">Action</th>
                                </thead>
                                <tbody>
                                <?php

                                  if(!empty($data['data'])){
                                     foreach($data['data'] as $key => $val): ?>
                                       <tr role="row" class="odd">
                                           <td><?=$val['title']?></td>
                                           <td><?=$val['coin_code']?></td>
                                           <td><?=$val['coin_value']?></td>
                                           <td><?=$val['coin_transfer_value']?></td>
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
                            <form class="col-md-12" id="anno_pagination" method="post" action="<?=BASEURL;?>Coin/Index/">
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
                 <?=Raise::t('coin','ann_edit')?></h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="card-block">     
            <div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label><?=Raise::t('coin','title')?></label>      
                    <input type="text" name="coinTitle" id="coinTitle" value="" class="form-control">
                </div>
            </div> 
			<div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label><?=Raise::t('coin','coin_value')?></label>      
                    <input type="text" name="coinValue" id="coinValue" value="" class="form-control">
                </div>
            </div>
			<div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label><?=Raise::t('coin','coin_transfer_value')?></label>      
                    <input type="text" name="coinTrasfervalue" id="coinTrasfervalue" value="" class="form-control">
                </div>
            </div>
			<div class="row m-b-30 form-group">
                <div class="col-lg-12 col-xl-12">
                    <label><?=Raise::t('coin','master_address')?></label>      
                    <input type="text" name="master_address" id="master_address" value="" class="form-control">
                </div>
            </div>
			<div class="row m-b-30 form-group">
              <div class="col-lg-12 col-xl-12">
                <label><?=Raise::t('coin','wallet_group')?></label>      
                <select class="form-control custom-select" id="wallet_group" name="wallet_group">
                  <?php
                        foreach ($wallet_groups_array as $key => $wallet_groups_array1) {
                            echo '<option value="'.$key.'">'.$wallet_groups_array1.'</option>';
                        }
                    ?>
                </select> 
                <input type="hidden" value="" id="coin_id" name="coin_id">      
                      
              </div>
            </div>
            <div class="row m-b-30 form-group">
              <div class="col-lg-12 col-xl-12">
                <label><?=Raise::t('coin','status')?></label>      
                <select class="form-control custom-select" id="choice" name="choice">
                  <option  value="1"><?=Raise::t('coin','active')?></option>
                  <option value="0"><?=Raise::t('coin','not_active')?></option>
                </select> 
                <input type="hidden" value="" id="coin_id" name="coin_id">      
                      
              </div>
            </div>                

          </div>
            
      
          <div class="modal-footer">
            <button type="button" id="save" class="btn btn-primary mr-3"><?=Raise::t('coin','save_change')?></button>
            <button type="button" class="btn btn-default" data-dismiss="modal"><?=Raise::t('coin','can')?></button>
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
		$('#coin_name_search').val("<?=$coin_name_search;?>");
		$('#wallet_group_search').val("<?=$wallet_group_search;?>");
        
        
        var status   = "<?=$status;?>"; 
		var coin_name_search   = "<?=$coin_name_search;?>";
		var wallet_group_search   = "<?=$wallet_group_search;?>";


    });
    
    function showModal(id) {
        $.post('<?= BASEURL ?>Coin/getEdit/',{'AnnId':id},function(response){
	
           $('#coinTitle').val(response.coin_name);
           $('#coin_id').val(id);
		   $('#choice').val(response.status);
		   $('#coinValue').val(response.value);
		   $('#coinTrasfervalue').val(response.transfer_out_value);
		   $('#wallet_group').val(response.wallet_group);
		   $('#master_address').val(response.master_address);
           $('#myModal').modal('show');
        });
    }

 $('#save').click(function(){

    data = new FormData();
    data.append('title', $('#coinTitle').val());
    data.append('status', $('#choice').val());
	data.append('value', $('#coinValue').val());
	data.append('transfer_out_value', $('#coinTrasfervalue').val());
	data.append('wallet_group', $('#wallet_group').val());
	data.append('master_address', $('#master_address').val());
    data.append('id', $('#coin_id').val());
    
    loadingoverlay('info',"Please wait..","loading...");
    
    $.ajax({
        url: '<?=BASEURL;?>Coin/Add/', 
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
        $('#CoinForm').submit();
    })

    $('#accordionExample').find('li a').attr("data-active","false");
    $('#coinlist').attr("data-active","true");

 

  function deleteThis(val){

     swal({
          title:'Are you sure?',
          text: "Delete This Coin !",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          padding: '2em'
        }).then(function(result) {
            if (result.value) {
               loadingoverlay('info',"Please wait..","loading...");
               $.post('<?= BASEURL ?>Coin/Delete/',{getId:val},function(response){ 
    
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

  function pageHistory(status,coin_name_search,wallet_group_search,page){
    
    $('.pagination').append('<input name="status" value="'+status+'" style="display:none;">');
	$('.pagination').append('<input name="coin_name_search" value="'+coin_name_search+'" style="display:none;">');
	$('.pagination').append('<input name="wallet_group_search" value="'+wallet_group_search+'" style="display:none;">');
    $('.pagination').append('<input name="page" value="'+page+'" style="display:none;">');
    $('#anno_pagination').submit();
  }
</script>

       



