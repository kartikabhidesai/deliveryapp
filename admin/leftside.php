<?php
$converter = new encryption();

if ($pagename == "dashboard") {
    $dashboardclass = " dashboardactive";
} else {
    $dashboardclass = "";
}

if ($pagename == "cat_list" || $pagename == "cat_add" || $pagename == "cat_edit") {
    $categorycls = " active selected";
    $categorycls = " visible";
} else {
    $categorycls = "";
    $categorycls1 = "";
}

if ($pagename == "product_list" || $pagename == "product_add" || $pagename == "product_edit") {
    $productcls = " active selected";
    $productcls = " visible";
} else {
    $productcls = "";
    $productcls = "";
}

if ($pagename == "cust_list" || $pagename == "add_cust") {
    $vendorCls = " active selected";
    $vendorCls1 = " visible";
} else {
    $vendorCls = "";
    $vendorCls1 = "";
}

if ($pagename == "join_captain_list" || $pagename == "captain_list" || $pagename == "add_captain") {
    $captainCls = " active selected";
    $captainCls1 = " visible";
} else {
    $captainCls = "";
    $captainCls1 = "";
}

if ($pagename == "order_new_list" || $pagename == "order_inprogress_list" || $pagename == "order_completed_list" || $pagename == "order_cancelled_list") {
    $orderCls = " active selected";
    $orderCls1 = " visible";
} else {
    $orderCls = "";
    $orderCls1 = "";
}

if ($pagename == "not_master_list" || $pagename == "not_type_list" || $pagename == "add_not_type" || $pagename == "not_cust_list") {
    $notMasterCls = " active selected";
    $notMasterCls1 = " visible";
} else {
    $notMasterCls = "";
    $notMasterCls1 = "";
}

if ($pagename == "content_list" || $pagename == "add_content") {
    $contentCls = " active selected";
    $contentCls1 = " visible";
} else {
    $contentCls = "";
    $contentCls1 = "";
}

if ($pagename == "package_list" || $pagename == "add_package" || $pagename == "coupon_list" || $pagename == "add_coupon" || $pagename == "commission_list" || $pagename == "fee_setting" || $pagename == "general_settings"
) {
    $masterCls = " active selected";
    $masterCls1 = " visible";
} else {
    $masterCls = "";
    $masterCls1 = "";
}

if ($pagename == "sitesettings" || $pagename == "changepassword") {
    $settingclass = " active selected";
} else {
    $settingclass = "";
}
?>
<!-- Sidebar Menu -->

<!-- BEGIN SIDEBAR LEFT -->
<div class="sidebar-left sidebar-nicescroller">
    <ul class="sidebar-menu">
        <li>
            <a href="dashboard.php">
                <i class="fa fa-dashboard icon-sidebar"></i>
                Dashboard
                <!--<span class="label label-success span-sidebar">UPDATED</span>-->
            </a>
        </li>

        <li class="<?php echo $vendorCls; ?>">
            <a href="#fakelink">
                <i class="fa fa-list-alt icon-sidebar"></i>
                <i class="fa fa-angle-right chevron-icon-sidebar"></i>
                Category 
            </a>
            <ul class="submenu <?php echo $categorycls; ?>">
                <li <?php echo (($pagename == "cat_list") ? ' class="selected"' : ""); ?>><a href="cat_list.php" title="Category list">Category List</a></li>
                <li <?php echo (($pagename == "cat_add") ? ' class="selected"' : ""); ?>><a href="cat_add.php" title="Add Category">Add Category</a></li>

            </ul>
        </li>
        
        <li class="<?php echo $productcls; ?>">
            <a href="#fakelink">
                <i class="fa fa-list-alt icon-sidebar"></i>
                <i class="fa fa-angle-right chevron-icon-sidebar"></i>
                Product
            </a>
            <ul class="submenu <?php echo $productcls; ?>">
                <li <?php echo (($pagename == "product_list") ? ' class="selected"' : ""); ?>><a href="product_list.php" title="Product list">Product List</a></li>
                <li <?php echo (($pagename == "product_add") ? ' class="selected"' : ""); ?>><a href="product_add.php" title="Add Product">Add Product</a></li>

            </ul>
        </li>
        
        <li class="<?php echo $vendorCls; ?>">
            <a href="#fakelink">
                <i class="fa fa-users icon-sidebar"></i>
                <i class="fa fa-angle-right chevron-icon-sidebar"></i>
                Customer
            </a>
            <ul class="submenu <?php echo $vendorCls1; ?>">
                <li <?php echo (($pagename == "cust_list") ? ' class="selected"' : ""); ?>><a href="cust_list.php" title="Customer list">Customer list</a></li>
                <!-- <li <?php echo (($pagename == "add_cust") ? ' class="selected"' : ""); ?>><a href="add_cust.php" title="Add Customer">Add Customer</a></li> -->

            </ul>
        </li>

        <li class="<?php echo $captainCls; ?>">
            <a href="#fakelink">
                <i class="fa fa-users icon-sidebar"></i>
                <i class="fa fa-angle-right chevron-icon-sidebar"></i>
                Captain
            </a>
            <ul class="submenu <?php echo $captainCls1; ?>">
                <li <?php echo (($pagename == "join_captain_list") ? ' class="selected"' : ""); ?>><a href="join_captain_list.php" title="Join Request list">Join Request</a></li>
                <li <?php echo (($pagename == "captain_list") ? ' class="selected"' : ""); ?>><a href="captain_list.php" title="Captain list">Captain list</a></li>
            </ul>
        </li>

        <li class="<?php echo $orderCls; ?>">
            <a href="#fakelink">
                <i class="fa fa-usd icon-sidebar"></i>
                <i class="fa fa-angle-right chevron-icon-sidebar"></i>
                Order
            </a>
            <ul class="submenu <?php echo $orderCls1; ?>">

                <li <?php echo (($pagename == "order_new_list") ? ' class="selected"' : ""); ?>><a href="order_new_list.php" title="Order list"> New Orders</a></li>
                <li <?php echo (($pagename == "order_inprogress_list") ? ' class="selected"' : ""); ?>><a href="order_inprogress_list.php" title="Order list"> InProgress Orders</a></li>
                <li <?php echo (($pagename == "order_completed_list") ? ' class="selected"' : ""); ?>><a href="order_completed_list.php" title="Order list"> Completed Orders</a></li>
                <li <?php echo (($pagename == "order_cancelled_list") ? ' class="selected"' : ""); ?>><a href="order_cancelled_list.php" title="Order list"> Cancelled Orders</a></li>
            </ul>
        </li>

        <li class="<?php echo $notMasterCls; ?>">
            <a href="#fakelink">
                <i class="fa fa-file-text icon-sidebar"></i>
                <i class="fa fa-angle-right chevron-icon-sidebar"></i>
                Notification
            </a>
            <ul class="submenu <?php echo $notMasterCls1; ?>">
                <li <?php echo (($pagename == "not_type_list") ? ' class="selected"' : ""); ?>><a href="not_type_list.php" title="Notification Type">Type</a></li>
                <li <?php echo (($pagename == "add_not_type") ? ' class="selected"' : ""); ?>><a href="add_not_type.php" title="Add Notification Type">Add Type</a></li>
                <li <?php echo (($pagename == "not_master_list" || $pagename == "not_cust_list") ? ' class="selected"' : ""); ?>><a href="not_master_list.php" title="Notifications">Notifications</a></li>

            </ul>
        </li>

        <li class="<?php echo $contentCls; ?>">
            <a href="#fakelink">
                <i class="fa fa-file-text icon-sidebar"></i>
                <i class="fa fa-angle-right chevron-icon-sidebar"></i>
                Contents
            </a>
            <ul class="submenu <?php echo $contentCls1; ?>">
                <li <?php echo (($pagename == "content_list" || $pagename == "add_content") ? ' class="selected"' : ""); ?>><a href="content_list.php" title="Contents">Content list</a></li>
            </ul>
        </li>

        <li class="<?php echo $masterCls; ?>">
            <a href="#fakelink">
                <i class="fa fa-file-text icon-sidebar"></i>
                <i class="fa fa-angle-right chevron-icon-sidebar"></i>
                Masters
            </a>
            <ul class="submenu <?php echo $masterCls1; ?>">
                <li <?php echo (($pagename == "package_list" || $pagename == "add_package") ? ' class="selected"' : ""); ?>><a href="package_list.php" title="Recharge Packages">Recharge Packages</a></li>
                <li <?php echo (($pagename == "coupon_list" || $pagename == "add_coupon") ? ' class="selected"' : ""); ?>><a href="coupon_list.php" title="Coupon Management">Coupon Management</a></li>
                <li <?php echo (($pagename == "fee_setting" || $pagename == "fee_setting") ? ' class="selected"' : ""); ?>><a href="fee_setting.php" title="Settings">Delivery Fees</a></li>
                <li <?php echo (($pagename == "commission_list" || $pagename == "commission_list") ? ' class="selected"' : ""); ?>><a href="commission_list.php" title="Commision Setting">Commision Setting</a></li>
                <li <?php echo (($pagename == "general_settings" || $pagename == "general_settings") ? ' class="selected"' : ""); ?>><a href="general_settings.php" title="Settings">General Settings</a></li>
            </ul>
        </li>

    </ul>
</div><!-- /.sidebar-left -->