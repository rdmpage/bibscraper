<?php

// OAI harvester just fetches the data, we process it later

require_once (dirname(__FILE__) . '/class_oai.php');
require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');

class OaiFetch extends OaiHarvester
{

	function Process()
	{
		echo $this->xml;
	}
	
	

}

//$treebase = new OaiFetch('https://treebase.org/treebase-web/top/oai', 'oai_dc');
//$treebase->harvest();

// http://bibliotheques.mnhn.fr/EXPLOITATION/Infodoc/oaiserver.ashx?verb=ListRecords&set=mnhn:periodiques&metadataPrefix=oai_dc
// http://bibliotheques.mnhn.fr/EXPLOITATION/Infodoc/oaiserver.ashx?verb=ListRecords&metadataPrefix=oai_dc&set=mnhn:periodique

$set = 'mnhn:periodiques';
$set = 'mnhn:images';

$mnhn = new OaiFetch('http://bibliotheques.mnhn.fr/EXPLOITATION/Infodoc/oaiserver.ashx', 'oai_dc', $set);
$mnhn->harvest();



?>