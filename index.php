<?php
require_once 'cfs.php';
require_once 'myfunc.php';
$captcha = create_random();
$_SESSION["capcha_code"] = $captcha;
if (isset($_GET["captcha"])) {
	if (isset($_SESSION["capcha_code"])) create_image($_SESSION["capcha_code"]);
	else create_image("ERROR!");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo $SETTING_NAME_CFS; ?> Confession</title>
	<meta charset="utf-8">
	<link rel="icon" href="favicon.ico">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="css/metro-icons.min.css" rel="stylesheet">
	<link href="css/metro.min.css" rel="stylesheet">
	<script src="js/jquery.min.js"></script>
	<script src="js/metro.min.js"></script>
	<script src="js/cfs.js"></script>
	<link href="css/metro-responsive.min.css" rel="stylesheet">
	<style type="text/css">
		.tacenter {
			margin-left: 1rem;
			margin-right: 1rem;
		}
	</style>
</head>
<body class="bg-grayed">
	<a href="<?php echo $SETTING_LINK_CFS; ?>"><p class="align-center"><img src="img\logo.png" height="150px" width="150px"></p></a>
	<div class="grid" style="opacity: 1; transform: scale(1); transition: 0.5s;">
		<div class="container">
			<div class="panel">
				<div class="heading align-center">
					<span class="title"><?php echo $SETTING_NAME_CFS; ?> Confession</span>
				</div>
				<div id="slider_cfs" class="carousel bg-cfs" data-period="9000" data-role="carousel" data-markers="false" data-height="200" data-control-next="<span class='mif-chevron-right'></span>" data-control-prev="<span class='mif-chevron-left'></span>" style="width: 100%; height: 150px; display: block;">
					<div class="slide padding40 text-shadow">
						<h2><?php echo $SETTING_NAME_CFS; ?> Confession</h2>
						<p>
							Trong cuộc sống ai cũng gặp nhiều niềm vui, đôi khi cũng có chút buồn. Có thể bạn đã có người yêu hoặc đơn giản là đang tận hưởng cuộc sống độc thân hiện tại. Bạn vô tình bắt gặp 1 câu chuyện nào đó trong cuộc sống của mình hay chính mình là nhân vật trong câu chuyện đó.<br>
							Đừng mãi giữ trong lòng! Hãy chia sẻ với chúng tôi và mọi người.
						</p>
					</div>
					<div class="slide" style="display: block;" data-period="5000" ><img width="100%" src="img/cfs-logo.jpg"></div>
					<div class="slide padding40 text-shadow">
						<h2>Hãy là Cfs-er lịch sự</h2>
						<p>
							Confession để giúp bạn nói những điều thầm kín mà bạn không dám chia sẻ trực tiếp, vì vui lòng không dùng confession để lăng mạ, sỉ nhục, đả kích người khác, không viết confession lúc bực bội, hãy dùng những ngôn từ lịch sự khi viết confession.<br>
							Confession cũng không phải là nơi để đề cập đến các vấn đề nhạy cảm điển hình như chính trị.
						</p>
					</div>
					<div class="slide" style="display: block;" data-period="5000" ><img width="100%" src="img/cfs-img.jpg"></div>
					<span class="carousel-switch-next"><span class="mif-chevron-right"></span></span><span class="carousel-switch-prev"><span class="mif-chevron-left"></span></span>
				</div>
				<div class="content">
					<div id="post_cfs" style="display: block;">
						<div class="padding10">
							<div class="input-control textarea tacenter required">
								<textarea rows="4" cols="10000"  placeholder="Hãy chia sẻ cho mọi người confession của bạn tại đây..."  id="cfs_input"></textarea>
							</div>
							<div class="padding10 align-center">
								<div class="input-control modern text" data-role="input">
									<input type="text" id="captcha_input">
									<span class="label">Captcha</span>
									<span class="informer">Vui lòng nhập mã xác nhận kế bên</span>
									<span class="placeholder" style="display: block;">Nhập mã xác nhận</span>
								</div>
								<a onclick="refeshCaptcha()">
									<img id="img_captcha" src="?captcha=1">
									<span class="mif-loop2 mif-ani-pulse"></span>
								</a>
							</div>
							<p class="align-center">
								<button class="command-button icon-left primary text-shadow" onclick="sendCfs()">
									<span class="icon mif-heart mif-ani-heartbeat mif-ani-fast"></span>
									Gửi Confession
									<small>Sẽ không ai biết là bạn trừ khi bạn nói ra</small>
								</button>
							</p>
						</div>
					</div>
					<div id="done_cfs" style="display: none;">
						<div class="padding30 align-center text-shadow">
							<h3>Xin cảm ơn! <span class="mif-thumbs-up mif-ani-bounce mif-ani-fast"></span></h3>
							Confession của bạn đã được gửi<br>
							Chúng mình sẽ sớm duyệt và đăng confession của bạn trên fanpage <?php echo $SETTING_NAME_CFS; ?> Confession <span class="mif-heart mif-ani-heartbeat mif-ani-fast"></span>
							<p class="align-center">
								<a class="command-button icon-left primary text-shadow" href="<?php echo $SETTING_LINK_CFS; ?>">
									<span class="icon mif-earth mif-ani-ripple mif-ani-fast"></span>
									Vào <?php echo $SETTING_NAME_CFS; ?> Confession
									<small>Ghé thăm và đọc <?php echo $SETTING_NAME_CFS; ?> Confession</small>
								</a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="coded" class="align-center padding20">
		<a href="http://opdo.vn" target="_bank"><b>Coded by Vinh Pham</b></a>
	</div>
	<!-- DIAGLOG -->
	<div id="required_dialog" data-type="alert" data-role="dialog" class="padding20" data-overlay="true" data-overlay-click-close="true" data-close-button="true" data-windows-style="true">
		<div class="container">
			<h1><span class="mif-warning mif-ani-flash mif-ani-fast"></span> Lỗi!</h1>
			<p id="txt_dialog">
				Vui lòng nhập đủ thông tin bên trên để gửi confession ^^
			</p>
		</div>
	</div>

	<!-- DIAGLOG -->
</body>
</html>