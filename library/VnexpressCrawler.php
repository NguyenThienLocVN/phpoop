<?php
include('Crawler.php');

// Custom class to crawl
class VnexpressCrawler extends Crawler
{
	public function outputData(){
		$headers = $_SERVER['REQUEST_URI']; //all link after 'localhost' : /phpoop/public/curl.php?input-url=https%3A%2F%2Fvnexpress.net
		$parse = parse_url($headers); //remove file path (php/public/curl.php)
		$last =  urldecode($parse['query']); //decode into url link
		$link = substr($last, 10, -14).'<br>'; //output: https://vnexpress.net/tin-tuc/thoi-su/...

		// Get domain name from input url
		$split = parse_url($link);
		$domain = $split['host'];
		
		$html = parent::inputURL($link);

		$result = array();
		if(preg_match('#<title>(.*?)</title>#', $html, $match))
		{
			$result['Title'] = $match[1];
		}
		if(preg_match('#<span>(.*?)</span>#', $html, $match))
		{
			$result['Content'] = $match[1];
		}
		return $result;
	}
}

?>