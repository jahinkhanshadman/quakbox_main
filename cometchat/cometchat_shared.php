<?php

/*

CometChat
Copyright (c) 2016 Inscripts

CometChat ('the Software') is a copyrighted work of authorship. Inscripts
retains ownership of the Software and any copies of it, regardless of the
form in which the copies may exist. This license is not a sale of the
original Software or any copies.

By installing and using CometChat on your server, you agree to the following
terms and conditions. Such agreement is either on your own behalf or on behalf
of any corporate entity which employs you or which you represent
('Corporate Licensee'). In this Agreement, 'you' includes both the reader
and any Corporate Licensee and 'Inscripts' means Inscripts (I) Private Limited:

CometChat license grants you the right to run one instance (a single installation)
of the Software on one web server and one web site for each license purchased.
Each license may power one instance of the Software on one domain. For each
installed instance of the Software, a separate license is required.
The Software is licensed only to you. You may not rent, lease, sublicense, sell,
assign, pledge, transfer or otherwise dispose of the Software in any form, on
a temporary or permanent basis, without the prior written consent of Inscripts.

The license is effective until terminated. You may terminate it
at any time by uninstalling the Software and destroying any copies in any form.

The Software source code may be altered (at your risk)

All Software copyright notices within the scripts must remain unchanged (and visible).

The Software may not be used for anything that would represent or is associated
with an Intellectual Property violation, including, but not limited to,
engaging in any activity that infringes or misappropriates the intellectual property
rights of others, including copyrights, trademarks, service marks, trade secrets,
software piracy, and patents held by individuals, corporations, or other entities.

If any of the terms of this Agreement are violated, Inscripts reserves the right
to revoke the Software license at any time.

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

*/
if(DB_SESSION == 1){
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'cometchat_session.php');
}

global $dbh,$userid,$memcache;

if(!function_exists("mysqli_connect")){

	function mysqli_connect($db_server,$db_username,$db_password,$db_name,$port){
		return mysql_connect($db_server.':'.$port,$db_username,$db_password);
	}

	function mysqli_real_escape_string($dbh,$userid){
		return mysql_real_escape_string($userid);
	}

	function mysqli_select_db($dbh,$db_name){
		return mysql_select_db($db_name,$dbh);
	}

	function mysqli_connect_errno($dbh){
		return !$dbh;
	}

	function mysqli_query($dbh,$sql){
		return mysql_query($sql);
	}

	function mysqli_multi_query($dbh,$sql){
		$sqlarr = explode(';', $sql);
		foreach ($sqlarr as $sql){
			mysql_query($sql);
		}
		return true;
	}

	function mysqli_error($dbh){
		return mysql_error();
	}

	function mysqli_fetch_assoc($query){
		return mysql_fetch_assoc($query);
	}

	function mysqli_insert_id($dbh){
		return mysql_insert_id();
	}

	function mysqli_num_rows($query){
		return mysql_num_rows($query);
	}

	function mysqli_affected_rows($query){
		return mysql_affected_rows($query);
	}
}

function cometchatDBConnect()
{
	global $dbh;
	$port = DB_PORT;
	if(empty($port)){
		$port = '3306';
	}

	$dbserver = explode(':',DB_SERVER);

	if(!empty($dbserver[1])){
	    $port = $dbserver[1];
	}

	$db_server = $dbserver[0];
	$dbh = mysqli_connect($db_server,DB_USERNAME,DB_PASSWORD,DB_NAME,$port);

	if (mysqli_connect_errno($dbh)) {
		$dbh = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_NAME,$port,'/tmp/mysql5.sock');
	}

	if (mysqli_connect_errno($dbh)) {
		echo "<h3>Unable to connect to database due to following error(s). Please check details in configuration file.</h3>";
		if (!defined('DEV_MODE') || (defined('DEV_MODE') && DEV_MODE != '1')){
			ini_set('display_errors','On');
			echo mysqli_connect_error($dbh);
			ini_set('display_errors','Off');
		}
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
		header('Retry-After: 10');  /* 10 seconds */
		exit();
	}

	mysqli_select_db($dbh,DB_NAME);
	mysqli_query($dbh,"SET NAMES utf8");
	mysqli_query($dbh,"SET CHARACTER SET utf8");
	mysqli_query($dbh,"SET COLLATION_CONNECTION = 'utf8_general_ci'");
}

function cometchatMemcacheConnect(){
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."cometchat_cache.php");
	global $memcache;
	if(MEMCACHE!=0 && MC_NAME=='memcachier'){
		$memcache = new MemcacheSASL();
		$memcache->addServer(MC_SERVER,MC_PORT);
		$memcache->setSaslAuthData(MC_USERNAME,MC_PASSWORD);
	}elseif(MEMCACHE!=0){
		phpFastCache::setup("path",dirname(__FILE__).DIRECTORY_SEPARATOR.'cache');
		phpFastCache::setup("storage",MC_NAME);
		$memcache = phpFastCache();
	}
}

function sanitize($text) {
	global $smileys_sorted;
	global $smileys_default;

	$temp = $text;
	$text = sanitize_core($text);
	$text = $text." ";
	$text = str_replace('&amp;','&',$text);

	$search  = "/((?#Email)(?:\S+\@)?(?#Protocol)(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?#Username:Password)(?:\w+:\w+@)?(?#Subdomains)(?:(?:[-\w]+\.)+(?#TopLevel Domains)(?:com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|travel|a[cdefgilmnoqrstuwz]|b[abdefghijmnorstvwyz]|c[acdfghiklmnoruvxyz]|d[ejkmnoz]|e[ceghrst]|f[ijkmnor]|g[abdefghilmnpqrstuwy]|h[kmnrtu]|i[delmnoqrst]|j[emop]|k[eghimnprwyz]|l[abcikrstuvy]|m[acdghklmnopqrstuvwxyz]|n[acefgilopruz]|om|p[aefghklmnrstwy]|qa|r[eouw]|s[abcdeghijklmnortuvyz]|t[cdfghjkmnoprtvwz]|u[augkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw]|aero|arpa|biz|com|coop|edu|info|int|gov|mil|museum|name|net|org|pro))(?#Port)(?::[\d]{1,5})?(?#Directories)(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|#)?(?#Query)(?:(?:\?(?:[-\w~!$+|\/.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*(?#Anchor)(?:#(?:[-\w~!$+|\/.,*:=]|%[a-f\d]{2})*)?)([^[:alpha:]]|\?)/i";

	if (DISABLE_LINKING != 1) {
		$text = preg_replace_callback($search, "autolink", $text);
	}
	if (DISABLE_SMILEYS != 1) {

		foreach ($smileys_sorted as $pattern => $result) {
			$title = str_replace("-"," ",ucwords(preg_replace("/\.(.*)/","",$result)));
			$class = str_replace("-"," ",preg_replace("/\.(.*)/","",$result));
			$text = str_replace(str_replace('&amp;','&',htmlspecialchars($pattern, ENT_NOQUOTES)).' ','<img class="cometchat_smiley" height="20" width="20" src="'.BASE_URL.'images/smileys/'.$result.'" title="'.$title.'"> ',$text.' ');
		}
	}
	return trim($text);
}

function sanitize_core($text) {
	global $bannedWords;
	$text = htmlspecialchars($text, ENT_NOQUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n"," <br> ",$text);

	for ($i=0;$i < count($bannedWords);$i++) {
		$text = str_ireplace(' '.$bannedWords[$i].' ',' '.$bannedWords[$i][0].str_repeat("*",strlen($bannedWords[$i])-1).' ',' '.$text.' ');
	}
	$text = trim($text);
	return $text;
}

function autolink($matches) {

	$link = $matches[1];

	if (preg_match("/\@/",$matches[1])) {
		$text = "<a href=\"mailto: {$link}\">{$matches[0]}</a>";
	} else {
		if (!preg_match("/(file|gopher|news|nntp|telnet|http|ftp|https|ftps|sftp):\/\//",$matches[1])) {
			$link = "http://".$matches[1];
		}

		if (DISABLE_YOUTUBE != 1 && preg_match('#(?:<\>]+href=\")?(?:http://)?((?:[a-zA-Z]{1,4}\.)?youtube.com/(?:watch)?\?v=(.{11}?))[^"]*(?:\"[^\<\>]*>)?([^\<\>]*)(?:)?#',$link,$match)) {

			/*

			// Bandwidth intensive function to fetch details about the YouTube video

			$contents = file_get_contents("http://gdata.youtube.com/feeds/api/videos/{$match[2]}?alt=json");

			$data = json_decode($contents);
			$title = $data->entry->title->{'$t'};

			if (strlen($title) > 50) {
				$title = substr($title,0,50)."...";
			}

			$description = substr($data->entry->content->{'$t'},0,100);
			$length = seconds2hms($data->entry->{'media$group'}->{'yt$duration'}->seconds);
			$rating = $data->entry->{'gd$rating'}->average;

			*/

			$text = '<a href="'.$link.'" target="_blank">'.$link.'</a><br/><a href="'.$link.'" target="_blank" style="display:inline-block;margin-bottom:3px;margin-top:3px;"><img src="http://img.youtube.com/vi/'.$match[2].'/default.jpg" border="0" style="padding:0px;display: inline-block; width: 120px;height:90px;">
			<div style="margin-top:-30px;text-align: right;width:110px;margin-bottom:10px;">
			<img height="20" border="0" width="20" style="opacity: 0.88;" src="'.BASE_URL.'images/play.gif"/>
			</div></a>';

		} else {
			$text = $matches[1];

			if (strlen($matches[1]) > 30) {
				$left = substr($matches[1],0,22);
				$right = substr($matches[1],-5);
				$matches[1] = $left."...".$right;
			}

			$text = "<a href=\"{$link}\" target=\"_blank\" title=\"{$text}\">{$matches[1]}</a>$matches[2]";
		}
	}


	return $text;
}

function seconds2hms ($sec, $padHours = true) {
	$hms = "";
	$hours = intval(intval($sec) / 3600);
	if ($hours != 0) {
		$hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':' : $hours. ':';
	}

	$minutes = intval(($sec / 60) % 60);
	$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';
$seconds = intval($sec % 60);
	$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
	return $hms;
}

function decode_controlmessage($message){
	if(strpos($message,'CC^CONTROL_') !== false){
		$message = str_ireplace('CC^CONTROL_','',$message);
		$cc_array = json_decode($message, true);
		return $cc_array;
	}else{
		return array();
	}
}

function encode_controlmessage($cc_array){
	if(!empty($cc_array)){
		$cc_array = json_encode($cc_array);
		$cc_array = 'CC^CONTROL_'.$cc_array;
		return $cc_array;
	}else{
		return '';
	}
}

function sendMessageTo($to,$message) {
	$response = sendMessage($to,$message,1);
	parsePusher($to,$response['id'],$response['m']);
}

function sendSelfMessage($to,$message,$sessionMessage = '') {
	$id_message = sendMessage($to,$message,2);
	return $id_message;
}

function sendMessage($to,$message,$dir = 0,$type = '') {
	global $userid;
	global $cookiePrefix;
	global $chromeReorderFix;
	global $plugins;
	global $blockpluginmode;
	$stickersflag = 0;

	if($dir === 0 && (empty($type) || ($type != 'filetransfer' && $type != 'handwrite'))) {
		$message = sanitize($message);
	}
	$block = 0;
	if (in_array('block',$plugins)) {
		$blockedIds = getBlockedUserIDs();
		if(in_array($to,$blockedIds)){
			$block = 2;
			if(!empty($_REQUEST['callbackfn']) && $_REQUEST['callbackfn']=='mobileapp'){
				$response = array();
				$response['id'] = "-1";
				$response['m'] = "You are blocked";
				sendCCResponse(json_encode($response));
			}
			if($blockpluginmode == 1 && in_array($to,$blockedIds)){
				if( $dir == 1){
					$dir = 3;
				} else {
					$dir = 2;
				}
			} else {
				exit;
			}
		}
	}

	if (!empty($to) && isset($message) && $message!='' && $userid > 0) {
		if(strpos($message,'CC^CONTROL_') !== false){
			$message = str_ireplace('CC^CONTROL_','',$message);
			$message = sanitize($message);
			$controlparameters = json_decode($message,true);
			switch($controlparameters['name']){
				case 'avchat':
					switch($controlparameters['method']){
						case 'endcall':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_ENDCALL_'.$controlparameters['params']['grp'];
						break;
						case 'rejectcall':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_REJECTCALL_'.$controlparameters['params']['grp'];
						break;
						case 'noanswer':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_NOANSWER_'.$controlparameters['params']['grp'];
						break;
						case 'canceloutgoingcall':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_CANCELCALL_'.$controlparameters['params']['grp'];
						break;
						case 'busycall':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_BUSYCALL_'.$controlparameters['params']['grp'];
						break;
						default :
							$message = '';
						break;
					}
					break;
				case 'audiochat':
					switch($controlparameters['method']){
						case 'endcall':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_ENDCALL_'.$controlparameters['params']['grp'];
						break;
						case 'rejectcall':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_REJECTCALL_'.$controlparameters['params']['grp'];
						break;
						case 'noanswer':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_NOANSWER_'.$controlparameters['params']['grp'];
						break;
						case 'canceloutgoingcall':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_CANCELCALL_'.$controlparameters['params']['grp'];
						break;
						case 'busycall':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_BUSYCALL_'.$controlparameters['params']['grp'];
						break;
						default :
							$message = '';
						break;
					}
					break;
				case 'broadcast':
					switch($controlparameters['method']){
						case 'endcall':
							$message = 'CC^CONTROL_PLUGIN_BROADCAST_ENDCALL_'.$controlparameters['params']['grp'];
						break;
						default :
							$message = '';
						break;
					}
					break;
				case 'stickers':
						$stickersflag = 1;
						$message = 'CC^CONTROL_'.$message;
					break;
				default :
					break;
			}
		}
		if($dir === 0 && $stickersflag == 0){
			$message = str_ireplace('CC^CONTROL_','',$message);
		}
		if (!empty($_REQUEST['callback'])) {
		    if (!empty($_SESSION['cometchat']['duplicates'][$_REQUEST['callback']])) {
		        exit;
		    }
		    $_SESSION['cometchat']['duplicates'][$_REQUEST['callback']] = 1;
		}
		$old=0;
		if (USE_COMET == 1) {

			$key = '';
			if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
				$key = KEY_A.KEY_B.KEY_C;
			}

			$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read, cometchat.direction) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."', '".mysqli_real_escape_string($GLOBALS['dbh'],$to)."','".mysqli_real_escape_string($GLOBALS['dbh'],$message)."','".mysqli_real_escape_string($GLOBALS['dbh'],getTimeStamp())."','".mysqli_real_escape_string($GLOBALS['dbh'],$old)."','".mysqli_real_escape_string($GLOBALS['dbh'],$dir)."')");
			$query = mysqli_query($GLOBALS['dbh'],$sql);
			if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }
			$insertedid = mysqli_insert_id($GLOBALS['dbh']);
			$response = array("id" => $insertedid, "m" => $message, "from" => $to, "direction" => $dir);
			if($dir <> 3) {
				$key_prefix = $dir === 2 ? $userid:$to;
				$from = $dir === 2 ? $to:$userid;
				$self = $dir === 2 ? 1 : 0;
				$channel = md5($key_prefix.$key);
				$comet = new Comet(KEY_A,KEY_B);
				if(method_exists($comet, 'processChannel')){
					$channel = processChannel($channel);
				}
				$info = $comet->publish(array(
					'channel' => $channel,
					'message' => array ( "id" => $insertedid, "from" => $from, "message" => ($message), "sent" => (getTimeStamp()), "self" => $self, "direction" => $dir)
				));
			}

		} else {
			$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read, cometchat.direction) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."', '".mysqli_real_escape_string($GLOBALS['dbh'],$to)."','".mysqli_real_escape_string($GLOBALS['dbh'],$message)."','".mysqli_real_escape_string($GLOBALS['dbh'],getTimeStamp())."','".mysqli_real_escape_string($GLOBALS['dbh'],$old)."','".mysqli_real_escape_string($GLOBALS['dbh'],$dir)."')");
			$query = mysqli_query($GLOBALS['dbh'],$sql);
			if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }
			$insertedid = mysqli_insert_id($GLOBALS['dbh']);
			$response = array("id" => $insertedid, "m" => $message, "from" => $to, "direction" => $dir);
   		}
		if (empty($_SESSION['cometchat']['cometchat_user_'.$to])) {
			$_SESSION['cometchat']['cometchat_user_'.$to] = array();
		}
		if ($dir <> 1){
			$_SESSION['cometchat']['cometchat_user_'.$to][$chromeReorderFix.$insertedid] = array("id" => $insertedid, "from" => $to, "message" => $response['m'], "self" => 1, "old" => 1, 'sent' => (getTimeStamp()), 'direction' => $dir);
		}
		if(defined('TAPATALK')){
			hooks_tapatalkPush($userid,$to,$response,$block);
		}
		if (function_exists('hooks_message')) {
			hooks_message($userid,$to,$message);
		}
   		return $response;
	}
}

function broadcastMessage($broadcast) {
	global $userid;
	global $cookiePrefix;
	global $chromeReorderFix;
	global $plugins;
	global $blockpluginmode;

	if (in_array('block',$plugins)) {
		$blockedIds = getBlockedUserIDs();
		for ($i=0; $i < sizeof($broadcast); $i++) {
			if($blockpluginmode == 1 && in_array($broadcast[$i]['to'],$blockedIds)){
				if( $broadcast[$i]['dir'] == 1){
					$broadcast[$i]['dir'] = 3;
				} else {
					$broadcast[$i]['dir'] = 2;
				}
			} else if(in_array($broadcast[$i]['to'],$blockedIds)){
				array_splice($broadcast, $i,1);
			}
		}
	}

	if (!empty($broadcast) && $userid > 0) {
		for ($i=0; $i < sizeof($broadcast); $i++) {
			if( empty($broadcast[$i]['to'])	|| !isset($broadcast[$i]['message']) || $broadcast[$i]['to'] == '' || $broadcast[$i]['message'] == ''){
				array_splice($broadcast, $i,1);
			}
			if($broadcast[$i]['dir'] === 0){
				$broadcast[$i]['message'] = str_ireplace('CC^CONTROL_','',$broadcast[$i]['message']);

			}
			sanitize($broadcast[$i]['message']);
		}
	}
	if (!empty($broadcast) && $userid > 0) {
		$sizeof_broadcast=sizeof($broadcast);
		if (!empty($_REQUEST['callback'])) {
			if (!empty($_SESSION['cometchat']['duplicates'][$_REQUEST['callback']])) {
				exit;
			}
			$_SESSION['cometchat']['duplicates'][$_REQUEST['callback']] = 1;
		}
		if (USE_COMET == 1) {
			$send_response = array();

			$sqlpart = "('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."', '".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[0]['to'])."','".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[0]['message'])."','".getTimeStamp()."',0,".$broadcast[0]['dir'].")";
			for ($i=1; $i < $sizeof_broadcast; $i++) {
				$sqlpart .= ",('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."', '".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[$i]['to'])."','".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[$i]['message'])."','".getTimeStamp()."',0,".$broadcast[$i]['dir'].")";
			}

			$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read, cometchat.direction) values".$sqlpart);
			$query = mysqli_query($GLOBALS['dbh'],$sql);
			$insertedid = mysqli_insert_id($GLOBALS['dbh']);

			for ($i=0; $i < $sizeof_broadcast; $i++) {
				$broadcast[$i]['message'] = $broadcast[$i]['message'];
				$response = array("id" => $insertedid+$i, "m" => $broadcast[$i]['message'], "from"=> $broadcast[$i]['to'], "direction" => $broadcast[$i]['dir']);
				array_push($send_response, $response);
			}

			if(strlen($insertedid)<13){
				for ($i=0; $i <$sizeof_broadcast; $i++) {
					if (empty($_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to']])) {
						if (empty($_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to'].'_clear'])) {
							unset($_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to'].'_clear']);
						}
						$_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to']] = array();
					}
					if($broadcast[$i]['dir']!=1){
						$_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to']][$chromeReorderFix.($insertedid+$i)] = array("id" => ($insertedid+$i), "from" => $broadcast[$i]['to'], "message" => $broadcast[$i]['message'], "self" => 1, "old" => 1, 'sent' => (getTimeStamp()), 'direction' => $broadcast[$i]['dir']);
					}

					if (function_exists('hooks_message')) {
						hooks_message($userid,$broadcast[$i]['to'],$broadcast[$i]['message']);
					}
				}
			}
		} else {
			$sqlpart = "('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."', '".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[0]['to'])."','".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[0]['message'])."','".mysqli_real_escape_string($GLOBALS['dbh'],getTimeStamp())."',0,".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[0]['dir']).")";
			for ($i=1; $i < $sizeof_broadcast; $i++) {
				$sqlpart .= ",('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."', '".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[$i]['to'])."','".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[$i]['message'])."','".mysqli_real_escape_string($GLOBALS['dbh'],getTimeStamp())."',0,".mysqli_real_escape_string($GLOBALS['dbh'],$broadcast[$i]['dir']).")";
			}
			$sql = ("insert into cometchat (cometchat.from,cometchat.to,cometchat.message,cometchat.sent,cometchat.read, cometchat.direction) values".$sqlpart);

			$query = mysqli_query($GLOBALS['dbh'],$sql);

			if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }

			$insertedid = mysqli_insert_id($GLOBALS['dbh']);
			$send_response = array();
			for ($i=0; $i <$sizeof_broadcast; $i++) {
				$response = array("id" => $insertedid+$i, "m" => $broadcast[$i]['message'], "from"=> $broadcast[$i]['to'], "direction" => $broadcast[$i]['dir']);
				array_push($send_response, $response);
				if (empty($_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to']])) {
					if (empty($_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to'].'_clear'])) {
						unset($_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to'].'_clear']);
					}
					$_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to']] = array();
				}

				if($broadcast[$i]['dir']!=1){
					$_SESSION['cometchat']['cometchat_user_'.$broadcast[$i]['to']][$chromeReorderFix.$response['id']] = array("id" => $response['id'], "from" => $broadcast[$i]['to'], "message" => $response['m'], "self" => 1, "old" => 1, 'sent' => (getTimeStamp()), 'direction' => $broadcast[$i]['dir']);
				}

				if (function_exists('hooks_message')) {
					hooks_message($userid,$broadcast[$i]['to'],$broadcast[$i]['message']);
				}
			}

		}
		return $send_response;
	}
}

function getBlockedUserIDs($receive=0){
	global $plugins;
	global $userid;
	global $blockpluginmode;
	$blockedIds = array();
	if (in_array('block',$plugins)) {
		if($receive == 0) {
			if(!is_array($blockedIds = getCache('blocked_id_of_'.$userid))){
				$blockedIds = array();
				$sql = ("select group_concat(blockedid) blockedids from (select fromid as blockedid from cometchat_block where toid = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."' UNION select toid as blockedid from cometchat_block where fromid = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."') as blocked");
				$query = mysqli_query($GLOBALS['dbh'],$sql);
				$blockedId = mysqli_fetch_assoc($query);
				if (!empty($blockedId['blockedids'])) {
					$blockedIds = explode(',',$blockedId['blockedids']);
				}
				setCache('blocked_id_of_'.$userid,$blockedIds,3600);
			}
		} else if(!is_array($blockedIds = getCache('blocked_id_of_receive_'.$userid)) && $receive == 1) {
			$blockedIds = array();
			$sql = ("select group_concat(toid) blockedids from cometchat_block where fromid = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."'");
			$query = mysqli_query($GLOBALS['dbh'],$sql);
			$blockedId = mysqli_fetch_assoc($query);
			if (!empty($blockedId['blockedids'])) {
				$blockedIds = explode(',',$blockedId['blockedids']);
			}
			setCache('blocked_id_of_receive_'.$userid,$blockedIds,3600);
		}
	}
	return $blockedIds;
}

function publishCometMessages($broadcast,$id) {
	global $userid;
	global $plugins;
	global $blockpluginmode;

	if (in_array('block',$plugins)) {
		$blockedIds = getBlockedUserIDs();
		for ($i=0; $i < sizeof($broadcast); $i++) {
			if($blockpluginmode == 1 && in_array($broadcast[$i]['to'],$blockedIds)){
				if( $broadcast[$i]['dir'] == 1){
					$broadcast[$i]['dir'] = 3;
				} else {
					$broadcast[$i]['dir'] = 2;
				}
			} else if(in_array($broadcast[$i]['to'],$blockedIds)){
				array_splice($broadcast, $i,1);
			}
		}
	}
	$sizeof_broadcast=sizeof($broadcast);
	for ($i=0; $i < $sizeof_broadcast; $i++) {
		$insertedid = $id+$i;
		$key = '';
		if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
			$key = KEY_A.KEY_B.KEY_C;
		}
		$key_prefix = $broadcast[$i]['dir'] === 2 ? $userid:$broadcast[$i]['to'];
		$from = $broadcast[$i]['dir'] === 2 ? $broadcast[$i]['to']:$userid;
		$self = $broadcast[$i]['dir'] === 2 ? 1 : 0;
		$channel = md5($key_prefix.$key);
		$comet = new Comet(KEY_A,KEY_B);
		if(method_exists($comet, 'processChannel')){
			$channel = processChannel($channel);
		}
		if($broadcast[$i]['dir'] != 3 && empty($broadcast[$i]['type'])){
			$info = $comet->publish(array(
				'channel' => $channel,
				'message' => array ( "id" => $insertedid, "from" => $from, "message" => ($broadcast[$i]['message']), "sent" => getTimeStamp(),"self" => $self,"direction" => $broadcast[$i]['dir'])
				));

		} else{
			$channel = md5($userid.$key);
			$self = 1;
			$from = $broadcast[$i]['to'];
			$info = $comet->publish(array(
				'channel' => $channel,
				'message' => array ( "id" => $insertedid, "from" => $from, "message" => ($broadcast[$i]['message']), "sent" => getTimeStamp(),"self" => $self,"direction" => $broadcast[$i]['dir'])
				));
		}

		if (function_exists('hooks_message')) {
			hooks_message($userid,$broadcast[$i]['to'],$broadcast[$i]['message']);
		}
	}
}

function sendChatroomMessage($to = 0,$message = '',$notsilent = 1) {
	global $userid;
	global $cookiePrefix;
	global $bannedUserIDs;
	$stickersflag = 0;

	if(($to == 0 && empty($_POST['currentroom'])) || ($message == '' && $notsilent == 0) || (isset($_POST['message']) && $_POST['message'] == '') || empty($userid) || in_array($userid, $bannedUserIDs)){
		return;
	}

	if (isset($_POST['message']) && !empty($_POST['currentroom'])) {
		$to = mysqli_real_escape_string($GLOBALS['dbh'],$_POST['currentroom']);
		$message = $_POST['message'];
	}

	if(isset($message) && $message != '') {
		if(strpos($message,'CC^CONTROL_') !== false){
			$message = str_ireplace('CC^CONTROL_','',$message);
			$message = sanitize($message);
			$controlparameters = json_decode($message,true);
			$chatroommode = $controlparameters['params']['chatroommode'];
			switch($controlparameters['name']){
				case 'avchat':
					$grp = $controlparameters['params']['grp'];
					switch($controlparameters['method']){
						case 'endcall':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_ENDCALL_'.$grp.'_'.$chatroommode;
						break;
						case 'rejectcall':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_REJECTCALL_'.$grp.'_'.$chatroommode;
						break;
						case 'noanswer':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_NOANSWER_'.$grp.'_'.$chatroommode;
						break;
						case 'canceloutgoingcall':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_CANCELCALL_'.$grp.'_'.$chatroommode;
						break;
						case 'busycall':
							$message = 'CC^CONTROL_PLUGIN_AVCHAT_BUSYCALL_'.$grp.'_'.$chatroommode;
						break;
						default :
							$message = '';
						break;
					}
					break;
				case 'audiochat':
					$grp = $controlparameters['params']['grp'];
					switch($controlparameters['method']){
						case 'endcall':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_ENDCALL_'.$grp.'_'.$chatroommode;
						break;
						case 'rejectcall':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_REJECTCALL_'.$grp.'_'.$chatroommode;
						break;
						case 'noanswer':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_NOANSWER_'.$grp.'_'.$chatroommode;
						break;
						case 'canceloutgoingcall':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_CANCELCALL_'.$grp.'_'.$chatroommode;
						break;
						case 'busycall':
							$message = 'CC^CONTROL_PLUGIN_AUDIOCHAT_BUSYCALL_'.$grp.'_'.$chatroommode;
						break;
						default :
							$message = '';
						break;
					}
					break;
				case 'broadcast':
					$grp = $controlparameters['params']['grp'];
					switch($controlparameters['method']){
						case 'endcall':
							$message = 'CC^CONTROL_PLUGIN_BROADCAST_ENDCALL_'.$grp.'_'.$chatroommode;
						break;
						default :
							$message = '';
						break;
					}
					break;
				case 'stickers':
					$stickersflag = 1;
					$message = 'CC^CONTROL_'.$message;
					break;
				case 'chatroom':
					$delid = $controlparameters['params']['id'];
					switch($controlparameters['method']){
						case 'deletemessage':
							$message = 'CC^CONTROL_deletemessage_'.$delid;
						break;
						case 'kicked':
							$message = 'CC^CONTROL_kicked_'.$delid;
						break;
						case 'banned':
							$message = 'CC^CONTROL_banned_'.$delid;
						break;
						default :
							$message = '';
						break;
					}
					break;
				default :
					break;
			}
		}
	}

	if($notsilent !== 0 && $stickersflag == 0){
		$message = str_ireplace('CC^CONTROL_','',$message);
		$message = sanitize($message);
	}

	$styleStart = '';
	$styleEnd = '';

	if (!empty($_COOKIE[$cookiePrefix.'chatroomcolor']) && preg_match('/^[a-f0-9]{6}$/i', $_COOKIE[$cookiePrefix.'chatroomcolor']) && $notsilent == 1 && $stickersflag == 0) {
		$styleStart = '<span style="color:#'.mysqli_real_escape_string($GLOBALS['dbh'],$_COOKIE[$cookiePrefix.'chatroomcolor']).'">';
		$styleEnd = '</span>';
	}
	$timestamp = getTimeStamp();
	if (USE_COMET == 1 && COMET_CHATROOMS == 1) {

		if (empty($_SESSION['cometchat']['username'])) {
			$name = '';
			$sql = getUserDetails($userid);

			if($userid>10000000) $sql = getGuestDetails($userid);
			$result = mysqli_query($GLOBALS['dbh'],$sql);

			if($row = mysqli_fetch_assoc($result)) {
				if (function_exists('processName')) {
					$row['username'] = processName($row['username']);
				}
				$name = $row['username'];
			}
			$_SESSION['cometchat']['username'] = $name;
		} else {
			$name = $_SESSION['cometchat']['username'];
		}

		if (!empty($name)) {
			$sql = ("insert into cometchat_chatroommessages (userid,chatroomid,message,sent) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."', '".mysqli_real_escape_string($GLOBALS['dbh'],$to)."','".mysqli_real_escape_string($GLOBALS['dbh'],$styleStart).mysqli_real_escape_string($GLOBALS['dbh'],$message).mysqli_real_escape_string($GLOBALS['dbh'],$styleEnd)."','".$timestamp."')");
			$query = mysqli_query($GLOBALS['dbh'],$sql);
			$insertedid = mysqli_insert_id($GLOBALS['dbh']);
			$channel = md5('chatroom_'.$to.KEY_A.KEY_B.KEY_C);
			$comet = new Comet(KEY_A,KEY_B);
			if(method_exists($comet, 'processChannel')){
				$channel = processChannel($channel);
			}
			if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }
			$info = $comet->publish(array(
					'channel' => $channel,
					'message' => array ( "id" => $insertedid, "from" => $name, "fromid"=> $userid, "message" => $styleStart.$message.$styleEnd, "sent" => ($timestamp*1000))
				));
			if($notsilent == 1){
				sendCCResponse(json_encode(array("id" => $insertedid,"m" => $styleStart.$message.$styleEnd)));
			}
		}
	} else {
		$sql = ("insert into cometchat_chatroommessages (userid,chatroomid,message,sent) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."', '".mysqli_real_escape_string($GLOBALS['dbh'],$to)."','".mysqli_real_escape_string($GLOBALS['dbh'],$styleStart).mysqli_real_escape_string($GLOBALS['dbh'],$message).mysqli_real_escape_string($GLOBALS['dbh'],$styleEnd)."','".mysqli_real_escape_string($GLOBALS['dbh'],$timestamp)."')");
		$query = mysqli_query($GLOBALS['dbh'],$sql);
		$insertedid = mysqli_insert_id($GLOBALS['dbh']);
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }
		if($notsilent == 1){
			sendCCResponse(json_encode(array("id" => $insertedid,"m" => $styleStart.$message.$styleEnd)));
		}
	}

	parsePusher($to,$insertedid,$message,'1',0,$timestamp);

	$sql = ("update cometchat_chatrooms set lastactivity = '".mysqli_real_escape_string($GLOBALS['dbh'],$timestamp)."' where id = '".mysqli_real_escape_string($GLOBALS['dbh'],$to)."'");
	$query = mysqli_query($GLOBALS['dbh'],$sql);

	if($notsilent == 0) {
			return $insertedid;
		}
}

function sendAnnouncement($to,$message) {
	global $userid;

	if (!empty($to) && isset($message)) {

		$sql = ("insert into cometchat_announcements (announcement,time,`to`) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$message)."', '".mysqli_real_escape_string($GLOBALS['dbh'],getTimeStamp())."','".mysqli_real_escape_string($GLOBALS['dbh'],$to)."')");
		$query = mysqli_query($GLOBALS['dbh'],$sql);

		if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }
	}
}
function getPrevMessages($id){
	global $messages;
	global $userid;
	global $chromeReorderFix;
	global $prependLimit;

	if(!empty($_SESSION['cometchat']['cometchat_user_'.$id.'_clear'])){
		return;
	}

	$prelimit = bigintval($prependLimit);
	$messages = array();
	$condition = '';
	if(!empty($_REQUEST['lastid'])){
		$condition = " and (cometchat.id < '".mysqli_real_escape_string($GLOBALS['dbh'],$_REQUEST['lastid'])."') ";
	}

	$sql = ("select * from cometchat where ((cometchat.from = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."' and cometchat.to = '".mysqli_real_escape_string($GLOBALS['dbh'],$id)."' and direction <>1) or ( cometchat.from = '".mysqli_real_escape_string($GLOBALS['dbh'],$id)."' and cometchat.to = '".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."' and direction <>2 )) and cometchat.direction <> 3 $condition order by cometchat.id desc limit $prelimit;");

	$query = mysqli_query($GLOBALS['dbh'],$sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }

	while ($chat = mysqli_fetch_assoc($query)) {
		$self = 0;
		$old = 0;
		if ($chat['from'] == $userid) {
			$chat['from'] = $chat['to'];
			$self = 1;
			$old = 1;
		}

		if ($chat['read'] == 1) {
			$old = 1;
		}

		$messages[$chromeReorderFix.$chat['id']] = array('id' => $chat['id'], 'from' => $chat['from'], 'message' => $chat['message'], 'self' => $self, 'old' => $old, 'sent' => ($chat['sent']), 'direction' => $chat['direction']);
	}
}

function cmp($a, $b) {
	return $a['id'] - $b['id'];
}

function getChatboxData($id) {
	global $messages;
	global $userid;
	global $chromeReorderFix;
	global $prependLimit;
	if(empty($_REQUEST['prepend'])){
		if(USE_COMET == 1 && !empty($id)) {
			if(!empty($_SESSION['cometchat']['cometmessagesafter'])) {
				$limit   = 10;
				$prelimit = ' limit '.intval($limit);
				if(!empty($_REQUEST['callbackfn']) && $_REQUEST['callbackfn'] == 'mobileapp'){
					$prelimit = ' limit 10';
				}
				if (!empty($_SESSION['cometchat']['cometchat_user_'.$id])) {
					$messages = array_merge($messages,$_SESSION['cometchat']['cometchat_user_'.$id]);
				}
				$moremessages = array();
				$messagesafter = $_SESSION['cometchat']['cometmessagesafter'];
				if (!empty($_SESSION['cometchat']['cometchat_user_'.$id.'_clear']) && $_SESSION['cometchat']['cometchat_user_'.$id.'_clear']['timestamp'] > $_SESSION['cometchat']['cometmessagesafter']) {
					$messagesafter = $_SESSION['cometchat']['cometchat_user_'.$id.'_clear']['timestamp'];
				}
				$sql = ("select * from cometchat where ((cometchat.from = ".mysqli_real_escape_string($GLOBALS['dbh'],$userid)." and cometchat.to = ".mysqli_real_escape_string($GLOBALS['dbh'],$id)." and direction <>1) or ( cometchat.from = ".mysqli_real_escape_string($GLOBALS['dbh'],$id)." and cometchat.to = ".mysqli_real_escape_string($GLOBALS['dbh'],$userid)." and direction <>2 )) and cometchat.direction <> 3 order by cometchat.id desc ".mysqli_real_escape_string($GLOBALS['dbh'],$prelimit).";");
				$query = mysqli_query($GLOBALS['dbh'],$sql);
				while($message = mysqli_fetch_assoc($query)) {
					if ($message['from'] == $id && $message['sent'] >= intval($messagesafter/1000)) {
						$self = 0;
						if ($message['from'] == $userid) {
							$message['from'] = $message['to'];
							$self = 1;
						}

						$moremessages[$chromeReorderFix.$message['id']] = array("id" => $message['id'], "from" => $message['from'], "message" => $message['message'], "self" => $self, "old" => 1, 'sent' => ($message['sent']), 'direction' => $message['direction']);
					}
				}
				if(!empty($id) && empty($_SESSION['cometchat']['cometchat_user_'.$id])){
					getPrevMessages($id);
				}
				$messages = array_merge($messages,$moremessages);
				usort($messages,"cmp");
			}else{
				if (!empty($id) && !empty($_SESSION['cometchat']['cometchat_user_'.$id])) {
					$messages = array_merge($messages,$_SESSION['cometchat']['cometchat_user_'.$id]);
				}
			}
		} else {
			if (!empty($id) && !empty($_SESSION['cometchat']['cometchat_user_'.$id])) {
				$messages = array_replace($messages,$_SESSION['cometchat']['cometchat_user_'.$id]);
			}
			if(!empty($id) && empty($_SESSION['cometchat']['cometchat_user_'.$id])){
				getPrevMessages($id);
				$messages = array_reverse($messages);
			}
		}
	} else {
		$prelimit = intval($prependLimit);
		$messages = array();
		if($_REQUEST['prepend'] != '-1'){
			$prepend = bigintval($_REQUEST['prepend']);
			$sql = ("select * from cometchat where ((cometchat.from = ".mysqli_real_escape_string($GLOBALS['dbh'],$userid)." and cometchat.to = ".mysqli_real_escape_string($GLOBALS['dbh'],$id)." and direction <>1) or ( cometchat.from = ".mysqli_real_escape_string($GLOBALS['dbh'],$id)." and cometchat.to = ".mysqli_real_escape_string($GLOBALS['dbh'],$userid)." and direction <> 2)) and (cometchat.id < $prepend)  and cometchat.direction <> 3 order by cometchat.id desc limit $prelimit;");
		} else {
			$sql = ("select * from cometchat where ((cometchat.from = ".mysqli_real_escape_string($GLOBALS['dbh'],$userid)." and cometchat.to = ".mysqli_real_escape_string($GLOBALS['dbh'],$id)." and direction <>1) or ( cometchat.from = ".mysqli_real_escape_string($GLOBALS['dbh'],$id)." and cometchat.to = ".mysqli_real_escape_string($GLOBALS['dbh'],$userid)." and direction <>2 )) and cometchat.direction <> 3 order by cometchat.id desc limit $prelimit;");
		}
		$query = mysqli_query($GLOBALS['dbh'],$sql);
		if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }
		while ($chat = mysqli_fetch_assoc($query)) {
			$self = 0;
			$old = 0;
			if ($chat['from'] == $userid) {
				$chat['from'] = $chat['to'];
				$self = 1;
				$old = 1;
			}
			if ($chat['read'] == 1) {
				$old = 1;
			}

			$messages[$chromeReorderFix.$chat['id']] = array('id' => $chat['id'], 'from' => $chat['from'], 'message' => $chat['message'], 'self' => $self, 'old' => $old, 'sent' => ($chat['sent']), 'direction' => $chat['direction']);
		}
		$messages = array_reverse($messages);
	}
}

function socialLogin($social_details) {
	$userid = 0;
	if(USE_CCAUTH == 1) {
		if(empty($social_details->firstName)){
			$social_details->firstName = $social_details->displayName;
		}
		$sql = "select ".DB_USERTABLE.".".DB_USERTABLE_USERID." from ".DB_USERTABLE." where ".DB_USERTABLE.".".DB_USERTABLE_USERNAME." = '".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->network_name)."_".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->identifier)."'";
		$result = mysqli_query($GLOBALS['dbh'],$sql);
	    if($row = mysqli_fetch_assoc($result)){
	        $sql = "update ".DB_USERTABLE." set ".DB_USERTABLE.".".DB_USERTABLE_NAME."='".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->firstName)."',".DB_AVATARFIELD."='".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->photoURL)."',".DB_USERTABLE.".".DB_LINKFIELD."='".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->profileURL)."' where ".DB_USERTABLE.".".DB_USERTABLE_USERNAME."='".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->network_name)."_".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->identifier)."'";
	        mysqli_query($GLOBALS['dbh'],$sql);
	        if(!empty($row[DB_USERTABLE_USERID])){
	            $userid = $row[DB_USERTABLE_USERID];
	        }
	    }else{
	        $sql = "insert into ".DB_USERTABLE." (".DB_USERTABLE.".".DB_USERTABLE_USERNAME.",".DB_USERTABLE.".".DB_USERTABLE_NAME.",".DB_AVATARFIELD.",".DB_USERTABLE.".".DB_LINKFIELD.",".DB_USERTABLE.".".DB_GROUPFIELD.") values ( '".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->network_name)."_".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->identifier)."','".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->firstName)."','".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->photoURL)."','".mysqli_real_escape_string($GLOBALS['dbh'],$social_details->profileURL)."','".mysqli_real_escape_string($GLOBALS['dbh'],ucfirst($social_details->network_name))."')";
	        mysqli_query($GLOBALS['dbh'],$sql);
	        $userid = mysqli_insert_id($GLOBALS['dbh']);
	    }
	    $_SESSION['cometchat']['userid'] = $userid;
	    $_SESSION['cometchat']['ccauth'] = '1';
	} else if (function_exists('hooks_social_login')){
		$userid = hooks_social_login($social_details);
	}
    return $userid;
}

function comparetime($a, $b) { return strnatcmp($a['sent'], $b['sent']); }

function text_translate($text, $from = 'en', $to = 'en') {
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR.'realtimetranslate'.DIRECTORY_SEPARATOR.'translate.php');
	return translate_text($text,$from,$to);
}

function unescapeUTF8EscapeSeq($str) {
	return preg_replace_callback("/\\\u([0-9a-f]{4})/i", create_function('$matches', 'return bin2utf8(hexdec($matches[1]));'), $str);
}

function bin2utf8($bin) {
	if ($bin <= 0x7F) {
		return chr($bin);
	} elseif ($bin >= 0x80 && $bin <= 0x7FF) {
		return pack("C*", 0xC0 | $bin >> 6, 0x80 | $bin & 0x3F);
	} else if ($bin >= 0x800 && $bin <= 0xFFF) {
		return pack("C*", 0xE0 | $bin >> 11, 0x80 | $bin >> 6 & 0x3F, 0x80 | $bin & 0x3F);
	} else if ($bin >= 0x10000 && $bin <= 0x10FFFF) {
		return pack("C*", 0xE0 | $bin >> 17, 0x80 | $bin >> 12 & 0x3F, 0x80 | $bin >> 6& 0x3F, 0x80 | $bin & 0x3F);
	}
}

function checkcURL($http = 0, $url = '', $params = '', $return = 0, $cookiefile = '') {
	if (!function_exists('curl_init')) {
		return false;
	}
	if (empty($url)) {
		if ($http == 0) {
			$url = 'http://www.microsoft.com';
		} else {
			$url = 'https://www.microsoft.com';
		}
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_URL, $url);
	if (!empty($cookiefile)) {
		curl_setopt($ch, CURLOPT_COOKIEJAR, $cookiefile);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $cookiefile);
	}
	if ($return == 1) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
	}

	$data = curl_exec($ch);
	curl_close($ch);
	if ($return == 1) {
		return $data;
	}
	if (empty($data)) {
		return false;
	}
	return true;
}

function setCache($key,$contents,$timeout = 60) {
	if (MEMCACHE == 0 || empty($contents) || empty($key)) {
		return false;
	}
	removeCache($key);
	if(!empty($GLOBALS['cookiePrefix'])){
		$key.=$GLOBALS['cookiePrefix'];
	}
	if(!empty($GLOBALS['chromeReorderFix'])){
		$key.=$GLOBALS['chromeReorderFix'];
	}
	$GLOBALS['memcache']->set($key,$contents,$timeout);
}

function getCache($key) {
	if (MEMCACHE == 0  || empty($key)) {
		return;
	}
	if(!empty($GLOBALS['cookiePrefix'])){
		$key.=$GLOBALS['cookiePrefix'];
	}
	if(!empty($GLOBALS['chromeReorderFix'])){
		$key.=$GLOBALS['chromeReorderFix'];
	}
	return $GLOBALS['memcache']->get($key);
}

function removeCache($key) {
	if (MEMCACHE == 0  || empty($key)) {
		return;
	}
	if(!empty($GLOBALS['cookiePrefix'])){
		$key.=$GLOBALS['cookiePrefix'];
	}
	$GLOBALS['memcache']->delete($key.'_');
	$GLOBALS['memcache']->delete($key);
}

function parsePusher($to,$insertedid,$message,$isChatroom = '0',$isWRTC = '0',$sent = '0'){
	if(empty($sent)){
		$sent = $insertedid;
	}
	$emojiUTF8= include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."extensions".DIRECTORY_SEPARATOR."mobileapp".DIRECTORY_SEPARATOR."emoji_notification.php");
	if(strpos($message,'cometchat_smiley')!==false){
		preg_match_all('/<img[^>]+\>/i',$message,$matches);

		for($i=0;$i<sizeof($matches[0]);$i++){
			$msgpart = (explode('/images/smileys/',$matches[0][$i]));
			$imagenamearr = explode('"',$msgpart[1]);
			$imagename = $imagenamearr[0];
			$smileynamearr = explode('.',$imagename);
			$smileyname = $smileynamearr[0];
			if(!empty($imagename)&&!empty($emojiUTF8[$imagename])){
				$message = str_replace($matches[0][$i],$emojiUTF8[$imagename],$message);
			}else{
				$message = str_replace($matches[0][$i],':'.$smileyname.':',$message);
			}
		}

	}

	include_once (dirname(__FILE__).DIRECTORY_SEPARATOR."extensions".DIRECTORY_SEPARATOR."mobileapp".DIRECTORY_SEPARATOR."parse_push.php");
	global $userid;
	global $channelprefix;
	if($isChatroom === '0'){
		$rawMessage = array("name" => $_SESSION['cometchat']['user']['n'], "fid"=> $userid, "m" => $message, "sent" => $sent);
		if(strlen($insertedid) < 13) {
			$rawMessage['id'] = $insertedid;
		}
		$channel = md5($channelprefix."USER_".$to.BASE_URL);
	} else {
		$chatroom_name = base64_decode($_SESSION['cometchat']['chatroom']['n']);
		$parse_message = $_SESSION['cometchat']['user']['n']."@".$chatroom_name.": ".$message;
		if (strpos($message, "has shared a file") !== false) {
			$parse_message = $_SESSION['cometchat']['user']['n']."@".$chatroom_name.": "."has shared a file";
		}

		$rawMessage = array( "id" => $insertedid, "from" => $_SESSION['cometchat']['user']['n'], "fid"=> $userid, "m" => sanitize($parse_message), "sent" => $sent, "cid" => $to);
		$channel = md5($channelprefix."CHATROOM_".$to.BASE_URL);
	}

	$parse = new Parse();
	$parse->sendNotification($channel, $rawMessage, $isChatroom, 0, $isWRTC);
}

function incrementCallback(){
	if(!empty($_REQUEST['callback'])){
		$explodedCallback = explode('_',$_REQUEST['callback']);
		$explodedCallback[1]++;
		$_REQUEST['callback'] = implode('_', $explodedCallback);
	}
}
function decrementCallback(){
	if(!empty($_REQUEST['callback'])){
		$explodedCallback = explode('_',$_REQUEST['callback']);
		$explodedCallback[1]--;
		$_REQUEST['callback'] = implode('_', $explodedCallback);
	}
}

function sendCCResponse($response){
	@ob_end_clean();
	header("Connection: close");
	ignore_user_abort();

	/*$useragent = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : '';
	if(phpversion()>='4.0.4pl1'&&(strstr($useragent,'compatible')||strstr($useragent,'Gecko'))){
		if(extension_loaded('zlib')&&GZIP_ENABLED==1 && !in_array('ob_gzhandler', ob_list_handlers())){
			ob_start('ob_gzhandler');
		}else{
			ob_start();
		}
	}else{*/
		ob_start();
	/*}*/
	echo $response;

	$size = ob_get_length();
	header("Content-Length: $size");
	ob_end_flush();
	flush();
 	session_write_close();
}

function bigintval($value) {
  $value = trim($value);
  if (ctype_digit($value)) {
    return $value;
  }
  $value = preg_replace("/[^0-9](.*)$/", '', $value);
  if (ctype_digit($value)) {
    return $value;
  }
  return 0;
}

function updateLastActivity($userid) {
		$sql = ("insert into cometchat_status (userid,lastactivity) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."','".getTimeStamp()."') on duplicate key update lastactivity = '".getTimeStamp()."'");

		return $sql;
	}

function updateLastSeen($userid) {
	$sql = ("insert into cometchat_status (userid,lastseen) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."','".getTimeStamp()."') on duplicate key update lastseen = '".getTimeStamp()."'");

	return $sql;
}

function setLastseensettings($message) {

	global $userid;
	if($message == 'true'){
		$message = 1;
	} else{
		$message = 0;
	}
	$sql = ("insert into cometchat_status (userid,lastseensetting) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."','".$message."') on duplicate key update lastseensetting = '".$message."'");
	$query = mysqli_query($GLOBALS['dbh'],$sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }

	if (function_exists('hooks_activityupdate')) {
		hooks_activityupdate($userid,$message);
	}

}

function getActivechatboxdetails($userids) {
//	$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username,  ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." link, ".DB_AVATARFIELD." avatar, cometchat_status.lastactivity lastactivity, cometchat_status.lastseen lastseen, cometchat_status.lastseensetting lastseensetting, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid ".DB_AVATARTABLE." where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." IN (".$userids.")");
	$sql = ("select DISTINCT ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." userid, ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." username,  ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_NAME." link, NULL avatar, cometchat_status.lastactivity lastactivity, cometchat_status.lastseen lastseen, cometchat_status.lastseensetting lastseensetting, cometchat_status.status, cometchat_status.message, cometchat_status.isdevice from ".TABLE_PREFIX.DB_USERTABLE." left join cometchat_status on ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." = cometchat_status.userid where ".TABLE_PREFIX.DB_USERTABLE.".".DB_USERTABLE_USERID." IN (".$userids.")");

	return $sql;
}

function getStatus() {
	global $response;
	global $userid;
	global $status;
	global $startOffline;
	global $processFurther;
	global $channelprefix;
	global $language;
	global $cookiePrefix;
	global $announcementpushchannel;

    if ($userid > 10000000) {
        $sql = getGuestDetails($userid);
    } else {
        $sql = getUserDetails($userid);
    }

 	$query = mysqli_query($GLOBALS['dbh'],$sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') { echo mysqli_error($GLOBALS['dbh']); }
	if(mysqli_num_rows($query) > 0) {
		$chat = mysqli_fetch_assoc($query);
		if (!empty($_REQUEST['callbackfn'])) {
			$_SESSION['cometchat']['startoffline'] = 1;
		}
		if ($startOffline == 1 && empty($_SESSION['cometchat']['startoffline'])) {
			$_SESSION['cometchat']['startoffline'] = 1;
			$chat['status'] = 'offline';
			setStatus('offline');
			$_SESSION['cometchat']['cometchat_sessionvars']['buddylist'] = 0;
			$processFurther = 0;
		} else {
			if (empty($chat['status'])) {
				$chat['status'] = 'available';
			} else {
				if ($chat['status'] == 'away') {
					$chat['status'] = 'available';
					setStatus('available');
				}

				if ($chat['status'] == 'offline') {
					$processFurther = 0;
					$_SESSION['cometchat']['cometchat_sessionvars']['buddylist'] = 0;
				}
			}
		}

		if (empty($chat['message'])) {
			$chat['message'] = $status[$chat['status']];
		}

		if(file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."announcements".DIRECTORY_SEPARATOR."config.php")){
			include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."modules".DIRECTORY_SEPARATOR."announcements".DIRECTORY_SEPARATOR."config.php");
		}

		$chat['message'] = html_entity_decode($chat['message']);

		$ccmobileauth = 0;
		if (!empty($_REQUEST['callbackfn']) && $_REQUEST['callbackfn'] == 'ccmobiletab') {
			$ccmobileauth = md5($_SESSION['basedata'].'cometchat');
		}

		if (empty($chat['ch'])) {
			if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
				$key = KEY_A.KEY_B.KEY_C;
			}
			$chat['ch'] = md5($chat['userid'].$key);
		}

		if(!isset($chat['lastseen'])){
			$chat['lastseen'] = 0;
		}
		if(!isset($chat['lastseensetting'])){
			$chat['lastseensetting'] = 0;
		}

	    $s = array('id' => $chat['userid'], 'n' => $chat['username'], 'l' => fetchLink($chat['link']), 'a' => getAvatar($chat['avatar']), 's' => $chat['status'], 'm' => $chat['message'],'push_channel' => 'C_'.md5($channelprefix."USER_".$userid.BASE_URL), 'ccmobileauth' => $ccmobileauth, 'push_an_channel' => $announcementpushchannel, 'webrtc_prefix' => $channelprefix, 'ch' => $chat['ch'], 'ls' => $chat['lastseen'], 'lstn' => $chat['lastseensetting']);

		$response['userstatus'] = $_SESSION['cometchat']['user'] = $s;
	} else {
		$response['loggedout'] = '1';
		$response['logout_message'] = $language[30];
		setcookie($cookiePrefix.'guest','',time()-3600,'/');
		setcookie($cookiePrefix.'state','',time()-3600,'/');
		unset($_SESSION['cometchat']);
	}
}

function setStatus($message) {
	global $userid;
	$sql = ("insert into cometchat_status (userid,status) values ('".mysqli_real_escape_string($GLOBALS['dbh'],$userid)."','".mysqli_real_escape_string($GLOBALS['dbh'],sanitize_core($message))."') on duplicate key update status = '".mysqli_real_escape_string($GLOBALS['dbh'],sanitize_core($message))."'");
	$query = mysqli_query($GLOBALS['dbh'],$sql);
	if (defined('DEV_MODE') && DEV_MODE == '1') {
		echo mysqli_error($GLOBALS['dbh']);
	}
	if (function_exists('hooks_activityupdate')) {
		hooks_activityupdate($userid,$message);
	}
}

function encryptUserid($userid) {
	$encrypteduserid = $userid;
	if($userid && function_exists('mcrypt_encrypt') && defined('ENCRYPT_USERID') && ENCRYPT_USERID == '1') {
		$key = "";
		if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
			$key = KEY_A.KEY_B.KEY_C;
		}
		$encrypteduserid = rawurlencode(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $userid, MCRYPT_MODE_CBC, md5(md5($key)))));
	}
	return $encrypteduserid;
}

function decryptUserid($encrypteduserid) {
	$userid = 0;
	if (!empty($encrypteduserid)) {
		if (function_exists('mcrypt_encrypt') && defined('ENCRYPT_USERID') && ENCRYPT_USERID == '1') {
			$key = "";
			if( defined('KEY_A') && defined('KEY_B') && defined('KEY_C') ){
				$key = KEY_A.KEY_B.KEY_C;
			}
			$uid = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode(rawurldecode($encrypteduserid)), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
			if (intval($uid) > 0) {
				$userid = $uid;
			}
		} else {
			$userid = $encrypteduserid;
		}
	}
	return $userid;
}