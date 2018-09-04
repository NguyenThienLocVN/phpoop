<?php
include('../library/Database.php');
include('../library/Crawler.php');
include('../library/VnexpressCrawler.php');
include('../library/VietnamnetCrawler.php');



if($domain == 'vnexpress.net')
{
	$vnec = new VnexpressCrawler();
	$item = $vnec->outputData();
}

if($domain == 'vietnamnet.vn')
{
	$vnnc = new VietnamnetCrawler();
	$item = $vnnc->outputData();
}


// Add data to database
$db = new Database();
$insertArray = array(
	"domain" => $domain,
	"title" => $item['Title'],
	"content" => $item['Content']
);

$db->insertDatabase("data", $insertArray);
?>




<!-- After get data, we fetch data into table -->
<a href="../index.html">Back</a>
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
