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

include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."plugins.php");
include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR."en.php");
if (file_exists(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php")) {
	include_once(dirname(__FILE__).DIRECTORY_SEPARATOR."lang".DIRECTORY_SEPARATOR.$lang.".php");
}
/*Uncomment to enable push notifications for CometChat Legacy Apps*/
/*include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."extensions".DIRECTORY_SEPARATOR."mobileapp".DIRECTORY_SEPARATOR."sendnotification.php");*/
/*Uncomment to enable push notifications for CometChat Legacy Apps*/
include_once(dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR."extensions".DIRECTORY_SEPARATOR."mobileapp".DIRECTORY_SEPARATOR."config.php");

$message = '';
$mediauploaded = 1;
$filename = '';
$isImage = false;
$isVideo = false;
$isAudio = false;
$mediaType = 0;
$imageFormats = array("jpg", "jpeg", "png", "gif");
$videoFormats = array("3gp", "mp4", "wmv", "avi", "mov", "flv", "mpg", "webm");
$audioFormats = array("aac", "mp3", "wav", "wma", "ogg");
if (!empty($_REQUEST['callbackfn']) && $_REQUEST['callbackfn'] == 'mobileapp') {
	$filename = preg_replace("/[^a-zA-Z0-9\. ]/", "", mysqli_real_escape_string($GLOBALS['dbh'],$_POST['name']));
	$isImage = (strpos($_POST['name'], 'MG-'))? true : false;
	$isVideo = (strpos($_POST['name'], 'ID-'))? true : false;
	$width = $_POST['imagewidth'];
	$height = $_POST['imageheight'];
	$path = pathinfo($filename);
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	if (in_array(strtolower($ext), $imageFormats)) {
		$mediaType = 1;
		$isImage = true;
	}
	if (in_array(strtolower($ext), $videoFormats)) {
		$mediaType = 2;
		$isVideo = true;
	}
	if (in_array(strtolower($ext), $audioFormats)) {
		$mediaType = 3;
		$isAudio = true;
	}
} else {
	$filename = preg_replace("/[^a-zA-Z0-9\. ]/", "", mysqli_real_escape_string($GLOBALS['dbh'],$_FILES['Filedata']['name']));
	$filename = str_replace(" ", "_",$filename);
	$path = pathinfo($filename);
	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	if (in_array(strtolower($ext), $imageFormats)) {
		$isImage = true;
		$mediaType = 1;
		list($width, $height) = getimagesize($_FILES['Filedata']['tmp_name']);
	} else if (in_array(strtolower($ext), $videoFormats)) {
		$width = "512";
		$height = "512";
		$isVideo = true;
		$mediaType = 2;
	} else if (in_array(strtolower($ext), $audioFormats)) {
		$width = "512";
		$height = "512";
		$isAudio = true;
		$mediaType = 3;
	}
}

$md5filename = md5(str_replace(" ", "_",str_replace(".","",$filename))."cometchat".time());
if ($isImage||$isVideo){
	$md5filename .= ".".strtolower($path['extension']);
}
$unencryptedfilename=rawurlencode($filename);

if (!empty($isImage) && $isImage) {
	$imgHeight = "";

	if ($width >= $height && $height >= 50 ) {
		$imgHeight = '70px';
	} else if ($width <= $height && $height >=50 && $height <= 100) {
		$imgHeight = '50px';
	} else if ($width <= $height &&  $height >= 100) {
		$imgHeight = '170px';
	} else {
		$imgHeight = '70px';
	}

	$imgtag = "<img class=\"file_image\" type=\"image\" src=\"".BASE_URL."plugins/filetransfer/uploads/".$md5filename."\" style=\"max-height:".$imgHeight.";\"/>";
} else if (!empty($isVideo) && $isVideo) {
	$imgtag = "(".$filename.")<img class=\"file_video\" type=\"video\" src=\"".BASE_URL."images/videoicon.png\"/>";
} else if (!empty($isAudio) && $isAudio) {
	$imgtag = "(".$filename.")<img class=\"file_audio\" type=\"audio\" src=\"".BASE_URL."images/audioicon.png\"/>";
}

if (!empty($_FILES['Filedata']) && is_uploaded_file($_FILES['Filedata']['tmp_name'])) {
	if (!move_uploaded_file($_FILES['Filedata']['tmp_name'], dirname(__FILE__).DIRECTORY_SEPARATOR.'uploads' .DIRECTORY_SEPARATOR. $md5filename)) {
		$message = 'An error has occurred. Please contact administrator. Closing Window.';
		$mediauploaded = 0;
	}
}
if (empty($message)) {
	$insertedId = "";
	$server_url = '//'.$_SERVER['SERVER_NAME'].BASE_URL;
	if(filter_var(BASE_URL, FILTER_VALIDATE_URL)){
		$server_url = BASE_URL;
	}
	if (!empty($_POST['chatroommode'])) {
		if ((!empty($isImage) && $isImage) || (!empty($isVideo) && $isVideo) || (!empty($isAudio) && $isAudio) ) {
			$insertedId = sendChatroomMessage($_POST['to'],$filetransfer_language[9]."<br/><a class=\"imagemessage mediamessage\" filename=\"".$unencryptedfilename."\" encfilename=\"".$md5filename."\" mediatype=\"".$mediaType."\"  href=\"".$server_url."plugins/filetransfer/download.php?file=".$md5filename."&amp;unencryptedfilename=".$unencryptedfilename."\" imageheight=\"".$height."\" imagewidth=\"".$width."\">".$imgtag."</a>",0);
		} else {
			$insertedId = sendChatroomMessage($_POST['to'],$filetransfer_language[9]." (".$filename."). <a href=\"".$server_url."plugins/filetransfer/download.php?file=".$md5filename."&amp;unencryptedfilename=".$unencryptedfilename."\" target=\"_blank\" mediaType=\"0\">".$filetransfer_language[6]."</a>",0);
		}
	} else {
		if ((!empty($isImage) && $isImage) || (!empty($isVideo) && $isVideo) || (!empty($isAudio) && $isAudio) ) {
			$response = sendMessage($_POST['to'],$filetransfer_language[5]."<br/><a class=\"imagemessage mediamessage\" filename=\"".$unencryptedfilename."\" encfilename=\"".$md5filename."\" mediatype=\"".$mediaType."\" href=\"".$server_url."plugins/filetransfer/download.php?file=".$md5filename."&amp;unencryptedfilename=".$unencryptedfilename."\">".$imgtag."</a>",0,'filetransfer');
			$processedMessage = $_SESSION['cometchat']['user']['n'].": ".$filetransfer_language[5];
			parsePusher($_POST['to'],$response['id'],$processedMessage);
			if(USE_COMET == 1){
				$cometmessage = array();
				$cometresponse = array('to' => $_POST['to'],'message' => $filetransfer_language[5]."<br/><a class=\"imagemessage mediamessage\" filename=\"".$unencryptedfilename."\" encfilename=\"".$md5filename."\" mediatype=\"".$mediaType."\" href=\"".$server_url."plugins/filetransfer/download.php?file=".$md5filename."&amp;unencryptedfilename=".$unencryptedfilename."\">".$imgtag."</a>", 'dir' => 0,'type' => "filetransfer");
				array_push($cometmessage, $cometresponse);
				publishCometMessages($cometmessage,$response['id']);
			}
			$insertedId = $response['id'];
		} else {
			$response = sendMessage($_POST['to'],$filetransfer_language[5]." (".$filename."). <a class=\"imagemessage\" href=\"".$server_url."plugins/filetransfer/download.php?file=".$md5filename."&amp;unencryptedfilename=".$unencryptedfilename."\" target=\"_blank\">".$filetransfer_language[6]."</a>",0,'filetransfer');
			$processedMessage = $_SESSION['cometchat']['user']['n'].": ".$filetransfer_language[5];
			parsePusher($_POST['to'],$response['id'],$processedMessage);
			if(USE_COMET == 1){
				$cometmessage = array();
				$cometresponse = array('to' => $_POST['to'],'message' => $filetransfer_language[7]." (".$filename.").", 'dir' => 0,'type' => "filetransfer");
				array_push($cometmessage, $cometresponse);
				publishCometMessages($cometmessage,$response['id']);
			}
			$insertedId = $response['id'];
		}
		/*Uncomment to enable push notifications for CometChat Legacy Apps*/
		/*if (isset($_REQUEST['sendername']) && $pushNotifications == 1) {
			pushMobileNotification($filetransfer_language[9], $_REQUEST['sendername'], $_POST['to'], $_POST['to']);
		}*/
		/*Uncomment to enable push notifications for CometChat Legacy Apps*/
	}

	if (!empty($_REQUEST['callbackfn']) && $_REQUEST['callbackfn'] == 'mobileapp') {
		echo $insertedId; exit;
	}
	$message = $filetransfer_language[8];
}

$embed = '';
$embedcss = '';
$close = "setTimeout('closePopup();',2000);";

if (!empty($_GET['embed']) && $_GET['embed'] == 'web') {
	$embed = 'web';
	$embedcss = 'embed';
} else if (!empty($_GET['embed']) && $_GET['embed'] == 'desktop') {
	$embed = 'desktop';
	$embedcss = 'embed';
	$close = "setTimeout('parentSandboxBridge.closeCCPopup(\"filetransfer\");',2000);";
}
if (!empty($_REQUEST['callbackfn']) && $_REQUEST['callbackfn'] == 'mobileapp') {
	echo $mediauploaded;
} else {
echo <<<EOD
	<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>{$filetransfer_language[0]} (closing)</title>
		<link type="text/css" rel="stylesheet" media="all" href="../../css.php?type=plugin&name=filetransfer" />
		<script type="text/javascript">
			function closePopup(){
				var controlparameters = {'type':'plugins', 'name':'filetransfer', 'method':'closeCCPopup', 'params':{'name':'filetransfer'}};
				controlparameters = JSON.stringify(controlparameters);
				if(typeof(parent) != 'undefined' && parent != null && parent != self){
					parent.postMessage('CC^CONTROL_'+controlparameters,'*');
				} else {
					window.close();
				}
			}
		</script>
	</head>
	<body onload={$close}>
		<div class="container">
			<div class="container_title {$embedcss}">{$filetransfer_language[1]}</div>
			<div class="container_body {$embedcss}">
				<div>{$message}</div>
				<div style="clear:both"></div>
			</div>
		</div>
	</body>
	</html>
EOD;
}