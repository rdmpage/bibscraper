<?php

// http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200201006&journal_id=jtsb_cn

require_once(dirname(dirname(__FILE__)) . '/config.inc.php');
require_once(dirname(dirname(__FILE__)) . '/adodb5/adodb.inc.php');

//--------------------------------------------------------------------------------------------------
$db = NewADOConnection('mysql');
$db->Connect("localhost", 
	$config['db_user'] , $config['db_passwd'] , $config['db_name']);

// Ensure fields are (only) indexed by column name
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

$db->EXECUTE("set names 'utf8'"); 


require_once(dirname(dirname(__FILE__)) . '/lib.php');

require(dirname(dirname(__FILE__)) . '/simplehtmldom_1_5/simple_html_dom.php');

// 2010 issue 1
$urls = array(
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2328&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2333&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2337&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2338&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2354&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2356&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2357&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2358&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2363&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2364&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2366&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2368&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2369&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2372&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2382&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2397&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2400&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2415&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2424&journal_id=jtsb_cn'
);

$urls=array(

'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2301&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2335&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2352&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2361&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2362&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2367&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2374&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2378&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2380&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2381&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2385&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2390&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2393&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2396&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2402&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2407&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2408&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2409&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2410&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2411&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2413&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2416&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2417&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2418&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2422&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2423&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2425&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2433&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2435&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2437&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2440&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2441&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2442&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2443&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2444&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2445&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2450&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2458&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2461&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2466&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2468&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2471&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2474&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2479&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2480&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2481&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2482&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2484&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2486&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2490&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2495&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2496&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2497&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2500&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2502&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2507&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2513&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2516&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2525&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2526&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2529&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2531&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2534&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2535&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2536&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2539&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2541&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2542&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2543&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2568&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2569&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2578&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2591&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2610&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2636&journal_id=jtsb_cn'
);

$urls=array(
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2470&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2476&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2510&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2518&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2527&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2532&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2556&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2562&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2565&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2567&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2570&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2573&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2576&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2581&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2583&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2588&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2595&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2617&journal_id=jtsb_cn'
);

$urls=array(
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1986&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2018&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2028&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2036&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2054&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2061&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2074&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2076&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2077&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2082&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2084&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2087&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2090&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2094&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2099&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2101&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2102&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2103&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2104&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2105&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2107&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2108&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2109&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2112&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2114&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2116&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2120&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2121&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2123&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2124&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2125&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2126&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2137&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2141&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2143&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2145&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2151&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2156&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2161&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2162&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2163&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2165&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2166&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2168&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2170&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2174&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2175&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2177&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2178&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2180&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2183&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2185&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2187&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2189&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2190&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2191&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2192&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2193&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2197&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2200&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2201&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2202&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2205&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2206&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2211&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2215&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2218&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2219&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2221&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2222&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2226&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2229&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2231&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2232&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2233&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2236&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2245&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2246&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2247&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2248&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2249&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2251&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2272&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2276&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2283&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2284&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2286&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2287&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2289&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2290&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2293&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2296&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2299&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2304&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2308&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2311&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2313&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2320&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2323&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2326&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2327&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2329&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2330&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2339&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2340&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2344&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2398&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2439&journal_id=jtsb_cn'
);

$urls=array(
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1852&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1865&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1885&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1890&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1906&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1907&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1911&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1915&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1918&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1924&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1926&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1930&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1936&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1938&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1942&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1943&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1944&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1945&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1946&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1947&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1948&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1951&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1956&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1958&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1960&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1961&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1962&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1963&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1964&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1965&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1972&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1974&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1976&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1977&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1978&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1979&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1982&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1983&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1987&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1990&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1993&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1995&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1996&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1997&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=1998&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2000&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2004&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2008&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801001&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801002&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801003&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801004&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801005&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801006&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801007&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801008&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801009&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801010&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801011&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801012&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801013&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200801014&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802001&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802002&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802003&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802004&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802005&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802006&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802007&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802008&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802009&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802010&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802011&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802012&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802013&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802014&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802015&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802016&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802017&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200802018&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2009&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2010&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2012&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2017&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2019&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2020&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2023&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2034&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2037&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2039&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2044&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2047&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2048&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2053&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2060&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2064&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2067&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2069&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2072&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2075&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2078&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2173&journal_id=jtsb_cn');

$urls=array(
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070601&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070602&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070603&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070604&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070605&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070606&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070607&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070608&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070609&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070610&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070611&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070612&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070613&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070614&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070615&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=20070616&journal_id=jtsb_cn'
);

$urls=array(
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2661&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2664&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2666&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2671&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2678&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2681&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2683&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2686&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2687&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2690&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2693&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2695&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2703&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2708&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2730&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2767&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2775&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2776&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2892&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2896&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2899&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2907&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2908&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2912&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2916&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2917&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2918&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2921&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2925&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2926&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2927&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2930&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2955&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2957&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2968&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2969&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2971&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2972&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2974&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2975&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2976&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2978&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2980&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2987&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2992&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2994&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2998&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=2999&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3000&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3001&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3002&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3055&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3101&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3108&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3110&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3113&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3114&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3115&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3117&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3120&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3121&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3122&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3123&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3125&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3127&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3128&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3130&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3132&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3134&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3135&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3137&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3145&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3147&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3148&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3149&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3152&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3154&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3158&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3159&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3160&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3162&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3164&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3168&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3169&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3171&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3172&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3173&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3174&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3175&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3176&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3179&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3187&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3188&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3189&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3191&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3193&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3195&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3201&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3202&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3203&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3205&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3207&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3208&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3211&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3212&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3213&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3214&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3216&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3220&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3221&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3223&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3227&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3228&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3229&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3230&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3237&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3240&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3243&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3244&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3245&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3246&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3247&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3248&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3249&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3252&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3258&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3259&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3261&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3262&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3263&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3265&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3266&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3270&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3271&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3273&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3274&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3275&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3292&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3296&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3303&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3306&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3323&journal_id=jtsb_cn'
);

$urls=array(
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3267&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3269&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3279&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3282&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3287&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3288&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3289&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3290&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3295&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3299&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3301&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3302&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3304&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3305&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3307&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3308&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3311&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3312&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3315&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3317&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3319&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3322&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3324&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3325&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3327&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3330&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3331&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3333&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3334&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3338&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3339&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3340&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3342&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3343&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3344&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3345&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3347&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3348&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3351&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3353&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3355&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3357&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3359&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3361&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3362&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3363&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3366&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3367&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3368&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3370&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3372&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3373&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3374&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3375&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3376&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3377&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3378&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3379&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3381&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3382&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3383&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3385&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3386&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3389&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3390&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3392&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3393&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3394&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3395&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3397&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3400&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3402&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3403&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3404&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3407&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3408&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3409&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3411&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3413&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3419&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3420&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3422&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3424&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3425&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3430&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3431&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3438&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3442&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3446&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3450&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3457&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3463&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3467&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3480&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3481&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3483&journal_id=jtsb_cn',
'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=3510&journal_id=jtsb_cn'
);

$count = 0;

foreach ($urls as $url)
{
	
	echo "-- $url\n";
	
	//$h = file_get_contents('med.html');
	
	//echo $h;
	//echo "--------\n\n";

	$h = get($url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');
	
	//echo $h;
	
	if ($h != '')
	{

		$html = str_get_html($h);
		
		//echo $html;

		$guid = '';
		
		$pdf = '';
		
		$year = '';
		$volume = '';
		$spage = '';

		$keys = array();
		$values = array();

		$multilingual_keys = array();
		$multilingual_values = array();

		$have_authors = false;
		$have_abstract = false;
		
		$authors_en = array();
		$authors_zh = array();
		
		// Citation string
		$span = $html->find('span[id=ReferenceText]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			//echo $text . "\n";
			
			// 2002,10(1):33~34
			if (preg_match('/(?<year>[0-9]{4}),(?<volume>\d+)\((?<issue>\d+)\):(?<spage>\d+)(~(?<epage>\d+))?/u', $text, $m))
			{
				$keys[] = 'year';
				$values[] = $m['year'];
				
				$year = $m['year'];
				
				if ($m['volume'] != '')
				{
					$keys[] = 'volume';
					$values[] = $m['volume'];	
					
					$volume = $m['volume'];				
				}
				
		
				if ($m['issue'] != '')
				{
					$keys[] = 'issue';
					$values[] = preg_replace('/^0/', '', $m['issue']);					
				}
		
				$keys[] = 'spage';
				$values[] = $m['spage'];
				
				$spage = $m['spage'];		
		
				if ($m['epage'] != '')
				{
					$keys[] = 'epage';
					$values[] = $m['epage'];					
				}			
			}
		}
		// Authors Chinese
		$span = $html->find('span[id=ReferenceText]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			// echo $text . "\n";
			
			// 
			if (preg_match('/^(?<authors>(.*)(,.*))\./Uu', $text, $m))
			{
				$authors = $m['authors'];
				$authors = str_replace(',', ';', $authors);
								
				$language = 'zh';

				$kk = array();
				$vv = array();
				$kk[] = "`key`";
				$vv[] = '"authors"';

				$kk[] = 'language';
				$vv[] = '"' . $language . '"';
			
				$kk[] = 'value';
				$vv[] = '"' . addcslashes($authors, '"') . '"';

				$multilingual_keys[] = $kk;
				$multilingual_values[] = $vv;			
				
			}
		}		
		
		// Authors
		$span = $html->find('td[nowrap] a font[color=blue] u');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			//echo $text . "\n";
			
			//exit();
			
			$text = preg_replace('/，\s+/u', ';' , $text);
			
			$authors_en[] = $text;
		}		
		
		if (count($authors_en) > 0)
		{
			$language = 'en';
			
			$text = join(';', $authors_en);

			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"authors"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
		
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($text, '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;			
		}	
			
		
				
		
		// Abstract
		$span = $html->find('span[id=EnAbstract]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			//echo $text . "\n";
			
			$keys[] = 'abstract';
			$values[] = '"' . addcslashes($text, '"') . '"';
			
			$language = 'en';

			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"abstract"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
			
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($text, '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;			
			
		}		
		

		// DOI
		$span = $html->find('span[id=DOI]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			//echo $text . "\n";
			
			$guid = $text;
			
			$keys[] = 'doi';
			$values[] = '"' . addcslashes($text, '"') . '"';
			
			
		}		

		// PDF
		$span = $html->find('span[id=URL] a');
		foreach ($span as $p)
		{
			$text = $p->href;
			
			//echo $text . "\n";
			
			$text = 'http://jtsb.scib.ac.cn/jtsb_en/ch/reader/' . $text;
			
			$pdf = $text;

			$keys[] = 'pdf';
			$values[] = '"' . addcslashes($text, '"') . '"';
		}		
		
		// journal
		$keys[] = 'journal';
		$values[] = '"Journal of Tropical and Subtropical Botany"';
		
		
		$language = 'en';

		$kk = array();
		$vv = array();
		$kk[] = "`key`";
		$vv[] = '"journal"';

		$kk[] = 'language';
		$vv[] = '"' . $language . '"';
			
		$kk[] = 'value';
		$vv[] = '"Journal of Tropical and Subtropical Botany"';

		$multilingual_keys[] = $kk;
		$multilingual_values[] = $vv;			
		
		
		
		// 热带亚热带植物学报
		$language = 'zh';

		$kk = array();
		$vv = array();
		$kk[] = "`key`";
		$vv[] = '"journal"';

		$kk[] = 'language';
		$vv[] = '"' . $language . '"';
			
		$kk[] = 'value';
		$vv[] = '"热带亚热带植物学报"';

		$multilingual_keys[] = $kk;
		$multilingual_values[] = $vv;			
		
		
		
		// title (English)
		$span = $html->find('span[id=EnTitle]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			$keys[] = 'title';
			$values[] = '"' . addcslashes($text, '"') . '"';
			
			
			$language = 'en';

			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"title"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
				
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($text, '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;			
		}		

		// title (Chinese)
		$span = $html->find('span[id=FileTitle]');
		foreach ($span as $p)
		{
			$text = $p->plaintext;
			
			$language = 'zh';

			$kk = array();
			$vv = array();
			$kk[] = "`key`";
			$vv[] = '"title"';

			$kk[] = 'language';
			$vv[] = '"' . $language . '"';
				
			$kk[] = 'value';
			$vv[] = '"' . addcslashes($text, '"') . '"';

			$multilingual_keys[] = $kk;
			$multilingual_values[] = $vv;			
		}		
	
		$keys[] ='url';
		$values[] = '"' . addcslashes($url, '"') . '"';
	
		if ($guid == '')
		{
			$guid = $url;
		}
		/*
		if ($guid == '')
		{	
			$guid = md5(join('', $values));
		}
		*/
		
		// Expand DOI for more recent articles if we are adding info
		if (0)
		{
			if (preg_match('/(?<one>10.3969\/j.issn.1005-3395.[0-9]{4}\.)(?<two>\d)(?<three>\.\d+)/', $guid, $m))
			{
				$guid = $m['one'] . '0' . $m['two'] . $m['three'];
			}
		}
			
		$keys[] = 'guid';
		$values[] = '"' . $guid . '"';
		
		$keys[] = 'issn';
		$values[] = '"1005-3395"';


		//print_r($keys);
		//print_r($values);

		//print_r($multilingual_keys);
		//print_r($multilingual_values);
		
		// update
		if (1)
		{		
			/*
			if ($guid != '' && $pdf != '')
			{
				$sql = 'UPDATE publication SET pdf="' . $pdf . '" WHERE guid="' . $guid . '";';
				echo $sql . "\n";
			}
			*/
			if (
				($volume != '')
				&& ($spage != '')
				&& ($pdf != '')
				)
			{
				$sql = 'UPDATE publications SET pdf="' . $pdf . '" WHERE issn="1005-3395" AND volume="' . $volume . '" AND spage="' . $spage . '";';
				echo $sql . "\n";
			}			
		}
		
		// populate from scratch
		if (0) // in_array($reference->journal->volume, array(26,27))) 
		{
			$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') values('
				. join(',', $values) . ');';
			echo $sql . "\n";
		}
		
		if (
			($volume != '')
			&& ($spage != '')
			&& ($pdf != '')
			)
		{
			$sql = 'SELECT guid FROM publications WHERE issn="1005-3395" AND volume="' . $volume . '" AND spage="' . $spage . '";';
			$result = $db->Execute($sql);
			if ($result == false) die("failed [" . __LINE__ . "]: " . $sql);
			if ($result->NumRows() == 1)
			{
				$guid = $result->fields['guid'];
				
				echo "-- $guid\n";
				
				if ($guid != '')
				{
					$n = count($multilingual_keys);
					for($i =0; $i < $n; $i++)
					{
						$multilingual_keys[$i][] = 'guid';
						$multilingual_values[$i][] = '"' . $guid . '"';

						$sql = 'REPLACE INTO multilingual(' . join(',', $multilingual_keys[$i]) . ') values('
							. join(',', $multilingual_values[$i]) . ');';
						echo $sql . "\n";
					}	
				
				}
			}
		}
		

		/*
		$n = count($multilingual_keys);
		for($i =0; $i < $n; $i++)
		{
			$multilingual_keys[$i][] = 'guid';
			$multilingual_values[$i][] = '"' . $guid . '"';

			$sql = 'REPLACE INTO multilingual(' . join(',', $multilingual_keys[$i]) . ') values('
				. join(',', $multilingual_values[$i]) . ');';
			echo $sql . "\n";
		}	
		*/	
	}
	
	if (($count++ % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
		usleep($rand);
	}
	
	
}

?>