<?php ob_start();
session_start();
include_once '../config.php';
include_once '../includes/time_stamp.php';

$member_id = $_SESSION['SESS_MEMBER_ID'];
$comment = $_POST['report'];
$comment	 = 	f($comment, 'escapeAll');
$comment   = mysqli_real_escape_string($con, $comment);


$msg_id = $_POST['msg_id'];
$msg_id	 = 	f($msg_id, 'strip');
$msg_id	 = 	f($msg_id, 'escapeAll');
$msg_id   = mysqli_real_escape_string($con, $msg_id);

mysqli_query($con, "INSERT INTO groups_wall_report (member_id,msg_id,report, date_created)
VALUES('$member_id','$msg_id','$comment','".strtotime(date("Y-m-d H:i:s"))."')") or die(mysqli_error($con));

//mail function
$to = 'onkar.nawghare@gmail.com';
$subject = "Report of ".$site_name."";
$message = "
<html>
<head>
<title>'".$subject."'</title>
</head>
<body style='font-family:Verdana, Geneva, sans-serif; font-size:14px;'>

<p>Hello '".$msg_id."',</p><br />
<p>	Report of this msg id.</p>
<br>

</body>
</html>
";

// Always set content-type when sending HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";

$headers .= "From: ".$site_email."";
$mail = mail($to, $subject, $message, $headers); 


?>


