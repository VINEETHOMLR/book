<?php
    use inc\Raise;
     $servicesArray = $_SESSION['INF_privilages'];
     $servicesArray = explode(",", $servicesArray[0]);
     $servicesArray = array_filter($servicesArray);

     $role = $_SESSION['INF_role'];
?> 

<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            
           <!--  <li class="menu">
                <a href="<?=BASEURL?>" id="dashboard" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                <span>Dashboard</span>
                    </div>
                </a>
            </li> -->


            <li class="menu">
                <a href="<?=BASEURL;?>Shipment/Index/" id="shipmentlist" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                <span>Shipment</span>
                    </div>
                </a>
            </li> 

            <li class="menu">
                <a href="#userNav" id="userMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Driver Management</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="userNav" data-parent="#accordionExample">
                    
                    <li id="userList">
                        <a href="<?=BASEURL;?>Driver/Index/"> Driver List </a>
                    </li>
                
                </ul>
            </li>



       <!--  <?php if( in_array(1, $servicesArray) || in_array(2, $servicesArray) || ($role==1)) { ?>

            <li class="menu">
                <a href="#adminNav" id="adminMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Admin Management</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="adminNav" data-parent="#accordionExample">
                    <?php if( in_array(1, $servicesArray) || ($role==1) ){ ?>
                    <li id="adminList">
                        <a href="<?=BASEURL;?>Admin/Index/"> Admin List </a>
                    </li>
                    <?php } if( $role==1){ ?>
                    <li id="subAdminNav">
                        <a href="<?=BASEURL;?>Admin/SubadminActivity/">Activity Log</a>
                    </li>
                    <?php } if( in_array(2, $servicesArray) || ($role==1)){ ?>
                    <li id="serviceNav">
                        <a href="<?=BASEURL;?>Service/Index/"> Service Group </a>
                    </li>
                    <?php } ?>
                </ul>
            </li>
        <?php } if( in_array(3, $servicesArray) || in_array(22, $servicesArray) || ($role==1)) { ?>
            <li class="menu">
                <a href="#userNav" id="userMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>User Management</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="userNav" data-parent="#accordionExample">
                    <?php if( in_array(3, $servicesArray) || ($role==1)){ ?>
                    <li id="userList">
                        <a href="<?=BASEURL;?>Users/Index/"> User List </a>
                    </li>
                    <?php } if( in_array(4, $servicesArray) || ($role==1)){ ?>
                    <li id="userActivity">
                        <a href="<?=BASEURL;?>Users/Activity/"> User Activity </a>
                    </li>
                    <?php } if( in_array(7, $servicesArray) || ($role==1)){ ?>
                        <li id="userKyc">
                        <a href="<?=BASEURL;?>Kyc/Index/"> KYC Verification </a>
                    </li>
                <?php } ?>
                </ul>
            </li>
        
             <?php } if( in_array(16, $servicesArray) || in_array(17, $servicesArray)|| in_array(18, $servicesArray) || in_array(19, $servicesArray)  || ($role==1)) { ?>
             <li class="menu">
                <a href="#wallethistoryNav" id="walletHisMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                        <span>Wallet History</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="wallethistoryNav" data-parent="#accordionExample">
                    <?php if( in_array(16, $servicesArray) || ($role==1)) { ?>
                    <li id="btcWal">
                        <a href="<?=BASEURL;?>Wallet/BTC/">BTC Wallet </a>
                    </li>
                    <?php } if( in_array(17, $servicesArray) || ($role==1)) { ?>
                    <li id="usdtWal">
                        <a href="<?=BASEURL;?>Wallet/USDT/"> USDT Wallet </a>
                    </li>
                    <?php }if( in_array(18, $servicesArray) || ($role==1)) { ?>
                    <li id="ethWal">
                        <a href="<?=BASEURL;?>Wallet/ETH/"> ETH Wallet </a>
                    </li>
                <?php }if( in_array(19, $servicesArray) || ($role==1)) { ?>
                    <li id="ittWal">
                        <a href="<?=BASEURL;?>Wallet/ITT/"> ITT Wallet </a>
                    </li>
                     <?php }?>
                </ul>
            </li>
             <?php } if( in_array(8, $servicesArray) || in_array(9, $servicesArray)|| in_array(11, $servicesArray)  || ($role==1)) { ?>
             <li class="menu">
                <a href="#transhistoryNav" id="TranshistoryMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Transaction History</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="transhistoryNav" data-parent="#accordionExample">
                    <?php if( in_array(8, $servicesArray) || ($role==1)) { ?>
                    <li id="withdrawhis">
                        <a href="<?=BASEURL;?>Withdraw/Index/">Withdraw History </a>
                    </li>
                    <?php } if( in_array(9, $servicesArray) || ($role==1)) { ?>
                    <li id="coinswaphis">
                        <a href="<?=BASEURL;?>CoinSwap/Index/"> Coinswap History </a>
                    </li>
                  <?php }if( in_array(11, $servicesArray) || ($role==1)) { ?>
                    <li id="deposithis">
                        <a href="<?=BASEURL;?>Deposit/Index/"> Deposit History </a>
                    </li>
                     <?php } if($role==1){ ?>
                    <li id="transferhis">
                        <a href="<?=BASEURL;?>Transfer/Index/"> Transfer History </a>
                    </li>
                     <?php }?>
                </ul>
            </li>
             <?php } if( in_array(14, $servicesArray) || in_array(15, $servicesArray) || ($role==1)) { ?>
             <li class="menu">
                <a href="#reportNav" id="ReportMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Report</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="reportNav" data-parent="#accordionExample">
                    <?php if( in_array(14, $servicesArray) || ($role==1)) { ?>
                    <li id="consolidate">
                        <a href="<?=BASEURL;?>Report/Consolidate/">Consolidate Report </a>
                    </li>
                    <?php } if( in_array(15, $servicesArray) || ($role==1)) { ?>
                    <li id="daywiseNav">
                        <a href="<?=BASEURL;?>Report/Daywise/">Daywise Report </a>
                    </li>
                  <?php }?>
                    
                </ul>
            </li>
            <?php } if( in_array(5, $servicesArray) || ($role==1)) { ?>
             <li class="menu">
                <a href="<?=BASEURL;?>Announcement/Index/" id="announcement" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                <span>Announcement</span>
                    </div>
                </a>
            </li> 
            <?php } if( in_array(6, $servicesArray) || ($role==1)) { ?>
             <li class="menu">
                <a href="<?=BASEURL?>Ticket/chat/" id="ticket" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                <span>Ticketing System</span>
                    </div>
                </a>
            </li>
            <?php }  if( in_array(12, $servicesArray) || ($role==1)) { ?>
             <li class="menu">
                <a href="<?=BASEURL;?>Game/Index/" id="gamelist" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                <span>Game</span>
                    </div>
                </a>
            </li> 
            <?php }if( in_array(13, $servicesArray) || ($role==1)) { ?>
             <li class="menu">
                <a href="<?=BASEURL;?>Coin/Index/" id="coinlist" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                <span>Coin</span>
                    </div>
                </a>
            </li> 
            <?php } if( in_array(10, $servicesArray) || ($role==1)) { ?> 
            <li class="menu">
                <a href="<?=BASEURL?>Download/Index/" id="export" aria-expanded="false" class="dropdown-toggle">
                   <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-square"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                                <span>Export</span>
                    </div>
                </a>
            </li>
            <?php } if($role==1) { ?> 

                <li class="menu">
                <a href="#settingsNav" id="settingsMenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-box"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        <span>Settings</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled" id="settingsNav" data-parent="#accordionExample">
                    <li id="settings">
                        <a href="<?=BASEURL;?>Settings/Index/"> Settings </a>
                    </li>
                    <li id="serviceSettings">
                        <a href="<?=BASEURL;?>Settings/serviceIndex/"> Service Settings </a>
                    </li>

                    
                </ul>
            </li>


            <?php }?> -->
             
        </ul>
    </nav>
</div>