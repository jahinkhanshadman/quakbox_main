<?php 	 
 	require_once($_SERVER['DOCUMENT_ROOT'].'/common/common.php');

$member_id1=$_SESSION['SESS_MEMBER_ID'];
$album_id=$_REQUEST['album_id'];
$get_country=mysqli_query($con,"Select * from user_album where album_id='$album_id'");
$get_country_result=mysqli_fetch_array($get_country);
$country_id=$get_country_result['country_id'];

?>







<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>QuakBox</title>

<link rel="stylesheet" type="text/css" href="css/component.css" />


<style>
		.myButton {
			background: linear-gradient(to bottom, #33bdef 5%, #019ad2 100%) repeat scroll 0 0 #33bdef;
			border-radius: 11px;
			box-shadow: 0 0 0 0 #f0f7fa;
			color: #ffffff;
			cursor: pointer;
			display: inline-block;
			font-family: Verdana;
			font-size: 17px;
			font-weight: bold;
			padding: 8px 19px;
			text-decoration: none;
			text-shadow: 0 -1px 0 #5b6178;
		}
				
		.PopupPanel
		{
			border: solid 1px black;
		        border: solid 1px black;
			position: absolute;
			left: 50%;
		   /*top: 50%;*/
			background-color:white;
			z-index: 999;
		
			height: 250px;
			margin-top: -200px;
		
			width: 600px;
			margin-left: -300px;
			
			
			
		}
		</style>

<script>
	

		function hide_div()

		{

		//alert('hi');

		$("#vinod").hide();

		}

		function disp_photo()

		{

		$( ".slideshow" ).show();

		}

		

		function editimg(albumid,imgid)

		{

		//slide_caption_

		x=$("#actimage_"+imgid).position();

		//alert($(window).height());

		//var doch=$( document ).height()/2;

		//alert(doch)

		var mid=$(window).height()/2;

	

		$("#vinod").show();

		var scrol=$(document).scrollTop();

		//alert(scrol);

		$("#vinod").css('top', scrol+300);


		var caption=$('#slidecaption_'+imgid).text();

		var caption2=caption.trim();

		//alert(caption2)

		$('#imgcapt').val(caption2);

		

		var desc=$('#slidedesc_'+imgid).text();

		var desc2=desc.trim();

		$('#imgdesc').val(desc2);

		//alert(albumid);

		//alert(imgid);

		 

		 $("#imgid").val(imgid);

		 $('#album_id').val(albumid);

		}

		

		function savechanges()

		{

		var cntid=$("#cntid").val();

		//alert(cntid);

		var imgid=$("#imgid").val();

		var album_id=$("#album_id").val();

		//alert(imgid);

		//alert(album_id);

		var imgcapt=$("#imgcapt").val();

		var imgdesc=$("#imgdesc").val();

		//alert(imgcapt);

		//alert(imgdesc);

		      if(imgcapt=="" && imgdesc=="")

		      {

		       alert('<?php echo $lang['Please Enter Photo Caption Or Description'];?>');

		       return false;

		      }

		      else

		      {

		

		

					        $.ajax({

			      url: '../saveimgcapt_desc.php',

			      type: 'post',

			      data: {'imgid':imgid, 'album_id': album_id,'imgcapt':imgcapt,'imgdesc':imgdesc},

			      success: function(data, status) {

			      

			          $('#vinod').html(data);

			          $('#slidecaption_'+imgid).html(imgcapt);

			          $('#slidedesc_'+imgid).html(imgdesc);

			       // $("#gridimgdesc_"+imgid).html(imgdesc);

			         //   $("#gridimgcapt_"+imgid).html(imgdesc);

			            $("#vinod").hide();

			            location.reload(true);

			          

			          

			         

			      },

			     

			    }); // end ajax call

			}		
                 

		}

		

		function showphoto(album_id,img_id)

		{

		//alert(country_id);

		//alert(album_id);

		//alert(img_id);

		 window.moveTo(0,0);

         var w = screen.width-400;

        var h = screen.height-300;

        var left = Number((screen.width/2)-(w/2));

        var tops = Number((screen.height/2)-(h/2));

       // $("#vinod").show();

       window.open("<?php echo $base_url;?>albums.php?member_id="+<?php echo $member_id1; ?>+"&back_page=photo_gall/myphotogall.php?album_id="+album_id+"&album_id="+album_id+"&image_id="+img_id+"&popup=1", '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+tops+', left='+left);



//window.open("http://google.com", '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+tops+', left='+left);





 }

</script>
 

<script src="js/modernizr.custom.js"></script>
<script src="<?php echo $base_url;?>js/jquery.min.js"></script>
<script src="js/script.js"></script>


	
</head>
    
<body >
<div id="wrapper">
    
 
<?php include($_SERVER['DOCUMENT_ROOT'].'/includes/qb_header.php'); ?>
   
    
<input type="hidden" id="cntid" value="<?php echo $country_id;?>">

	<?php 
	$sql = mysqli_query($con, "select * from geo_country where country_id='".$country_id."'") or die(mysqli_error($con));
	$res = mysqli_fetch_array($sql);
	$country_title = $res['country_title'];
	?>
		
		
  
   <div class="insideWrapper container" style="margin-top:90px">
             <div class="row">  
                <div class="col-lg-12 col-md-12 col-sm-12">
	<?php

			$pic1= "SELECT gp.upload_data_id,gp.date_created,gp.FILE_NAME,gp.album_id,m.member_id, gp.caption, gp.description

						FROM upload_data gp LEFT JOIN member m on gp.USER_CODE = m.member_id 

						WHERE gp.album_id='$album_id' 

						ORDER by gp.upload_data_id DESC";

			$stpic1=mysqli_query($con, $pic1) or die(mysqli_error($con));

			$row123=mysqli_fetch_array($stpic1);

			

			    $m_id123=$row123['member_id'];

			   $sql_m123="select * from member where member_id=$m_id123";

			   $st_m123=mysqli_query($con, $sql_m123);

			   $row1234=mysqli_fetch_array($st_m123);

			  $username=$row1234['username'];
			   
			   ?>
                   <?php
                   $pic121= "SELECT gp.upload_data_id,gp.date_created,gp.FILE_NAME,gp.album_id,m.member_id, gp.caption, gp.description

						FROM upload_data gp LEFT JOIN member m on gp.USER_CODE = m.member_id 

						WHERE gp.album_id='$album_id' 

						ORDER by gp.upload_data_id DESC";

			$stpic121=mysqli_query($con, $pic121) or die(mysqli_error($con));
			$row111=mysqli_fetch_array($stpic121);?>
    
            <input class="button" type="button"  onclick="window.open('<?php echo $base_url.'photos/'.$username;?>','_self')" value="<?php echo $lang['Back to Photos'];?>">
    
            <a href="#" class="topopup"><input type="button"  class="button" value="<?php echo $lang['Add photo'];?>" />
        
        </a> 
        <?php //echo $_SESSION['SESS_MEMBER_ID'];
 
        if($_SESSION['SESS_MEMBER_ID']==$row111['member_id'])
    
        {
    $alb_usr=mysqli_query($con, "SELECT * FROM member WHERE member_id='".$_SESSION['SESS_MEMBER_ID']."'") or die(mysqli_error($con));
    $sql_uname=mysqli_fetch_assoc($alb_usr);	
    $uname=$sql_uname['username'];				
    
                            ?>
                            
        <input type="button" id="<?php echo $album_id;?>" user_id="<?php echo $uname;?>" class="stdeletealb" value="<?php echo $lang['Delete album'];?>"
         /><?php } ?>
                
                
    
                             <?php
    
    
               /* while($row=mysqli_fetch_array($stpic))
    
                {	
    
                   $m_id=$row['member_id'];
    
                   $img_id=$row['upload_data_id'];
    
                   $sql_m="select * from member where member_id=$m_id";
    
                   $st_m=mysqli_query($con, $sql_m);
    
                   $row2=mysqli_fetch_array($st_m);
    
                   $postedby=$row2['username'];
    
                    $postDate = date("l jS F Y",$row['date_created']);
    
                    $sql_get_m_id="select * from message where photo_id=$img_id";
    
                    
    
                   $st_pic=mysqli_query($con, $sql_get_m_id);
    
                   $rowpic=mysqli_fetch_array($st_pic);
    
                   $message_id = $rowpic['messages_id'];
    
                
    
                $sqllike=mysqli_query($con, "SELECT bleh_id FROM bleh WHERE remarks='$message_id'"); 
    
                $count_like=mysqli_num_rows($sqllike);  
    
                
    
                $sqldislike="SELECT dislike_id FROM post_dislike WHERE msg_id='$message_id'"; 
    
                
    
                $count_dislike=mysqli_num_rows(mysqli_query($con,$sqldislike));  
    
                $sql_share="select * from count_share where message_id='$message_id'";
    
                $count_share=mysqli_num_rows(mysqli_query($con,$sql_share));  
    
                
    
            $count_comment="SELECT * FROM postcomment WHERE msg_id = '$message_id'";
    
            $count_comm=mysqli_num_rows(mysqli_query($con,$count_comment));
				}*/
?>	
                          
                           
    			
    
                            
    
    
    <?php
// disable warnings
if (version_compare(phpversion(), "5.3.0", ">=")  == 1)
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
else
  error_reporting(E_ALL & ~E_NOTICE); 


    require_once($_SERVER['DOCUMENT_ROOT'].'/qb_classes/connection/qb_database.php'); // include service classes to work with database and comments
    require_once($_SERVER['DOCUMENT_ROOT'].'/photo_gall/classes/MyComments.php');

// prepare a list with photos

$dbconn =new database();
/*$aItems =$dbconn ->execQueryWithFetchAll("SELECT gp.upload_data_id, gp.FILE_NAME,gp.date_created,gp.album_id,m.member_id, m.username, gp.caption, gp.description
    
                            FROM upload_data gp LEFT JOIN member m on gp.USER_CODE = m.member_id 
    
                            WHERE gp.album_id='$album_id'
    
                            ORDER by gp.upload_data_id DESC"); // get photos info*/
$query= "SELECT min(gp.upload_data_id) upload_data_id,min(gp.date_created) date_created,gp.FILE_NAME,gp.album_id,m.member_id,m.username, gp.caption, gp.description
    
                            FROM upload_data gp LEFT JOIN member m on gp.USER_CODE = m.member_id 
    
                            WHERE gp.album_id='$album_id' 
                            
                            group by gp.FILE_NAME,gp.album_id,m.member_id,m.username, gp.caption, gp.description
    
                            ORDER by gp.upload_data_id DESC";
    
$aItems=mysqli_query($con, $query) or die(mysqli_error($con));
                

$count=mysqli_num_rows($aItems);
if($count==0)
 $sPhotos='<b><font color="red">'.$lang['NO PHOTO AVAILABLE'].'</font></b>';
    
else  
{	
$sPhotos='<div id="grid-gallery" class="grid-gallery" > <section class="grid-wrap"> <ul class="grid"> <li class="grid-sizer"></li>';
while($aItemInfo=mysqli_fetch_array($aItems)) 				
//foreach ($aItems as $i => $aItemInfo)
 {
    //$sPhotos .= '<div class="photo"><img src="../'.$aItemInfo['FILE_NAME'].'" id="'.$aItemInfo['upload_data_id'].'" /><p>'.$aItemInfo['description'].' item</p><i>'.$aItemInfo['description'].'</i></div>';
$postDate = date("l jS F Y",$aItemInfo['date_created']);
$sPhotos .='<li> 
           <div class="photo"> 
           <figure  id="grid_caption_'.$aItemInfo['upload_data_id'].'" style="padding:initial !important; margin-top: 23px ! important;">
<img src="../'.$aItemInfo['FILE_NAME'].'" alt="'.$aItemInfo['description'].'" title="'.$aItemInfo['description'].'" height="200" width="200" id="'.$aItemInfo['upload_data_id'].'"/>
<figcaption>
<h3 id="gridimgcapt_'.$aItemInfo['upload_data_id'].'">'.$aItemInfo['caption'].'</h3>
<p style="margin-top: -10px;" id="gridimgdesc_'.$aItemInfo['upload_data_id'].'"'.$aItemInfo['description'].'" </p>
<font size="2" color="#3300CC">
<i>'.$lang['Posted By'].': '.$aItemInfo['username'].'<br>'.$lang['On'].' '.$postDate.'</i>
</font> 
</figcaption>
</figure>
</div>
</li>';
	}
 $sPhotos .='</ul> </section> </div>';  // grid-gallery 
}
?>         

<!-- Container with last photos -->
    <div class="container">
       <?=$sPhotos ?>
        
    </div>
     
      
    

<!-- Hidden preview block -->
    <div id="photo_preview" style="display:none">
        <div class="photo_wrp">
            <img class="close" src="../images/closebox.png" />
            <div style="clear:both"></div>
            <div class="pleft">test1</div>
            <div class="pright">test2</div>
            <div style="clear:both"></div>
        </div>
    </div>
                
                </div>
            </div>
        </div>
      
		
</div>
    
    
<?php
        include_once ($_SERVER['DOCUMENT_ROOT'].'/share.php');
	include($_SERVER['DOCUMENT_ROOT'].'/includes/qb_footer.php');
?>

    
    
    
</body>
</html>
