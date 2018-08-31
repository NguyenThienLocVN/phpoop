
<?php 
include('config/db.php');
class Crawler {
	public $headers; 
	public $userAgent; 
	public $compression;
	public $proxy;

	// Contruct function declare variable like HTTP Headers
	public function __construct($compression='gzip',$proxy='') {
		$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		$this->userAgent = 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)';
		$this->compression=$compression;
		$this->proxy=$proxy;
	}


	// Function get data from url
	public function getData($url) {
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->userAgent);

		if ($this->proxy) curl_setopt($process, CURLOPT_PROXY, $this->proxy);
			curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
			$return = curl_exec($process);
			curl_close($process);
		return $return;
	}

	public function fetchData(){
		$html = $this->getData($_GET['input-url']);

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
		print_r($_GET['input-url']);
	}
}

class VnexpressCrawler extends Crawler
{
	
}

class VietnamnetCrawler extends Crawler
{

}

$vnec = new Crawler();
$item = $vnec->fetchData();
?>




<!-- After get data, we fetch data into table -->
<a href="index.html">Back</a>
<table style="border:1px solid; width:500px; text-align:center; margin:auto;">
    <tr>
        <th>Categories</th>
        <th>Content</th>
    </tr>
    <tr>
        <td>Title</td>
        <td><?php print_r($item['Title']); ?></td>
    </tr>
    <tr>
        <td>Content</td>
        <td><?php print_r($item['Content']); ?></td>
    </tr>
</table>
