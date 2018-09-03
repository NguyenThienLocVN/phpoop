
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php 
mysqli_query("SET character_set_results=utf8");
include('config/db.php');


// Class to crawl the information
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
	public function setData($url) {
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
}

// Class to handling database
class Database
{
	private $conn;
	private $host = "localhost";
	private $username = "root";
	private $password = "123456";
	private $database = "crawl";

	public function __construct() {
		$this->conn = new mysqli($this->host, $this->username, 
			$this->password, $this->database);
	
		if(!$this->conn) {
			echo "Failed to connecto to : " . mysql_connect_error($this->conn);
		}
	}

	public function insertDatabase($tableName, $data)
	{	
		$string = "insert into ".$tableName." (";
		$string .= implode(",", array_keys($data)) . ') VALUES (';            
		$string .= "'" . implode("','", array_values($data)) . "')";  
		
		if(mysqli_query($this->conn, $string))  
		{  
			 return true;  
		}  
		else  
		{  
			 echo mysqli_error($this->conn);  
		}

	}

	public function __destruct()
	{
		$conn->close();
	}
}


// Custom class to crawl
class VnexpressCrawler extends Crawler
{
	public function getData(){
		$html = parent::setData($_GET['input-url']);

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
// Custom class to crawl
class VietnamnetCrawler extends Crawler
{
	public function getData(){
		$html = parent::setData($_GET['input-url']);

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

// Get domain name from input url
$url = $_GET['input-url'];
$parse = parse_url($url);
$domain = $parse['host'];


if($domain == 'vnexpress.net')
{
	$vnec = new VnexpressCrawler();
	$item = $vnec->getData();
}

if($domain == 'vietnamnet.vn')
{
	$vnnc = new VietnamnetCrawler();
	$item = $vnnc->getData();
}


// Add data to database
$db = new Database();
$insertArray = array(
	"domain" => $domain,
	"title" => $item['Title'],
	"content" => $item['Content']
);

$db->insertDatabase("data", $insertArray);
// mysql_query("SET NAMES 'utf8'");


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
