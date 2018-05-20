<?php

// http://jtsb.scib.ac.cn/jtsb_en/ch/reader/view_abstract.aspx?flag=1&file_no=200201006&journal_id=jtsb_cn

// guihaia

require_once(dirname(dirname(__FILE__)) . '/lib.php');

require_once(dirname(dirname(__FILE__)) . '/simplehtmldom_1_5/simple_html_dom.php');



$ids = array();

for($year = 2015; $year < 2016; $year++)
{
	for($issue = 1; $issue < 7; $issue++)
	{
		for($article = 1; $article < 30; $article++)
		{
			$ids[] = $year . str_pad($issue, 2, STR_PAD_LEFT, '0') . str_pad($article, 2, STR_PAD_LEFT, '0');
		}
	}
}

// One record
//$ids=array(); $ids[]='200504002';

// http://d.wanfangdata.com.cn/Periodical/gxzw200701001
//$ids = array(); $ids[] = '20070425';



$count = 0;

foreach ($ids as $id)
{
	$url = 'http://www.guihaia-journal.com/ch/reader/view_abstract.aspx?file_no=' . $id . '&flag=1';
	
	echo "-- $url\n";
	
	
	//echo $h;
	//echo "--------\n\n";

	$html = get($url, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/600.5.17 (KHTML, like Gecko) Version/8.0.5 Safari/600.5.17');
	
	$guid = $url;
	$issn = '1000-3142';
	
	$pdf = '';
	$year = '';
	$spage = '';

	
	// regex
	// PDF
	// <a href="create_pdf.aspx?file_no=20090201&amp;flag=1&amp;year_id=2009&amp;quarter_id=2">【下载PDF全文】</a>
	if (preg_match('/<a href="(?<pdf>create_pdf.aspx\?file_no=(.*))">/Uu', $html, $m))
	{
		//print_r($m);
		$pdf= 'http://www.guihaia-journal.com/ch/reader/' . $m['pdf'];
	}
	
	// Citation
	// <span id="test">韦毅刚, 王文采.广西楼梯草属三新种和一新变种[J].广西植物,2009,(2):143-148</span>
	if (preg_match('/<span id="test">(.*)(?<year>[0-9]{4}),((?<volume>\d+))?\((?<issue>\d+)\):(?<spage>\d+)(-(?<epage>\d+))<\/span>/Uu', $html, $m))
	{
		//print_r($m);
		//exit();
		
		$year = $m['year'];
		$spage = $m['spage'];
	}
	
	if ($year != '' && $spage != '' && $pdf != '')
	{
		$sql = 'UPDATE publications SET pdf="' . $pdf . '" WHERE issn="' . $issn . '" AND year="' . $year . '" AND spage="' . $spage . '";';
		echo $sql . "\n";
	}

	
	if (($count++ % 10) == 0)
	{
		$rand = rand(1000000, 3000000);
		echo '-- sleeping for ' . round(($rand / 1000000),2) . ' seconds' . "\n";
		usleep($rand);
	}
	
	
}

?>