<?php
    require_once 'cfs.php';
    require_once 'myfunc.php';
	if (isset($_GET["logout"])) {
		if ($_GET["logout"] == 1) {
			unset($_SESSION);
			header("Location: login.php");
		}
	}
	if (!isset($_SESSION["login"],$_SESSION["admin"])) header("Location: login.php");
	include_once("myfunc.php");
	$_SESSION["cfs_limit"] = 20;
	$_SESSION["cfs_page"] = 0;
	$cfs_page = _Cfs_List();
	$_SESSION["cfs_list"] = json_encode($cfs_page);
	$_SESSION["cfs_total"] = count($cfs_page);
	$_SESSION["cfs_data"] = "";
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $SETTING_NAME_CFS; ?> Confession - Admin</title>
	<meta charset="utf-8">
	<link rel="icon" href="favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<link href="css/metro-icons.min.css" rel="stylesheet">
	<link href="css/metro.min.css" rel="stylesheet">
	<script src="js/jquery.min.js"></script>
	<script src="js/metro.min.js"></script>
	<script src="js/adcfs.js"></script>
	<link href="css/metro-responsive.min.css" rel="stylesheet">
</head>
<body>
	<div class="app-bar fixed-top navy" data-role="appbar">
		<div class="container">
				<a class="app-bar-element" <?php if ($_SESSION["admin"] == "admin") echo 'onclick="_show(1)"'; else echo 'onclick="_show(3)"'; ?> href="#"><img src="img\logo.png" height="40px" width="40px"> <?php echo $SETTING_NAME_CFS; ?> Confession</a>
				<div class="app-bar-element place-right">
				<a  href="?logout=1" class="fg-white"><span class="mif-enter"></span> Logout</a>
				</div>
				<ul class="app-bar-menu no-flexible">
					<?php
					if ($_SESSION["admin"] == "admin") echo '
						<li>
							<a href="#" class="dropdown-toggle">Nhân sự</a>
							<ul class="d-menu" data-role="dropdown">
								<li><a onclick="_show(1)">Danh sách</a></li>
								<li><a onclick="_show(2)">Tạo mới</a></li>
							</ul>
						</li>
						<li>
							<a href="#" class="dropdown-toggle">Confession</a>
							<ul class="d-menu" data-role="dropdown">
								<li><a onclick="_show(3)">Chờ duyệt</a></li>
								<li><a onclick="_show(4)">Đã duyệt</a></li>
								<li><a onclick="_show(5)">Đã từ chối</a></li>
							</ul>
						</li>
						<li><a onclick="_show(6)">Thiết lập</a></li>';

									?>
									<li><a onclick="_show(7)">Cá nhân</a></li>
								</ul>
							</div>
			
	</div>

	<div class="page-content" style="padding-top: 60px">
		<div id="tab1" class="container padding10" style="<?php if ($_SESSION["admin"] == "admin") echo "display:block"; else echo "display:none"; ?>;opacity: 1; transform: scale(1); transition: 0.5s;">
			<h1>Quản lí nhân sự</h1>
			<div id="nhansu_list">
				<table class="table hovered striped border bordered" id="main_table_demo">
					<thead>
						<tr>
							<th>Account</th>
							<th>Họ tên</th>
							<th>Chức vụ</th>
							<th>Nickname</th>
							<th>Hành động</th>
						</tr>
					</thead>
					<tbody>
						<?php
							if ($_SESSION["admin"] == "admin") echo $member_page->_PrintHtml();
						?>
					</tbody>
				</table>

				<div class="align-center">
					<div data-role="group" data-group-type="one-state" id="nhansu_list_page">
				    <?php
				    	if ($_SESSION["admin"] == "admin") echo $member_page->_PrintPage();
				    ?>
				    </div>
				</div>
			</div>
		</div>
		<div id="tab2" class="container padding10" style="display:none;opacity: 1; transform: scale(1); transition: 0.5s;">
			<h1>Tạo mới nhân sự</h1>
			<div class="cell padding30">
				<label>ID Facebook người dùng</label>
				<div class="input-control text full-size">
					<input id="reg_acc" type="text">
				</div>
				<label>Tên người dùng</label>
				<div class="input-control text full-size">
					<input id="reg_name" type="text">
				</div>
				<label>Nickname</label>
				<div class="input-control text full-size">
					<input id="reg_nick" type="text">
				</div>
				<label>Loại tài khoản: </label>
				<form id="regtype">
					<label class="input-control radio small-check">
					    <input type="radio" name="n3" value="mod" checked>
					    <span class="check"></span>
					    <span class="caption">Tài khoản Mod</span>
					</label>
					<label class="input-control radio small-check">
					    <input type="radio" name="n3" value="admin">
					    <span class="check"></span>
					    <span class="caption">Tài khoản Admin</span>
					</label>
				</form>
				<p class="align-right">
					<button onclick="_show(1)" class="button danger">Hủy bỏ</button>
					<button onclick="_reg()" class="button success">Tạo mới</button>
				</p>
			</div>
		</div>
		<div id="tab3" class="container padding10" style="<?php if ($_SESSION["admin"] != "admin") echo "display:block"; else echo "display:none"; ?>;opacity: 1; transform: scale(1); transition: 0.5s;">
			<h1>Quản lí confession</h1>
			<div id="tab3_list">
				<?php
					echo _CfsPage_PrintHtml();
				?>
			</div>
			<p id="tab3_page" class="align-center">
				<?php
					echo _CfsPage_PrintPage();
				?>
			</p>
	    </div>
	    <div id="tab6" class="container padding10" style="display:none;opacity: 1; transform: scale(1); transition: 0.5s;">
			<h1>Thiết lập Cfs</h1>
			<div class="cell padding30">
				<div class="grid">
				    <div class="row cells3">
				        <div class="cell colspan2">Tiếp nhận Confession</div>
				        <div class="cell">
							<label class="switch-original">
							    <input id="setting_cfs_cfs" type="checkbox">
							    <span class="check"></span>
							</label>
				        </div>
				    </div>
				</div>
				<div class="grid">
				    <div class="row cells3">
				        <div class="cell colspan2">Cho phép Mod đăng nhập hệ thống Confession</div>
				        <div class="cell">
							<label class="switch-original">
							    <input id="setting_cfs_login" type="checkbox">
							    <span class="check"></span>
							</label>
				        </div>
				    </div>
				</div>
				<div class="grid">
				    <div class="row cells3">
				        <div class="cell colspan2">Đánh số Confession</div>
				        <div class="cell">
							<div class="input-control text full-size">
							    <input id="setting_cfs_number" type="text">
							</div>
				        </div>
				    </div>
				</div>
				<div class="grid">
				    <div class="row cells3">
				        <div class="cell colspan2">Tiền tố Confession</div>
				        <div class="cell">
							<div class="input-control text full-size">
							    <input id="setting_cfs_token" type="text">
							</div>
				        </div>
				    </div>
				</div>
				<div class="grid">
				    <div class="row cells3">
				        <div class="cell colspan2">ID Confession Page</div>
				        <div class="cell">
							<div class="input-control text full-size">
							    <input id="setting_cfs_idpage" type="text">
							</div>
				        </div>
				    </div>
				</div>
				<p class="align-right">
					<button onclick="_cfs_setting_save()" class="button success">Lưu lại</button>
				</p>
			</div>
	    </div>
	    <div id="wait" class="container padding10" style="display:none;opacity: 1; transform: scale(1); transition: 0.5s;">
	    	<div class="cell padding30 align-center">
	    			<h3>Vui lòng đợi tiến trình hoàn tất</h3>
	    			<br>
	    			<div style="margin: auto" data-role="preloader" data-type="cycle" data-style="color"></div>
	    	</div>
	    </div>
	    <div id="tab7" class="container padding10" style="display:none;opacity: 1; transform: scale(1); transition: 0.5s;">
	    	<h1>Thông tin cá nhân</h1>
			<div class="cell padding30">
				<label>ID Facebook</label>
				<div class="input-control text full-size">
					<input type="text" value=<?php echo '"'.$_SESSION["acc"].'"'; ?> disabled>
				</div>
				<label>Tên người dùng</label>
				<div class="input-control text full-size">
					<input type="text" value=<?php echo '"'.$_SESSION["name"].'"'; ?> disabled>
				</div>
				<label>Nickname</label>
				<div class="input-control text full-size">
					<input type="text" value=<?php echo '"'.$_SESSION["user"].'"'; ?> disabled>
				</div>
			</div>
	    	<h1 style="display:none;">Đổi mật khẩu</h1>
			<div style="display:none;" class="cell padding30">
				<label>Mật khẩu cũ</label>
				<div class="input-control text full-size">
					<input id="d_pass1" type="password">
				</div>
				<label>Mật khẩu mới</label>
				<div class="input-control text full-size">
					<input id="d_pass2" type="password">
				</div>
				<label>Xác nhận mật khẩu mới</label>
				<div class="input-control text full-size">
					<input id="d_pass3" type="password">
				</div>
				<button class="button success" onclick="_pass()">Xác nhận đổi</button>
			</div>
	    </div>
	    <div id="tab_cfs" class="container padding10" style="display:none;opacity: 1; transform: scale(1); transition: 0.5s;">
			<div class="container">
			    <h1>Duyệt Cfs</h1>
			    <div id="duyet_cfs_txt">
			    </div>
			    <p class="align-right">
					<button class="button danger" onclick="_show(3)">Hủy bỏ</button>
			    	<button id="cfs_button_post" class="button success" onclick="_cfs_post()">Đăng Confession</button>
			    </p>
		    </div>
	    </div>
	</div>
	<!-- DIAGLOG -->
	<div id="required_dialog" data-role="dialog" class="padding20" data-overlay="true" data-overlay-click-close="true" data-close-button="true" data-type="alert" data-windows-style="true">
		<div class="container">
			<h1>Thông báo</h1>
			<p id="txt_dialog">
			</p>
		</div>
	</div>
	<div id="required_dialog2" data-type="info" data-role="dialog" class="padding20" data-overlay="true" data-overlay-click-close="true" data-close-button="true" data-windows-style="true">
		<div class="container">
			<h1>Thông báo</h1>
			<p id="txt_dialog2">
			</p>
		</div>
	</div>
	<div id="coded" class="align-center padding20">
		<a href="http://opdo.vn" target="_bank"><b>Coded by Vinh Pham</b></a>
	</div>
	<!-- DIAGLOG -->

</body>
