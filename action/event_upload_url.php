<?php ob_start();
$member_id = $_SESSION['SESS_MEMBER_ID'];
include('../config.php');

	$url=mysqli_real_escape_string($con, f($_POST['mylinktext'],'escapeAll'));
	$url_title=mysqli_real_escape_string($con, f($_POST['title'],'escapeAll'));
	$event_id = mysqli_real_escape_string($con, f($_POST['event_id'],'escapeAll'));
	$privacy = mysqli_real_escape_string($con, f($_POST['privacy'],'escapeAll'));
	$time = time();
	$ip=$_SERVER['REMOTE_ADDR'];
	
		
if(isset($_POST['mylinktext']))
{
	$sql="INSERT INTO event_wall(event_id,messages,url,url_title,member_id,type,date_created,ip,wall_privacy) VALUES ('$event_id','$url','$url','$url_title','$member_id',3,'$time','$ip','$privacy')";
	mysqli_query($con, $sql) or die(mysqli_error($con));
	
	
	///////////////////// Send Notification Added By Yasser Hossam & Moshera Ahmad 9/2/2015
// Get Event Name
$event = mysqli_query($con, "SELECT  `event_name` FROM `event` WHERE id=$event_id");
$event_res = mysqli_fetch_array($event_members);
$event_name = $event_res['event_name'];


require_once($_SERVER['DOCUMENT_ROOT'].'/qb_classes/qb_member.php');
require_once($_SERVER['DOCUMENT_ROOT'].'/qb_classes/qb_send_email.php');
$member = new Member();
$email = new SendEmail();

$event_members = mysqli_query($con, "SELECT DISTINCT `member_id` FROM `event_members` WHERE `event_id` = $event_id AND `member_id` <> member_id");
$event_members_res = mysqli_fetch_array($event_members);

    foreach( $event_members_res as $row ) 
	{
	$event_member_id = $row['member_id'];
	$newsql = "INSERT  INTO notifications (sender_id, received_id, type_of_notifications, title, href, is_unread, date_created) 
	VALUES($member_id,$event_member_id,40,'Posted a new URL in the $event_name event','event_view.php?id=$event_id',0,".strtotime(date("Y-m-d H:i:s")).")";
	mysqli_query($con, $newsql) or die(mysqli_error($con));
	
	/////////////////// Send Notification Message 
	$sender_name = $member->get_member_name($member_id);
	$message_body = "<a href='$url_title'>$url_title</a>" ;
	 
	$subject = " Posted a new photo in the $event_name event" ;

	////////send_notification_email($sender_id,$receiver_id,$subject,$message_body,$media)
	$email->send_notification_email($member_id,$event_member_id,$subject,$message_body,"");	
	///// End Send Notification Message
	
	}
////////////////////////////////////////////////////	



		
		header("location: ".$base_url."event_view.php?id=".$event_id."");			
	exit();
}
?> 
