<?php
    require_once 'cfs.php';
    require_once 'myfunc.php';
	if (isset($_POST["cmd"])) {
		$cmd = $_POST["cmd"];
		if ($cmd == "refeshCaptcha") {
			$_SESSION["capcha_code"] = create_random();
			die();
		}
		if ($cmd == "sendCfs" && isset($_POST["capt"],$_POST["cfs"])) {
			$_POST["cfs"] = urldecode($_POST["cfs"]);
			$return = array('success' => 0);
			if (isset($_SESSION["capcha_code"])) {
				if ($_POST["capt"] === $_SESSION["capcha_code"]) {
					if (strlen($_POST["cfs"]) > 30) {
						$word = explode(" ", $_POST["cfs"]);
						if (count($word) > 10) {
							if (_Cfs_Get_Setting("cfs_cfs") == 1) {
								$cam  = array('đụ', 'địt', 'đỉ', 'đĩ', 'lồn', 'cặc', 'nứng', 'cứt', 'fuck', 'chịch', 'di me', 'du ma','con cac');
								$hasString = false;
								foreach($cam as $keyword) {
								    if (strpos($_POST["cfs"], $keyword) !== false) {
								        $hasString = true;
								        break; // stops searching the rest of the keywords if one was found
								    }
								}
								if (!$hasString) {
									_SendConfess($_POST["cfs"]);
									unset($_SESSION["capcha_code"]);
									$return["success"] = 1;
									$return["msg"] = "";
								} else $return["msg"] = "Không chấp nhận các từ ngữ không đúng chuẩn mực đạo đức";
							} else $return["msg"] = "Hệ thống Confession tạm ngưng hoạt động";
						} else $return["msg"] = "Confession của bạn quá ngắn, chúng tôi chỉ chấp nhận confession có 10 từ trở lên";
					} else $return["msg"] = "Confession của bạn quá ngắn, chúng tôi chỉ chấp nhận confession có 30 ký tự trở lên";
				} else $return["msg"] = "Mã xác nhận không chính xác, vui lòng nhập lại";
				die(json_encode($return));
			} else $return["msg"] = "Có lỗi xảy ra, vui lòng làm mới làm trang";
		}
	}
	if (isset($_POST["admin"])) {
		if (!isset($_SESSION["login"],$_SESSION["admin"])) die("Error! You didnt login yet");
		$cmd = $_POST["admin"];
		if ($cmd == "upfile") {
			unset($_SESSION["cfs_img"]);
			$_SESSION["cfs_img_logic"] = 1;
			if (isset($_FILES["file"])) {
				$return = array('success' => 0);
				if ((($_FILES["file"]["type"] == "image/png") || ($_FILES["file"]["type"] == "image/jpg") || ($_FILES["file"]["type"] == "image/jpeg") || ($_FILES["file"]["type"] == "image/gif")) && ($_FILES["file"]["size"] < 5000000)) {
					$temporary = explode(".", $_FILES["file"]["name"]);
					$file_extension = end($temporary);
					$path = 'cfs_img/' . $_SESSION["user"].".".$file_extension;
					if (file_exists($path)) unlink($path);
					$_SESSION["cfs_img"] = 'cfs_img/' . $_SESSION["user"].".".$file_extension;
					move_uploaded_file($_FILES['file']['tmp_name'], $path);
					$return["success"] = 1;
				}
				die(json_encode($return));
			}
		}
		if ($cmd == "mempage") {
			if ($_SESSION["admin"] != "admin") die("Error! You didnt have permission");
			$member_page = $GLOBALS['member_page'];
			$return = array();
			$page = $_POST["page"];
			$member_page->_ChangePage((int)$page);
			$return["list"] = $member_page->_PrintHtml();
			$return["page"] = $member_page->_PrintPage();
			die(json_encode($return));
		}
		if ($cmd == "cpass") {
			if (isset($_POST["p1"],$_POST["p2"],$_POST["p3"])) {
				$_POST["p1"] = urldecode($_POST["p1"]);
				$_POST["p2"] = urldecode($_POST["p2"]);
				$_POST["p3"] = urldecode($_POST["p3"]);
				$return = array('success' => 0);
				if ($_POST["p2"] == $_POST["p3"]) {
					if (_Check_Pass($_POST["p1"])) {
						if (strlen($_POST["p2"]) >= 6) {
							$return["success"] = 1;
							$return["msg"] = "Mật khẩu đã được đổi thành công thành ".$_POST["p2"];
							mysqli_query($conn,"UPDATE dblogin SET pass='".md5($_POST["p2"])."' WHERE acc='".$_SESSION["acc"] ."'");
						} else $return["msg"] = "Mật khẩu phải lớn hơn 6 ký tự";
					} else $return["msg"] = "Mật khẩu hiện tại không chính xác";
				} else $return["msg"] = "Mật khẩu xác nhận không chính xác";
				die(json_encode($return));
			}
		}
		if ($cmd == "reg") {
			if ($_SESSION["admin"] != "admin") die("Error! You didnt have permission");
			if (isset($_POST["nick"],$_POST["acc"],$_POST["name"],$_POST["type"])) {
				$acc = $_POST["acc"];
				$type = $_POST["type"];
				$nick = $_POST["nick"];
				$name = $_POST["name"];
				if ($type != "admin") $type = "mod";
				$return = array("success" => 0);
				$conn = $GLOBALS['conn'];
				$query = mysqli_query($conn,"SELECT * FROM dblogin WHERE acc='". $acc ."'");
				if ($acc && $type && $nick && $name) {
					if (!$query || mysqli_num_rows($query) == 0) {
						$aValid = array('-', '_');
						$aValid2 = array('-', '_', ' ');
						if (ctype_alnum(str_replace($aValid, '', $acc)) && ctype_alnum(str_replace($aValid2, '', $name)) && ctype_alnum(str_replace($aValid2, '', $nick))) {
							_Admin_Create_Account($acc,$name,$nick,$type);
							$return["success"] = 1;
							$return["msg"] = "Tài khoản đã được tạo thành công";
						} else $return["msg"] = "Tên tài khoản và tên người dùng chỉ được chứa ký tự, số, dash và underscore";
					} else $return["msg"] = "Tài khoản này đã tồn tại";
				} else $return["msg"] = "Vui lòng điền đầy đủ thông tin";
				die(json_encode($return));
			}
		}
		if ($cmd == "del") {
			if ($_SESSION["admin"] != "admin") die("Error! You didnt have permission");
			$acc = $_POST["acc"];
			$return = array("success" => 0);
			if ($acc != $_SESSION["acc"]) {
				$query = mysqli_query($conn,"SELECT * FROM dblogin WHERE acc='". $acc ."'");
				if (!$query || mysqli_num_rows($query) == 0) $return["msg"] = "Không tìm thấy tài khoản này";
				else {
					$return["success"] = 1;
					mysqli_query($conn,"DELETE FROM dblogin WHERE acc='". $acc ."'");
					$return["msg"] = "Xóa thành công tài khoản ".$acc;
				}
			} else $return["msg"] = "Bạn không thể tự xóa tài khoản của mình";
			die(json_encode($return));
		}
		if ($cmd == "block") {
			if ($_SESSION["admin"] != "admin") die("Error! You didnt have permission");
			$acc = $_POST["acc"];
			$return = array("success" => 0);
			if ($acc != $_SESSION["acc"]) {
				$query = mysqli_query($conn,"SELECT * FROM dblogin WHERE acc='". $acc ."'");
				if (!$query || mysqli_num_rows($query) == 0) $return["msg"] = "Không tìm thấy tài khoản này";
				else {
					$return["success"] = 1;
					$row = mysqli_fetch_array($query, MYSQLI_ASSOC);
					if ($row["block"] == 1) {
						$return["block"] = 0;
						$return["msg"] = "Mở khóa thành công tài khoản ".$acc;
						mysqli_query($conn,"UPDATE dblogin SET block='0' WHERE acc='". $acc ."'");
					} else {
						$return["block"] = 1;
						$return["msg"] = "Khóa thành công tài khoản ".$acc;
						mysqli_query($conn,"UPDATE dblogin SET block='1' WHERE acc='". $acc ."'");
					}
				}
			} else $return["msg"] = "Bạn không thể tự khóa tài khoản của mình";
			die(json_encode($return));
		}
		if ($cmd == "reset") {
			if ($_SESSION["admin"] != "admin") die("Error! You didnt have permission");
			$acc = $_POST["acc"];
			$return = array("success" => 0);
			$query = mysqli_query($conn,"SELECT * FROM dblogin WHERE acc='". $acc ."'");
			if (!$query || mysqli_num_rows($query) == 0) $return["msg"] = "Không tìm thấy tài khoản này";
			else {
				$return["success"] = 1;
				$return["msg"] = "Mật khẩu được reset thành 'hutech'";
				mysqli_query($conn,"UPDATE dblogin SET pass='".md5("hutech")."' WHERE acc='". $acc ."'");
			}
			die(json_encode($return));
		}
		if ($cmd == "load_cfs") {
			$data = $_SESSION["cfs_data"];
			if (isset($_POST["data"])) {
				$data = $_POST["data"];
				if ($data== "sent") $data = "";
				$_SESSION["cfs_page"] = 0;
				$cfs_page = _Cfs_List($data);
				$_SESSION["cfs_list"] = json_encode($cfs_page);
				$_SESSION["cfs_total"] = count($cfs_page);
				$_SESSION["cfs_data"] = $data;
			}
			
			$return = array();
			$return["list"] = _CfsPage_PrintHtml();
			$return["page"] = _CfsPage_PrintPage();
			die(json_encode($return));
		}
		if ($cmd == "cfs_denied") {
			if (isset($_POST["cfs_id"])) {
				$cfs_id = $_POST["cfs_id"];
				if ($cfs_id) {
					$return = array("success" => 0);
					$id = explode(",", $cfs_id);
					for ($i=0; $i < count($id); $i++) cfs_denied($id[$i]);
					$return["msg"] = "Đã từ chối ".count($id)." cfs bạn chọn";
					die(json_encode($return));
				}
			}
		}
		if ($cmd == "cfs_restore") {
			if ($_SESSION["admin"] != "admin") die("Error! You didnt have permission");
			if (isset($_POST["cfs_id"])) {
				$cfs_id = $_POST["cfs_id"];
				if ($cfs_id) {
					$return = array("success" => 0);
					$id = explode(",", $cfs_id);
					for ($i=0; $i < count($id); $i++) cfs_restore($id[$i]);
					$return["msg"] = "Đã phục hồi ".count($id)." cfs bạn chọn";
					die(json_encode($return));
				}
			}
		}
		if ($cmd == "cfs_del") {
			if ($_SESSION["admin"] != "admin") die("Error! You didnt have permission");
			if (isset($_POST["cfs_id"])) {
				$cfs_id = $_POST["cfs_id"];
				if ($cfs_id) {
					$return = array("success" => 0);
					$id = explode(",", $cfs_id);
					for ($i=0; $i < count($id); $i++) cfs_del($id[$i]);
					$return["msg"] = "Đã xóa hoàn toàn ".count($id)." cfs bạn chọn";
					die(json_encode($return));
				}
			}
		}
		if ($cmd == "cfs_msg") {
			if (isset($_POST["cfs_id"])) {
				$cfs_id = $_POST["cfs_id"];
				if ($cfs_id) {
					$return = array("success" => 1);
					$return["msg"] = '
						<div class="input-control text full-size required">
							<input id="cfs_header"  placeholder="Header của confession tại đây nếu có!" type="text"></input>
						</div>
					';
					$id = explode(",", $cfs_id);
					for ($i=0; $i < count($id); $i++) $return["msg"] = $return["msg"] . '
			    	<div class="input-control textarea tacenter full-size required">
			    		<div class="grid" name="cfs_edit_hover">
						    <div class="row cells9">
						        <div class="cell cfs_edit_button1 colspan9">
			    					<textarea rows="4" cfs="'.$id[$i].'" class="cfs_input">'._Cfs_GetMsg($id[$i]).'</textarea>
			    				</div>
			    				<div class="cell cfs_edit_button2" style="display:none;">
			    					<button class="button mini-button danger xoa"><span class="mif-cross"></span> Loại bỏ</button>
			    				</div>
			    			</div>
			    		</div>
			    	</div>
					';
					$return["msg"] = $return["msg"] . '
				    	<div class="input-control textarea tacenter full-size warning">
				    		<textarea rows="4"  placeholder="Nhận xét, bình luận, ghi chú của admin/mod tại đây" class="cfs_cmt" id="cfs_cmt"></textarea>
				    	</div>
				    	<div class="align-center">
				    	<form id="cfs_type">
						<label class="input-control radio">
                            <input type="radio" value="1" name="cfs_type" checked="">
                            <span class="check"></span>
                            <span class="caption">Đánh số thứ tự Confession</span>
                        </label>
						<label class="input-control radio">
                            <input type="radio" value="2" name="cfs_type">
                            <span class="check"></span>
                            <span class="caption">Đánh số thứ tự thông thường</span>
                        </label>
						<label class="input-control radio">
                            <input type="radio" value="3" name="cfs_type">
                            <span class="check"></span>
                            <span class="caption">Không đánh số thứ tự</span>
                        </label>
                        </form>
                        </div>
						<div class="input-control file full-size required" data-role="input">
						    <input id="cfs_img" type="file">
						    <button class="button"><span class="mif-folder fg-cyan"></span></button>
						</div>
					';
					die(json_encode($return));
				}
			}
		}
		if ($cmd == "cfs_post") {
				if (isset($_POST["cfs_id"],$_POST["cfs_txt"],$_POST["cfs_cmt"],$_POST["cfs_header"],$_POST["cfs_type"])) {
					$cfs_id = $_POST["cfs_id"];
					$cfs_txt = $_POST["cfs_txt"];
					$cfs_cmt = $_POST["cfs_cmt"];
					$cfs_type = $_POST["cfs_type"];
					$cfs_header = $_POST["cfs_header"];
					if ($cfs_id && $cfs_txt) {
						$id = explode(",", $cfs_id);
						$txt = explode(",", $cfs_txt);
						if (count($id) == count($txt)) {
							$return = cfs_post($id,$txt,$cfs_cmt,$cfs_header,$cfs_type);
							die(json_encode($return));
						}
					}
				}
		}
		if ($cmd == "cfs_load") {
			$return = cfs_load($_SESSION["cfs_data"]);
			echo json_encode($return);
			die();
		}
		if ($cmd == "get_info") {
			$info = $_POST["info"];
			if ($info) {
				echo _Cfs_Get_Setting($info);
				die();
			}
		}
		if ($cmd == "save_info") {
			if ($_SESSION["admin"] != "admin") die("Error! You didnt have permission");
			$info = $_POST["info"];
			$data = $_POST["data"];
			if ($info) {
				_Cfs_Save_Setting($info,$data);
				die();
			}
		}
	}

	die("Error!");
?>
