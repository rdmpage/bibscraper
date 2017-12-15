<?php

// Gets metadata from <meta> tags
// harvest OAI

require_once (dirname(__FILE__) . '/lib.php');
require_once (dirname(__FILE__) . '/utils.php');
require_once (dirname(__FILE__) . '/simplehtmldom_1_5/simple_html_dom.php');

// get list of articles for each issue

$issues = array(
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/400',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/400',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/402',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/402',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/403',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/403',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/405',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/405',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/406',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/406',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/408',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/408',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/410',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/410',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/419',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/419',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/420',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/420',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/421',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/421',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/422',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/422',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/424',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/424',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/426',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/426',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/428',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/428',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/431',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/431',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/433',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/433',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/434',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/434',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/436',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/436',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/437',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/437',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/438',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/438',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/481',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/481',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/592',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/592',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/647',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/647',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/650',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/650',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/368',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/368',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/392',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/392',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/397',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/397',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/398',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/398',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/399',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/399',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/412',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/412',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/414',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/414',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/415',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/415',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/417',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/417',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/418',
'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/418');

//$issues=array('http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401');

//'http://www.revistascientificas.udg.mx/index.php/DUG/issue/view/401/showToc'

$issues = array(
/*'http://journals.fcla.edu/jon/issue/view/4758',
'http://journals.fcla.edu/jon/issue/view/4457',
'http://journals.fcla.edu/jon/issue/view/4363',
'http://journals.fcla.edu/jon/issue/view/4275',
'http://journals.fcla.edu/jon/issue/view/4251',
'http://journals.fcla.edu/jon/issue/view/4245',
'http://journals.fcla.edu/jon/issue/view/4230',
'http://journals.fcla.edu/jon/issue/view/4210',
'http://journals.fcla.edu/jon/issue/view/4125',
'http://journals.fcla.edu/jon/issue/view/4073',
'http://journals.fcla.edu/jon/issue/view/4051',
'http://journals.fcla.edu/jon/issue/view/4026',
'http://journals.fcla.edu/jon/issue/view/3996',
'http://journals.fcla.edu/jon/issue/view/3974',
'http://journals.fcla.edu/jon/issue/view/3963',
'http://journals.fcla.edu/jon/issue/view/3960',
'http://journals.fcla.edu/jon/issue/view/3959',
'http://journals.fcla.edu/jon/issue/view/3932',
'http://journals.fcla.edu/jon/issue/view/3911',
'http://journals.fcla.edu/jon/issue/view/3827',
'http://journals.fcla.edu/jon/issue/view/3804',
'http://journals.fcla.edu/jon/issue/view/3803',
'http://journals.fcla.edu/jon/issue/view/3795',
'http://journals.fcla.edu/jon/issue/view/3762',
'http://journals.fcla.edu/jon/issue/view/3761',
'http://journals.fcla.edu/jon/issue/view/3760',
'http://journals.fcla.edu/jon/issue/view/3759',
'http://journals.fcla.edu/jon/issue/view/3758',
'http://journals.fcla.edu/jon/issue/view/3300',
'http://journals.fcla.edu/jon/issue/view/3299',
'http://journals.fcla.edu/jon/issue/view/3298',
'http://journals.fcla.edu/jon/issue/view/3297',
'http://journals.fcla.edu/jon/issue/view/3296',
'http://journals.fcla.edu/jon/issue/view/3295',
'http://journals.fcla.edu/jon/issue/view/3294',
'http://journals.fcla.edu/jon/issue/view/3293',
'http://journals.fcla.edu/jon/issue/view/3292',
'http://journals.fcla.edu/jon/issue/view/3291',
'http://journals.fcla.edu/jon/issue/view/3290',
'http://journals.fcla.edu/jon/issue/view/3289',
'http://journals.fcla.edu/jon/issue/view/3288',
'http://journals.fcla.edu/jon/issue/view/3287',
'http://journals.fcla.edu/jon/issue/view/3286',
'http://journals.fcla.edu/jon/issue/view/3285',
'http://journals.fcla.edu/jon/issue/view/3284',
'http://journals.fcla.edu/jon/issue/view/3283',
'http://journals.fcla.edu/jon/issue/view/3282',
'http://journals.fcla.edu/jon/issue/view/3281',
'http://journals.fcla.edu/jon/issue/view/3280',
'http://journals.fcla.edu/jon/issue/view/3364',
'http://journals.fcla.edu/jon/issue/view/3278',
'http://journals.fcla.edu/jon/issue/view/3277',
'http://journals.fcla.edu/jon/issue/view/3276',
'http://journals.fcla.edu/jon/issue/view/3275',
'http://journals.fcla.edu/jon/issue/view/3363',
'http://journals.fcla.edu/jon/issue/view/3362',
'http://journals.fcla.edu/jon/issue/view/3361',
'http://journals.fcla.edu/jon/issue/view/3360',
'http://journals.fcla.edu/jon/issue/view/3270',
'http://journals.fcla.edu/jon/issue/view/3359',
'http://journals.fcla.edu/jon/issue/view/3268',
'http://journals.fcla.edu/jon/issue/view/3267',
'http://journals.fcla.edu/jon/issue/view/3266',
'http://journals.fcla.edu/jon/issue/view/3265',
'http://journals.fcla.edu/jon/issue/view/3264',
'http://journals.fcla.edu/jon/issue/view/3263',
'http://journals.fcla.edu/jon/issue/view/3262',
'http://journals.fcla.edu/jon/issue/view/3261',
'http://journals.fcla.edu/jon/issue/view/3260',
'http://journals.fcla.edu/jon/issue/view/3358',
'http://journals.fcla.edu/jon/issue/view/3357',
'http://journals.fcla.edu/jon/issue/view/3257',
'http://journals.fcla.edu/jon/issue/view/3256',
'http://journals.fcla.edu/jon/issue/view/3255',
'http://journals.fcla.edu/jon/issue/view/3254',
'http://journals.fcla.edu/jon/issue/view/3253',
'http://journals.fcla.edu/jon/issue/view/3252',
'http://journals.fcla.edu/jon/issue/view/3251',
'http://journals.fcla.edu/jon/issue/view/3250',
'http://journals.fcla.edu/jon/issue/view/3249',
'http://journals.fcla.edu/jon/issue/view/3248',
'http://journals.fcla.edu/jon/issue/view/3247',
'http://journals.fcla.edu/jon/issue/view/3246',
'http://journals.fcla.edu/jon/issue/view/3245',
'http://journals.fcla.edu/jon/issue/view/3244',
'http://journals.fcla.edu/jon/issue/view/3243',
'http://journals.fcla.edu/jon/issue/view/3242',
'http://journals.fcla.edu/jon/issue/view/3241',
'http://journals.fcla.edu/jon/issue/view/3240',
'http://journals.fcla.edu/jon/issue/view/3356',
'http://journals.fcla.edu/jon/issue/view/3238',
'http://journals.fcla.edu/jon/issue/view/3237',
'http://journals.fcla.edu/jon/issue/view/3236',
'http://journals.fcla.edu/jon/issue/view/3235',
'http://journals.fcla.edu/jon/issue/view/3234',
'http://journals.fcla.edu/jon/issue/view/3233',
'http://journals.fcla.edu/jon/issue/view/3232',
'http://journals.fcla.edu/jon/issue/view/3355',
'http://journals.fcla.edu/jon/issue/view/3230',
'http://journals.fcla.edu/jon/issue/view/3229',
'http://journals.fcla.edu/jon/issue/view/3228',
'http://journals.fcla.edu/jon/issue/view/3227',
'http://journals.fcla.edu/jon/issue/view/3226',
'http://journals.fcla.edu/jon/issue/view/3225',
'http://journals.fcla.edu/jon/issue/view/3224',
'http://journals.fcla.edu/jon/issue/view/3223',
'http://journals.fcla.edu/jon/issue/view/3222',
'http://journals.fcla.edu/jon/issue/view/3221',
'http://journals.fcla.edu/jon/issue/view/3354',
'http://journals.fcla.edu/jon/issue/view/3219',
'http://journals.fcla.edu/jon/issue/view/3218',
'http://journals.fcla.edu/jon/issue/view/3353',
'http://journals.fcla.edu/jon/issue/view/3216',
'http://journals.fcla.edu/jon/issue/view/3215',
'http://journals.fcla.edu/jon/issue/view/3214',
'http://journals.fcla.edu/jon/issue/view/3352',
'http://journals.fcla.edu/jon/issue/view/3212',
'http://journals.fcla.edu/jon/issue/view/3211',
'http://journals.fcla.edu/jon/issue/view/3210',
'http://journals.fcla.edu/jon/issue/view/3209',
'http://journals.fcla.edu/jon/issue/view/3208',
'http://journals.fcla.edu/jon/issue/view/3207',
'http://journals.fcla.edu/jon/issue/view/3206',
'http://journals.fcla.edu/jon/issue/view/3205',
'http://journals.fcla.edu/jon/issue/view/3204',
'http://journals.fcla.edu/jon/issue/view/3351',
'http://journals.fcla.edu/jon/issue/view/3202',
'http://journals.fcla.edu/jon/issue/view/3201',
'http://journals.fcla.edu/jon/issue/view/3200',
'http://journals.fcla.edu/jon/issue/view/3199',
'http://journals.fcla.edu/jon/issue/view/3198',
'http://journals.fcla.edu/jon/issue/view/3197',
'http://journals.fcla.edu/jon/issue/view/3196',
'http://journals.fcla.edu/jon/issue/view/3350',
'http://journals.fcla.edu/jon/issue/view/3349',
'http://journals.fcla.edu/jon/issue/view/3193',
'http://journals.fcla.edu/jon/issue/view/3192',
'http://journals.fcla.edu/jon/issue/view/3191',*/
'http://journals.fcla.edu/jon/issue/view/3190',
'http://journals.fcla.edu/jon/issue/view/3348',
'http://journals.fcla.edu/jon/issue/view/3188',
'http://journals.fcla.edu/jon/issue/view/3187',
'http://journals.fcla.edu/jon/issue/view/3186',
'http://journals.fcla.edu/jon/issue/view/3185',
'http://journals.fcla.edu/jon/issue/view/3184',
'http://journals.fcla.edu/jon/issue/view/3183',
'http://journals.fcla.edu/jon/issue/view/3182',
'http://journals.fcla.edu/jon/issue/view/3181',
'http://journals.fcla.edu/jon/issue/view/3180',
'http://journals.fcla.edu/jon/issue/view/3179',
'http://journals.fcla.edu/jon/issue/view/3178',
'http://journals.fcla.edu/jon/issue/view/3177',
'http://journals.fcla.edu/jon/issue/view/3176',
'http://journals.fcla.edu/jon/issue/view/3175',
'http://journals.fcla.edu/jon/issue/view/3174',
'http://journals.fcla.edu/jon/issue/view/3173',
'http://journals.fcla.edu/jon/issue/view/3172',
'http://journals.fcla.edu/jon/issue/view/3171',
'http://journals.fcla.edu/jon/issue/view/3170',
'http://journals.fcla.edu/jon/issue/view/3169',
'http://journals.fcla.edu/jon/issue/view/3168',
'http://journals.fcla.edu/jon/issue/view/3167',
'http://journals.fcla.edu/jon/issue/view/3166',
'http://journals.fcla.edu/jon/issue/view/3165',
'http://journals.fcla.edu/jon/issue/view/3164',
'http://journals.fcla.edu/jon/issue/view/3163',
'http://journals.fcla.edu/jon/issue/view/3162',
'http://journals.fcla.edu/jon/issue/view/3161',
'http://journals.fcla.edu/jon/issue/view/3160',
'http://journals.fcla.edu/jon/issue/view/3159',
'http://journals.fcla.edu/jon/issue/view/3158',
'http://journals.fcla.edu/jon/issue/view/3157',
'http://journals.fcla.edu/jon/issue/view/3156',
'http://journals.fcla.edu/jon/issue/view/3155',
'http://journals.fcla.edu/jon/issue/view/3154',
'http://journals.fcla.edu/jon/issue/view/3153',
'http://journals.fcla.edu/jon/issue/view/3152',
'http://journals.fcla.edu/jon/issue/view/3151',
'http://journals.fcla.edu/jon/issue/view/3150',
'http://journals.fcla.edu/jon/issue/view/3149',
'http://journals.fcla.edu/jon/issue/view/3148',
'http://journals.fcla.edu/jon/issue/view/3147',
'http://journals.fcla.edu/jon/issue/view/3146',
'http://journals.fcla.edu/jon/issue/view/3145',
'http://journals.fcla.edu/jon/issue/view/3144',
'http://journals.fcla.edu/jon/issue/view/3143',
'http://journals.fcla.edu/jon/issue/view/3142',
'http://journals.fcla.edu/jon/issue/view/3141',
'http://journals.fcla.edu/jon/issue/view/3140',
'http://journals.fcla.edu/jon/issue/view/3139',
'http://journals.fcla.edu/jon/issue/view/3138',
'http://journals.fcla.edu/jon/issue/view/3137',
'http://journals.fcla.edu/jon/issue/view/3136',
'http://journals.fcla.edu/jon/issue/view/3135',
'http://journals.fcla.edu/jon/issue/view/3134',
'http://journals.fcla.edu/jon/issue/view/3133',
'http://journals.fcla.edu/jon/issue/view/3132',
'http://journals.fcla.edu/jon/issue/view/3131',
'http://journals.fcla.edu/jon/issue/view/3130',
'http://journals.fcla.edu/jon/issue/view/3129',
'http://journals.fcla.edu/jon/issue/view/3128',
'http://journals.fcla.edu/jon/issue/view/3127',
'http://journals.fcla.edu/jon/issue/view/3126',
'http://journals.fcla.edu/jon/issue/view/3125',
'http://journals.fcla.edu/jon/issue/view/3124',
'http://journals.fcla.edu/jon/issue/view/3347'
);

$issues = array(
'http://search.informit.com.au/browsePublication;py=2014;vol=131;res=IELHSS;issn=0042-5184;iss=1'
);

$issues = array(
'https://biotaxa.org/rce/issue/view/4264',
'https://biotaxa.org/rce/issue/view/4264',
'https://biotaxa.org/rce/issue/view/4266',
'https://biotaxa.org/rce/issue/view/4266',
'https://biotaxa.org/rce/issue/view/4267',
'https://biotaxa.org/rce/issue/view/4267',
'https://biotaxa.org/rce/issue/view/4270',
'https://biotaxa.org/rce/issue/view/4270',
'https://biotaxa.org/rce/issue/view/4271',
'https://biotaxa.org/rce/issue/view/4271',
'https://biotaxa.org/rce/issue/view/4273',
'https://biotaxa.org/rce/issue/view/4273',
'https://biotaxa.org/rce/issue/view/4275',
'https://biotaxa.org/rce/issue/view/4275',
'https://biotaxa.org/rce/issue/view/4316',
'https://biotaxa.org/rce/issue/view/4316',
'https://biotaxa.org/rce/issue/view/4511',
'https://biotaxa.org/rce/issue/view/4511'
);

foreach ($issues as $issue)
{
	$u = $issue;
	
	$u = $issue . '/showToc'; // OJS

	$html = get($u);
	
	$urls = array();
	
	if (preg_match_all('/<a href="(?<url>.*article\/view\/\d+)">/Uu', $html, $m))
	{
		foreach ($m['url'] as $url)
		{
			$urls[] = $url;
		}
	}
	
	if (preg_match_all('/<a href="(?<url>\/documentSummary;dn=\d+;res=IELHSS)"/', $html, $m))
	{
		foreach ($m['url'] as $url)
		{
			$urls[] = 'http://search.informit.com.au' . $url;
		}	
	}
		

	$count = 1;


	foreach ($urls as $url)
	{

		$html = get($url);

		$reference = new stdclass;
		$reference->authors = array();
		//$reference->id = $id;


		$dom = str_get_html($html);

		$metas = $dom->find('meta');

		/*
		foreach ($metas as $meta)
		{
			echo $meta->name . " " . $meta->content . "\n";
		}
		*/				

		foreach ($metas as $meta)
		{
			switch ($meta->name)
			{

				// DC

				case 'DC.title':
				case 'dc.Title':
					$reference->title =  $meta->content;
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				case 'DC.description':
				case 'DC.Description':
				case 'citation_abstract':
					$reference->abstract =  $meta->content;
					$reference->abstract = str_replace("\n", "", $reference->abstract);
					$reference->abstract = str_replace("&amp;", "&", $reference->abstract);
					$reference->abstract = preg_replace('/\s\s+/u', ' ', $reference->abstract);		
					$reference->abstract = preg_replace('/^\s+/u', '', $reference->abstract);		
					$reference->abstract = html_entity_decode($reference->abstract);
					break;
			
				case 'DC.Creator.PersonalName':
					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;	
			
				case 'DC.Source.ISSN':
					$reference->issn =  $meta->content;
					break;	

				// eprints

				case 'eprints.creators_name':
					$author = $meta->content;

					// clean
					if (preg_match('/^(?<lastname>.*),\s+(?<firstname>[A-Z][A-Z]+)$/u', $author, $m))
					{
						$parts = str_split($m['firstname']);
						$author = $m['lastname'] . ', ' . join(". ", $parts) . '.';
					}
					if (!in_array($author, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;

				case 'eprints.publication':
					$reference->journal =  $meta->content;
					break;

				case 'eprints.issn':
					$reference->issn =  $meta->content;
					break;


				case 'eprints.volume':
					$reference->volume =  $meta->content;
					break;

				case 'eprints.pagerange':
					$pages =  $meta->content;
					$parts = explode("-", $pages);
					if (count($parts) > 1)
					{
						$reference->spage = $parts[0];
						$reference->epage = $parts[1];
					}
					else
					{
						$reference->spage = $pages;
					}
					break;

				case 'eprints.date':
					if (preg_match('/^[0-9]{4}$/', $meta->content))
					{
						$reference->year = $meta->content;
					}

					if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
					{
						$reference->year = $m['year'];
					}
					break;

				case 'eprints.document_url':
					$reference->pdf =  urldecode($meta->content);
					break;

				// Google	
				case 'citation_authors':
				case 'citation_author':
	//							$reference->authors[] =  mb_convert_case($meta->content, MB_CASE_TITLE);

					if (!in_array($meta->content, $reference->authors))
					{
						$reference->authors[] =  $meta->content;
					}
					break;

				case 'citation_title':
					$reference->title = trim($meta->content);
					$reference->title = preg_replace('/\s\s+/u', ' ', $reference->title);
					break;

				case 'citation_doi':
					$reference->doi =  $meta->content;
					break;

				case 'citation_journal_title':
					$reference->journal =  $meta->content;
					$reference->genre = 'article';
					break;

				case 'citation_issn':
					if (!isset($reference->issn))
					{
						$reference->issn =  $meta->content;
					}
					break;

				case 'citation_volume':
					$reference->volume =  $meta->content;
					break;

				case 'citation_issue':
					$reference->issue =  $meta->content;
					break;

				case 'citation_firstpage':
					$reference->spage =  $meta->content;
			
					if (preg_match('/(?<spage>\d+)[-|-](?<epage>\d+)/u', $meta->content, $m))
					{
						$reference->spage =  $m['spage'];
						$reference->epage =  $m['epage'];
					}
					break;

				case 'citation_lastpage':
					$reference->epage =  $meta->content;
					break;

				case 'citation_abstract_html_url':
					$reference->url =  $meta->content;
					break;

				case 'citation_pdf_url':
					$reference->pdf =  $meta->content;
					break;
			
				case 'citation_fulltext_html_url':
					$reference->pdf =  $meta->content;
					//$reference->pdf = str_replace('/view/', '/download/', $reference->pdf);
					break;
			

				case 'citation_date':
					if (preg_match('/^[0-9]{4}$/', $meta->content))
					{
						$reference->year = $meta->content;
					}

					if (preg_match('/^(?<year>[0-9]{4})\//', $meta->content, $m))
					{
						$reference->year = $m['year'];
					}
					break;

				case 'DC.Date':
				case 'dc.Date':
					$reference->date = $meta->content;
					break;


				default:
					break;
			}
		}		
		//print_r($reference);

		if ($reference->issn == '2413-3299')
		{
			unset($reference->doi);
			$reference->issn = '1815-8242';
		}				

		if (isset($reference->pdf))
		{
			$reference->pdf = str_replace('/view/', '/download/', $reference->pdf);
		}

		if (($year != '') && !isset($reference->year))
		{
			$reference->year = $year;
		}


		if (isset($reference->issue))
		{
			if ($reference->issue == 0)
			{
				unset($reference->issue);
			}
		}

		// Dugesiana
		// Vol. 5, Núm. 2 (1998)
		if (preg_match('/Vol. \d+, Núm. \d+ \((?<year>[0-9]{4})\)/u', $html, $m))
		{
			$reference->year = $m['year'];
		}


		echo reference_to_ris($reference);

		// Give server a break every 10 items
		if (($count++ % 10) == 0)
		{
			$rand = rand(1000000, 3000000);
			echo "\n...sleeping for " . round(($rand / 1000000),2) . ' seconds' . "\n\n";
			usleep($rand);
		}
	}
}


?>