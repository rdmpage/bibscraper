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


$urls=array(
/*"https://dialnet.unirioja.es/servlet/articulo?codigo=211129&orden=141417&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211139&orden=141418&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211145&orden=141419&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211421&orden=141420&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211422&orden=141421&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211439&orden=141422&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211442&orden=141423&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211448&orden=141424&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211454&orden=141425&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211456&orden=141426&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211460&orden=141427&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211462&orden=141428&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211466&orden=141429&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211467&orden=141430&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211468&orden=141431&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211473&orden=141432&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=211474&orden=141433&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=234611&orden=141408&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=234613&orden=141409&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=234616&orden=141410&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=234625&orden=141411&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=234626&orden=141412&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=234627&orden=141413&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=234630&orden=141414&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=234636&orden=141415&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=234638&orden=141416&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269768&orden=141394&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269779&orden=141395&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269782&orden=141396&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269799&orden=141397&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269803&orden=141398&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269805&orden=141399&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269814&orden=141400&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269821&orden=141401&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269824&orden=141402&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269844&orden=141403&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269848&orden=141404&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269850&orden=141405&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269854&orden=141406&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=269861&orden=141407&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210210&orden=141380&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210220&orden=141381&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210241&orden=141382&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210274&orden=141383&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210300&orden=141384&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210306&orden=141385&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210317&orden=141386&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210532&orden=141387&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210558&orden=141388&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210575&orden=141393&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210590&orden=141389&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210609&orden=141390&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210652&orden=141391&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=1210674&orden=141392&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2252893&orden=141370&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2252910&orden=141371&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2252912&orden=141372&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2252919&orden=141373&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2252922&orden=141374&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2252930&orden=141375&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2252937&orden=141376&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2252942&orden=141377&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2252947&orden=141378&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=2253363&orden=141379&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413686&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413687&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413688&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413689&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413690&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413691&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413692&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413693&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413694&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413695&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413696&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413697&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413698&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/ejemplar?codigo=421500&info=open_link_ejemplar",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413700&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413701&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413702&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413703&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413704&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413705&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413706&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413707&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413708&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413709&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413710&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413711&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413712&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413713&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413714&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413715&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413716&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413717&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413718&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413719&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413720&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413721&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413722&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413723&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413724&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413725&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413726&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413727&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413728&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413729&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413730&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413731&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413732&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413733&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413734&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413735&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413736&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413737&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413738&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413739&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413740&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413741&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413742&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413743&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413744&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413745&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413746&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413747&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413748&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413749&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413750&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413751&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413752&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413753&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413754&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413755&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413756&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413757&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413758&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413759&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413760&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413761&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413762&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413763&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413764&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413765&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413766&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413767&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413768&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413769&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413770&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413771&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413772&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413773&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413774&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413775&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413776&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413777&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413778&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413779&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413780&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413781&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413782&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413783&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413784&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413785&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413786&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413787&orden=0&info=link",*/
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413788&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413789&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413790&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413791&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413792&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413793&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413794&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=5413795&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=6046877&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=6046878&orden=0&info=link",
"https://dialnet.unirioja.es/servlet/articulo?codigo=6046879&orden=0&info=link",);


//$dois=array('10.7751/telopea8562');

$pdfs = array();

$count = 1;

foreach ($urls as $url)
{
	
	$redirect = get_redirect($url);
	
	echo "redirect=$redirect\n";
	
	if ($redirect != '')
	{
		//echo "UPDATE `publications` SET pdf='" . $redirect . "' WHERE doi='" . $doi . "' AND pdf IS NULL;" . "\n";	
		echo "UPDATE `publications` SET pdf='" . $redirect . "' WHERE pdf='" . $url . "';" . "\n";	
		
		$pdfs[] = $redirect;
	}
	
		//if (($count++ % 5) == 0)
		{
			$rand = rand(400000, 10000000);
			echo '...sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
			usleep($rand);
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