<?php
use inc\Raise;
$this->mainTitle ='Ticketing System';

?>
<style>
   
.chat-system .chat-box .chat-conversation,#statusChange{
   position: relative;
    width: 100%;
    margin-bottom: 50px;
    text-align: center;
    }
.dot{
    background: #1b55e2;
    border-radius: 50%;
    position: absolute;
    padding: 6px;
    margin-left: 5px;
}
    </style>
        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">

                <div class="widget-content widget-content-area br-6 layout-top-spacing">
                    <div class="row">
                        <div class="row col-md-12 col-xs-12">
                            <form id="userForm" class="row col-md-12" method="post" action="<?=BASEURL;?>Ticket/chat/">
                                  <div class="form-group col-sm-2">
                                        <input type="text" id="datefrom" class="form-control" placeholder="From Date" name="datefrom" onchange="selfrom();">
                                  </div>
                                  <div class="form-group col-sm-2">
                                        <input type="text" class="form-control" max="" id="dateto" name="dateto" value="" placeholder="To Date" onchange="selto();">
                                  </div>
                                  
                                  <div class="form-group col-sm-2 input-group">
                                        <input type="text" class="form-control" id="username" name="username" value="" placeholder="Username">
                                        <div class="input-group-append">
                                            <span class="input-group-text" style="cursor: pointer;" onclick="$('#username').val('');">x</span>
                                        </div>
                                  </div>
                                  
                                  <div class="form-group col-sm-2">
                                        <select type="option" name="status" class="form-control custom-select" id="status">
                                            <option value="">Ticket Status</option>
                                            <option value="0">New</option>
                                            <option value="1">Processing</option>
                                            <option value="2">Closed</option>
                                        </select>
                                  </div>
                                  <div class="form-group col-sm-2">
                                  <input type="submit" class="btn btn-success col-12 div_float" id="search" name="Search" value="<?=Raise::t('app','search_txt')?>">
                                     </div>
                            </form>
                        </div>
                        <div class="row col-md-12 col-lg-12">
                           
                                <div class="col-md-5 col-lg-5">
                                  <span class="badge badge-primary datebuttons"> <a class="date-yesterday" style="color:#fff;cursor: pointer;"><?=Raise::t('app','yesterday_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-today" style="color:#fff;cursor: pointer;"><?=Raise::t('app','today_txt')?></a> </span>
                                  <span class="badge badge-primary datebuttons"> <a class="date-seven" style="color:#fff;cursor: pointer;"><?=Raise::t('app','7days_txt')?></a> </span>
                                      
                                </div>
                        </div>
                    </div>
                </div>

                <div class="chat-section layout-top-spacing">
                    <div class="row">

                        <div class="col-xl-12 col-lg-12 col-md-12">

                            <div class="chat-system">
                                <div class="hamburger"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu mail-menu d-lg-none"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>
                                <div class="user-list-box">
                                    <!--<div class="search hidden" >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        <form id="ticket_form" method="post"action="<?=BASEURL;?>Ticket/chat/">
                                        <input type="text" id="search" name="Tid"  class="form-control" placeholder="Search" />
                                    </div>-->
                                    <div class="people">
                                        <?php 
                                       foreach($chatlists as $chatlist){?>
                                         <div class="person" data-uid="<?=$chatlist['userid'];?>"data-tid="<?=$chatlist['tid'];?>">
                                            <div class="user-info">
                                                <div class="f-head">
                                                    <?php if(empty($chatlist['Status'])){ echo '<span class="dot"></span>';}?>
                                                    <img src="<?=WEB_PATH?>assets/img/90x90.jpg" alt="avatar">
                                                </div>
                                                <div class="f-body">
                                                    <div class="meta-info">
                                                        <span class="user-name" data-name="Nia Hillyer"><?=$chatlist['title'];?></span>
                                                       <!--  <span class="user-meta-time"><?=$chatlist['ticket'];?></span>  -->
                                                    </div>
                                                    <span class="preview"><?=$chatlist['user'];?></span>
                                                    <!-- <span class="preview"><?=$chatlist['category'];?></span>  -->
                                                </div>
                                            </div>
                                        </div> 
                                        <?php }?>
                                                                          
                                    </div>
                                </div>
                                <div class="chat-box">

                                    <div class="chat-not-selected">
                                        <p> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> Click User To Chat</p>
                                    </div>

                                    <div class="overlay-phone-call">
                                        <div class="">
                                            <div class="calling-user-info">
                                                <div class="">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle go-back-chat"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                                    <span class="user-name"></span>
                                                    <span class="call-status">Calling...</span>
                                                </div>
                                            </div>

                                            <div class="calling-user-img">
                                                <div class="">
                                                    <img src="<?=WEB_PATH?>assets/img/90x90.jpg" alt="dynamic-image">
                                                </div>

                                                <div class="timer"><label class="minutes">00</label> : <label class="seconds">00</label></div>
                                            </div>

                                            <div class="calling-options">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video switch-to-video-call"><polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic switch-to-microphone"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus add-more-caller"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-off cancel-call"><path d="M10.68 13.31a16 16 0 0 0 3.41 2.6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7 2 2 0 0 1 1.72 2v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.42 19.42 0 0 1-3.33-2.67m-2.67-3.34a19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91"></path><line x1="23" y1="1" x2="1" y2="23"></line></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="overlay-video-call">
                                        <img src="<?=WEB_PATH?>assets/img/175x115.jpg" class="video-caller" alt="video-chat">
                                        <div class="">
                                            <div class="calling-user-info">
                                                <div class="d-flex">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle go-back-chat"><circle cx="12" cy="12" r="10"></circle><polyline points="12 8 8 12 12 16"></polyline><line x1="16" y1="12" x2="8" y2="12"></line></svg>
                                                    <div class="">
                                                        <span class="user-name"></span>
                                                        <div class="timer"><label class="minutes">00</label> : <label class="seconds">00</label></div>
                                                    </div>
                                                    <span class="call-status">Calling...</span>
                                                </div>
                                            </div>

                                            <div class="calling-user-img">
                                                <div class="">
                                                    <img src="<?=WEB_PATH?>assets/img/90x90.jpg" alt="dynamic-image">
                                                </div>

                                            </div>
                                            <div class="calling-options">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone switch-to-phone-call"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mic switch-to-microphone"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path><path d="M19 10v2a7 7 0 0 1-14 0v-2"></path><line x1="12" y1="19" x2="12" y2="23"></line><line x1="8" y1="23" x2="16" y2="23"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus add-more-caller"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off cancel-call"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="chat-box-inner">
                                        <div class="chat-meta-user">
                                            <div class="current-chat-user-name"><span><img src="<?=WEB_PATH?>assets/img/90x90.jpg" alt="dynamic-image"><span class="name"></span></span></div>

                                            <div class="chat-action-btn align-self-center">
                                               
                                            </div>
                                        </div>
                                        <div class="chat-conversation-box" id="chat-conversation-box">
                                            <!-- <div id="chat-conversation-box-scroll" class="chat-conversation-box-scroll">
                                                  <div class="chat" data-chat="person1">
                                                    <div class="conversation-start">
                                                        <span>Today, 6:48 AM</span>
                                                    </div>
                                                    <div class="bubble you">
                                                        Hello,
                                                    </div>
                                                    <div class="bubble you">
                                                        It's me.
                                                    </div>
                                                    <div class="bubble you">
                                                        I have a question regarding project.
                                                    </div>
                                                </div>  
                                                         
                                                                             
                                                
                                                
                                                
                                               
                                            </div> -->

                                        </div>

                                        <div class="chat-footer">
                                            <div class="chat-input">
                                                <form class="chat-form" action="javascript:void(0);">
                                                    <!-- <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg> -->
                                                    <input type="text" class="mail-write-box form-control" placeholder="Message"/>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

        <input type="hidden" value="" id="ticket_list_id" >
		<input type="hidden" value="" id="user_id" >

<script type="text/javascript">

$(function () { 

 $('#accordionExample').find('li a').attr("data-active","false");
  $('#ticket').attr("data-active","true");

  });

    var f1 = flatpickr(document.getElementById('datefrom'),{
      dateFormat:"d-m-Y",
    });
    var f2 = flatpickr(document.getElementById('dateto'),{
      dateFormat:"d-m-Y",
    });

    $('#status').val("<?=$status;?>");
    $('#datefrom').val("<?=$datefrom;?>");
    $('#dateto').val("<?=$dateto;?>");
    $('#username').val("<?=$username;?>");
    

$('.user-list-box .person').on('click', function(event) {
    if ($(this).hasClass('.active')) {
        return false;
    } else {
        var userId=$(this).attr('data-uid');
        var TicketId=$(this).attr('data-tid');
		$('#ticket_list_id').val(TicketId);
		$('#user_id').val(userId);
     $.ajax
        ({
            type:"POST",
            url:"<?=BASEURL?>Ticket/getUserMessage",
            data:{'userId' :userId,'ticktId':TicketId},
            datatype:'html',
            success:function(data) 
            {
               $("#chat-conversation-box").html(data);
               }
        });
        
        var findChat = $(this).attr('data-chat');
        var personName = $(this).find('.user-name').text();
        var personImage = $(this).find('img').attr('src');
        var hideTheNonSelectedContent = $(this).parents('.chat-system').find('.chat-box .chat-not-selected').hide();
        var showChatInnerContent = $(this).parents('.chat-system').find('.chat-box .chat-box-inner').show();

        if (window.innerWidth <= 767) {
          $('.chat-box .current-chat-user-name .name').html(personName.split(' ')[0]);
        } else if (window.innerWidth > 767) {
          $('.chat-box .current-chat-user-name .name').html(personName);
        }
        $('.chat-box .current-chat-user-name img').attr('src', personImage);
        $('.chat').removeClass('active-chat');
        $('.user-list-box .person').removeClass('active');
        $('.chat-box .chat-box-inner').css('height', '100%');
        $(this).addClass('active');
        $('.chat[data-chat = '+findChat+']').addClass('active-chat');
    }
    if ($(this).parents('.user-list-box').hasClass('user-list-box-show')) {
      $(this).parents('.user-list-box').removeClass('user-list-box-show');
    }
    $('.chat-meta-user').addClass('chat-active');
    $('.chat-box').css('height', 'calc(100vh - 233px)');
    $('.chat-footer').addClass('chat-active');

  const ps = new PerfectScrollbar('.chat-conversation-box', {
    suppressScrollX : true
  });

  const getScrollContainer = document.querySelector('.chat-conversation-box');
  getScrollContainer.scrollTop =100;

 
});


window.setInterval(function(){
	
  /// call your function here
        var userId   = $('#user_id').val();
        var TicketId =$('#ticket_list_id').val();
		//$('#ticket_list_id').val(TicketId);
	if(userId!=""){
     $.ajax
        ({
            type:"POST",
            url:"<?=BASEURL?>Ticket/getUserMessage1",
            data:{'userId' :userId,'ticktId':TicketId},
            datatype:'html',
            success:function(data) 
            {
				if(data!=""){
					$("#chat-conversation-box").html(data);
				}
               }
        });
        
       
	}
  
}, 1000);

window.setInterval(function(){
	
  /// call your function here
       var status   = $('#status').val();
	   var datefrom = $('#datefrom').val();
	   var dateto   = $('#dateto').val();
	   var username = $('#username').val();
		
	
     $.ajax
        ({
            type:"POST",
            url:"<?=BASEURL?>Ticket/getChat1",
            data:{'status' :status,'datefrom':datefrom,'dateto':dateto,'username':username},
            datatype:'html',
            success:function(data) 
            {
				if(data!=""){
					$(".people").html(data);
					
						$('.user-list-box .person').on('click', function(event) {
						if ($(this).hasClass('.active')) {
							return false;
						} else {
							var userId=$(this).attr('data-uid');
							var TicketId=$(this).attr('data-tid');
							$('#ticket_list_id').val(TicketId);
							$('#user_id').val(userId);
						 $.ajax
							({
								type:"POST",
								url:"<?=BASEURL?>Ticket/getUserMessage",
								data:{'userId' :userId,'ticktId':TicketId},
								datatype:'html',
								success:function(data) 
								{
								   $("#chat-conversation-box").html(data);
								   }
							});
							
							var findChat = $(this).attr('data-chat');
							var personName = $(this).find('.user-name').text();
							var personImage = $(this).find('img').attr('src');
							var hideTheNonSelectedContent = $(this).parents('.chat-system').find('.chat-box .chat-not-selected').hide();
							var showChatInnerContent = $(this).parents('.chat-system').find('.chat-box .chat-box-inner').show();

							if (window.innerWidth <= 767) {
							  $('.chat-box .current-chat-user-name .name').html(personName.split(' ')[0]);
							} else if (window.innerWidth > 767) {
							  $('.chat-box .current-chat-user-name .name').html(personName);
							}
							$('.chat-box .current-chat-user-name img').attr('src', personImage);
							$('.chat').removeClass('active-chat');
							$('.user-list-box .person').removeClass('active');
							$('.chat-box .chat-box-inner').css('height', '100%');
							$(this).addClass('active');
							$('.chat[data-chat = '+findChat+']').addClass('active-chat');
						}
						if ($(this).parents('.user-list-box').hasClass('user-list-box-show')) {
						  $(this).parents('.user-list-box').removeClass('user-list-box-show');
						}
						$('.chat-meta-user').addClass('chat-active');
						$('.chat-box').css('height', 'calc(100vh - 233px)');
						$('.chat-footer').addClass('chat-active');

					  const ps = new PerfectScrollbar('.chat-conversation-box', {
						suppressScrollX : true
					  });

					  const getScrollContainer = document.querySelector('.chat-conversation-box');
					  getScrollContainer.scrollTop =100;

					 
					});
					
					
				}
               }
        });
        
  
	
  
}, 5000);

$('.mail-write-box').on('keydown',function(event) {
    if(event.keyCode ==13) {
       
        
        var chatMessageValue = $('.mail-write-box').val();
        var userId=$('#live').attr('data-id');
        var TicktId=$('#live').attr('data-tid');
        
        if (chatMessageValue ==='') { return; }
        else{
              $.ajax
        ({
            type:"POST",
            url:"<?=BASEURL?>Ticket/Sendmessage",
            data:{'userId' :userId,'reply_type':1,'message':chatMessageValue,'read_status':0,'TicktId':TicktId},
            datatype:'html',
            success:function(data) 
            {
                $messageHtml = '<div class="bubble me">' + chatMessageValue + '</div>';
                var appendMessage = $('.mail-write-box').parents('.chat-system').find('#live').append($messageHtml);
                const getScrollContainer = document.querySelector('.chat-conversation-box');
                getScrollContainer.scrollTop = getScrollContainer.scrollHeight;
                var clearChatInput = $('.mail-write-box').val('');

            }
        });

        }
        
    }
    
});

function changeTicket(userId,TicketId,reopen){
    
      $.post('<?= BASEURL ?>Ticket/UpdateTicket/',{'userId':userId,'TicketId':TicketId,'reopen':reopen},function(response){ //alert(response);
             newResp = JSON.parse(response);
              
            if(newResp['status'] == 'success'){
                   openSuccess(newResp['response'])
            }else{ 
                  loadingoverlay('error','Error',newResp['response']);
            }
      });
}

$('#search').on('keyup', function() {
    var rex = new RegExp($(this).val(), 'i');
    $('.people .person').hide();
    $('.people .person').filter(function() {
        return rex.test($(this).text());
    }).show();
});

</script>
