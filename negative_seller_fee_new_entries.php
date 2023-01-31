<?php
/*==========================================================================*\
|| ######################################################################## ||
|| # ILance Marketplace Software 3.2.0 Build 1352
|| # -------------------------------------------------------------------- # ||
|| # Customer License # LuLTJTmo23V1ZvFIM-KH-jOYZjfUFODRG-mPkV-iVWhuOn-b=L
|| # -------------------------------------------------------------------- # ||
|| # Copyright ©2000–2010 ILance Inc. All Rights Reserved.                # ||
|| # This file may not be redistributed in whole or significant part.     # ||
|| # ----------------- ILANCE IS NOT FREE SOFTWARE ---------------------- # ||
|| # http://www.ilance.com | http://www.ilance.com/eula	| info@ilance.com # ||
|| # -------------------------------------------------------------------- # ||
|| ######################################################################## ||
\*==========================================================================*/

echo "test";

// #### load required phrase groups ############################################
$phrase['groups'] = array(
'administration'
);

// #### load required javascript ###############################################
$jsinclude = array(
'functions',
'ajax',
'inline',
'cron',
'autocomplete',
'search',
'tabfx',
'jquery',
'modal',
'yahoo-jar', 
'flashfix'
);
// #### setup script location ##################################################
define('LOCATION', 'admin');

// #### require backend ########################################################
require_once('./../functions/config.php');
$ilance->accounting = construct_object('api.accounting');
$ilance->bid = construct_object('api.bid');
$ilance->bid_lowest_unique = construct_object('api.bid_lowest_unique');

// #### setup default breadcrumb ###############################################
$navcrumb = array($ilpage['dashboard'] => $ilcrumbs[$ilpage['dashboard']]); 
$area_title = $phrase['_admin_cp_dashboard'];
$page_title = SITE_NAME . ' - ' . $phrase['_admin_cp_dashboard'];

if (!empty($_SESSION['ilancedata']['user']['userid']) AND $_SESSION['ilancedata']['user']['userid'] > 0 AND $_SESSION['ilancedata']['user']['isstaff'] == '1')
{

 //global $ilance, $phrase, $ilconfig, $ilpage;
$ilance->accounting = construct_object('api.accounting');
$ilance->accounting_fees = construct_object('api.accounting_fees');

$sql_projects = $ilance->db->query("SELECT c.coin_id,c.user_id,p.project_id,p.user_id,p.winner_user_id,p.currentprice
   
FROM
    " . DB_PREFIX . "coins c
LEFT JOIN " . DB_PREFIX . "projects p on  p.project_id = c.coin_id AND haswinner = '1' AND filtered_auctiontype = 'regular' AND winner_user_id > '0'
WHERE
    c.coin_id IN(1277181) AND c.fvf_id = 23");
if ($ilance->db->num_rows($sql_projects) > 0)
{		
	while($res = $ilance->db->fetch_array($sql_projects))
	{

	
	 $res['user_id'] = $res['user_id'];

	  $res['project_id'] =$res['project_id'];

										
																																						 
	$sql_bids = $ilance->db->query(" SELECT *
	FROM " . DB_PREFIX . "project_bids
	WHERE project_id = '" . $res['project_id'] . "' and bidstatus='awarded'
	", 0, null, __FILE__, __LINE__);

	$res_bids = $ilance->db->fetch_array($sql_bids);		    

	$ilance->accounting_fees->construct_final_value_fee_new($res_bids['bid_id'], $res['user_id'], $res['project_id'], 'charge', 'product');



	}
}


}
else
{
refresh($ilpage['login'] . '?redirect=' . urlencode(SCRIPT_URI), HTTPS_SERVER_ADMIN. $ilpage['login'] . '?redirect=' . urlencode(SCRIPT_URI));
exit();
}
