<?php
	// This script and data application were generated by AppGini 23.11
	// Download AppGini for free from https://bigprof.com/appgini/download/

	include_once(__DIR__ . '/settings-manager.php');
	include_once(__DIR__ . '/datalist.php');
	include_once(__DIR__ . '/incCommon.php');

	detect_config();
	migrate_config();

	checkAppRequirements();

	ob_start();
	initSession();

	// check if membership system exists
	setupMembership();

	// silently apply db changes, if any
	@include_once(__DIR__ . '/updateDB.php');

	// include global hook functions
	@include_once(__DIR__ . '/hooks/__global.php');

	// do we have a login request?
	Authentication::signIn();

	// convert expanded sorting variables, if provided, to SortField and SortDirection
	$postedOrderBy = [];
	for($i = 0; $i < maxSortBy; $i++) {
		if(Request::val("OrderByField$i")) {
			$sd = (Request::val("OrderDir$i") == 'desc' ? 'desc' : 'asc');
			if($sfi = intval(Request::val("OrderByField$i"))) {
				$postedOrderBy[] = [$sfi => $sd];
			}
		}
	}
	if(count($postedOrderBy)) {
		$_REQUEST['SortField'] = '';
		$_REQUEST['SortDirection'] = '';
		foreach($postedOrderBy as $obi) {
			$sfi = ''; $sd = '';
			foreach($obi as $sfi => $sd);
			$_REQUEST['SortField'] .= "$sfi $sd,";
		}
		$_REQUEST['SortField'] = substr(Request::val('SortField'), 0, -2 - strlen($sd));
		$_REQUEST['SortDirection'] = $sd;
	} elseif(Request::val('apply_sorting')) {
		/* no sorting and came from filters page .. so clear sorting */
		$_REQUEST['SortField'] = $_REQUEST['SortDirection'] = '';
	}

	// include nav menu links
	@include_once(__DIR__ . '/hooks/links-navmenu.php');
