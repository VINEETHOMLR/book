<?php
error_reporting(E_ALL);
use inc\Raise;

$this->mainTitle = 'Settings';
$this->subTitle  = 'Settings';
$role = $_SESSION['JEC_role'];
?>
<style type="text/css">
  svg {
    
    color: #1b55e2;
    width: 20px;
    margin-right: 5px;
  }
  img{
    height: 25px;
    width: 25px;
    margin-right: 5px;
  }

  .center {
 
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}


  /*.widget-content {
    left: 30%;
  }*/
@media only screen and (max-width: 700px) {
  .widget-content {
    left: 0%;
  }
}
 @media only screen and (min-width: 700px) and (max-width: 1000px)  {
  .widget-content {
    left: 10%;
  }
}
</style>
<div id="content" class="main-content">
    <div class="layout-px-spacing">
          <div class="row layout-top-spacing">
              <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
               
                 <div class="statbox widget box box-shadow"style="margin-bottom:10px;">
                     
                        <form id="settingsForm" method="post" role="form" >
                          <div class="col-md-12 align-self-center">
                            <div class="row">
                              <div class="row col-md-12">
                                <div class="form-group col-md-12">
                                  <label>Withdrawal Fee</label>
                                </div>
                                <div class="form-group col-md-3">
                                  <div class="col-md-12">
									  <label>BTC*</label>
                                    <input type="text"  name="btc" id="btc"  class="form-control" value="<?=$btc_val?>" placeholder="">
                                  </div>
                                </div>
                                <div class="form-group col-md-3">
                                  <div class="col-md-12">
									  <label>USDT*</label>
                                    <input type="text"  name="usdt" id="usdt"  class="form-control" value="<?=$usdt_val?>" placeholder="">    
                                  </div>
                                </div>
                                <div class="form-group col-md-3">
									<label>ETH*</label>
                                  <div class="col-md-12">
									  
                                    <input type="text"  name="eth" id="eth"  class="form-control" value="<?=$eth_val?>" placeholder="">    
                                  </div>
                                </div>
                                <div class="form-group col-md-3">
                                  <div class="col-md-12  text-center" style="margin-top:18%;">
									  
                                    <button class="btn btn-primary UpdateAutoMin" id="update" name="update" type="button">Update</button>
                           
                                  </div>
                                </div>
                            </div>
                             
                          </div> 
                        </div>
                        
                         

                        </form>
                      
                      
                      </div>  
          	  </div>
      	  </div>
  	
		   
		   
		    <div class="row layout-top-spacing">
              <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
               
                 <div class="statbox widget box box-shadow"style="margin-bottom:10px;">
                     
                        <form id="settingsFormMaster" method="post" role="form" >
                          <div class="col-md-12 align-self-center">
                            <div class="row">
                              <div class="row col-md-12">
                                <div class="form-group col-md-12">
                                  <label>Master Address</label>
                                </div>
                                <div class="form-group col-md-5">
                                  <div class="col-md-12">
									  <label>BTC Master Address *</label>
                                    <input type="text"  name="btc_master" id="btc"  class="form-control" value="<?=$btc_masteraddress?>" placeholder="">
                                  </div>
                                </div>
                                <div class="form-group col-md-5">
                                  <div class="col-md-12">
									  <label>ETH Master Address *</label>
                                    <input type="text"  name="eth_master" id="usdt"  class="form-control" value="<?=$eth_masteraddress?>" placeholder="">    
                                  </div>
                                </div>
                                
                                <div class="form-group col-md-2">
                                  <div class="col-md-12  text-center" style="margin-top:18%;">
									  
                                    <button class="btn btn-primary UpdateAutoMin" id="updateMaster" name="updateMaster" type="button">Update</button>
                           
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

  <script type="text/javascript">
	  $(document).ready(function(){
		  
		  
			 $('#update').click(function(){
			  
				  postdata = $('#settingsForm').serializeArray();
				  //alert(postdata);return false;
				  loadingoverlay('info','Please Wait','Please Wait');
				
				  $.post('<?=BASEURL;?>Settings/Update/',postdata,function(response){ 
					
					  newResp  = JSON.parse(response);
						if(newResp['status'] == 'success'){
						  openSuccess(newResp['response'])
						}else{ 
							  loadingoverlay('error','',newResp['response']);
						}
				  });
				  return false;
				});
				
				$('#updateMaster').click(function(){
					postdata = $('#settingsFormMaster').serializeArray();
					loadingoverlay('info','Please Wait','Please Wait');
					
					$.post('<?=BASEURL;?>Settings/UpdateAddress/',postdata,function(response){ 
					
						  newResp  = JSON.parse(response);
							if(newResp['status'] == 'success'){
							  openSuccess(newResp['response'])
							}else{ 
								  loadingoverlay('error','',newResp['response']);
							}
					  });
					  return false;
					});
			});

</script>
           


