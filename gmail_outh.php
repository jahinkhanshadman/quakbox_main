<?php ob_start();
session_start();
require_once('config.php');
$member_id = $_SESSION['SESS_MEMBER_ID'];
//$client_id = '354718587142-nkpjcbns45j864tigaaq8pnstlcfsqgb.apps.googleusercontent.com';
//$client_secret = 'vd9QBWvo-EdUcY1RpdyXx_Tj';
//$client_id = '485256929182-pe5onhcpir3dlrpe1kc8kb579erdi0pq.apps.googleusercontent.com';
//$client_secret = 'sTHJmNGt2R_-MVQDIXD10Eji';
$client_id = '348717858845-dikjlrhnrobd16ktq3ai60u8kkeq0vfe.apps.googleusercontent.com';
$client_secret = 'WarVxKbg8k89rnNeSLx1Kp-E';
$redirect_uri = $base_url.'gmail_outh.php';
$max_results = 2500;

$auth_code = $_GET["code"];

function curl_file_get_contents($url)
{
 $curl = curl_init();
 $userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)';
 
 curl_setopt($curl,CURLOPT_URL,$url);	//The URL to fetch. This can also be set when initializing a session with curl_init().
 curl_setopt($curl,CURLOPT_RETURNTRANSFER,TRUE);	//TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
 curl_setopt($curl,CURLOPT_CONNECTTIMEOUT,5);	//The number of seconds to wait while trying to connect.	
 
 curl_setopt($curl, CURLOPT_USERAGENT, $userAgent);	//The contents of the "User-Agent: " header to be used in a HTTP request.
 curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);	//To follow any "Location: " header that the server sends as part of the HTTP header.
 curl_setopt($curl, CURLOPT_AUTOREFERER, TRUE);	//To automatically set the Referer: field in requests where it follows a Location: redirect.
 curl_setopt($curl, CURLOPT_TIMEOUT, 10);	//The maximum number of seconds to allow cURL functions to execute.
 curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);	//To stop cURL from verifying the peer's certificate.
 curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
 
 $contents = curl_exec($curl);
 curl_close($curl);
 return $contents;
}

$fields=array(
    'code'=>  urlencode($auth_code),
    'client_id'=>  urlencode($client_id),
    'client_secret'=>  urlencode($client_secret),
    'redirect_uri'=>  urlencode($redirect_uri),
    'grant_type'=>  urlencode('authorization_code')
);
$post = '';
foreach($fields as $key=>$value) { $post .= $key.'='.$value.'&'; }
$post = rtrim($post,'&');

$curl = curl_init();
curl_setopt($curl,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');
curl_setopt($curl,CURLOPT_POST,5);
curl_setopt($curl,CURLOPT_POSTFIELDS,$post);
curl_setopt($curl, CURLOPT_RETURNTRANSFER,TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
$result = curl_exec($curl);
curl_close($curl);

$response =  json_decode($result);
$accesstoken = $response->access_token;

$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results='.$max_results.'&oauth_token='.$accesstoken;
$xmlresponse =  curl_file_get_contents($url);
if((strlen(stristr($xmlresponse,'Authorization required'))>0) && (strlen(stristr($xmlresponse,'Error '))>0))
{
	echo "<h2>OOPS !! Something went wrong. Please try reloading the page.</h2>";
	exit();
}

$xml =  new SimpleXMLElement($xmlresponse);
$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');
$result = $xml->xpath('//gd:email');
print_r($result);
foreach ($result as $title) {
  
  $email = $title->attributes()->address;
  
  $sql = mysqli_query($con, "SELECT id FROM import_contact WHERE member_id = '$member_id' AND email = '$email'");

if(mysqli_num_rows($sql) == 0){
mysqli_query($con, "insert into import_contact(member_id,email) VALUES('$member_id','$email')");
}
}
header("location: ".$base_url."import_contact.php");
exit();
?>