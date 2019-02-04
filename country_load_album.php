<?php ob_start();
	session_start();
	require_once('config.php');
	
if(!isset($_SESSION['SESS_MEMBER_ID']))
	{
		header("location: ".$base_url."index.php");
		exit();
	}
	
	$member_id = $_SESSION['SESS_MEMBER_ID'];
	$country_id = $_REQUEST['country_id'];
	$album_id = $_REQUEST['album_id'];
	
	$sql = mysqli_query($con, "select ga.album_name, ga.album_id, m.member_id FROM 
						user_album ga INNER JOIN members m ON m.member_id = ga.album_user_id 
						WHERE ga.album_id='".$album_id."'") or die(mysqli_error($con));
						
	$res = mysqli_fetch_array($sql);
	
	$cquery = "select country_title from geo_country WHERE country_id = '$country_id'";
	$csql = mysqli_query($con, $cquery);
	$cres = mysqli_fetch_array($csql);
	$country_title = $cres['country_title'];	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title><?php echo ucfirst($res['album_name']);?> Photos</title>
<head>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="css/share_box.css"/>
<link href="css/style5forimageGallery.css" rel="stylesheet" type="text/css" media="all" />
<link rel="icon" href="images/favicon.ico" type="image" />
<link rel="shortcut icon" href="images/favicon.ico" type="image" />

<link rel="stylesheet" type="text/css" href="css/format.css"/>
<link rel="stylesheet" type="text/css" href="css/search.css"/>
<link rel="stylesheet" type="text/css" href="css/dropdown.css"/>
   <!--Style for slider-->
       
        <style>
		.dg-container{
	width: 100%;
	height: 450px;
	position: relative;
}
.dg-wrapper{
	width: 481px;
	height: 316px;
	margin: 0 auto;
	position: relative;
	-webkit-transform-style: preserve-3d;
	-moz-transform-style: preserve-3d;
	-o-transform-style: preserve-3d;
	-ms-transform-style: preserve-3d;
	transform-style: preserve-3d;
	-webkit-perspective: 1000px;
	-moz-perspective: 1000px;
	-o-perspective: 1000px;
	-ms-perspective: 1000px;
	perspective: 1000px;
}
.dg-wrapper a{
	width: 482px;
	height: 316px;
	display: block;
	position: absolute;
	left: 0;
	top: 0;
	background: transparent url(../images/browser.png) no-repeat top left;
	box-shadow: 0px 10px 20px rgba(0,0,0,0.3);
}
.dg-wrapper a.dg-transition{
	-webkit-transition: all 0.5s ease-in-out;
	-moz-transition: all 0.5s ease-in-out;
	-o-transition: all 0.5s ease-in-out;
	-ms-transition: all 0.5s ease-in-out;
	transition: all 0.5s ease-in-out;
}
.dg-wrapper a img{
	display: block;
	padding: 41px 0px 0px 1px;
}
.dg-wrapper a div{
	font-style: italic;
	text-align: center;
	line-height: 50px;
	text-shadow: 1px 1px 1px rgba(255,255,255,0.5);
	color: #333;
	font-size: 16px;
	width: 100%;
	bottom: -55px;
	display: none;
	position: absolute;
}
.dg-wrapper a.dg-center div{
	display: block;
}
.dg-container nav{
	width: 58px;
	position: absolute;
	z-index: 1000;
	bottom: 40px;
	left: 50%;
	margin-left: -29px;
}
.dg-container nav span{
	text-indent: -9000px;
	float: left;
	cursor:pointer;
	width: 24px;
	height: 25px;
	opacity: 0.8;
	background: transparent url(../images/arrows.png) no-repeat top left;
}
.dg-container nav span:hover{
	opacity: 1;
}
.dg-container nav span.dg-next{
	background-position: top right;
	margin-left: 10px;
}
		</style>
         <!--Style for slider-->
<script src="js/ibox.js"></script>
<script src="js/jquery.livequery.js" type="text/javascript"></script>
<script src="js/jquery.oembed.js"></script>
<script src="js/jquery-1.9.1.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/script.js"></script>
<script type="text/javascript" src="js/jquery.livequery.js"></script>
<script type="text/javascript" src="js/modernizr.custom.53451.js"></script>
<!-- drag -->
  <link rel="stylesheet" href="css/jquery-ui.css" />
  <link rel="stylesheet" href="/resources/demos/style.css" />

<style>
.ui-widget-content { width:50px; height: 50px; padding: 0.5em;z-index:11000; border:#0000FF ridge 2px;;}

.ui-widget-content1 { width:50px; height: 50px;z-index:11000; display:none; position:absolute;}

.tagged { width:50px; height: 50px;z-index:12000; position:absolute;}
.commentMark{width:200px;}
  </style>

<script type="text/javascript">

function showHide() 
{			
   if(document.getElementById("privacy").selectedIndex == 1) 
   {
        document.getElementById("mvm").style.display = ""; // This line makes the DIV visible
		document.getElementById("mvm1").style.display = "none";
	   document.getElementById("mvm2").style.display = "none";
   }
   else
   {
	   document.getElementById("mvm").style.display = "none";	   
   } 
   if(document.getElementById("privacy").selectedIndex == 2)
   {            
        document.getElementById("mvm1").style.display = "";
		document.getElementById("mvm").style.display = "none"; 
		document.getElementById("mvm2").style.display = "none"; 
   }
   else
   {
	   document.getElementById("mvm1").style.display = "none";	   
   }
   if(document.getElementById("privacy").selectedIndex == 3)
   {            
        document.getElementById("mvm2").style.display = "";
		document.getElementById("mvm1").style.display = "none"; 
		document.getElementById("mvm").style.display = "none"; 
   }
   else
   {
	   document.getElementById("mvm2").style.display = "none";
	   	   
   }
   
}


function notification(maiid,divid){
  //alert(divid);
  if(document.getElementById(divid).style.display=='none'){
    document.getElementById(divid).style.display='block'
    document.getElementById('messageid').style.display='none';
    document.getElementById('wallidid').style.display='none';
  }else{
    document.getElementById(divid).style.display='none'
  }
}

function notification1(maiid,divid){
  //alert(maiid+divid);
  if(document.getElementById(divid).style.display=='none'){
    document.getElementById(divid).style.display='block'
    document.getElementById('fri_noti').style.display='none';
    document.getElementById('wallidid').style.display='none';
  }else{
    document.getElementById(divid).style.display='none'
  }
}

function notification2(maiid,divid){
  //alert(maiid+divid);
  if(document.getElementById(divid).style.display=='none'){
    document.getElementById(divid).style.display='block';
    document.getElementById('fri_noti').style.display='none';
    document.getElementById('messageid').style.display='none';
  }else{
    document.getElementById(divid).style.display='none'
  }
}
</script>
<script type="text/javascript">
$('.commentMark').livequery("focus", function(e){
			
			var parent  = $('.commentMark').parent();
			$(".commentBox").children(".commentMark").css('width','320px');
			$(".commentBox").children("a#SubmitComment").hide();
			$(".commentBox").children(".CommentImg").hide();			
		
			var getID =  parent.attr('id').replace('record-','');			
			$("#commentBox-"+getID).children("a#SubmitComment").show();
			$('.commentMark').css('width','300px');
			$("#commentBox-"+getID).children(".CommentImg").show();			
		});	
		
		//showCommentBox
		$('a.showCommentBox').livequery("click", function(e){
			
			var getpID =  $(this).attr('id').replace('post_id','');	
			
			$("#commentBox-"+getpID).css('display','');
			$("#commentMark-"+getpID).focus();
			$("#commentBox-"+getpID).children("CommentImg").show();			
			$("#commentBox-"+getpID).children("a#SubmitComment").show();		
		});	
		
		//SubmitComment
		$('a.comment').livequery("click", function(e){
			
			var getpID =  $(this).parent().attr('id').replace('commentBox-','');	
			var comment_text = $("#commentMark-"+getpID).val();
			
			if(comment_text != "Write a comment...")
			{
				$.post("add_photo_comment.php?comment_text="+comment_text+"&post_id="+getpID, {
	
				}, function(response){
					
					$('#CommentPosted'+getpID).append($(response).fadeIn('slow'));
					$("#commentMark-"+getpID).val("Write a comment...");					
				});
			}
			
		});	
		
		//more records show
		$('a.more_records').livequery("click", function(e){
			
			var next =  $('a.more_records').attr('id').replace('more_','');
			
			$.post("posts.php?show_more_post="+next, {

			}, function(response){
				$('#bottomMoreButton').remove();
				$('#posting').append($(response).fadeIn('slow'));

			});
			
		});	
		
		//deleteComment
		$('a.c_delete').livequery("click", function(e){
			
			if(confirm('Are you sure you want to delete this comment?')==false)

			return false;
	
			e.preventDefault();
			var parent  = $('a.c_delete').parent();
			var c_id =  $(this).attr('id').replace('CID-','');	
			
			$.ajax({

				type: 'get',

				url: 'delete_comment.php?c_id='+ c_id,

				data: '',

				beforeSend: function(){

				},

				success: function(){

					parent.fadeOut(200,function(){

						parent.remove();
						location.reload();

					});

				}

			});
		});	
		
		/// hover show remove button
		$('.friends_area').livequery("mouseenter", function(e){
			$(this).children("a.delete").show();	
		});	
		$('.friends_area').livequery("mouseleave", function(e){
			$('a.delete').hide();	
		});	
		/// hover show remove button
		
		
		$('a.delete').livequery("click", function(e){

		if(confirm('Are you sure you want to delete this post?')==false)

		return false;

		e.preventDefault();

		var parent  = $('a.delete').parent();

		var temp    = parent.attr('id').replace('record-','');

		var main_tr = $('#'+temp).parent();

			$.ajax({

				type: 'get',

				url: 'delete.php?id='+ parent.attr('id').replace('record-',''),

				data: '',

				beforeSend: function(){

				},

				success: function(){

					parent.fadeOut(200,function(){

						main_tr.remove();
						location.reload();

					});

				}

			});

		});

		$('textarea').elastic();

		jQuery(function($){

		   $("#watermark").Watermark("What's on your mind?");
		   $(".commentMark").Watermark("Write a comment...");

		});

		jQuery(function($){

		   $("#watermark").Watermark("watermark","#369");
		   $(".commentMark").Watermark("watermark","#EEEEEE");

		});	

		function UseData(){

		   $.Watermark.HideAll();

		   //Do Stuff

		   $.Watermark.ShowAll();

		}
		

		
document.getElementById('imageDiv').style.visibility='hidden';
</script>
<script type="text/javascript">
$('.like').die('click').live("click",function() 
{	
var ID = $(this).attr("id");
var sid=ID.split("like"); 
var New_ID=sid[1];

var REL = $(this).attr("rel");
var URL='load_data/photo_like_ajax.php';
var dataString = 'msg_id=' + New_ID +'&rel='+ REL;
$.ajax({
type: "POST",
url: URL,
data: dataString,
cache: false,
success: function(html){

if(REL=='Like')
{
$("#youlike"+New_ID).slideDown('slow').prepend("<span id='you"+New_ID+"'><a href='#'>You</a> like this.</span>.");
$("#likes"+New_ID).prepend("<span id='you"+New_ID+"'><a href='#'>You</a>, </span>");
$('#'+ID).html('Unlike').attr('rel', 'Unlike').attr('title', 'Unlike');
}
else
{
$("#youlike"+New_ID).slideUp('slow');
$("#you"+New_ID).remove();
$('#'+ID).attr('rel', 'Like').attr('title', 'Like').html('Like');
}
}
});
});

$('.like1').die('click').live("click",function() 
{	
var ID = $(this).attr("id");
var sid=ID.split("like1"); 
var New_ID=sid[1];

var REL = $(this).attr("rel");
var URL='load_data/photo_comment_like_ajax.php';
var dataString = 'msg_id=' + New_ID +'&rel='+ REL;
$.ajax({
type: "POST",
url: URL,
data: dataString,
cache: false,
success: function(html){

if(REL=='Like')
{
$("#youlike"+New_ID).slideDown('slow').prepend("<span id='you1"+New_ID+"'><a href='#'>You</a> like this.</span>.");
$("#likes"+New_ID).prepend("<span id='you"+New_ID+"'><a href='#'>You</a>, </span>");
$('#'+ID).html('Unlike').attr('rel', 'Unlike').attr('title', 'Unlike');
}
else
{
$("#youlike"+New_ID).slideUp('slow');
$("#you1"+New_ID).remove();
$('#'+ID).attr('rel', 'Like').attr('title', 'Like').html('Like');
}
}
});
});

// delete update
$('.stdelete').live("click",function() 
{
	var ID = $(this).attr("id");
	var dataString = 'msg_id='+ ID;

	if(confirm("Sure you want to delete this Photo? There is NO undo!"))
	{
	
		$.ajax({
		type: "POST",
		url: "action/delete_photo_from_albums.php",
		data: dataString,
		cache: false,
		success: function(html){
		 location.reload();
		 }
		 });
	}
	return false;
	
});


	

</script>
<script type="text/javascript">
	$(document).ready(function(){	
	
	
	$(".settings-button").click(function()
{
var X=$(this).attr('title');
//alert(X);
var H=$(this).attr('value');
//alert(H);
//document.getElementById("submenuhidden").value=H;

if(X==1)
{
$("#"+H+"-submenu12").hide();
$(this).attr('title', '0');	
}
else
{

$("#"+H+"-submenu12").show();
$(this).attr('title', '1');
}
	
});
//var M=document.getElementById("submenuhidden").value;
//alert(M);
//Mouseup textarea false
$(".submenu12").mouseup(function()
{
return false
});
$(".settings-button").mouseup(function()
{
return false
});


//Textarea without editing.
$(document).mouseup(function()
{
$(".submenu12").hide();
$(".settings-button").attr('title', '');
});
	

	
$('.edit_link').click(function()
{
var ID = $(this).attr("id");

$('#text_wrapper'+ID).hide();
var data=$('#text_wrapper'+ID).html();

$('#edit'+ID).show();
$('#editbox'+ID).html(data);
$('#editbox'+ID).focus();

});

$(".editbox").mouseup(function() 
{
return false
});

$(".editbox").change(function() 
{
var ID = $(this).attr("id");
var sid=ID.split("editbox"); 
var New_ID=sid[1];
$('.edit').hide();
var boxval = $("#editbox"+New_ID+"").val();
var dataString = 'data='+ boxval +'&c_id=' +New_ID;
$.ajax({
type: "POST",
url: "load_data/update_comment_ajax.php",
data: dataString,
cache: false,
success: function(html)
{
$('#text_wrapper'+New_ID+'').html(boxval);
$('.text_wrapper').show();

}
});

});
 
$(document).mouseup(function()
{
$('.edit').hide();
$('.text_wrapper').show();
});	

	
//edit CAption
$('.captions').click(function()
{
var ID = $(this).attr("id");
$('#text_wrapper_caption'+ID).hide();
$('#edit_caption'+ID).show();
//$('#editbox_caption'+ID).html(data);
$('#editbox_caption'+ID).focus();



});


$(".editbox_caption").mouseup(function() 
{
return false
});

$(".editbox_caption").change(function() 
{
var ID = $(this).attr("id");
var sid=ID.split("editbox_caption"); 
var New_ID=sid[1];
$('.edit_caption').hide();
var boxval = $("#editbox_caption"+New_ID+"").val();
var dataString = 'data='+ boxval +'&c_id=' +New_ID;


$.ajax({
type: "POST",
url: "load_data/update_caption_description_ajax.php",
data: dataString,
cache: false,
success: function(html)
{
location.reload();

}
});



});
 
$(document).mouseup(function()
{
$('.edit_caption').hide();
$('.text_wrapper_caption').show();
});	

//edit Desccription
$('.descriptions').click(function()
{
var ID = $(this).attr("id");
$('#text_wrapper_description'+ID).hide();
$('#edit_description'+ID).show();
//$('#editbox_caption'+ID).html(data);
$('#editbox_description'+ID).focus();



});


$(".editbox_description").mouseup(function() 
{
return false
});

$(".editbox_description").change(function() 
{
var ID = $(this).attr("id");
var sid=ID.split("editbox_description"); 
var New_ID=sid[1];
$('.edit_description').hide();
var boxval = $("#editbox_description"+New_ID+"").val();
var dataString = 'd_data='+ boxval +'&c_id=' +New_ID;


$.ajax({
type: "POST",
url: "load_data/update_caption_description_ajax.php",
data: dataString,
cache: false,
success: function(html)
{
location.reload();

}
});



});
 
$(document).mouseup(function()
{
$('.edit_description').hide();
$('.text_wrapper_description').show();
});	

//status share
$('.share_open').live("click",function() 
{
var ID = $(this).attr("id");
var dataString = 'msg_id='+ ID;

$.ajax({
type: "POST",
url: "load_data/share_info.php",
data: dataString,
cache: false,
success: function(html){
$(".share_popup").show();
$(".share_body").append(html);
 }
 });

return false;
});

});
</script>
<style>
a
{
text-decoration:none;
color:#006699;

}

</style>

</head>
<body>
<div id="wrapper">
<?php include('includes/header.php');?>
<div id="mainbody">

<div class="column_left">

	<div class="" style="width:">
    
    <input type="button" class="button" value="<?php echo $country_title;?>" 
    onclick="window.open('country_wall.php?country=<?php echo $country_title;?>','_self');" />
    <div align="left">
    
	 <?php //include('phototest.php');?>
	</div>
    <!--
    <input type="button" class="button" value="Back to album" 
    onclick="window.open('country_photos.php?country=<?php echo $country_title;?>','_self');" />    
     <a href="#" class="topopup"><input type="button" class="button" value="Add photo" />
    </a>
    <div class="componentheading">
    <div id="submenushead"><?php echo $res['album_name']; ?> photos</div>
    </div>!-->
   <?php 
      function _make_url_clickable_cb($matches) {
	$ret = '';
	$url = $matches[2];
 
	if ( empty($url) )
		return $matches[0];
	// removed trailing [.,;:] from URL
	if ( in_array(substr($url, -1), array('.', ',', ';', ':')) === true ) {
		$ret = substr($url, -1);
		$url = substr($url, 0, strlen($url)-1);
	}
	return $matches[1] . "<a href=\"$url\" rel=\"nofollow\">$url</a>" . $ret;
}

function _make_web_ftp_clickable_cb($matches) {
	$ret = '';
	$dest = $matches[2];
	$dest = 'http://' . $dest;
 
	if ( empty($dest) )
		return $matches[0];
	// removed trailing [,;:] from URL
	if ( in_array(substr($dest, -1), array('.', ',', ';', ':')) === true ) {
		$ret = substr($dest, -1);
		$dest = substr($dest, 0, strlen($dest)-1);
	}
	return $matches[1] . "<a href=\"$dest\" rel=\"nofollow\" target=\"_blank\">$dest</a>" . $ret;
}
 
function _make_email_clickable_cb($matches) {
	$email = $matches[2] . '@' . $matches[3];
	return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
}
    function clickable_link($text = '')
	{
		$text = preg_replace_callback('#(script|about|applet|activex|chrome):#is','function($matches) {
        return $matches[1];
			}', $text);
		$ret = ' ' . $text;
		$ret = preg_replace_callback("#(^|[\n ])([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*)#is",'_make_url_clickable_cb', $ret);
		
		$ret = preg_replace_callback("#(^|[\n ])((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*)#is", '_make_web_ftp_clickable_cb', $ret);
		$ret = preg_replace_callback("#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#i", '_make_email_clickable_cb', $ret);
		$ret = substr($ret, 1);
		return $ret;
	}
	?>
	<!--test!-->
	 <div style="clear:both">&nbsp;</div>
	 
	
	
	
        
	<?php
	/*
	$gaquery = "SELECT gp.upload_data_id, gp.FILE_NAME,gp.album_id,m.member_id, gp.caption, gp.description
			FROM upload_data gp LEFT JOIN members m on gp.USER_CODE = m.member_id 
			WHERE gp.country_id=".$_REQUEST['country_id']." 
			ORDER by gp.upload_data_id ASC";
			
	//echo $gaquery ;		
	
	
	$gasql = mysqli_query($gaquery) or die(mysqli_error());
	$totalnumofrec=mysqli_num_rows($gasql);
	//echo $totalnumofrec;
	?>
	<div style="clear:both">
			<?php 
		if($totalnumofrec<=0)
		{
		
		?>
		NO PHOTO
		<?php
		}
		?>
	</div>
	<?php
	$countrecord=1;
	while ($row = mysqli_fetch_array($gasql) )
	{
		 
		$row['upload_data_id'];
	  $total_comments = mysqli_query("SELECT count(*) FROM photo_comments where c_item_id = ".$row['upload_data_id']." order by c_when asc");
	  $records = mysqli_fetch_array($total_comments);
	  $records = $records[0];
	  
	    $comments = mysqli_query("SELECT *,
		UNIX_TIMESTAMP() - c_when AS CommentTimeSpent FROM photo_comments, members where members.member_id=photo_comments.c_user_id and c_item_id = ".$row['upload_data_id']." order by c_when asc limit 0,4") or die(mysqli_error());		
		$comment_num_row = mysqli_num_rows(@$comments);
?>


		
	  
<?php	
	  echo '<div class="photo" style="z-index:100009;margin-top: 20px;" >';
	  if($_SESSION['SESS_MEMBER_ID'] == $row['member_id'])
{
?>

<a class="stdelete" href="#" id="<?php echo $row['upload_data_id'];?>" title="Delete Photo">X</a>
<?php }



	 echo '<div id='.$row['upload_data_id'].' class="topup">';
	 echo "album id=".$row['album_id'];
	  ?>
	  
      <a href="country_photo_view.php?back_page=country_photos.php?country=<?php echo $country_title;?>&country_id=<?php echo $country_id; ?>&album_id=<?php echo $row['album_id']; ?>&image_id=<?php echo $row['upload_data_id'];?>" >
<img width="265" height="205" src="<?php echo $row['FILE_NAME'];?>" id="<?php echo $row['upload_data_id'];?>" value="<?php echo $_REQUEST['album_id'];?>" />
	  
	  
	  </a></div>
	
<?php	  
	  if(!$row['caption'])
	  {
	  	if($_SESSION['SESS_MEMBER_ID'] == $row['member_id'])
	  	{
	  	?>
	  	<div class="text_wrapper_caption" id="text_wrapper_caption<?php echo $row['upload_data_id']; ?>" style="">
	  	<a href="javascript: void(0)" class="captions" id="<?php echo $row['upload_data_id']; ?>"><p>Add Caption </p></a>	  	</div>
	  	<div class="edit_caption" id="edit_caption<?php echo $row['upload_data_id']; ?>" style="display:none">
	  	<textarea class="editbox_caption" id="editbox_caption<?php echo $row['upload_data_id']; ?>" size="80px" rows="2"
	  	name="profile_box">
	  	</textarea></div>
	  <?php
	  	  
	  	}
	  
	  }
	  else
	  {
		  echo '<p>'.$row['caption'].'</p>';
	  }
	  if(!$row['description'])
	  {
	  	if($_SESSION['SESS_MEMBER_ID'] == $row['member_id'])
	  	{
	  	  ?>
	  	<div class="text_wrapper_description" id="text_wrapper_description<?php echo $row['upload_data_id']; ?>" style="">
	  	<a href="javascript: void(0)" class="descriptions" id="<?php echo $row['upload_data_id']; ?>"><p>Add Description</p></a>	  	</div>
	  	<div class="edit_description" id="edit_description<?php echo $row['upload_data_id']; ?>" style="display:none">
	  	<textarea class="editbox_description" id="editbox_description<?php echo $row['upload_data_id']; ?>" cols="23" rows="2"
	  	name="profile_box">
	  	</textarea></div>
	    
	    <span class="app-box">
	    <?php
	  	  
	  	  //echo '<i>'.$row['description'].'</i>';
	  	  
	  	}
	  }
	  else
	  {
		echo '<i>'.$row['description'].'</i>';  
	  }
	  echo '</div>';
	  echo '<div id="toPopup'.$row['upload_data_id'].'" class="toPopup">';
	  
	  ?>
	   </span>
			
			      	
        <div class="close"></div>
       	<span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
		<div id="popup_content"> <!--your content start-->
          
				<div id="div_left_panel" class="leftpanel" align="center">
				
				
			<?php 
				$tag_sql = "SELECT tag_id, member_in_tag_id, div_top, div_left FROM tags Where upload_data_id='".$row['upload_data_id']."'"; 
	
				$tag_data = mysqli_query($tag_sql);
			while( $info=mysqli_fetch_array($tag_data)){
			echo "<div class='tagged'  id='tagged".$info['tag_id']."' style='top:".$info['div_top']."px;left:".$info['div_left']."px;' title='".$info['member_in_tag_id']."' >
		 	</div>";
			
			}
			
				
			echo "<div class='ui-widget-content1'>
		 	<div id='draggable".$row['upload_data_id']."' class='ui-widget-content'>
		 
		 	<input type='button' value='Ok' class='btn_tag_submit' id='".$row['upload_data_id']."'>
		 	<input type='button' value='X' name='btn_tag_box_close' 		   	onclick='hide_photo_tag()'>
			<br>		 
			<center>
			<input type='text' id='tag_text".$row['upload_data_id'] ."' class='tag_text' name='tag_text' size='3px' />
			</center>
</div> </div>";
				
				
				
				list($width, $height) = getimagesize("uploadedimage/".$row['FILE_NAME'].""); 
				
				if($width>600 || $height>550)
				{
				echo '<img height="100%" width="100%" align="middle" src="'.$row['FILE_NAME'].'" id="'.$row['upload_data_id'].'" value="'.$_REQUEST['album_id'].'" />'; 	
				
				}
				else
				{
				
				$height1=(550-$height)/2;
				echo '<div style="margin-top:'.$height1.'px">';
				echo '<img align="middle" src="'.$row['FILE_NAME'].'" id="'.$row['upload_data_id'].'" value="'.$_REQUEST['album_id'].'" />';
				echo '</div>';
				} 
				
				$previousvalu= mysqli_query("SELECT `upload_data_id` FROM `upload_data` WHERE album_id=".$_REQUEST['album_id']." and `upload_data_id` < 				'".$row['upload_data_id']."' ORDER BY `upload_data_id` DESC LIMIT 1");
				while ($getpreval = mysqli_fetch_array($previousvalu) )
				{
					$prevval=$getpreval['upload_data_id'];
				}
				
				$nextvalu= mysqli_query("SELECT `upload_data_id` FROM `upload_data` WHERE album_id=".$_REQUEST['album_id']." and `upload_data_id` > 				'".$row['upload_data_id']."' ORDER BY `upload_data_id` ASC LIMIT 1");
				while ($getnextval = mysqli_fetch_array($nextvalu) )
				{
					$nextval=$getnextval['upload_data_id'];
					
					
				}
				
				if($countrecord!=1)
				{?>
				
				<div class="leftnav" id="<?php echo $prevval; ?>">		</div>
				<?php } 
					if($countrecord!=$totalnumofrec)
				{
				?>
				<div class="rightnav" id="<?php echo $nextval; ?>">				</div>
				<?php } ?>
				</div>
				<div class="rightpanel">
				<div class="rightTop">
				<div class="image" style="height:60px">
				
				<img src="<?php echo $row['profImage']; ?>" width="60" height="60" class="CommentImg" style="float:left;" alt="" />
				<p><span style="margin-left:10px;">
				<b><?php echo $row['username']; ?></b></p></span>
				<p>
				<span style="margin-left:10px;">
				  <?php  
		   
		         // echo strtotime($row['date_created'],"Y-m-d H:i:s");
   		    
		         $days = floor($row['TimeSpent'] / (60 * 60 * 24));
			     $remainder = $row['TimeSpent'] % (60 * 60 * 24);
			     $hours = floor($remainder / (60 * 60));
			     $remainder = $remainder % (60 * 60);
			     $minutes = floor($remainder / 60);
			     $seconds = $remainder % 60;
			
			     if($days > 0)
			     echo date('F d Y', $row['date_created']);
			     elseif($days == 0 && $hours == 0 && $minutes == 0)
		  	     echo "few seconds ago";		
		    	 elseif($days == 0 && $hours == 0)
			     echo $minutes.' minutes ago';
			     else
			     echo "few seconds ago";	
			
		          ?>
				</span>				</p>
				</div>
				<div class="caption" >
				<?php
				if(!$row['caption'])
				{
					if($_SESSION['SESS_MEMBER_ID'] == $_REQUEST['member_id'])
	  				{
		  				echo '<p class="captions" id="'.$row['upload_data_id'].'">Add Caption</p>';
		  				//echo $row['caption'];
	  	  
		  			}
		  		}
		  		else
		  		{
		  			echo $row['caption'];
		  		}
				 ?>
				</div>
				<div class="description" >
				<?php
				if(!$row['description'])
				{
					if($_SESSION['SESS_MEMBER_ID'] == $_REQUEST['member_id'])
					{
						echo '<p class="descriptions" id="'.$row['upload_data_id'].'">Add Description</p>';
						//echo $row['description'];
	  	  
					}
				}
				else
				{
					echo $row['description'];
				}
				 ?>
				</div>
				
		<a href="#" class="share_open" id="<?php echo $row['messages_id'];?>" title="Share" onClick="">
<span style="font-size: 1.0em;float: left;width: 53px;cursor: pointer;color: #005689;" id="share">Share</span></a> &nbsp;

<?php echo "<a href='javascript:show_photo_tag()' id='photo_tag'>photo tag</a>"; 

?> &nbsp;



				<a href="javascript: void(0)" id="post_id<?php  echo $row['upload_data_id']?>" class="showCommentBox">Comments</a>
								<?php
		   
		   $q = mysqli_query("SELECT upload_data_like_id FROM upload_data_like WHERE upload_data_user_id='".$member_id."' and upload_data_item_id='".$row['upload_data_id']."' ") or die(mysqli_error());

if(mysqli_num_rows($q) > 0)
{
	echo '<a href="#" class="like" id="like'.$row['upload_data_id'].'" title="Unlike" rel="Unlike">Unlike</a>';
} 
else 
{ 
	echo '<a href="#" class="like" id="like'.$row['upload_data_id'].'" title="Like" rel="Like">Like</a>';
} 
		?><?php	
			
$like_count = $row['like_count'];

if($like_count > 0) 
{ 
$query=mysqli_query("SELECT U.username,U.member_id FROM upload_data_like M, members U WHERE U.member_id=M.upload_data_user_id AND M.upload_data_item_id='".$row['upload_data_id']."' LIMIT 3");
$like = mysqli_num_rows($query);
?>
<div class='likeUsers' id="likes<?php echo $row['upload_data_id'] ?>">
<?php
$new_like_count = $like_count-3; 
while($row12=mysqli_fetch_array($query))
{
$like_uid=$row12['member_id']; 
$likeusername=$row12['username']; 
if($like_uid==$member_id)
{
echo '<span id="you'.$row['upload_data_id'].'"><a href="'.$likeusername.'">You, </a></span>';
}
else
{ 
echo '<a href="'.$likeusername.'">'.$likeusername.',</a>';
}  
}
if($new_like_count>0)
echo 'and '.$like.' other friends';
echo 'Like this';
?> 
</div>
<?php }
else { 
echo '<div class="likeUsers" id="elikes'.$row['upload_data_id'].'"></div>';
} 
 
			?>
				</div>
				<div class="rightbottom">
				
				<div class="friends_area" id="record-<?php  echo $row['upload_data_id']?>">
				
				<br clear="all" />
				<div id="CommentPosted<?php  echo $row['upload_data_id']?>">
				<?php
				$comment_num_row = mysqli_num_rows(@$comments);
				if($comment_num_row > 0)
				{
					while ($rows = mysqli_fetch_array($comments))
					{
						$days2 = floor($rows['CommentTimeSpent'] / (60 * 60 * 24));
						$remainder = $rows['CommentTimeSpent'] % (60 * 60 * 24);
						$hours = floor($remainder / (60 * 60));
						$remainder = $remainder % (60 * 60);
						$minutes = floor($remainder / 60);
						$seconds = $remainder % 60;	
				?>
				<div class="commentPanel" id="record-<?php  echo $rows['c_id'];?>" align="left">
						<img src="<?php echo $rows['profImage'];?>" height="30" width="30" class="CommentImg" style="float:left;" alt="" />
						<label style="float:left" class="name">

		   <b><?php echo $rows['username'];?></b> </label>
						<label class="postedComments">
							<?php  echo clickable_link($rows['c_text']);?>
						</label>
						<br clear="all" />
						<span style="margin-left:43px; color: #e1e1e1; font-size:11px">
						<?php
						
						if($days2 > 0)
						echo date('F d Y', $rows['c_when']);
						elseif($days2 == 0 && $hours == 0 && $minutes == 0)
						echo "few seconds ago";		
						elseif($days2 == 0 && $hours == 0)
						echo $minutes.' minutes ago';
						else
			echo "few seconds ago";	
						?>
						</span>
						 <?php
		   
		   $q1 = mysqli_query("SELECT pcl_id FROM photo_comments_like WHERE photo_comment_user_id='".$member_id."' and photo_comment_id='".$rows['c_id']."' ");

if(mysqli_num_rows($q1) > 0)
{
	echo '<a class="like1" id="like1'.$rows['c_id'].'" title="Unlike" rel="Unlike">Unlike</a>';
} 
else 
{ 
	echo '<a class="like1" id="like1'.$rows['c_id'].'" title="Like" rel="Like">Like</a>';
} 
		?><?php	
			
$like_count1 = $rows['like_count'];

if($like_count1 > 0) 
{ 
$query1=mysqli_query("SELECT U.username,U.member_id FROM photo_comments_like M, members U WHERE U.member_id=M.photo_comment_user_id AND M.photo_comment_id='".$rows['c_id']."' LIMIT 3");
$like1 = mysqli_num_rows($query1);
?>
<div class='likeUsers' id="likes<?php echo $rows['c_id'] ?>">
<?php
$new_like_count1 = $like_count1-3; 
while($row121=mysqli_fetch_array($query1))
{
$like_uid1=$row121['member_id']; 
$likeusername1=$row121['username']; 
if($like_uid1=$member_id)
{ 
echo '<span id="you1'.$rows['c_id'].'"><a style="color:#FFFFFF; font-size: 11px; margin: 3px 3px 3px 6px; text-decoration: none;" href="'.$likeusername1.'">You  '; 
if($like_count1>1)
echo ' ,'; 
echo '</a></span>';
}
else
{ 
echo '<a style="color:#FFFFFF; font-size: 11px; margin: 3px 3px 3px 6px; text-decoration: none;" href="'.$likeusername1.'">'.$likeusername1.',</a>';
}  
}
if($new_like_count1>0)
echo 'and '.$like1.' other friends';
echo 'Like this';
?> 
</div>
<?php }
else { 
echo '<div class="likeUsers" id="elikes1'.$rows['c_id'].'"></div>';
} 

		
			?>
						
						<?php
						$userip = $_SERVER['REMOTE_ADDR'];
						if($rows['c_user_id'] == $member_id){?>
						<div>
						<div id="normal-button" class="settings-button" title="0" value="<?php echo $rows['c_id']; ?>" >
						<span title="Edit & Delete" style="bottom: 2px;float: right;position: relative;width: 33px;cursor: pointer;" class="">
						<img src="images/edit_icon.png"/>						</span></div>
						<div class="submenu12" id="<?php echo $rows['c_id']; ?>-submenu12" style="display: none;float: right;
						 position: relative; background:#000000">
						<a href="#" class="edit_link" id="<?php  echo $rows['c_id'];?>" title="Edit">Edit</a><br>
						<a href="#" id="CID-<?php  echo $rows['c_id'];?>" class="c_delete">Delete</a>						</div>
						</div>
						
						<?php
						}?>
				  </div>
					<?php 
					}
				}?>
				</div><!--  CommentPosted -->
				<div class="commentBox" align="right" id="commentBox-<?php  echo $row['upload_data_id'];?>" <?php echo (($comment_num_row) ? '' :'style="display:none"')?>>
				<img src="<?php echo $res['profImage']; ?>" width="40" class="CommentImg" style="float:left;" alt="" />
				<label id="record-<?php  echo $row['upload_data_id'];?>">
					<textarea class="commentMark" id="commentMark-<?php  echo $row['upload_data_id'];?>" name="commentMark" cols="60"></textarea>
				</label>
				<br clear="all" />
				<a id="SubmitComment" class="small button comment"> Comment</a>			</div>
				</div> <!--  friends_area --> 
				</div>
				</div> 
        </div> <!--your content end-->
    </div> 
	<!--toPopup end-->
    
	<div class="loader" id="loader"></div>
   	<div class="backgroundPopup" id="backgroundPopup"></div>
	<?php
	$countrecord++;}
?>

    

</div>
</div>

</div>

</div>
<div id="toPopup">     	
        <div class="close"></div>
       	<span class="ecs_tooltip">Press Esc to Cancel <span class="arrow"></span></span>
		<div id="popup_content"> <!--your content start-->
            <p>
            
			<form action="action/add_photos.php" method="POST" enctype="multipart/form-data">			
			Choose photos <input class="sumbitform" type="file" name="files[]" multiple/>
            <input type="hidden" name="member_id" id="member_id" value="<?php echo $member_id;?>" />
            <input type="hidden" name="album_id" id="album_id" value="<?php echo $album_id?>" />
            <input type="hidden" name="country_id" id="country_id" value="<?php echo $country_id;?>" />
			<input type="submit" value="Upload" class=""/>
            </form>
            </p>            
        </div> <!--your content end-->    
    </div> <!--toPopup end-->
	
	
	<div id="share_popup" class="share_popup" style="display:none;">


<div id="custom_privacy" style="width:445px; margin:10% 40%; background:#FFF; border-radius:2px;">
<div style="background:#999; border:solid 1px #000000; width:445; text-align:center; padding:5px; font-weight:bold;">Share This</div>
<form action="action/add_share-exec.php" method="post">
<div style="padding:5px;">

<div style="margin-bottom:10px;">
<label style="float:left; font-size:12px; font-weight:bold; color:#00C; margin-right:5px;">Share With</label>
<select name="privacy" id="privacy" onChange="showHide()" style="padding:5px;">
<option value="1">On your own member wall</option>
<option value="2">On your friends member wall</option>
<option value="3">In a group</option>
<option value="4">On country wall</option>
<option value="5">On world wall</option>
</select>
</div>

<div style="margin-bottom:10px; margin-top:10px; display:none; " id="mvm">   
<input type="text" name="friend_name" id="friend_name" class="friend_name" style="padding:5px; width:95%;" placeholder="Friends Name">
</div>
<div style="margin-bottom:10px; margin-top:10px; display:none;" id="mvm1">
<input type="text" name="group_name" id="group_name" style="padding:5px; width:95%;" placeholder="Group Name">
</div>
<div style="margin-bottom:10px; margin-top:10px; display:none;" id="mvm2">
<input type="text" name="country_name" id="country_name" style="padding:5px; width:95%;" placeholder="Country Name">
</div>

<div>
<div style="margin-bottom:5px;">
<textarea name="share_status" id="share_status" style="width:95%;" placeholder="Write Something...."></textarea>
</div>
<div class="share_body"></div>
</div>

</div>
<a href="javascript:;" onclick="ai2display_bkmk(this, 'bkmk', '', '');">
<span style="float: left;width: 103px;cursor: pointer;color:#000000;" class="shareonsocial">Share on Social</span>
</a>

<?php 
if($row['type']==0)
{
?>
<div style="background:#999; border:solid 1px #000000; width:auto; height:30px; padding:3px;">
    <div style="float:right">
   
<input type="submit" name="submit" value="Share Status" style="float:right; height:26px;" />
<?php 
} 
if($row['type']==1)
{
?>
<input type="submit" name="submit" value="Share Photo" style="float:right; height:26px;" />
<?php }
if($row['type']==2)
{
?>
<input type="submit" name="submit" value="Share Video" style="float:right; height:26px;" />
<?php }
if($row['type']==3)
{
?>
<input type="submit" name="submit" value="Share Video" style="float:right; height:26px;" />
<?php }*/?>
<!--<input type="button" class="cancel_share" name="submit" value="Cancel" style="height:26px;" />!-->
</div>
</div>
</form>
</div>

</div>
<?php include('includes/footer.php');?>
</body>
</html>