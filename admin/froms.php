<!DOCTYPE html>
<!--[if lt IE 7]> <html class="ie lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html class="ie lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html class="ie lt-ie9"> <![endif]-->
<!--[if gt IE 8]> <html class="ie gt-ie8"> <![endif]-->
<!--[if !IE]><!--><html><!-- <![endif]-->
<head><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PHP New CMS Admin Screen</title>
<!-- ================================ include css as .less file ========================= -->
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="css/uniform.default.css">
<link rel="stylesheet" type="text/css" href="css/bootstrap-select.css">
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="css/jasny-bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/font-awesome-ie7.min.css" />
<link rel="stylesheet/less" type="text/css" href="css/colors.less" media="screen"/>

<!-- ======================================== End css =================================== -->
<script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script><script language=javascript>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            </script></head>

<body>
<div class="container-fluid fluid menu-left">
  <!-- Top navbar -->
  <div class="navbar main hidden-print">     
    <!-- Brand --> 
    <a href="#" class="appbrand"><span>PHP Admin Demo <span>v1.0</span></span></a>  
    <!-- Top Menu Right -->
    <ul class="topnav pull-right">
      
    
      <!-- Dropdown -->
      <li class="dropdown visible-abc"> <a href="#" data-toggle="dropdown" class="glyphicons cogwheel"><i class="icon-cog"></i>Dropdown <span class="caret"></span></a>
        <ul class="dropdown-menu pull-right">
          <li class="dropdown submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Level 2</a>
            <ul class="dropdown-menu submenu-show submenu-hide pull-left">
              <li class="dropdown submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Level 2.1</a>
                <ul class="dropdown-menu submenu-show submenu-hide pull-left">
                  <li><a href="#">Level 2.1.1</a></li>
                  <li><a href="#">Level 2.1.2</a></li>
                  <li><a href="#">Level 2.1.3</a></li>
                  <li><a href="#">Level 2.1.4</a></li>
                </ul>
              </li>
              <li class="dropdown submenu"> <a href="#" class="dropdown-toggle" data-toggle="dropdown">Level 2.2</a>
                <ul class="dropdown-menu submenu-show submenu-hide pull-left">
                  <li><a href="#">Level 2.2.1</a></li>
                  <li><a href="#">Level 2.2.2</a></li>
                </ul>
              </li>
            </ul>
          </li>
          <li><a href="#">Some option</a></li>
          <li><a href="#">Some other option</a></li>
          <li><a href="#">Other option</a></li>
        </ul>
      </li>
      <!-- // Dropdown END --> 
      
      <!-- Profile / Logout menu -->
      <li class="account"> <a data-toggle="dropdown" href="#" class="glyphicons logout rightalign"><span class="hidden-phone text">mosaicpro</span><i class="icon-lock"></i></a>
        <ul class="dropdown-menu pull-right">
          <li><a href="my_account9ed2.html?lang=en" class="glyphicons">Settings<i class="icon-cog"></i></a></li>
          <li><a href="my_account9ed2.html?lang=en" class="glyphicons">My Photos<i class="icon-camera"></i></a></li>
          <li class="highlight profile"> <span> <span class="heading">Profile <a href="my_account9ed2.html?lang=en" class="pull-right">edit</a></span> <span class="img"></span> <span class="details"> <a href="my_account9ed2.html?lang=en">Jhon Smith</a> contact@emailid.com </span> <span class="clearfix"></span> </span> </li>
          <li> <span> <a class="btn btn-default btn-mini pull-right" href="login9ed2.html?lang=en">Sign Out</a> </span> </li>
        </ul>
      </li>
      <!-- // Profile / Logout menu END -->
      
    </ul>
    <!-- // Top Menu Right END -->     
  </div>
  <!-- Top navbar END -->   
  <!-- Sidebar menu & content wrapper -->
  <div id="wrapper">     
    <!-- Sidebar Menu -->
    <div id="menu" class="hidden-phone hidden-print"> 
      
      <!-- Scrollable menu wrapper with Maximum height -->
      <div class="slim-scroll" data-scroll-height="800px"> 
        
        <!-- Sidebar Profile --> 
        <span class="profile"> <a class="img" href="#"><img src="images/mrawesome.jpg" alt="Mr. Awesome" /></a> <span> <strong>Welcome</strong> <a href="my_account9ed2.html?lang=en" class="glyphicons">mosaicpro</a> </span> </span> 
        <!-- // Sidebar Profile END --> 
        
        
        <!-- Regular Size Menu -->
        <ul>
          
          <!-- Menu Regular Item -->
          <li class="active"><a href="#" class="glyphicons"><i class="icon-desktop"></i><span>Dashboard</span></a></li>
          
          <!-- Components Submenu Level 1 -->
          <li class="hasSubmenu cogwheels"> <a class="glyphicons" data-toggle="collapse" href="#menu_components"><i class="icon-cogs"></i><span>Components</span></a>
            <ul class="collapse" id="menu_components">
              
              <!-- Components Submenu Level 2 -->
              <li class="hasSubmenu"> <a data-toggle="collapse" href="#menu_forms"><span>Forms</span></a>
                <ul class="collapse" id="menu_forms">
                  <li class=""><a href="form_wizards9ed2.html?lang=en"><span>Form Wizards</span></a></li>
                  <li class=""><a href="form_elements9ed2.html?lang=en"><span>Form Elements</span></a></li>
                  <li class=""><a href="form_validator9ed2.html?lang=en"><span>Form Validator</span></a></li>
                  <li class=""><a href="file_managers9ed2.html?lang=en"><span>File Managers</span></a></li>
                </ul>
                <span class="count">4</span> </li>
              <!-- // Components Submenu Level 2 END --> 
              
              <!-- Components Submenu Regular Items -->
              <li class=""><a href="ui9ed2.html?lang=en"><span>UI Elements</span></a></li>
              <li class=""><a href="icons9ed2.html?lang=en"><span>Icons</span></a></li>
              <li class=""><a href="widgets9ed2.html?lang=en"><span>Widgets</span></a></li>
              <li class=""><a href="tabs9ed2.html?lang=en"><span>Tabs</span></a></li>
              <li class=""><a href="sliders9ed2.html?lang=en"><span>Sliders</span></a></li>
              <li class=""><a href="charts9ed2.html?lang=en"><span>Charts</span></a></li>
              <!-- // Components Submenu Regular Items END --> 
              
              <!-- Components Submenu Level 2 -->
              <li class="hasSubmenu"> <a data-toggle="collapse" href="#menu_tables"><span>Tables</span></a>
                <ul class="collapse" id="menu_tables">
                  <li class=""><a href="tables9ed2.html?lang=en"><span>Tables</span></a></li>
                  <li class=""><a href="pricing_tables9ed2.html?lang=en"><span>Pricing tables</span></a></li>
                </ul>
                <span class="count">2</span> </li>
              <!-- // Components Submenu Level 2 --> 
              
              <!-- Components Submenu Regular Items -->
              <li class=""><a href="grid9ed2.html?lang=en"><span>Grid system</span></a></li>
              <li class=""><a href="notifications9ed2.html?lang=en"><span>Notifications</span></a></li>
              <li class=""><a href="modals9ed2.html?lang=en"><span>Modals</span></a></li>
              <li class=""><a href="thumbnails9ed2.html?lang=en"><span>Thumbnails</span></a></li>
              <li class=""><a href="carousels9ed2.html?lang=en"><span>Carousels</span></a></li>
              <li class=""><a href="tour9ed2.html?lang=en"><span>Page Tour</span></a></li>
              <!-- // Components Submenu Regular Items END -->
              
            </ul>
            <span class="count">18</span> </li>
          <!-- Components Submenu Level 1 END --> 
          
          <!-- Extra Submenu Level 1 -->
          <li class="hasSubmenu gift"> <a data-toggle="collapse" class="glyphicons" href="#menu_extra"><i class="icon-th"></i><span>Extra</span></a>
            <ul class="collapse" id="menu_extra">
              <li class=""><a href="my_account_advanced9ed2.html?lang=en"><span>Advanced profile</span></a></li>
              <li class=""><a href="my_account9ed2.html?lang=en"><span>My Account</span></a></li>
              <li class=""><a href="bookings9ed2.html?lang=en"><span>Bookings</span></a></li>
              <li class=""><a href="finances9ed2.html?lang=en"><span>Finances</span></a></li>
              <li class=""><a href="pages9ed2.html?lang=en"><span>Site Pages</span></a></li>
              <li><a href="error9ed2.html?lang=en"><span>Error page</span></a></li>
              <li><a href="blank9ed2.html?lang=en"><span>Blank page</span></a></li>
            </ul>
            <span class="count">7</span> </li>
          <!-- // Extra Submenu Level 1 END --> 
          
          <!-- Gallery Submenu Level 1 -->
          <li class="hasSubmenu"> <a data-toggle="collapse" class="glyphicons" href="#menu_gallery"><i class="icon-picture"></i><span>Photo Gallery</span></a>
            <ul class="collapse" id="menu_gallery">
              <li class=""><a href="gallery_19ed2.html?lang=en"><span>Gallery #1</span></a></li>
              <li class=""><a href="gallery_29ed2.html?lang=en"><span>Gallery #2</span></a></li>
            </ul>
            <span class="count">2</span> </li>
          <!-- // Gallery Submenu Level 1 END --> 
          
          <!-- Shop Submenu Level 1 -->
          <li class="hasSubmenu"> <a data-toggle="collapse" class="glyphicons" href="#menu_ecommerce"><i class=" icon-shopping-cart"></i><span>Online Shop</span></a>
            <ul class="collapse" id="menu_ecommerce">
              <li class="hasSubmenu"> <a data-toggle="collapse" href="#menu_ecommerce_client"><i></i><span>Client interface</span></a>
                <ul class="collapse" id="menu_ecommerce_client">
                  <li class=""><a href="shop_client_products9ed2.html?lang=en"><span>Products</span></a></li>
                  <li class=""><a href="shop_client_product9ed2.html?lang=en"><span>Product details</span></a></li>
                  <li class=""><a href="shop_client_cart9ed2.html?lang=en"><span>Shopping cart</span></a></li>
                </ul>
                <span class="count">3</span> </li>
              <li class="hasSubmenu"> <a data-toggle="collapse" href="#menu_ecommerce_admin"><i></i><span>Management</span></a>
                <ul class="collapse" id="menu_ecommerce_admin">
                  <li class=""><a href="shop_admin_products9ed2.html?lang=en"><span>Products</span></a></li>
                  <li class=""><a href="shop_admin_product9ed2.html?lang=en"><span>Add product</span></a></li>
                </ul>
                <span class="count">2</span> </li>
            </ul>
            <span class="count">5</span> </li>
          <!-- // Shop Submenu Level 1 END --> 
          
          <!-- Menu Regular Items -->
          <li><a href="#" class="glyphicons"><i class="icon-tags"></i><span>FAQ</span></a></li>
          <li><a href="#" class="glyphicons"><i class="icon-calendar"></i><span>Calendar</span></a></li>
          <li><a href="#" class="glyphicons"><i class="icon-lock"></i><span>Login</span></a></li>
          <li><a href="#" class="glyphicons"><i class="icon-user"></i><span>Register</span></a></li>
          <li><a href="#" class="glyphicons"><i class="icon-credit-card"></i><span>Invoice</span></a></li>
          <!-- // Menu Regular Items END --> 
          
          <!-- Maps Submenu Level 1 -->
          <li class="hasSubmenu"> <a data-toggle="collapse" href="#menu_maps" class="glyphicons"><i class="icon-map-marker"></i><span>Maps</span></a>
            <ul class="collapse" id="menu_maps">
              <li class=""><a href="maps_vector9ed2.html?lang=en"><span>Vector maps</span></a></li>
              <li class=""><a href="maps_google9ed2.html?lang=en"><span>Google maps</span></a></li>
            </ul>
            <span class="count">2</span> </li>
          <!-- // Maps Submenu Level 1 END -->
          
        </ul>
        <div class="clearfix"></div>
        <div class="separator bottom"></div>
        <!-- // Regular Size Menu END --> 
        
        
      </div>
      <!-- // Scrollable Menu wrapper with Maximum Height END --> 
      
    </div>
    <!-- // Sidebar Menu END -->
    <!-- Content -->
    <div id="content">
			<!-- Breadcrumb -->
<ul class="breadcrumb">
	<li><a href="#" class="glyphicons"><i class="icon-home"></i> PHP Admin Demo</a></li>
	<li class="divider"></li>
	<li>Moduli</li>
	<li class="divider"></li>
	<li>Form Validator</li>
</ul>
<!-- // Breadcrumb END -->

<h3 class="heading-mosaic">Form Validator</h3>
<div class="innerLR">

	<!-- Form -->
	<form class="form-horizontal" style="margin-bottom: 0;" id="validateSubmitForm" method="get" autocomplete="off">
		
		<!-- Widget -->
		<div class="widget">
		
			<!-- Widget heading -->
			<div class="widget-head">
				<h4 class="heading">Text Box With Defrant Sizes</h4>
			</div>
			<!-- // Widget heading END -->
			
			<div class="widget-body">
			
				<!-- Row -->
				<div class="row-fluid">
				
					<!-- Column -->
					<div class="span6">
					
						<!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span1" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span2" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span3" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
                        <!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span4" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
                        <!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span5" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
                        <!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span6" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
						
					</div>
					<!-- // Column END -->
					
					<!-- Column -->
					<div class="span6">
					
						<!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span12" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span11" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span10" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
                        <!-- Group -->
						<div class="control-group error">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span9" id="" name="" type="text" /><p class="error help-block"><span class="label label-important">Please enter Filed Text</span></p></div>
						</div>
						<!-- // Group END -->
                        <!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span8" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
                        <!-- Group -->
						<div class="control-group">
							<label class="control-label" for="">Input Label Title</label>
							<div class="controls"><input class="span7" id="" name="" type="text" /></div>
						</div>
						<!-- // Group END -->
						
					</div>
					<!-- // Column END -->
					
				</div>
				<!-- // Row END -->
				
				<hr class="separator" />
				
				<!-- Row -->
				<div class="row-fluid">
				
					<!-- Column -->
					<div class="span4 uniformjs">
						<h4>Policy &amp; Newsletter</h4>
						<label class="checkbox" for="agree">
							<input type="checkbox" class="checkbox" id="agree" name="agree" />
							Please agree to our policy
						</label>
						<label class="checkbox" for="newsletter">
							<input type="checkbox" class="checkbox" id="newsletter" name="newsletter" />
							Receive Newsletter
						</label>
					</div>
					<!-- // Column END -->
					
					<!-- Column -->
					<div class="span4 uniformjs">
						<div id="newsletter_topics">
							<h4>Check Box</h4>
							<label for="topic_marketflash">
								<input type="checkbox" id="topic_marketflash" value="marketflash" name="topic" />
								Marketflash
							</label>
							<label for="topic_fuzz">
								<input type="checkbox" id="topic_fuzz" value="fuzz" name="topic" />
								Latest fuzz
							</label>
							<label for="topic_digester">
								<input type="checkbox" id="topic_digester" value="digester" name="topic" />
								Mailing list digester
							</label>
						</div>
					</div>
					<!-- // Column END -->
                    <!-- Column -->
					<div class="span4">
                    	<h4>Grid</h4>
                        
						<div class="row-fluid">
						<select class="selectpicker span3">
							<option>Mustard</option>
							<option>Ketchup</option>
							<option>Relish</option>
						</select> 
						<select class="selectpicker span9">
							<option>Mustard</option>
							<option>Ketchup</option>
							<option>Relish</option>
						</select>
					</div>
                    <div class="row-fluid">
						<select class="selectpicker span4">
							<option>Mustard</option>
							<option>Ketchup</option>
							<option>Relish</option>
						</select> 
						<select class="selectpicker span8">
							<option>Mustard</option>
							<option>Ketchup</option>
							<option>Relish</option>
						</select>
					</div>
                    <div class="row-fluid">
						<select class="selectpicker span6">
							<option>Mustard</option>
							<option>Ketchup</option>
							<option>Relish</option>
						</select> 
						<select class="selectpicker span6">
							<option>Mustard</option>
							<option>Ketchup</option>
							<option>Relish</option>
						</select>
					</div>
                    </div>
                    <!-- // Column END -->
					
				</div>
				<!-- // Row END -->
                
                	<hr class="separator" />
				
				<!-- Row -->
				<div class="row-fluid uniformjs">
				
					<!-- Column -->
					<div class="span4">
						<h4>Policy &amp; Newsletter</h4>
							<label class="radio">
								<input type="radio" class="radio2" name="radio2" value="1" />
								Option 1 - Sexy radio
							</label><br/>
                            <label class="radio">
                                <input type="radio" class="radio2" name="radio2" value="1" checked="checked" />
                                Option 2 - Checked
                            </label><br/>
                            <label class="radio">
                                <input type="radio" class="radio2 disabled" name="radio2" value="1" disabled="disabled" />
                                Option 3 - Disabled radio
                            </label>
					</div>
					<!-- // Column END -->
					
					<!-- Column -->
					<div class="span8">
						<div id="newsletter_topics">
							<h4>Radio</h4>
							<label class="radio">
								<input type="radio" class="radio" name="radio" value="1" />
								Option 1 - Sexy radio
							</label><br/>
                            <label class="radio">
                                <input type="radio" class="radio" name="radio" value="1" checked="checked" />
                                Option 2 - Checked
                            </label><br/>
                            <label class="radio">
                                <input type="radio" class="radio disabled" name="radio" value="1" disabled="disabled" />
                                Option 3 - Disabled radio
                            </label>
						</div>
					</div>
					<!-- // Column END -->
					
				</div>
				<!-- // Row END -->
			
				<hr class="separator" />
                <!-- Row -->
                <div class="row-fluid">
                	<!-- Column -->
                    <div class="span6">
                    	<h4>File Select</h4>
                        <div class="fileupload fileupload-new" data-provides="fileupload">
					  	<div class="input-append">
					    	<div class="uneditable-input span4"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div><span class="btn btn-default btn-file"><span class="fileupload-new">Select file</span><span class="fileupload-exists">Change</span><input type="file" /></span><a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
					  	</div>
					</div>
                    </div>
                    <!-- // Column END -->
                   
                </div>
                <!-- // Row END -->
				<hr class="separator" />
				<!-- Form actions -->
				<div class="form-actions">
					<button type="submit" class="btn btn-icon btn-primary glyphicons"><i class="icon-ok-circle"></i>Save</button>
					<button type="button" class="btn btn-icon btn-default glyphicons"><i class="icon-remove-circle"></i>Clear</button>
				</div>
				<!-- // Form actions END -->
				
			</div>
		</div>
		<!-- // Widget END -->
		
	</form>
	<!-- // Form END -->
	
</div>	
	
				</div>
    <!-- // Content END --> 
    
  </div>
  <div class="clearfix"></div>
  <!-- // Sidebar menu & content wrapper END -->
  
  <div id="footer" class="hidden-print">

    
    <!--  Copyright Line -->
    <div class="copy">&copy; 2012 - 2013 - MosaicPro - All Rights Reserved. <a href="#">PHP Admin Demo v1.0</a></div>
    <!--  End Copyright Line --> 
    
  </div>
  <!-- // Footer END --> 
  
</div>

<!-- ================================ include all js ========================= -->
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.custom.min.js"></script>
<script type="text/javascript" src="js/bootstrap-select.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script src="js/jasny-bootstrap.min.js"></script>
<script type="text/javascript" src="js/modernizr.custom.js"></script>
<script type="text/javascript" src="js/less-1.3.3.min.js"></script>
<script type="text/javascript" src="js/common.js"></script>
<!--<script type="text/javascript" src="js/generel.js"></script>-->
<!-- ================================= end js ================================ -->




	
	<!-- DateTimePicker Plugin -->
	<script src="http://mosaicpro.biz/adminkit/php/theme/scripts/plugins/forms/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>


</body>
</html>