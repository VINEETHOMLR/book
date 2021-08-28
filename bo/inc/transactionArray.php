<?php

$transactionArray = array
(  
	"1" => "Deposit",
	"2" => "Withdraw",
	"3" => "Coinswap",
	"4" => "Transfer",
	"31" => "System Credit", //admin credit
	"32" => "System Debit", //admin debit
	
);

$creditArray = array(0=>"Credit",1=>"Debit");

$wallet_decimal_limits = array
(   
    "usd" => "2",
	"btc" => "8",
	"usdt" => "6",
	"eth" => "6",
	"itt" =>"6",
);

$wallet_groups_array = array
(   
    "1" => "BTC group",
	"2" => "ETH group",
);
?>