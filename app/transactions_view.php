<?php
// This script and data application were generated by AppGini 5.93
// Download AppGini for free from https://bigprof.com/appgini/download/

	$currDir = dirname(__FILE__);
	include_once("{$currDir}/lib.php");
	@include_once("{$currDir}/hooks/transactions.php");
	include_once("{$currDir}/transactions_dml.php");

	// mm: can the current member access this page?
	$perm = getTablePermissions('transactions');
	if(!$perm['access']) {
		echo error_message($Translation['tableAccessDenied'], false);
		echo '<script>setTimeout(function() { window.location = "index.php?signOut=1"; }, 2000);</script>';
		exit;
	}

	$x = new DataList;
	$x->TableName = 'transactions';

	// Fields that can be displayed in the table view
	$x->QueryFieldsTV = [
		"`transactions`.`id`" => "id",
		"IF(    CHAR_LENGTH(`kids1`.`name`), CONCAT_WS('',   `kids1`.`name`), '') /* Kid */" => "kid",
		"if(`transactions`.`date`,date_format(`transactions`.`date`,'%d/%m/%Y %h:%i %p'),'')" => "date",
		"`transactions`.`amount`" => "amount",
		"`transactions`.`description`" => "description",
		"`transactions`.`balance`" => "balance",
	];
	// mapping incoming sort by requests to actual query fields
	$x->SortFields = [
		1 => '`transactions`.`id`',
		2 => '`kids1`.`name`',
		3 => '`transactions`.`date`',
		4 => '`transactions`.`amount`',
		5 => 5,
		6 => '`transactions`.`balance`',
	];

	// Fields that can be displayed in the csv file
	$x->QueryFieldsCSV = [
		"`transactions`.`id`" => "id",
		"IF(    CHAR_LENGTH(`kids1`.`name`), CONCAT_WS('',   `kids1`.`name`), '') /* Kid */" => "kid",
		"if(`transactions`.`date`,date_format(`transactions`.`date`,'%d/%m/%Y %h:%i %p'),'')" => "date",
		"`transactions`.`amount`" => "amount",
		"`transactions`.`description`" => "description",
		"`transactions`.`balance`" => "balance",
	];
	// Fields that can be filtered
	$x->QueryFieldsFilters = [
		"`transactions`.`id`" => "ID",
		"IF(    CHAR_LENGTH(`kids1`.`name`), CONCAT_WS('',   `kids1`.`name`), '') /* Kid */" => "Kid",
		"`transactions`.`date`" => "Date",
		"`transactions`.`amount`" => "Amount",
		"`transactions`.`description`" => "Description",
		"`transactions`.`balance`" => "Balance",
	];

	// Fields that can be quick searched
	$x->QueryFieldsQS = [
		"`transactions`.`id`" => "id",
		"IF(    CHAR_LENGTH(`kids1`.`name`), CONCAT_WS('',   `kids1`.`name`), '') /* Kid */" => "kid",
		"if(`transactions`.`date`,date_format(`transactions`.`date`,'%d/%m/%Y %h:%i %p'),'')" => "date",
		"`transactions`.`amount`" => "amount",
		"`transactions`.`description`" => "description",
		"`transactions`.`balance`" => "balance",
	];

	// Lookup fields that can be used as filterers
	$x->filterers = ['kid' => 'Kid', ];

	$x->QueryFrom = "`transactions` LEFT JOIN `kids` as kids1 ON `kids1`.`id`=`transactions`.`kid` ";
	$x->QueryWhere = '';
	$x->QueryOrder = '';

	$x->AllowSelection = 1;
	$x->HideTableView = ($perm['view'] == 0 ? 1 : 0);
	$x->AllowDelete = $perm['delete'];
	$x->AllowMassDelete = true;
	$x->AllowInsert = $perm['insert'];
	$x->AllowUpdate = $perm['edit'];
	$x->SeparateDV = 0;
	$x->AllowDeleteOfParents = 0;
	$x->AllowFilters = 1;
	$x->AllowSavingFilters = (getLoggedAdmin() !== false);
	$x->AllowSorting = 1;
	$x->AllowNavigation = 1;
	$x->AllowPrinting = 1;
	$x->AllowPrintingDV = 1;
	$x->AllowCSV = 1;
	$x->RecordsPerPage = 10;
	$x->QuickSearch = 1;
	$x->QuickSearchText = $Translation['quick search'];
	$x->ScriptFileName = 'transactions_view.php';
	$x->RedirectAfterInsert = 'transactions_view.php?SelectedID=#ID#';
	$x->TableTitle = 'Transactions';
	$x->TableIcon = 'resources/table_icons/table_money.png';
	$x->PrimaryKey = '`transactions`.`id`';
	$x->DefaultSortField = '`transactions`.`date`';
	$x->DefaultSortDirection = 'desc';

	$x->ColWidth = [150, 150, 150, 150, 150, ];
	$x->ColCaption = ['Kid', 'Date', 'Amount', 'Description', 'Balance', ];
	$x->ColFieldName = ['kid', 'date', 'amount', 'description', 'balance', ];
	$x->ColNumber  = [2, 3, 4, 5, 6, ];

	// template paths below are based on the app main directory
	$x->Template = 'templates/transactions_templateTV.html';
	$x->SelectedTemplate = 'templates/transactions_templateTVS.html';
	$x->TemplateDV = 'templates/transactions_templateDV.html';
	$x->TemplateDVP = 'templates/transactions_templateDVP.html';

	$x->ShowTableHeader = 1;
	$x->TVClasses = "";
	$x->DVClasses = "";
	$x->HasCalculatedFields = true;
	$x->AllowConsoleLog = false;
	$x->AllowDVNavigation = true;

	// mm: build the query based on current member's permissions
	$DisplayRecords = $_REQUEST['DisplayRecords'];
	if(!in_array($DisplayRecords, ['user', 'group'])) { $DisplayRecords = 'all'; }
	if($perm['view'] == 1 || ($perm['view'] > 1 && $DisplayRecords == 'user' && !$_REQUEST['NoFilter_x'])) { // view owner only
		$x->QueryFrom .= ', `membership_userrecords`';
		$x->QueryWhere = "WHERE `transactions`.`id`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='transactions' AND LCASE(`membership_userrecords`.`memberID`)='" . getLoggedMemberID() . "'";
	} elseif($perm['view'] == 2 || ($perm['view'] > 2 && $DisplayRecords == 'group' && !$_REQUEST['NoFilter_x'])) { // view group only
		$x->QueryFrom .= ', `membership_userrecords`';
		$x->QueryWhere = "WHERE `transactions`.`id`=`membership_userrecords`.`pkValue` AND `membership_userrecords`.`tableName`='transactions' AND `membership_userrecords`.`groupID`='" . getLoggedGroupID() . "'";
	} elseif($perm['view'] == 3) { // view all
		// no further action
	} elseif($perm['view'] == 0) { // view none
		$x->QueryFields = ['Not enough permissions' => 'NEP'];
		$x->QueryFrom = '`transactions`';
		$x->QueryWhere = '';
		$x->DefaultSortField = '';
	}
	// hook: transactions_init
	$render = true;
	if(function_exists('transactions_init')) {
		$args = [];
		$render = transactions_init($x, getMemberInfo(), $args);
	}

	if($render) $x->Render();

	// hook: transactions_header
	$headerCode = '';
	if(function_exists('transactions_header')) {
		$args = [];
		$headerCode = transactions_header($x->ContentType, getMemberInfo(), $args);
	}

	if(!$headerCode) {
		include_once("{$currDir}/header.php"); 
	} else {
		ob_start();
		include_once("{$currDir}/header.php");
		echo str_replace('<%%HEADER%%>', ob_get_clean(), $headerCode);
	}

	echo $x->HTML;

	// hook: transactions_footer
	$footerCode = '';
	if(function_exists('transactions_footer')) {
		$args = [];
		$footerCode = transactions_footer($x->ContentType, getMemberInfo(), $args);
	}

	if(!$footerCode) {
		include_once("{$currDir}/footer.php"); 
	} else {
		ob_start();
		include_once("{$currDir}/footer.php");
		echo str_replace('<%%FOOTER%%>', ob_get_clean(), $footerCode);
	}
