<?php

/**
 * @file class_oai.php OAI harvesting
 *
 */

require_once (dirname(__FILE__) . '/config.inc.php');
require_once (dirname(__FILE__) . '/lib.php');

//-------------------------------------------------------------------------------------------------
/**
 * @class OaiHarvester
 *
 * @brief Encapsulate harvesting an OAI repository
 *
 */
class OaiHarvester
{
	var $repository_url;
	var $repository_set;
	var $resumption_token;
	var $last_accessed;
	var $base_url;
	var $metadata_prefix;
	
	//----------------------------------------------------------------------------------------------
	function __construct($url, $metadata_prefix = 'oai_dc', $set = '')
	{
		$this->repository_url 	= $url;
		$this->repository_set	= $set;
		$this->metadata_prefix 	= $metadata_prefix;
		$this->last_accessed	= '';
		$this->xml				= '';
	}
	
	//----------------------------------------------------------------------------------------------
	function GetDateLastAccessed ()
	{
		/*
		global $config;
		global $ADODB_FETCH_MODE;

		$db = NewADOConnection('mysql');
		$db->Connect("localhost", 
			$config['db_user'] , $config['db_passwd'] , $config['db_name']);
		
		// Ensure fields are (only) indexed by column name
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		
		$sql = 'SELECT * FROM oai
		WHERE(repository = ' . $db->qstr($this->repository_url) . ')';
		if ($this->repository_set != '')
		{
			$sql .= ' AND (repository_set = ' . $db->qstr($this->repository_set) . ')';
		}
		$sql .= 'LIMIT 1';
		
		$result = $db->Execute($sql);
		if ($result == false) die("failed [" . __LINE__ . "]: " . $sql);
	
		if ($result->NumRows() == 1)
		{
			$this->last_accessed = $result->fields['last_accessed'];
		}
		
		echo "Last accessed: " . $this->last_accessed . "\n";
		
		$db->Close();
		*/
		
	}
	
	//----------------------------------------------------------------------------------------------
	function SetDateLastAccessed ()
	{
		/*
		global $config;
		global $ADODB_FETCH_MODE;

		$db = NewADOConnection('mysql');
		$db->Connect("localhost", 
			$config['db_user'] , $config['db_passwd'] , $config['db_name']);
		
		// Ensure fields are (only) indexed by column name
		$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
		
		
		if ($this->last_accessed == '')
		{
			// First time we've visited the repository
			$sql = 'INSERT oai(repository, repository_set, last_accessed) VALUES('
			.  $db->qstr($this->repository_url) . ','
			.  $db->qstr($this->repository_set)  . ','
			. 'NOW())';
		}
		else
		{
			
			// Store last time accessed
			$sql = 	'UPDATE oai SET '
			. 'last_accessed = NOW() '
			. 'WHERE (repository = ' . $db->qstr($this->repository_url) . ')'
			. ' AND (repository_set = ' . $db->qstr($this->repository_set)  . ')';
		}
		
		
		$result = $db->Execute($sql);
		if ($result == false) die("failed [" . __LINE__ . "]: " . $sql);
		$db->Close();	
		*/	
	}
	
	//----------------------------------------------------------------------------------------------
	function Cache()
	{
		global $config;
		
		$parts = parse_url($this->repository_url);
		$namespace = $parts['host'];
		$cache_namespace = $config['cache_dir']. "/" . $namespace;
		
		// Ensure cache subfolder exists for this repository
		if (!file_exists($cache_namespace))
		{
			$oldumask = umask(0); 
			mkdir($cache_namespace, 0777);
			umask($oldumask);
		}
		
		
		if ($this->repository_set != '')
		{
			// Ensure cache subfolder exists for this set
			$cache_namespace .= "/" . filename_safe($this->repository_set);

			if (!file_exists($cache_namespace))
			{
				$oldumask = umask(0); 
				mkdir($cache_namespace, 0777);
				umask($oldumask);
			}
		}
		
		// Use timestamp as filename
		$id = time();
		
		$cache_filename = $cache_namespace . "/" . $id . '.xml';
				
		
		// Store data in cache
		$cache_file = @fopen($cache_filename, "w+") or die("could't open file --\"$cache_filename\"");
		@fwrite($cache_file, $this->xml);
		fclose($cache_file);
	}
	
	
	
	

	//----------------------------------------------------------------------------------------------
	function Harvest ()
	{
		$this->base_url = $this->repository_url;
		$this->base_url .= '?verb=ListRecords';
		
		$this->resumption_token = '';
		
		do {
			$url = $this->base_url;
		
			if ($this->resumption_token == '')
			{
				$url .= '&metadataPrefix=' . $this->metadata_prefix;
				if ($this->repository_set != '')
				{
					$url .= '&set=' . $this->repository_set;
				}
			}
			else
			{
				$url .= '&resumptionToken=' . $this->resumption_token;
			}
			
			/*
			// If we've previously accessed this repository then limit search to newly 
			// added/modified records
			if ($this->last_accessed != '')
			{
				// do this more elegantly
				$last_accessed = preg_replace('/ /', 'T', $this->last_accessed);
				$url .= '&from=' . $last_accessed;
			}
			*/
		
			//echo $url . "\n";
			
			// make call, harvest XML, clean, store in database
			
			$this->xml = '';
			$this->xml = get($url);
			
			//echo $this->xml;
			
			//exit();
			
			$this->Cache();
			
			// Post process and store records...
			$this->Process();
			
			//$this->resumption_token = ''; // kill it after one go
			
			// Resumption token
			
			// do it properly
			$dom = new DOMDocument;
			$dom->loadXML($this->xml);
			$xpath = new DOMXPath($dom);
		
			$xpath->registerNamespace("dc", "http://purl.org/dc/elements/1.1/");	
			$xpath->registerNamespace("oaidc", "http://www.openarchives.org/OAI/2.0/oai_dc/");	
			$xpath->registerNamespace("xsi", "http://www.w3.org/2001/XMLSchema-instance");	
			
			$nc = $xpath->query ('//ListRecords/resumptionToken');
			foreach($nc as $n)
			{
				$this->resumption_token = $n->firstChild->nodeValue;
			}
			
			/*
			if (preg_match('/<resumptionToken(.*)>(?<token>(.*))<\/resumptionToken>/m', $this->xml, $matches))
			{
				$this->resumption_token = $matches['token'];
			}
			else
			{
				$this->resumption_token = '';
			}
			*/
			
			echo "-- Resumption token = " . $this->resumption_token . "\n";
			
		} while ($this->resumption_token != '');
		
		//$this->SetDateLastAccessed();		
	}
	
	function Process()
	{
		echo $this->xml;
	
	}
	
	function Export2Ris($reference)
	{
		//print_r($reference);
		
		echo "TY  - JOUR\n";
		echo "T1  - " . $reference->title . "\n";
		foreach ($reference->authors as $a)
		{
			// clean
			$pos = strpos($a, ';');
			if ($pos === false)
			{
			}
			else
			{
				$a = substr($a, 0, $pos);
			}
			$a = preg_replace('/\s\s+/u', ' ', $a);
			echo "A1  - " . trim($a) . "\n";
		}						
		echo "JF  - " . $reference->secondary_title . "\n";
		if (isset($reference->issn))
		{
			echo "SN  - " . $reference->issn . "\n";
		}			
		echo "Y1  - " . $reference->year . "///\n";
		echo "VL  - " . $reference->volume . "\n";
		if (isset($reference->issue))
		{
			echo "IS  - " . $reference->issue . "\n";
		}
		echo "SP  - " . $reference->spage . "\n";
		echo "EP  - " . $reference->epage . "\n";

		if (isset($reference->tags))
		{
			foreach ($reference->tags as $tag)
			{
				if (is_array($tag))
				{
					foreach ($tag as $t)
					{
						echo "KW  - " . trim($t) . "\n";
					}
				}
				else
				{
					if (trim($tag) != '')
					{
						echo "KW  - " . trim($tag) . "\n";
					}
				}
			}						
		}
		if (isset($reference->abstract))
		{
			echo "N2  - " . $reference->abstract . "\n";
		}
		if (isset($reference->url))
		{
			echo "UR  - " . $reference->url . "\n";
		}
		if (isset($reference->pdf))
		{
			echo "L1  - " . $reference->pdf . "\n";
		}
		if (isset($reference->notes))
		{
			echo "N1  - " . $reference->notes . "\n";
		}
		
		echo "ER  - \n\n";
	}

	
}

// test

//$oai = new OaiHarvester('http://qir.kyushu-u.ac.jp/dspace-oai/', 'hdl_2324_25');

//$oai = new OaiHarvester('http://cite.biodiversitylibrary.org/oai/', '');

//$oai = new OaiHarvester('http://digitallibrary.amnh.org/dspace-oai/', 'hdl_2246_9');

//$oai = new OaiHarvester('http://deepblue.lib.umich.edu/dspace-oai/', 'hdl_2027.42_49534');

//$oai = new OaiHarvester('http://scholarspace.manoa.hawaii.edu/dspace-oai/', 'hdl_10125_364');

// Plazi ants
//$oai = new OaiHarvester('http://plazi.org:8080/dspace-oai/', 'hdl_10199_12');

//$oai->GetDateLastAccessed();
//$oai->Harvest();




?>