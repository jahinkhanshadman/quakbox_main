<?php session_start();
if(isset($_SESSION['id']))
{
	include("config.php");
	$id = $_SESSION['id'];
	$query = "SELECT * FROM admins WHERE id = '$id'";
	$sql =  mysqli_query($conn, $query);
	$res =  mysqli_fetch_assoc($sql);
	$res1= $res['email'];
	?>
<?php
	$id1= $_GET['id'];
	$sql1="SELECT *, username as login_name, concat(first_name, ' ' , last_name ) as name FROM admins WHERE id='$id1'";
	$result= mysqli_query($conn,$sql1);
	$rows= mysqli_fetch_assoc($result); 
// get module access 
$memberAccessQuery = "SELECT * FROM member_accesslevel WHERE member_id = '$id1'";
$memberAccessResult = mysqli_query($conn,$memberAccessQuery );
$memberAccess = array();
while( $memberAccessRow = mysqli_fetch_object( $memberAccessResult )){
	$memberAccess[$memberAccessRow->module_name]['edit_access'] = $memberAccessRow->edit_access;
	$memberAccess[$memberAccessRow->module_name]['delete_access'] = $memberAccessRow->delete_access;
}
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
	<link href='css/validationEngine.jquery.css' rel='stylesheet'>

	<!-- The HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- The fav icon -->
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="js/jquery-1.7.2.min.js" type="text/javascript" charset="utf-8"></script>
	<script src="js/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8"></script>	
     <script src="js/jquery.validationEngine.js" type="text/javascript" charset="utf-8"></script>
      <script>
	 $.noConflict();
	 jQuery( document ).ready(function( $ ) {
		 $('#membertype').change(function(){
	        	if($('#membertype').val() == 'super admin') {
	        		$('#accesslevelDiv').hide(); 
	            }
	        	else {
	        		$('#accesslevelDiv').show(); 
	           	}
	        });
		 $('#membertype').trigger('change');
			 
		 $("#Edit_form").validationEngine({
		     ajaxFormValidation : false,
		     onAjaxFormComplete: ajaxValidationCallback,
				onBeforeAjaxFormValidation: beforeCall
		});	
       
       });
	 function beforeCall(form, options){
			if (console) 
			console.log("Right before the AJAX form validation call");
			return true;
		}
	 function ajaxValidationCallback(status, form, json, options) {
		 if (console) 
				console.log(status);

		 if(status == true) {
			 alert("the form is valid!");
		        }
		 }
    </script>
</head>

<body>
		<!-- topbar starts -->
	<?php include("include/header.php");
		  include("include/sidebar.php");
	 ?>
	
			<noscript>
				<div class="alert alert-block span10">
					<h4 class="alert-heading">Warning!</h4>
					<p>You need to have <a href="http://en.wikipedia.org/wiki/JavaScript" target="_blank">JavaScript</a> enabled to use this site.</p>
				</div>
			</noscript>
			
			<div id="content" class="span10">
			<!-- content starts -->
			

			<div>
				<ul class="breadcrumb">
					<li>
						<a href="index.php">Home</a> <span class="divider">/</span>
					</li>
					<li>
						<a href="#">Members</a> <span class="divider">/</span>
					</li>
                    <li>
						<a href="#">Edit Member</a>
					</li>
				</ul>
			</div>
			
			<div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Edit</h2>
						<div class="box-icon">
							</div>
					</div>
                    
                    

					

					<div class="box-content">
						
                      <form class="form-horizontal" action="member_edit_action.php" method="post" enctype="multipart/form-data" id="Edit_form">
							<fieldset>
                           		<input type="hidden" name="id" value="<?php echo $rows['id'];?>">
							  <div class="control-group">
								<label class="control-label" for="focusedInput">Name</label>
								<div class="controls">
								  <input class="input-xlarge focused validate[required,custom[onlyLetterSp],minSize[5],maxSize[50]] input-medium primary" id="name" type="text" name="name" value="<?php echo $rows['name'];?>">
								</div>
							  </div>
                              
                              <div class="control-group">
								<label class="control-label" for="focusedInput">Login Name</label>
								<div class="controls">
								   <!-- input class="input-xlarge focused" id="login_name" type="text" name="login_name" value="<?php echo $rows['login_name'];?>" -->
								  <label name="login_name"><?php echo $rows['login_name'];?></label> 
								</div>
							  </div>
                              
                              <div class="control-group">
								<label class="control-label" for="focusedInput">Password</label>
								<div class="controls">
								  <input class="input-xlarge focused input-medium primary" id="password" type="password" name="password" value="">
								</div>
							  </div>
                              
                              <div class="control-group">
								<label class="control-label" for="focusedInput">Confirm Password</label>
								<div class="controls">
								  <input class="input-xlarge focused validate[equals[password]]" id="cpassword" type="password" name="cpassword" value="">
								</div>
							  </div>
                              
                              <div class="control-group">
								<label class="control-label" for="focusedInput">E-mail</label>
								<div class="controls">
								  <input class="input-xlarge focused validate[required,custom[email]] input-medium primary" id="focusedInput" type="text" name="email" value="<?php echo $rows['email'];?>">
								</div>
							  </div>
							   <div class="control-group">
								<label class="control-label" for="focusedInput">Member Type</label>
								<div class="controls">
								  <select name="member_type" size="1" aria-controls="member_type" class="validate[required]" id="membertype">
								  	<option value="" selected="selected">-- Select Member Type --</option>
								  	<option value="super admin" <?php if( $rows['status'] == 'super admin'){echo 'selected="selected"';};?>>Super Admin</option>
								  	<option value="admin" <?php if( $rows['status'] == 'admin'){echo 'selected="selected"';};?>>Admin</option>
								  	<option value="manager" <?php if( $rows['status'] == 'manager'){echo 'selected="selected"';};?>>Manager</option>
								  </select>
								</div>
							  </div>
							  <div id="accesslevelDiv">
							  <div class="control-group">
								<label class="control-label" for="focusedInput">Access Level</label>
							  </div>
							  <?php
								foreach( $gModules as $module ):
								// check for edit access
								$checkedEdit = $checkedDelete = '';								
								if( isset($memberAccess[$module]['edit_access']) && $memberAccess[$module]['edit_access'] == 1 ){
									$checkedEdit = 'checked="checked"';
								}
								// check for delete access
								if( isset( $memberAccess[$module]['delete_access']) && $memberAccess[$module]['delete_access'] == 1 ){
									$checkedDelete = 'checked="checked"';
								}
							  ?>
							  <div class="control-group">
								<label class="control-label" for="focusedInput"><?php echo ucwords($module);?></label>
								<div class="controls">
								  <label class="checkbox inline"><input type="checkbox"  name= "module[<?php echo $module;?>][check_edit]" value="1" <?php echo $checkedEdit;?>>Edit </label>
								  <label class="checkbox inline"><input <?php echo $checkedDelete;?> type="checkbox"  name= "module[<?php echo $module;?>][check_del]"  value="1" enabled>Delete</label>
								</div>
							  </div>
							  <?php endforeach;?>							  

                              </div>
                              <div class="form-actions">
								<button type="submit" name="submit" class="btn btn-primary">Save</button>
								<a class="btn" href="member_table.php">Cancel</a>
							  </div>
                              </fieldset>
                              </form>  
                                  
					</div>
				</div><!--/span-->
			
			</div><!--/row-->
		<?php 
				include("include/footer.php");
			?>
		
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
</body>
</html>

<?php
}
else
header("Location: login.php");
?>
