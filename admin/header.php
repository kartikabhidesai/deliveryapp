
		<!--
		===========================================================
		BEGIN PAGE
		===========================================================
		-->
		
			<!-- BEGIN TOP NAV -->
			<div class="top-navbar">
				<div class="top-navbar-inner">
					
					<!-- Begin Logo brand -->
					<div class="logo-brand">
						<!--<a href="dashboard.php"><img src="../assets/img/sentir-logo-primary.png" alt="Sentir logo"></a>-->
						<a href="dashboard.php"><span><?php echo SITE_NAME;?></span></a>
					</div><!-- /.logo-brand -->
					<!-- End Logo brand -->
					
					<div class="top-nav-content">
						
						<!-- Begin button sidebar left toggle -->
						<div class="btn-collapse-sidebar-left">
							<i class="fa fa-bars"></i>
						</div><!-- /.btn-collapse-sidebar-left -->
						<!-- End button sidebar left toggle -->
						
						<!-- Begin button sidebar right toggle -->
						<!--<div class="btn-collapse-sidebar-right">
							<i class="fa fa-bullhorn"></i>
						</div><!-- /.btn-collapse-sidebar-right -->
						<!-- End button sidebar right toggle -->
						
						<!-- Begin button nav toggle -->
						<div class="btn-collapse-nav" data-toggle="collapse" data-target="#main-fixed-nav">
							<i class="fa fa-plus icon-plus"></i>
						</div><!-- /.btn-collapse-sidebar-right -->
						<!-- End button nav toggle -->
						
						
						<!-- Begin user session nav -->
						<ul class="nav-user navbar-right">
							<li class="dropdown">
							  <a href="#fakelink" class="dropdown-toggle" data-toggle="dropdown">
								<img src="../assets/img/avatar/avatar.jpg" class="avatar img-circle" alt="Avatar">
								Hi, <strong><?php echo $_SESSION[SESSION_NAME . "displayname"]; ?></strong>
							  </a>
							  <ul class="dropdown-menu square primary margin-list-rounded with-triangle">
								<li><a href="changepassword.php">Change password</a></li>
								<li><a href="logout.php">Log out</a></li>
							  </ul>
							</li>
						</ul>
						<!-- End user session nav -->
						
						<!-- Begin Collapse menu nav -->
					
						
						<!-- End Collapse menu nav -->
					</div><!-- /.top-nav-content -->
				</div><!-- /.top-navbar-inner -->
			</div><!-- /.top-navbar -->
			<!-- END TOP NAV -->