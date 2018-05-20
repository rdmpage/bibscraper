<?php

// DOI resolves straight to PDF

 
require_once(dirname(__FILE__) . '/config.inc.php');


//--------------------------------------------------------------------------
function pdf_to_filename($pdf)
{	
	$filename = $pdf;
	
	$filename = str_replace('http://boletinsgm.igeolcu.unam.mx/bsgm/vols/epoca04/', '', $filename);
	
	$filename = str_replace('webmedia.php?irn=', '', $filename);
	$filename = str_replace('&reftable=ebibliography', '', $filename);
	
	$filename = preg_replace('/[\/|\(|\)]/', '-', $filename);
		
	$filename = str_replace('&reftable=ebibliography', '', $filename);
	
	if (!preg_match('/\.pdf$/', $filename))
	{
		$filename .= '.pdf';
	}	

	return $filename;
}

//--------------------------------------------------------------------------
/**
 * @brief Test whether HTTP code is valid
 *
 * HTTP codes 200 and 302 are OK.
 *
 * For JSTOR we also accept 403
 *
 * @param HTTP code
 *
 * @result True if HTTP code is valid
 */
function HttpCodeValid($http_code)
{
	if ( ($http_code == '200') || ($http_code == '302') || ($http_code == '403'))
	{
		return true;
	}
	else{
		return false;
	}
}


//--------------------------------------------------------------------------
/**
 * @brief GET a resource
 *
 * Make the HTTP GET call to retrieve the record pointed to by the URL. 
 *
 * @param url URL of resource
 *
 * @result Contents of resource
 */
function get_redirect($url, $userAgent = '', $content_type = '', $cookie = null)
{
	global $config;
	
	$redirect = '';
	
	$ch = curl_init(); 
	curl_setopt ($ch, CURLOPT_URL, $url); 
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt ($ch, CURLOPT_FOLLOWLOCATION,  0); 
	curl_setopt ($ch, CURLOPT_HEADER,		  1);  
	
	// timeout (seconds)
	curl_setopt ($ch, CURLOPT_TIMEOUT, 240);

	curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.txt');
	
	if ($cookie)
	{
		curl_setopt($ch, CURLOPT_HTTPHEADER, $cookie);
	}
	
	if ($userAgent != '')
	{
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	}	
	
	if ($config['proxy_name'] != '')
	{
		curl_setopt ($ch, CURLOPT_PROXY, $config['proxy_name'] . ':' . $config['proxy_port']);
	}
	
	if ($content_type != '')
	{
		curl_setopt ($ch, CURLOPT_HTTPHEADER, array ("Accept: " . $content_type));
    }
	
			
	$curl_result = curl_exec ($ch); 
	
	//echo $curl_result;
	//exit();
	
	if (curl_errno ($ch) != 0 )
	{
		echo "CURL error: ", curl_errno ($ch), " ", curl_error($ch);
	}
	else
	{
		$info = curl_getinfo($ch);
		
		//print_r($info);
		 
		$header = substr($curl_result, 0, $info['header_size']);
		//echo $header;
		
		
		$http_code = $info['http_code'];
		
		if ($http_code == 303)
		{
			$redirect = $info['redirect_url'];
		}
		
		if ($http_code == 302)
		{
			$redirect = $info['redirect_url'];
		}
		
	}
	return $redirect;
}

//----------------------------------------------------------------------------------------
// safe file name, based on http://snipplr.com/view/5256/filename-safe/
function filename_safe($filename) 
{
	$temp = $filename;
	// Lower case
	$temp = strtolower($temp);

	// Replace spaces with a '_'
	$temp = str_replace(" ", "_", $temp);

	// Loop through string
	$result = '';
	for ($i=0; $i<strlen($temp); $i++) 
	{
		if (preg_match('([0-9]|[a-z]|_)', $temp[$i])) 
		{
			$result = $result . $temp[$i];
		}    
	}
	// Return filename
	return $result;
}


$dois=array(
'10.7751/telopea19834401',
'10.7751/telopea19834402',
'10.7751/telopea19834403',
'10.7751/telopea19834404',
'10.7751/telopea19834405',
'10.7751/telopea19834406',
'10.7751/telopea19834407',
'10.7751/telopea19834408',
'10.7751/telopea19834409',
'10.7751/telopea19844501',
'10.7751/telopea19844502',
'10.7751/telopea19844503',
'10.7751/telopea19844504',
'10.7751/telopea19844205',
'10.7751/telopea19864601',
'10.7751/telopea19864602',
'10.7751/telopea19864603',
'10.7751/telopea19864604',
'10.7751/telopea19864605',
'10.7751/telopea19864606',
'10.7751/telopea19864607',
'10.7751/telopea19864608',
'10.7751/telopea19864609',
'10.7751/telopea19864610',
'10.7751/telopea19864611',
'10.7751/telopea19864612',
'10.7751/telopea19864613',
'10.7751/telopea19864614',
'10.7751/telopea19864615',
'10.7751/telopea8309',
'10.7751/telopea8285',
'10.7751/telopea20035600',
'10.7751/telopea20035601',
'10.7751/telopea20035602',
'10.7751/telopea20035603',
'10.7751/telopea20035604',
'10.7751/telopea20035605',
'10.7751/telopea20035606',
'10.7751/telopea20035607',
'10.7751/telopea20035608',
'10.7751/telopea20035609',
'10.7751/telopea20035610',
'10.7751/telopea20035611',
'10.7751/telopea20035612',
'10.7751/telopea20035613',
'10.7751/telopea20035614',
'10.7751/telopea20035615',
'10.7751/telopea20035616',
'10.7751/telopea20035617',
'10.7751/telopea20035618',
'10.7751/telopea20035619',
'10.7751/telopea20035620',
'10.7751/telopea20035621',
'10.7751/telopea20035622',
'10.7751/telopea20035623',
'10.7751/telopea20035624',
'10.7751/telopea20035625',
'10.7751/telopea19904905',
'10.7751/telopea19971007',
'10.7751/telopea19982009',
'10.7751/telopea19982010',
'10.7751/telopea19982011',
'10.7751/telopea19982012',
'10.7751/telopea19982013',
'10.7751/telopea19982014',
'10.7751/telopea19982015',
'10.7751/telopea19982016',
'10.7751/telopea19982017',
'10.7751/telopea19993000',
'10.7751/telopea19993001',
'10.7751/telopea19993002',
'10.7751/telopea19934974',
'10.7751/telopea8500',
'10.7751/telopea19904911',
'10.7751/telopea19904912',
'10.7751/telopea19904913',
'10.7751/telopea19904914',
'10.7751/telopea19904915',
'10.7751/telopea19904916',
'10.7751/telopea19904917',
'10.7751/telopea19904918',
'10.7751/telopea19904919',
'10.7751/telopea19904920',
'10.7751/telopea19904921',
'10.7751/telopea19904922',
'10.7751/telopea19914923',
'10.7751/telopea19914924',
'10.7751/telopea19914925',
'10.7751/telopea19914926',
'10.7751/telopea19914927',
'10.7751/telopea19914928',
'10.7751/telopea19914929',
'10.7751/telopea19914930',
'10.7751/telopea19914931',
'10.7751/telopea19914932',
'10.7751/telopea19914933',
'10.7751/telopea19914934',
'10.7751/telopea19914944',
'10.7751/telopea19914945',
'10.7751/telopea19914932-c',
'10.7751/telopea19814946',
'10.7751/telopea19814947',
'10.7751/telopea19914926-c',
'10.7751/telopea19914928-c',
'10.7751/telopea19814948',
'10.7751/telopea19814949',
'10.7751/telopea19814950',
'10.7751/telopea19814951',
'10.7751/telopea19814952',
'10.7751/telopea19814953',
'10.7751/telopea20148076',
'10.7751/telopea20158145',
'10.7751/telopea20147247',
'10.7751/telopea20147404',
'10.7751/telopea20147461',
'10.7751/telopea2013001',
'10.7751/telopea2013002',
'10.7751/telopea20147931',
'10.7751/telopea20148124',
'10.7751/telopea20147924',
'10.7751/telopea20148041',
'10.7751/telopea20148040',
'10.7751/telopea20147392',
'10.7751/telopea20147402',
'10.7751/telopea20147465',
'10.7751/telopea20147427',
'10.7751/telopea20147554',
'10.7751/telopea19963040',
'10.7751/telopea19963041',
'10.7751/telopea19963042',
'10.7751/telopea19963043',
'10.7751/telopea19963025-c',
'10.7751/telopea19971000',
'10.7751/telopea19971001',
'10.7751/telopea19971002',
'10.7751/telopea19971003',
'10.7751/telopea19971004',
'10.7751/telopea19971005',
'10.7751/telopea19971006',
'10.7751/telopea19971008',
'10.7751/telopea19971009',
'10.7751/telopea19971010',
'10.7751/telopea19971011',
'10.7751/telopea19971012',
'10.7751/telopea19971013',
'10.7751/telopea19971014',
'10.7751/telopea19971015',
'10.7751/telopea19971016',
'10.7751/telopea19971017',
'10.7751/telopea19971018',
'10.7751/telopea19971019',
'10.7751/telopea19971020',
'10.7751/telopea19971021',
'10.7751/telopea19971022',
'10.7751/telopea19971023',
'10.7751/telopea19982000',
'10.7751/telopea19982001',
'10.7751/telopea19982002',
'10.7751/telopea19982003',
'10.7751/telopea19982004',
'10.7751/telopea19982005',
'10.7751/telopea19982006',
'10.7751/telopea19982007',
'10.7751/telopea19982008',
'10.7751/telopea8505',
'10.7751/telopea2012011',
'10.7751/telopea2012012',
'10.7751/telopea2012013',
'10.7751/telopea8127',
'10.7751/telopea8282',
'10.7751/telopea2012014',
'10.7751/telopea2012015',
'10.7751/telopea2012016',
'10.7751/telopea8510',
'10.7751/telopea19753101',
'10.7751/telopea8469',
'10.7751/telopea19753102',
'10.7751/telopea19753103',
'10.7751/telopea19753104',
'10.7751/telopea19753105',
'10.7751/telopea19753106',
'10.7751/telopea19753107',
'10.7751/telopea19753108',
'10.7751/telopea19753109',
'10.7751/telopea19753110',
'10.7751/telopea19753111',
'10.7751/telopea20147708',
'10.7751/telopea20147853',
'10.7751/telopea20147809',
'10.7751/telopea20147957',
'10.7751/telopea20147839',
'10.7751/telopea19804101',
'10.7751/telopea19804102',
'10.7751/telopea19804103',
'10.7751/telopea19804104',
'10.7751/telopea19904105',
'10.7751/telopea19804106',
'10.7751/telopea19804107',
'10.7751/telopea19804108',
'10.7751/telopea19804109',
'10.7751/telopea19804110',
'10.7751/telopea19804111',
'10.7751/telopea19804112',
'10.7751/telopea19804113',
'10.7751/telopea19804114',
'10.7751/telopea19804115',
'10.7751/telopea19804116',
'10.7751/telopea19804117',
'10.7751/telopea19804118',
'10.7751/telopea19804119',
'10.7751/telopea19804100',
'10.7751/telopea19995417',
'10.7751/telopea19995418',
'10.7751/telopea19995419',
'10.7751/telopea19995420',
'10.7751/telopea19995421',
'10.7751/telopea19995422',
'10.7751/telopea19995423',
'10.7751/telopea19995424',
'10.7751/telopea19995425',
'10.7751/telopea19995426',
'10.7751/telopea19995427',
'10.7751/telopea19995428',
'10.7751/telopea19995429',
'10.7751/telopea19995430',
'10.7751/telopea20002000',
'10.7751/telopea20002001',
'10.7751/telopea20002002',
'10.7751/telopea20002003',
'10.7751/telopea20002004',
'10.7751/telopea20002005',
'10.7751/telopea20002006',
'10.7751/telopea20002007',
'10.7751/telopea20002008',
'10.7751/telopea20002009',
'10.7751/telopea20105840',
'10.7751/telopea8526',
'10.7751/telopea8465',
'10.7751/telopea19824302-m',
'10.7751/telopea19814201',
'10.7751/telopea19814202',
'10.7751/telopea19814203',
'10.7751/telopea19814204',
'10.7751/telopea19814205',
'10.7751/telopea19814206',
'10.7751/telopea19824301',
'10.7751/telopea19824302',
'10.7751/telopea8337',
'10.7751/telopea20147916',
'10.7751/telopea20148179',
'10.7751/telopea20148083',
'10.7751/telopea20148075',
'10.7751/telopea20148280',
'10.7751/telopea20148023',
'10.7751/telopea20148299',
'10.7751/telopea20148130',
'10.7751/telopea2013024',
'10.7751/telopea2013025',
'10.7751/telopea2013018',
'10.7751/telopea2013013',
'10.7751/telopea2013014',
'10.7751/telopea2013015',
'10.7751/telopea2013016',
'10.7751/telopea2013020',
'10.7751/telopea2013021',
'10.7751/telopea2013022',
'10.7751/telopea2013023',
'10.7751/telopea8144',
'10.7751/telopea7935',
'10.7751/telopea8324',
'10.7751/telopea20147991',
'10.7751/telopea19763201',
'10.7751/telopea19763202',
'10.7751/telopea19763203',
'10.7751/telopea19763204',
'10.7751/telopea19763301',
'10.7751/telopea19763302',
'10.7751/telopea19773401',
'10.7751/telopea19783501',
'10.7751/telopea19783502',
'10.7751/telopea19783503',
'10.7751/telopea19783504',
'10.7751/telopea19783505',
'10.7751/telopea19783506',
'10.7751/telopea19783507',
'10.7751/telopea19783508',
'10.7751/telopea19803601',
'10.7751/telopea19803602',
'10.7751/telopea19803603',
'10.7751/telopea19803604',
'10.7751/telopea198036025',
'10.7751/telopea19803606',
'10.7751/telopea19803607',
'10.7751/telopea8343',
'10.7751/telopea2013008',
'10.7751/telopea2013009',
'10.7751/telopea2013010',
'10.7751/telopea2013011',
'10.7751/telopea2013012',
'10.7751/telopea2013017',
'10.7751/telopea2013019',
'10.7751/telopea20147421',
'10.7751/telopea20147401',
'10.7751/telopea20147400',
'10.7751/telopea20147483',
'10.7751/telopea20147851',
'10.7751/telopea20147894',
'10.7751/telopea20147788',
'10.7751/telopea20147814',
'10.7751/telopea20147918',
'10.7751/telopea20147536-c',
'10.7751/telopea20147847',
'10.7751/telopea20147543',
'10.7751/telopea20147532',
'10.7751/telopea20147709',
'10.7751/telopea20147536',
'10.7751/telopea20147551',
'10.7751/telopea20147562',
'10.7751/telopea20147553',
'10.7751/telopea20147790',
'10.7751/telopea19864701',
'10.7751/telopea19884801',
'10.7751/telopea19884802',
'10.7751/telopea19884803',
'10.7751/telopea19884804',
'10.7751/telopea19884805',
'10.7751/telopea19884806',
'10.7751/telopea19884807',
'10.7751/telopea19884808',
'10.7751/telopea19884809',
'10.7751/telopea19884810',
'10.7751/telopea19884811',
'10.7751/telopea19884812',
'10.7751/telopea19884813',
'10.7751/telopea19884814',
'10.7751/telopea19884815',
'10.7751/telopea19884816',
'10.7751/telopea19884817',
'10.7751/telopea19884818',
'10.7751/telopea19894901',
'10.7751/telopea19894902',
'10.7751/telopea20013000',
'10.7751/telopea20013001',
'10.7751/telopea20013002',
'10.7751/telopea20013003',
'10.7751/telopea20013004',
'10.7751/telopea20013005',
'10.7751/telopea20013006',
'10.7751/telopea20013007',
'10.7751/telopea20013008',
'10.7751/telopea20013009',
'10.7751/telopea20013010',
'10.7751/telopea20013011',
'10.7751/telopea20013012',
'10.7751/telopea20013013',
'10.7751/telopea20013014',
'10.7751/telopea20013015',
'10.7751/telopea20024000',
'10.7751/telopea20024001',
'10.7751/telopea20024002',
'10.7751/telopea20024003',
'10.7751/telopea20024004',
'10.7751/telopea20024005',
'10.7751/telopea20024006',
'10.7751/telopea20024007',
'10.7751/telopea20024008',
'10.7751/telopea20024009',
'10.7751/telopea20024010',
'10.7751/telopea20024011',
'10.7751/telopea20024012',
'10.7751/telopea20024013',
'10.7751/telopea20024014',
'10.7751/telopea20024015',
'10.7751/telopea20024016',
'10.7751/telopea20024017',
'10.7751/telopea20024018',
'10.7751/telopea20024019',
'10.7751/telopea20024020',
'10.7751/telopea20024021',
'10.7751/telopea20024022',
'10.7751/telopea20024023',
'10.7751/telopea20024024',
'10.7751/telopea20024025',
'10.7751/telopea19944994',
'10.7751/telopea19943006',
'10.7751/telopea19943007',
'10.7751/telopea19943008',
'10.7751/telopea19943009',
'10.7751/telopea19943010',
'10.7751/telopea19943012',
'10.7751/telopea19943013',
'10.7751/telopea19953014',
'10.7751/telopea19953015',
'10.7751/telopea19943016',
'10.7751/telopea19953017',
'10.7751/telopea19943008-c',
'10.7751/telopea19963019',
'10.7751/telopea19963020',
'10.7751/telopea19963021',
'10.7751/telopea19963022',
'10.7751/telopea19963023',
'10.7751/telopea19963024',
'10.7751/telopea19963025',
'10.7751/telopea19963026',
'10.7751/telopea19963027',
'10.7751/telopea19963028',
'10.7751/telopea19963029',
'10.7751/telopea19963030',
'10.7751/telopea19963031',
'10.7751/telopea19963032',
'10.7751/telopea19963033',
'10.7751/telopea19963034',
'10.7751/telopea19963035',
'10.7751/telopea19963036',
'10.7751/telopea19963037',
'10.7751/telopea19963038',
'10.7751/telopea19963039',
'10.7751/telopea19924954',
'10.7751/telopea19924955',
'10.7751/telopea19924956',
'10.7751/telopea19924957',
'10.7751/telopea19924958',
'10.7751/telopea19924959',
'10.7751/telopea19924960',
'10.7751/telopea19924961',
'10.7751/telopea19924962',
'10.7751/telopea19924963',
'10.7751/telopea19924964',
'10.7751/telopea19924965',
'10.7751/telopea19924966',
'10.7751/telopea19924967',
'10.7751/telopea19924968',
'10.7751/telopea19924969',
'10.7751/telopea20055700',
'10.7751/telopea20055701',
'10.7751/telopea20055702',
'10.7751/telopea20055703',
'10.7751/telopea20055704',
'10.7751/telopea20055705',
'10.7751/telopea20055706',
'10.7751/telopea20055707',
'10.7751/telopea20055708',
'10.7751/telopea20055709',
'10.7751/telopea20065710',
'10.7751/telopea20065711',
'10.7751/telopea20065712',
'10.7751/telopea20065713',
'10.7751/telopea20065714',
'10.7751/telopea20065715',
'10.7751/telopea20065716',
'10.7751/telopea20065717',
'10.7751/telopea20065718',
'10.7751/telopea20065719',
'10.7751/telopea20065720',
'10.7751/telopea20065721',
'10.7751/telopea20065722',
'10.7751/telopea20065723',
'10.7751/telopea20065724',
'10.7751/telopea20065725',
'10.7751/telopea20065726',
'10.7751/telopea20065728',
'10.7751/telopea20065729',
'10.7751/telopea20065730',
'10.7751/telopea20065731',
'10.7751/telopea20065732',
'10.7751/telopea20065733',
'10.7751/telopea20065734',
'10.7751/telopea20065735',
'10.7751/telopea20075736',
'10.7751/telopea20075738',
'10.7751/telopea20075739',
'10.7751/telopea20075740',
'10.7751/telopea20075741',
'10.7751/telopea20075742',
'10.7751/telopea20075743',
'10.7751/telopea20075744',
'10.7751/telopea20075745',
'10.7751/telopea20075746',
'10.7751/telopea19944988',
'10.7751/telopea19944989',
'10.7751/telopea19944990',
'10.7751/telopea19944991',
'10.7751/telopea19934992',
'10.7751/telopea19934993',
'10.7751/telopea19944995',
'10.7751/telopea19934996',
'10.7751/telopea19934997',
'10.7751/telopea19934998',
'10.7751/telopea19934999',
'10.7751/telopea19943000',
'10.7751/telopea19943001',
'10.7751/telopea19943002',
'10.7751/telopea19943003',
'10.7751/telopea19943004',
'10.7751/telopea19943005',
'10.7751/telopea19934987',
'10.7751/telopea19934980-c',
'10.7751/telopea19924970',
'10.7751/telopea19924971',
'10.7751/telopea19924972',
'10.7751/telopea19924973',
'10.7751/telopea19924974',
'10.7751/telopea199349741',
'10.7751/telopea19934975',
'10.7751/telopea19934976',
'10.7751/telopea19934977',
'10.7751/telopea19934978',
'10.7751/telopea19934979',
'10.7751/telopea19934980',
'10.7751/telopea19934981',
'10.7751/telopea19934982',
'10.7751/telopea19934984',
'10.7751/telopea19934985',
'10.7751/telopea19934986',
'10.7751/telopea2013005',
'10.7751/telopea2013006',
'10.7751/telopea2013007',
'10.7751/telopea2013003',
'10.7751/telopea2013004',
'10.7751/telopea20147808',
'10.7751/telopea20147840',
'10.7751/telopea20147805',
'10.7751/telopea20147824',
'10.7751/telopea20147757',
'10.7751/telopea20147090',
'10.7751/telopea',
'10.7751/telopea20147546',
'10.7751/telopea20085800',
'10.7751/telopea20085801',
'10.7751/telopea20085802',
'10.7751/telopea20085803',
'10.7751/telopea20085804',
'10.7751/telopea20085805',
'10.7751/telopea20085806',
'10.7751/telopea20105835',
'10.7751/telopea20105836',
'10.7751/telopea20105837',
'10.7751/telopea20105838',
'10.7751/telopea20105839',
'10.7751/telopea20105841',
'10.7751/telopea20105842',
'10.7751/telopea20105843',
'10.7751/telopea20105844',
'10.7751/telopea20105845',
'10.7751/telopea8484',
'10.7751/telopea8525',
'10.7751/telopea8529',
'10.7751/telopea8535',
'10.7751/telopea8564',
'10.7751/telopea8757',
'10.7751/telopea8880',
'10.7751/telopea8713',
'10.7751/telopea8903',
'10.7751/telopea20085807',
'10.7751/telopea20085808',
'10.7751/telopea20085809',
'10.7751/telopea20085810',
'10.7751/telopea20085811',
'10.7751/telopea20085812',
'10.7751/telopea20085813',
'10.7751/telopea20116010',
'10.7751/telopea20116009',
'10.7751/telopea20116036',
'10.7751/telopea20116000',
'10.7751/telopea20116001',
'10.7751/telopea20116002',
'10.7751/telopea20116003',
'10.7751/telopea20116004',
'10.7751/telopea20116005',
'10.7751/telopea20116006',
'10.7751/telopea20116007',
'10.7751/telopea20116008',
'10.7751/telopea20116035',
'10.7751/telopea20116034',
'10.7751/telopea20116033',
'10.7751/telopea20116032',
'10.7751/telopea20116031',
'10.7751/telopea20116030',
'10.7751/telopea20095823',
'10.7751/telopea20095824',
'10.7751/telopea20095825',
'10.7751/telopea20095826',
'10.7751/telopea20095827',
'10.7751/telopea20095828',
'10.7751/telopea20095829',
'10.7751/telopea20095830',
'10.7751/telopea20095831',
'10.7751/telopea20095832',
'10.7751/telopea20116028',
'10.7751/telopea20116029',
'10.7751/telopea20095822',
'10.7751/telopea20095821',
'10.7751/telopea9033',
'10.7751/telopea8906',
'10.7751/telopea8753',
'10.7751/telopea8851',
'10.7751/telopea8886',
'10.7751/telopea8891',
'10.7751/telopea9072',
'10.7751/telopea8890',
'10.7751/telopea8862',
'10.7751/telopea8533',
'10.7751/telopea8894',
'10.7751/telopea8885',
'10.7751/telopea9146',
'10.7751/telopea9100',
'10.7751/telopea9080',
'10.7751/telopea9030',
'10.7751/telopea8895',
'10.7751/telopea8762',
'10.7751/telopea8637',
'10.7751/telopea8767',
'10.7751/telopea8752',
'10.7751/telopea8907',
'10.7751/telopea9147',
'10.7751/telopea20095820',
'10.7751/telopea9162',
'10.7751/telopea8896',
'10.7751/telopea8562'
);

$dois=array(
"10.7751/telopea20116010",
"10.7751/telopea20116011",
"10.7751/telopea20116012",
"10.7751/telopea20116014",
"10.7751/telopea20116015",
"10.7751/telopea20116016",
"10.7751/telopea20116018",
"10.7751/telopea20116019",
"10.7751/telopea20116020",
"10.7751/telopea20116021",
"10.7751/telopea20116022",
"10.7751/telopea20116023",
"10.7751/telopea20116024",
"10.7751/telopea20116025",
"10.7751/telopea20116026"
);

$dois=array(
'10.7751/telopea2012001',
'10.7751/telopea2012002',
'10.7751/telopea2012003',
'10.7751/telopea2012004',
'10.7751/telopea2012005',
'10.7751/telopea2012006',
'10.7751/telopea2012007',
'10.7751/telopea2012008',
'10.7751/telopea2012010'
);

$dois=array(
'10.18268/bsgm2013v65n2a6',
'10.18268/BSGM2013v65n2a1',
'10.18268/bsgm2016v68n1a3',
'10.18268/bsgm2013v65n2a7',
'10.18268/bsgm2013v65n2a10',
'10.18268/bsgm2016v68n1a8',
'10.18268/bsgm2016v68n1a11',
'10.18268/bsgm2013v65n2a14',
'10.18268/bsgm2009v61n2a6',
'10.18268/bsgm2011v63n3a5',
'10.18268/bsgm2015v67n1a8',
'10.18268/bsgm2013v65n2a12',
'10.18268/bsgm2014v66n3a6',
'10.18268/bsgm2016v68n1a2',
'10.18268/bsgm2009v61n2a13',
'10.18268/bsgm2010v62n2a1',
'10.18268/BSGM2013v65n2a4',
'10.18268/bsgm2008v60n1a7',
'10.18268/bsgm2013v65n2a15',
'10.18268/bsgm2016v68n1a4',
'10.18268/bsgm2016v68n1a12',
'10.18268/bsgm2016v68n1a1',
'10.18268/bsgm2013v65n2a5',
'10.18268/BSGM2009v61n2a7',
'10.18268/BSGM2014v66n1a11',
'10.18268/bsgm2013v65n2a8',
'10.18268/bsgm2009v61n2a8',
'10.18268/bsgm2013v65n2a2'
);


//$dois=array('10.7751/telopea8562');

$pdfs = array();

foreach ($dois as $doi)
{
	$url = 'http://dx.doi.org/' . $doi;
	
	$redirect = get_redirect($url);
	
	echo "redirect=$redirect\n";
	
	if ($redirect != '')
	{
		echo "UPDATE `publications` SET pdf='" . $redirect . "' WHERE doi='" . $doi . "' AND pdf IS NULL;" . "\n";	
		
		$pdfs[] = $redirect;
	}


}


echo "--- list ---\n";
foreach ($pdfs as $pdf)
{
	echo "$pdf\n";
}


echo "--- curl fetch.sh ---\n";
foreach ($pdfs as $pdf)
{
	$filename = pdf_to_filename($pdf);

	echo "curl '$pdf' > '" . $filename . "'\n";
}

echo "--- extra.txt ---\n";
foreach ($pdfs as $pdf)
{
	$filename = pdf_to_filename($pdf);
	
	echo "/Users/rpage/Desktop/newpdfs/" . $filename . "\t$pdf\n";
}



?>