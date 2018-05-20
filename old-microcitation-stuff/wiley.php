<?php

require_once ('lib.php');

//--------------------------------------------------------------------------------------------------
// meta
function get_meta($html, &$keys, &$values)
{
	
	$html = str_replace("\n", "", $html);
	
	$k = array(
		'citation_journal_title', 
		'citation_title',
		'citation_doi',
		'citation_abstract_html_url',
		'citation_firstpage',
		'citation_lastpage',
		'citation_issn',
		'citation_volume',
		'citation_issue',
		'citation_publication_date'
	);
	
	$translate = array(
		'citation_journal_title' => 'journal',
		'citation_title' => 'title',
		'citation_doi' => 'doi',
		'citation_abstract_html_url' => 'url',
		'citation_firstpage' => 'spage',
		'citation_lastpage' => 'epage',
		'citation_issn' => 'issn',
		'citation_volume' => 'volume',
		'citation_issue' => 'issue',
		'citation_publication_date' => 'date'
		
	);	
	
	$authors = array();
	
	foreach ($k as $cite_key)
	{
		$pattern = '<meta(\s+xmlns="http:\/\/www.w3.org\/1999\/xhtml")?\s+name="' . $cite_key . '"\s+content="(?<' . $cite_key . '>.*)"\s*\/>';
		//echo $pattern . "\n";
		if (preg_match('/' . $pattern . '/Uu', $html, $m))
		{
			//print_r($m);
			
			
			switch ($cite_key)
			{
				case 'citation_publication_date':
					$keys[] = $translate[$cite_key];
					$values[] = "'" . str_replace('/', '-', $m[$cite_key]) . "'";				
				
					$keys[] = 'year';
					$values[] = "'" . preg_replace('/\/[0-9]{2}\/[0-9]{2}$/', '', $m[$cite_key]) . "'";
					break;
					
				case 'citation_doi':
					$keys[] = 'doi';
					$values[] = "'" . $m[$cite_key] . "'";

					$keys[] = 'guid';
					$values[] = "'" . $m[$cite_key] . "'";
					break;
				
				case 'citation_title':
					$keys[] = $translate[$cite_key];
					$values[] = "'" . addcslashes(html_entity_decode($m[$cite_key]), "'") . "'";				
				
					// 17. J. Weese: 
					if (preg_match('/^\d+\.\s+(?<authors>.*):\s+/Uu', $m[$cite_key], $mm))
					{
						$keys[] = 'authors';
						$values[] = "'" . addcslashes(html_entity_decode($mm['authors']), "'") . "'";
					}
					break;
					
					
				default:
					$keys[] = $translate[$cite_key];
					$values[] = "'" . addcslashes(html_entity_decode($m[$cite_key]), "'") . "'";
					break;
			}
			
			
			
		}
	}	
	
	// citation_author	
	if (preg_match_all('/<meta(\s+xmlns="http:\/\/www.w3.org\/1999\/xhtml")?\s+name="citation_author"\s+content="(?<citation_author>.*)"\s*\/>/Uu', $html, $m))
	{
		//print_r($m);

		$keys[] = 'authors';
		$values[] = "'" . addcslashes(html_entity_decode(join(";", $m['citation_author'])), "'") . "'";
	}
	
	


}

//----------------------------------------------------------------------------------------

$issues=array(
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1884.2.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1885.3.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1886.4.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1887.5.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1888.6.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1889.7.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1890.8.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1891.9.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1892.10.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1893.11.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1894.12.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1895.13.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1896.14.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1897.15.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1898.16.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1899.17.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1900.18.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1901.19.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1902.20.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1903.21.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1904.22.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1905.23.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1906.24.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1907.25.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1908.26.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1909.27.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1910.28.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1911.29.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-12/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1912.30.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1915.33.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1922.40.issue-9/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-10/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-11/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-5/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-6/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-7/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-8/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1111/plb.1923.41.issue-9/issuetoc'
);

// Berliner entomologische Zeitschrift
$issues=array(
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1:1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v10:1/3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v10:4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v11:1/2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v11:3/4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v12:1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v12:2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v12:3.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v12:4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v15:1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v15:1%2B/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v15:2/3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v16:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v16:2/4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v18:1/2/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v18:3/4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v19:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v19:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1914:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1914:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1914:3.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1914:4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1915:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1915:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1915:3.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1915:4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1915:5.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1915:6.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1920:3/4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1921:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1921:3.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1921:4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1922:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1922:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1922:3.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v1922:4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v2:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v2:3/4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v24:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v24:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v25:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v25:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v27:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v27:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v28:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v29:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v29:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v3:2/3/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v3:4/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v30:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v30:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v31:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v31:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v33:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v33:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v35:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v35:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v36:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v36:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v37:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v37:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v37:3.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v37:4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v38:1/2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v38:3/4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v39:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v39:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v39:4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v4:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v40:1.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v40:2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v40:3/4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v42:1/2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v42:3/4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v44:1/2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v44:3/4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v48:3.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v48:4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v50:1/2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v57:1/2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v57:1/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v57:3/4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v7:1/2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v8:1/2.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v8:3/4.o/issuetoc',
'http://onlinelibrary.wiley.com/doi/10.1002/mmnd.v9:3/4.o/issuetoc'
);

$count = 0;
foreach ($issues as $issue_url)
{
	//$issue_url = 'http://onlinelibrary.wiley.com/doi/10.1111/plb.1921.39.issue-3/issuetoc';

	//$issue_url = 'http://onlinelibrary.wiley.com/doi/10.1111/plb.1971.84.issue-11/issuetoc';

	$html = get($issue_url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like Gecko) Version/8.0.2 Safari/600.2.5');

	// <a href="/doi/10.1111/j.1438-8677.1921.tb07920.x/abstract">19. Runar Collander: Der Reizanlaß bei den thermotropischen Reaktionen der Wurzeln<span> (pages 120–122)</span></a>
	// http://onlinelibrary.wiley.com/doi/10.1002/mmnd.18630070102/full
	//if (preg_match_all('/<a href="(?<url>\/doi\/10.1111\/j.1438-8677\.[0-9]{4}\.tb\d+\.x\/abstract)"/Uu', $html, $m))
	if (preg_match_all('/<a href="(?<url>\/doi\/10.1002\/mmnd\.\d+\/abstract)"/Uu', $html, $m))
	{
		//print_r($m);
	
		foreach ($m['url'] as $u)
		{
			$html = get('http://onlinelibrary.wiley.com' . $u, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/600.2.5 (KHTML, like Gecko) Version/8.0.2 Safari/600.2.5');
	
			$keys = array();
			$values=array();

			get_meta($html, $keys, $values);


			//print_r($keys);
			//print_r($values);
		
				$sql = 'REPLACE INTO publications(' . join(',', $keys) . ') VALUES (' . join(',', $values) . ');';
			
				echo $sql . "\n";
		
		}
	}
	
	// Give server a break every 10 items
	//echo ".";
	if (($count++ % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
		usleep($rand);
	}

	
}


/*


$keys = array();
$values=array();

get_meta($html, $keys, $values);


print_r($keys);
print_r($values);
*/

?>