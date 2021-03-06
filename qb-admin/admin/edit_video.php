<?php session_start();

if(!isset($_SESSION['id']))
{
	header("location:login.php");
}
$id = $_SESSION['id'];
$video_id = $_GET['id'];

include("config.php");

$query = "SELECT * FROM admins WHERE id = '$id'";
$sql = mysqli_query($conn,$query);
$res = mysqli_fetch_array($sql);
$res1= $res['email'];

$video_sql = mysqli_query($conn,"SELECT a.type, a.location,a.location1,a.location2,a.thumburl,a.thumburl1,a.thumburl2,
                          a.thumburl3,a.thumburl4,a.thumburl5,a.title,a.description,a.title_size,a.title_color,
						  b.ads_name,c.name,a.ads,b.ads_name,a.status
                          FROM videos a 
                          LEFT JOIN videos_ads b ON a.ads_id = b.id
						  INNER JOIN videos_category c ON a.category = c.id 						   
						  WHERE video_id = '$video_id'") or die(mysqli_error($conn));	
$video_res = mysqli_fetch_array( $video_sql );
 
?>


<!DOCTYPE html>
<html lang="en">
<head>
	
	<meta charset="utf-8">
	<title>Quakbox</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Charisma, a fully featured, responsive, HTML5, Bootstrap admin template.">
	<meta name="author" content="Muhammad Usman">

	<!-- The styles -->
	<link id="bs-css" href="css/bootstrap-cerulean.css" rel="stylesheet">
	<style type="text/css">
	  body {
		padding-bottom: 40px;
	  }
	  .sidebar-nav {
		padding: 9px 0;
	  }
	</style>
	<link href="css/bootstrap-responsive.css" rel="stylesheet">
	<link href="css/charisma-app.css" rel="stylesheet">
	<link href="css/jquery-ui-1.8.21.custom.css" rel="stylesheet">
	<link href='css/fullcalendar.css' rel='stylesheet'>
	<link href='css/fullcalendar.print.css' rel='stylesheet'  media='print'>
	<link href='css/chosen.css' rel='stylesheet'>
	<link href='css/uniform.default.css' rel='stylesheet'>
	<link href='css/colorbox.css' rel='stylesheet'>
	<link href='css/jquery.cleditor.css' rel='stylesheet'>
	<link href='css/jquery.noty.css' rel='stylesheet'>
	<link href='css/noty_theme_default.css' rel='stylesheet'>
	<link href='css/elfinder.min.css' rel='stylesheet'>
	<link href='css/elfinder.theme.css' rel='stylesheet'>
	<link href='css/jquery.iphone.toggle.css' rel='stylesheet'>
	<link href='css/opa-icons.css' rel='stylesheet'>
	<link href='css/uploadify.css' rel='stylesheet'>

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- The fav icon -->
	<link rel="shortcut icon" href="images/favicon.ico">
		
</head>

<body>

	<?php include("include/header.php");
		  include("include/sidebar.php");

	 ?>
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="#">Home</a> 
					</li>
					<li>
					<!--	<a href="#">Dashboard</a>-->
					</li>
				</ul>
			</div>
            
						
			<div class="row-fluid">
				<div class="box span12">
					<div class="box-header well">
						<h2><i class="icon-info-sign"></i> Edit video</h2>
						<div class="box-icon">
							<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
                    
                    
                    <!--start processing form-->
                    
<div id="ProcessPage"><!--Second Division with Progress bar forms and thumbnails.-->

<div class="rightContainer">      

	<div id="video">       		

       	<div id="vidplayer">
        
        <video id="videojsidid" class="" controls autoplay preload="none" width="320" height="240" data-setup="{}">
        <source id="mp4src" src="<?php echo $base_url.$video_res['location']; ?>" type="video/mp4" />
        <source id="oggsrc" src="<?php echo $base_url.$video_res['location1']; ?>" type="video/ogg" />
        <source id="webmsrc" src="<?php echo $base_url.$video_res['location2']; ?>" type="video/webm" />
        </video>

		</div>

	</div>	

	

	<div id="videoTitle">
    
    <form class="form-horizontal">
						<fieldset>
                        <legend>Video Information </legend>
							<div class="control-group">
							  <label class="control-label" for="fileInput">Title</label>
							  <div class="controls">
								<input type="text" name="title" id="ftitle" class="input-xlarge" value="<?php echo $video_res['title']; ?>"/>
							  </div>
							</div>
                            
                            <div class="control-group">
							  <label class="control-label" for="fileInput">Description</label>
							  <div class="controls">
								<textarea name="description" id="description" class="input-xlarge"><?php echo $video_res['description']; ?></textarea>
							  </div>
							</div>
                            
                            <div class="control-group">
								<label class="control-label" for="selectError">Category</label>
								<div class="controls">
								  <select id="category" data-rel="chosen" class="input-xlarge" name="category">
                                  <?php 
								  $cat_sql = mysqli_query($conn,"SELECT * FROM videos_category");
								  while($cat_res = mysqli_fetch_array($cat_sql)){
								  ?>									
									<option value="<?php echo $cat_res['id']; ?>" <?php if($cat_res['name'] == $video_res['name']) echo 'selected' ?>><?php echo $cat_res['name']; ?></option>									
                                    <?php } ?>
								  </select>
								</div>
							  </div>
                              
                              <div class="control-group">
								<label class="control-label">Privacy</label>
								<div class="controls">
								  <label class="radio">
									<input type="radio" name="privacy" id="privacy" value="0" <?php if($video_res['type'] == 0) echo 'checked' ?>>
									Public
								  </label>
								  <div style="clear:both"></div>
								  <label class="radio">
									<input type="radio" name="privacy" id="privacy" value="1" <?php if($video_res['type'] == 1) echo 'checked' ?>>
									Private
								  </label>
								</div>
							  </div>
                            
                            <div class="control-group">
								<label class="control-label" for="selectError">Published</label>
								<div class="controls">
								  <select id="published" data-rel="chosen" class="input-xlarge" name="published">
									<option value="1" <?php if($video_res['status'] == 1) echo 'selected' ?>>Published</option>
									<option value="0" <?php if($video_res['status'] == 0) echo 'selected' ?>>Unpublished</option>									
								  </select>
								</div>
							  </div>
                              
                              <div class="control-group">
							  <label class="control-label" for="fileInput">Font size</label>
							  <div class="controls">
								<select id="fontsizeid" class="required inputbox text3" name="fontsize" style="width:90px;" >
					 	<option value="12" <?php if($video_res['title_size'] == 12) echo 'selected'; ?>>Size</option>
			<option value="14" <?php if($video_res['title_size'] == 14) echo 'selected'; ?>>14px</option>
			<option value="16" <?php if($video_res['title_size'] == 16) echo 'selected'; ?>>16px</option>
			<option value="18" <?php if($video_res['title_size'] == 18) echo 'selected'; ?>>18px</option> 
			<option value="20" <?php if($video_res['title_size'] == 20) echo 'selected'; ?>>20px</option>
			<option value="22" <?php if($video_res['title_size'] == 22) echo 'selected'; ?>>22px</option>
			<option value="24" <?php if($video_res['title_size'] == 24) echo 'selected'; ?>>24px</option>
			<option value="26" <?php if($video_res['title_size'] == 26) echo 'selected'; ?>>26px</option>
			<option value="28" <?php if($video_res['title_size'] == 28) echo 'selected'; ?>>28px</option>		
			    	</select>
							  </div>
							</div>
                            
                            <div class="control-group">
							  <label class="control-label" for="fileInput">Title Color</label>
							  <div class="controls">
								<input type="text" name="title_color" id="title_color" class="input-xlarge" value="<?php echo $video_res['title_color']; ?>"/>
							  </div>
							</div>
                            
                            <div class="control-group">
								<label class="control-label">Video ads</label>
								<div class="controls">
								  <label class="radio">
									<input type="radio" name="ads" id="ads" value="1" <?php if($video_res['ads'] == 1) echo 'checked' ?>>
									Enable
								  </label>
								  <div style="clear:both"></div>
								  <label class="radio">
									<input type="radio" name="ads" id="ads" value="0" <?php if($video_res['ads'] == 0) echo 'checked' ?>>
									Disable
								  </label>
								</div>
							  </div>
                              
                              <div class="control-group">
								<label class="control-label" for="selectError">Ads name</label>
								<div class="controls">
								  <select id="ads_id" data-rel="chosen" class="input-xlarge" name="ads_id">
                                  <?php 
								  $ads_sql = mysqli_query($conn,"SELECT * FROM videos_ads");
								  while($ads_res = mysqli_fetch_array($ads_sql)){
								  ?>									
									<option value="<?php echo $ads_res['id']; ?>" <?php if($ads_res['ads_name'] == $video_res['ads_name']) echo 'selected' ?>><?php echo $ads_res['ads_name']; ?></option>									
                                    <?php } ?>
								  </select>
								</div>
							  </div>
                            
                            <div class="form-actions">
                            	<input type="hidden" name="video_id" id="video_id" value="<?php echo $video_id; ?>">
								<button type="button" class="btn btn-primary" id="publish_video">Save changes</button>
								<button type="button" class="btn" onclick="window.open('video.php','_self');">Cancel</button>
							  </div>

						</fieldset>
					</form>
    
		 


	</div>
</div>  

</div>

					<!--end processing form-->
                    
						<div class="clearfix"></div>
					</div>
				</div>
			</div>
					
			<!--/span-->
						
				

		  
       
					<!-- content ends -->
			</div><!--/#content.span10-->
				</div><!--/fluid-row-->
				
		<hr>

		<div class="modal hide fade" id="myModal">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">??</button>
				<h3>Settings</h3>
			</div>
			<div class="modal-body">
				<p>Here settings can be configured...</p>
			</div>
			<div class="modal-footer">
				<a href="#" class="btn" data-dismiss="modal">Close</a>
				<a href="#" class="btn btn-primary">Save changes</a>
			</div>
		</div>

		
		<!--<footer>
			<p class="pull-left">&copy; <a href="http://usman.it" target="_blank">Muhammad Usman</a> 2012</p>
			<p class="pull-right">Powered by: <a href="http://usman.it/free-responsive-admin-template">Charisma</a></p>
		</footer>  -->
		
	</div><!--/.fluid-container-->

	<!-- external javascript
	================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->

	<!-- jQuery -->
	<script src="js/jquery-1.7.2.min.js"></script>
	<!-- jQuery UI -->
	<script src="js/jquery-ui-1.8.21.custom.min.js"></script>
	<!-- transition / effect library -->
	<script src="js/bootstrap-transition.js"></script>
	<!-- alert enhancer library -->
	<script src="js/bootstrap-alert.js"></script>
	<!-- modal / dialog library -->
	<script src="js/bootstrap-modal.js"></script>
	<!-- custom dropdown library -->
	<script src="js/bootstrap-dropdown.js"></script>
	<!-- scrolspy library -->
	<script src="js/bootstrap-scrollspy.js"></script>
	<!-- library for creating tabs -->
	<script src="js/bootstrap-tab.js"></script>
	<!-- library for advanced tooltip -->
	<script src="js/bootstrap-tooltip.js"></script>
	<!-- popover effect library -->
	<script src="js/bootstrap-popover.js"></script>
	<!-- button enhancer library -->
	<script src="js/bootstrap-button.js"></script>
	<!-- accordion library (optional, not used in demo) -->
	<script src="js/bootstrap-collapse.js"></script>
	<!-- carousel slideshow library (optional, not used in demo) -->
	<script src="js/bootstrap-carousel.js"></script>
	<!-- autocomplete library -->
	<script src="js/bootstrap-typeahead.js"></script>
	<!-- tour library -->
	<script src="js/bootstrap-tour.js"></script>
	<!-- library for cookie management -->
	<script src="js/jquery.cookie.js"></script>
	<!-- calander plugin -->
	<script src='js/fullcalendar.min.js'></script>
	<!-- data table plugin -->
	<script src='js/jquery.dataTables.min.js'></script>

	<!-- chart libraries start -->
	<script src="js/excanvas.js"></script>
	<script src="js/jquery.flot.min.js"></script>
	<script src="js/jquery.flot.pie.min.js"></script>
	<script src="js/jquery.flot.stack.js"></script>
	<script src="js/jquery.flot.resize.min.js"></script>
	<!-- chart libraries end -->

	<!-- select or dropdown enhancer -->
	<script src="js/jquery.chosen.min.js"></script>
	<!-- checkbox, radio, and file input styler -->
	<script src="js/jquery.uniform.min.js"></script>
	<!-- plugin for gallery image view -->
	<script src="js/jquery.colorbox.min.js"></script>
	<!-- rich text editor library -->
	<script src="js/jquery.cleditor.min.js"></script>
	<!-- notification plugin -->
	<script src="js/jquery.noty.js"></script>
	<!-- file manager library -->
	<script src="js/jquery.elfinder.min.js"></script>
	<!-- star rating plugin -->
	<script src="js/jquery.raty.min.js"></script>
	<!-- for iOS style toggle switch -->
	<script src="js/jquery.iphone.toggle.js"></script>
	<!-- autogrowing textarea plugin -->
	<script src="js/jquery.autogrow-textarea.js"></script>
	<!-- multiple file upload plugin -->
	<script src="js/jquery.uploadify-3.1.min.js"></script>
	<!-- history.js for cross-browser state change on ajax -->
	<script src="js/jquery.history.js"></script>
	<!-- application script for Charisma demo -->
	<script src="js/charisma.js"></script>
    <script src="../../js/form.js"></script>
    
    <script type="text/javascript">

 //Global Variables


 var uploadBoolCheck = false;//for preventing page refresh while processing by window unload function

 $(document).ready(function() {
 
//function to uploAD video

 $('#publish_video').click(function(){
	 
    var ftitle = $('#ftitle').val();	
	var description = $('#description').val();
	var category =  $('#category').val();
	var privacy = $('#privacy').val();
	var published = $('#published').val();
	var title_size = $('#fontsizeid').val();
	var title_color = $('#title_color').val();
	var video_id = $('#video_id').val();
	var ads = $('#ads').val();
	var ads_id = $('#ads_id').val();

    $.post('action/video_edit.php', { title : ftitle , desc : description, category : category, privacy : privacy, published : published , title_size:title_size, title_color:title_color, video_id : video_id, ads : ads, ads_id : ads_id}, function(data) {

    window.location.href= "video.php";

     });
});

 });
 
        	window.onbeforeunload = function(){
				if(uploadBoolCheck){
	    		    return 'You have unsaved changes!';
		        }
			}

 </script>
	
		
</body>
</html>