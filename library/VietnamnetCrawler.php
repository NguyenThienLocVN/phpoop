<?php
include('Crawler.php');

// Custom class to crawl
class VietnamnetCrawler extends Crawler
{
	public function outputData(){
		$html = parent::inputURL($_GET['input-url']);

		$result = array();
		if(preg_match('#<title>(.*?)</title>#', $html, $match))
		{
			$result['Title'] = $match[1];
		}
		if(preg_match('#<p>(.*?)</p>#', $html, $match))
		{
			$result['Content'] = $match[1];
		}
		return $result;
	}
}

?>