
	$current_year = 1983;
	$i = 0;
	$skipped = 0;
	foreach ($issues as $issue)
	{
		if (preg_match('/0044-5967(?<year>[0-9]{4})/', $issue, $m))
		{
			$year = $m['year'];
			if ($current_year != $year)
			{
				$i = 0;
				$skipped = 0;
				$current_year = $year;
			
			}
		}
		
		while ($i < 500)
		{
			$i++;
			$id = $issue . sprintf("%05d", $i);
			$url = $url_prefix . $id . $url_suffix;
			echo "-- $url\n";
			$reference = augment($url);
			if ($reference)
			{
				$skipped = 0;
				if (isset($reference->epage))
				{
					$i = $reference->epage;
				}
			}
			else
			{
				$skipped++;
			}
			if ($skipped > 5)
			{
				echo "-- skipped$skipped\n";
				break;
			}
		}
	
	
	}
	