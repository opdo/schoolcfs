<?php
    require_once 'cfs.php';

	$member_page = _Member_List();
	
	class Member_Page
	{
		protected $_member = array();
	    protected $_config = array(
	        'current_page'  => "", // Trang hiện tại
	        'total_record'  => "", // Tổng số record
	        'total_page'    => "", // Tổng số trang
	        'limit'         => "",// limit
	    );
	    function _ChangePage($page = 1) {
	    	if ($page > $this->_config["total_page"]) $page = $this->_config["total_page"];
	    	else if ($page < 1) $page = 1;
	    	$this->_config["current_page"] = $page;
	    }
	    function init($config = array()) {
			foreach ($config as $key => $val){
	            if (isset($this->_config[$key])){
	                $this->_config[$key] = $val;
	            }
	        }
	    }
	    function init_member($member = array()) {
	    	$this->_member = $member;
	    }
	    function _PrintHtml() {
	    	$return = "";
	    	$start = ($this->_config["current_page"]-1)*$this->_config["limit"]+1;
	    	$end = $start+$this->_config["limit"]-1;
	    	if ($end>$this->_config["total_record"]) $end = $this->_config["total_record"];
	    	for ($i= $start; $i <= $end; $i++) $return = $return . $this->_member[$i]->_PrintHtml();
	    	return $return;
	    }
	    function _PrintPage() {
	    	$return = "";
	    	$page = $this->_config["total_page"];
	    	$cpage = $this->_config["current_page"];
	    	$start = $cpage - 1;
	    	if ($start < 1) $start = 1;
	    	$end = $start+2;
	    	if ($end > $page) {
	    		$end = $page;
	    		$start = $end-2;
	    	}
	    	if ($start < 1) $start = 1;
	    	for ($i = $start; $i <= $end; $i++) {
	    		if ((int)$cpage == $i) $return = $return ."\n". '<button onclick="_mem_set_page('.$i.')" class="button active">'.$i.'</button>';
	    		else $return = $return ."\n". '<button onclick="_mem_set_page(\''.$i.'\')" class="button">'.$i.'</button>';
	    	}
	    	return $return;
	    }
	}
	class Member
	{
		public $account = "";
		public $name = "";
		public $type = "";
		public $nickname = "";
		public $block = false;
		function _PrintHtml()
		{
			$block = "Khóa";
			if ($this->block == "1") $block = "Mở khóa";
			return '<tr name="acc_'.$this->account.'">
						<td>'.$this->account.'</td>
						<td>'.$this->name.'</td>
						<td>'.$this->type.'</td>
						<td>'.$this->nickname.'</td>
						<td>
							<div>
							    <button onclick="_block(\''.$this->account.'\')" class="button warning"><span class="mif-security"></span> <a id="txt_lock_'.$this->account.'">'.$block.'</a></button>
							    <button onclick="_del(\''.$this->account.'\')" class="button danger"><span class="mif-blocked"></span> Xóa</button>
							</div>
						</td>
					</tr>';
		}
	}
	function _Check_Pass($pass) {
		$conn = $GLOBALS['conn'];
		$query = mysqli_query($conn,"SELECT * FROM dblogin WHERE acc='".$_SESSION["acc"] ."' and pass='".md5($pass)."'");
		if (!$query || mysqli_num_rows($query) == 0) return false;
		return true;
	}
	function _Login($acc, $pass = "hutech") {
		$conn = $GLOBALS['conn'];
		$return = array('success' => 0, "msg" => "ID ".$acc.' không tồn tại');
		$query = mysqli_query($conn,"SELECT * FROM dblogin WHERE acc='".$acc ."'");
		if (!$query || mysqli_num_rows($query) == 0) return $return;
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		$return["msg"] = "Tài khoản bạn đã bị khóa ";
		if ($row["block"] == 1) return $return;

		if (_Cfs_Get_Setting("cfs_login") == "0") {
			$return["msg"] = "Hệ thống đăng nhập đang tạm khóa";
			if ($row["type"] != "admin") return $return;
		}
		$_SESSION["login"] = 1;
		$_SESSION["acc"] = $acc;
		$_SESSION["user"] = $row["nick"];
		$_SESSION["admin"] = $row["type"];
		$_SESSION["name"] = $row["name"];
		$return["msg"] = "Đăng nhập thành công";
		$return["success"] = 1;
		return $return;
	}

	function _Cfs_Save_Setting($setting,$data) {
		$conn = $GLOBALS['conn'];
		mysqli_query($conn,"UPDATE setting SET data='".$data."' WHERE info='".$setting ."'");
	}
	function _Cfs_Get_Setting($setting) {
		$conn = $GLOBALS['conn'];
		$query = mysqli_query($conn,"SELECT * FROM setting WHERE info='".$setting ."'");
		if (!$query || mysqli_num_rows($query) == 0) return 0;
		$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
		return $row["data"];
	}
	function _Cfs_PrintHtml($cfs) {
		$msg = $cfs["cfs_msg"];
		if ($cfs["cfs_msg_edit"]) $msg = $cfs["cfs_msg_edit"];
		$status = "";
		$class = "place-blue";
		$button = '
						<button cfs="'.$cfs["cfs_id"].'" class="button mini-button primary duyet"><span class="mif-checkmark"></span> Duyệt</button>
						<button cfs="'.$cfs["cfs_id"].'" class="button mini-button danger tuchoi"><span class="mif-cross"></span> Từ chối</button>
		';
		if (($cfs["cfs_status"]) == "post") {
			$status = " - Duyệt bởi <b>".$cfs["cfs_admin"]."</b> lúc ".$cfs["cfs_time_edit"];
			$button = '
						<button cfs="'.$cfs["cfs_id"].'" class="button mini-button danger xoa"><span class="mif-cross"></span> Xóa hẳn</button>
		';
		}
		elseif (($cfs["cfs_status"]) == "denied") {
			$class = "place-gray";
			$status = " - Từ chối bởi <b>".$cfs["cfs_admin"]."</b> lúc ".$cfs["cfs_time_edit"];
			$button = '
						<button cfs="'.$cfs["cfs_id"].'" class="button mini-button primary phuchoi"><span class="mif-checkmark"></span> Phục hồi</button>
						<button cfs="'.$cfs["cfs_id"].'" class="button mini-button danger xoa"><span class="mif-cross"></span> Xóa hẳn</button>
		';
		}
		return '
		<div class="grid" name="cfs_hover" me="'.$cfs["cfs_id"].'">
			<div class="row cells5">
				<div class="cell colspan4">
					<blockquote class="'.$class.'">
						<p>
							<label class="input-control checkbox">
								<input cfs="'.$cfs["cfs_id"].'" class="cfs_checkbox" type="checkbox">
								<span class="check"></span>
							</label>
							'.$msg.'
						</p>
						<small>Ngày gửi '.$cfs["cfs_time"].$status.'</small>
					</blockquote>
				</div>
				<div class="cell cfs_button" style="display:none;"> 
					<p class="v-align-middle"> 
						'.$button.'
					</p>
				</div>
			</div>
		</div>
		';
	}

	function _CfsPage_PrintPage() {
		if ($_SESSION["cfs_page"] < $_SESSION["cfs_total"]) return '<button id="load_cfs" onclick="_cfs_loadpage()" class="button loading-pulse">Tải thêm confession...</button>';
		return "Không còn thông tin để hiển thị";
	}
	function _CfsPage_PrintHtml() {
		$return = "";
		$start = $_SESSION["cfs_page"];
		$end = $start+$_SESSION["cfs_limit"];
		if ($end>$_SESSION["cfs_total"]) $end = $_SESSION["cfs_total"];
		$array_temp = json_decode($_SESSION["cfs_list"],true);
		$array_slice = array_slice($array_temp,$start,$_SESSION["cfs_limit"]);
		$_SESSION["cfs_page"] = $end+1;
		foreach ($array_slice as $key => $value) $return = $return . _Cfs_PrintHtml($value);
		return $return;
	}
	function _Cfs_List($type = '') {
		$conn = $GLOBALS['conn'];
		$_cfs_Temp = array();
		$query = mysqli_query($conn,"SELECT * FROM cfs WHERE status='".$type ."' ORDER BY time_edit DESC");
		if ($query && mysqli_num_rows($query) > 0) {
			while($row = mysqli_fetch_array($query, MYSQL_ASSOC)) {
				$_cfs_Temp[$row["id"]]['cfs_id'] = $row["id"];
				$_cfs_Temp[$row["id"]]['cfs_time'] = $row["time_send"];
				$_cfs_Temp[$row["id"]]['cfs_time_edit'] = $row["time_edit"];
				$_cfs_Temp[$row["id"]]['cfs_status'] = $row["status"];
				$_cfs_Temp[$row["id"]]['cfs_msg'] = $row["msg"];
				$_cfs_Temp[$row["id"]]['cfs_msg_edit'] = $row["msg_edit"];
				$_cfs_Temp[$row["id"]]['cfs_admin'] = $row["admin"];
			}
		}
	    return $_cfs_Temp;
	}
	function _Member_List() {
		$conn = $GLOBALS['conn'];
		$_member = array();
		$member_page = new Member_Page();
		$query = mysqli_query($conn,"SELECT * FROM dblogin");
		if ($query && mysqli_num_rows($query) > 0) {
			$i = 1;
			while($row = mysqli_fetch_array($query, MYSQL_ASSOC)) {
				$_member[$i] = new Member();
				$_member[$i]->account = $row["acc"];
				$_member[$i]->name = $row["name"];
				$_member[$i]->type = $row["type"];
				$_member[$i]->nickname = $row["nick"];
				if ($row["block"] == "1") $_member[$i]->block = true;
				$i++;
			}
		}
		$_config = array(
	        'current_page'  => 1, // Trang hiện tại
	        'total_record'  => count($_member), // Tổng số record
	        'total_page'    => ceil(count($_member)/5), // Tổng số trang
	        'limit'         => 5,// limit
	    );
	    $member_page->init($_config);
	    $member_page->init_member($_member);
	    return $member_page;
	}
	function _Admin_Create_Account($acc,$name,$nick,$type) {
		$pass = md5("hutech");
		$conn = $GLOBALS['conn'];
		mysqli_query($conn,"INSERT INTO dblogin (acc,pass,name,nick,type) VALUES ('".$acc."','".$pass."','".$name."','".$nick."','".$type."')");
	}
	function _Cfs_GetMsg($id) {
		if (cfs_check_exist($id)) {
			$conn = $GLOBALS['conn'];
			$query = mysqli_query($conn,"SELECT * FROM cfs WHERE id='". $id  ."'");
			$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
			return $row["msg"];
		}
	}
	function _SendConfess($msg) {
		$conn = $GLOBALS['conn'];
		$id = _GetNewIDAds();
		mysqli_query($conn,"INSERT INTO cfs (id,msg) VALUES ('".$id."','".addslashes (urldecode($msg))."')");
	}
	function _GetNewIDAds() {
		$random = rand(10,999);
		$rand1 = substr(md5(microtime()),rand(0,26),4);
		$rand2 = substr(md5(microtime()),rand(0,26),4);
		$key = $rand1.$random.$rand2;
		$conn = $GLOBALS['conn'];
		$query = mysqli_query($conn,"SELECT * FROM cfs WHERE id='". $key  ."'");
		if (!$query || mysqli_num_rows($query) == 0) return $key;
		return _GetNewIDAds();
	}
	function cfs_load($data) {
		$return = array('success' => 0);
		$cfs_page = _Cfs_List($data);
		$cfs_me = json_decode($_SESSION["cfs_list"],true);
		if ($cfs_page!=$cfs_me) {

			$return["success"] = 1;
			$return["xoa"] = '';
			$return["them"] = '';
			$return["doi"] = '';
			$return["html"] = '';
			$i = 0;
			foreach ($cfs_me as $key => $value) {
				if (isset($cfs_page[$key])) {
					if ($cfs_page[$key] != $cfs_me[$key]) $return["doi"] = $return["doi"] . $key .',';
				} else {
					$return["xoa"] = $return["xoa"] . $key .',';
					$_SESSION["cfs_total"] -= 1;
					unset($cfs_me[$key]);
					if ($i <= $_SESSION["cfs_page"]) $_SESSION["cfs_page"] -= 1; 
				}
				$i += 1;
			}
			foreach ($cfs_page as $key => $value) {
				if (!isset($cfs_me[$key])) {
					$_SESSION["cfs_total"] += 1;
					$_SESSION["cfs_page"] += 1;
					$return["them"] = $return["them"] . $key .',';
					$return["html"] = $return["html"] . _Cfs_PrintHtml($value);
					$cfs_me = array_merge(array($key => $value),$cfs_me);
				}
			}
			$_SESSION["cfs_list"] = json_encode($cfs_me);
			$return["xoa"] = rtrim($return["xoa"],',');
			$return["them"] = rtrim($return["them"],',');
			$return["doi"] = rtrim($return["doi"],',');
			};
		return $return;
	}
	function cfs_check_exist($id) {
		$conn = $GLOBALS['conn'];
		$query = mysqli_query($conn,"SELECT * FROM cfs WHERE id='". $id  ."'");
		if (!$query || mysqli_num_rows($query) == 0) return false;
		return true;
	}
	function cfs_post($id = array(), $txt = array(),$cmt = '',$header,$type_cfs) {
		$return = array("success" => 0);
		if (!isset($_SESSION["cfs_img_logic"])) $_SESSION["cfs_img_logic"] = 0;
		if ($_SESSION["cfs_img_logic"] == 1 && empty($_SESSION["cfs_img"]))  $return["msg"] = "Upload hình ảnh thất bại";
		else {
			$conn = $GLOBALS['conn'];
			if (empty($_SESSION["cfs_img"])) $post = Page_PostCfs($txt,$cmt,$header,$type_cfs);
			else  $post = Page_PostCfs($txt,$cmt,$header,$type_cfs, $SETTING_DOMAIN.$_SESSION["cfs_img"]);
			if (!empty($_SESSION["cfs_img"])) {
			if (file_exists($_SESSION["cfs_img"])) {
				unlink($_SESSION["cfs_img"]);
			}
			}
			if ($post["success"] == 0) {
				$return["msg"] = $post["msg"];
			} else {
				$return["success"] = 1;
				$return["msg"] = "Đã post ".count($id)." confession thành công!";
				for ($i=0; $i < count($id); $i++) {
					if (cfs_check_exist($id[$i])) {
						$_SESSION["cfs_page"] -= 1;
						$_SESSION["cfs_total"] -= 1;
						cfs_ss_del($id[$i]);
						$txt[$i] = urldecode($txt[$i]);
						mysqli_query($conn,"UPDATE cfs SET msg_edit='".addslashes ($txt[$i])."',status='post',admin='".$_SESSION["user"]."',time_edit='".date("Y-m-d H:i:s")."' WHERE id='". $id[$i]  ."'");
					}
				}
			}
		}
		unset($_SESSION["cfs_img_logic"]);
		unset($_SESSION["cfs_img"]);
		return $return;
	}

	function cfs_denied($id) {
		$conn = $GLOBALS['conn'];
		if (cfs_check_exist($id)) {
			mysqli_query($conn,"UPDATE cfs SET status='denied',admin='".$_SESSION["user"]."',time_edit='".date("Y-m-d H:i:s")."' WHERE id='". $id  ."'");
			$_SESSION["cfs_page"] -= 1;
			$_SESSION["cfs_total"] -= 1;
			
			cfs_ss_del($id);
		}
	}

	function cfs_restore($id) {
		$conn = $GLOBALS['conn'];
		if (cfs_check_exist($id)) {
			mysqli_query($conn,"UPDATE cfs SET status='',admin='".$_SESSION["user"]."',time_edit='".date("Y-m-d H:i:s")."' WHERE id='". $id  ."'");
			$_SESSION["cfs_page"] -= 1;
			$_SESSION["cfs_total"] -= 1;
			cfs_ss_del($id);
		}
	}
	function cfs_del($id) {
		$conn = $GLOBALS['conn'];
		if (cfs_check_exist($id)) {
			mysqli_query($conn,"DELETE FROM cfs WHERE id='". $id  ."'");
			$_SESSION["cfs_page"] -= 1;
			$_SESSION["cfs_total"] -= 1;
			cfs_ss_del($id);
		}
	}
	function cfs_ss_del($id) {
		$list_temp = json_decode($_SESSION["cfs_list"], true);
		unset($list_temp[$id]);
		$_SESSION["cfs_list"] = json_encode($list_temp);
	}
	function create_random() {
		$md5_hash = md5(rand(0,999));
		return substr($md5_hash, 15, 5);
	}
	function create_image($security_code)
	{
		$width = 100;
		$height = 30;
		$image = imagecreate($width, $height);
		$white = imagecolorallocate($image, 255,255,255);
		$black = imagecolorallocate($image, 32,134,191);
		ImageFill($image, 0, 0, $black);
		imagestring($image, 5, 30, 6, $security_code, $white);
		header("Content-Type: image/jpeg");
		ImageJpeg($image);
		ImageDestroy($image);
	}
	function Page_PostCfs($cfs = array(),$cmt = '',$header,$type_cfs,$pic = '') {
		$fb = $GLOBALS['fb'];
		$return = array('success' => 0);
		$get_token = Page_AccessToken();
		if ($get_token["success"] == 0) {
			$return["msg"] = $get_token["msg"];
			return $return;
		}
		$number = "";
		$prefix = "";
		if ($type_cfs == 1) {
			$number = _Cfs_Get_Setting("cfs_number");
			$prefix = _Cfs_Get_Setting("cfs_prefix");
		}
		if ($type_cfs == 2) $number = 1;

		
		$token = $get_token["token"];
		$cfs_txt = $header;
		foreach ($cfs as $key => $value) {
			$value = trim(urldecode($value));
			if ($cfs_txt == "") $cfs_txt = $prefix.$number."\n". $value ."\n";
			else $cfs_txt = $cfs_txt."\n".$prefix.$number."\n". $value ."\n";
			if ($type_cfs != 3) $number+=1;
		}
		$cfs_txt = $cfs_txt."\n".'----------------------------------------'."\n".$cmt."\n".$_SESSION["user"];
		if ($type_cfs == 1) _Cfs_Save_Setting("cfs_number",$number);
		if ($pic == '') $post = Page_Cfs_Txt($cfs_txt,$token);
		else $post = Page_Cfs_Photo($cfs_txt,$pic,$token);
		if ($post) {
			$return["success"] = 1;
			return $return;
		}
		$return["msg"] = "Có lỗi xảy ra khi post confession";
		return $return;
	}
	function Page_Cfs_Txt($cfs,$token) {
		$fb = $GLOBALS['fb'];
		$linkData = [
		  'message' => $cfs
		  ];

		try {
		  // Returns a `Facebook\FacebookResponse` object
		  $response = $fb->post('/me/feed', $linkData,$token);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  return false;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  return false;
		}
		return true;
	}
	function Page_Cfs_Photo($cfs,$pic,$token) {
		$fb = $GLOBALS['fb'];
		$fb_file = $fb->fileToUpload($pic);
		$data = [
		  'message' => $cfs,
		  'source' => $fb_file
		];

		try {
		  $response = $fb->post('/me/photos', $data,$token);
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			$fb_file->close();
			return false;
		}
		$fb_file->close();
		return true;
	}
	function Page_AccessToken() {
		$fb = $GLOBALS['fb'];
		$return = array('success' => 0);
		$page_id = _Cfs_Get_Setting("cfs_page");
		try {
		  $response = $fb->get('/'.$page_id.'?fields=access_token',$_SESSION['facebook_access_token']);
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  $return["msg"] = "Có lỗi xảy ra khi dùng Facebook SDK";
		  return $return;
		}
		$array = $response->getDecodedBody();
		if (!isset($array["access_token"])) {
			$return["msg"] = "Không lấy được access token";
			return $return;
		}
		$return["success"] = 1;
		$return["token"] = $array["access_token"];
		return $return;
	}
?>