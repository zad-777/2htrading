<?php

	/**
	 * 
	 * @author Keanno Manuel R. Regino
	 * @version 3.5 
	 * @since June 19, 2021	
	 * 
	 * update: September 26, 2022
	 * 
	 */

	define('WT_VERSION', '3.7');

	$default_packages = array(

		# style

		array('style', 'fontawesome6/css/all.css'),
		array('style', 'bootstrap/css/bootstrap.css'),

		# script

		array('script', 'fontawesome6/js/all.js'),
		array('script', 'jquery/jquery-3.5.1.min.js'),
		array('script', 'popper/popper.min.js'),
		array('script', 'chart.js/package/dist/chart.min.js'),
		array('script', 'phaser-3.55.2/dist/phaser.min.js')
	);

	$link = create_connection();

	$libraries_path = "";
	
	if(WT_PROJECT_STATE == 1) {
		$libraries_path = "../repository/libraries/";
	}
	else if(WT_PROJECT_STATE == 2) {
		$libraries_path = "repository/libraries/";
	}
	else if(WT_PROJECT_STATE == 3) {
		$libraries_path = "repository/libraries/";
	}

	require_once('validator_list.php');
	require_once("{$libraries_path}PHPMailer/PHPMailer.php");
	require_once("{$libraries_path}PHPMailer/SMTP.php");
	require_once("{$libraries_path}PHPMailer/Exception.php");

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	function package_manager($type='style') {
		GLOBAL $default_packages, $res_path;
		$res_path = 'repository/libraries/';

		if($type == 'style') {
			foreach($default_packages as $package) {
				if($package[0] == 'style') {
					//die("is exists: ".file_exists("{$res_path}{$package[1]}"));			
							echo "<link rel='stylesheet' type='text/css' href='{$res_path}{$package[1]}'>";
				}
			}
		} else if($type == 'script') {
			foreach($default_packages as $package) {
				if($package[0] == 'script') {
					//die("is exists: ".file_exists("{$res_path}{$package[1]}"));
					echo "<script type='text/javascript' src='{$res_path}{$package[1]}'></script>";
				}
			}
			load_script('theme');
		}
	}

	function create_connection() {
		GLOBAL $link;

		$link = mysqli_connect(WT_DATABASE_HOST, WT_DATABASE_USERNAME, 
							   WT_DATABASE_PASSWORD, WT_DATABASE_NAME);

		if($link === false)
			die("ERROR: Could Not Connect!" . mysqli_connect_error());

		return $link;
	}
	
	function load_boilerplate($name) {
		echo '<link rel="stylesheet" type="text/css" href="repository/styles/boilerplates/'.$name.'.css">';
	}

	function load_css($name) {
		echo '<link rel="stylesheet" type="text/css" href="repository/styles/'.$name.'.css">';
	}

	function load_script($name) {
		echo '<script type="text/javascript" src="repository/scripts/'.$name.'.js"></script>';
	}

	function get_root($name) {

		return "{$_SERVER['DOCUMENT_ROOT']}/".WT_PROJECT_NAME."/$name";

	}

	function get_png($file_name) {
		if(WT_PROJECT_STATE == 1)
			return 'repository/assets/images/png/'.$file_name.'.png';
		else if(WT_PROJECT_STATE == 2)
			return 'repository/assets/images/png/'.$file_name.'.png';
	}

	function get_svg($file_name) {	
		if(WT_PROJECT_STATE == 1)
			return 'repository/assets/images/svg/'.$file_name.'.svg';
		else if(WT_PROJECT_STATE == 2)
			return 'repository/assets/images/svg/'.$file_name.'.svg';
	}

	function get_jpg($file_name) {
		if(WT_PROJECT_STATE == 1)
			return 'repository/assets/images/jpg/'.$file_name.'.jpg';
		else if(WT_PROJECT_STATE == 2)
			return 'repository/assets/images/jpg/'.$file_name.'.jpg';
	}
	function load_png($file_name) {
		if(WT_PROJECT_STATE == 1)
			return 'repository/images/'.$file_name.'.png';
		else if(WT_PROJECT_STATE == 2)
			return 'repository/images/'.$file_name.'.png';
	}

	function load_svg($file_name) {	
		if(WT_PROJECT_STATE == 1)
			return 'repository/images/'.$file_name.'.svg';
		else if(WT_PROJECT_STATE == 2)
			return 'repository/images/'.$file_name.'.svg';
	}

	function load_jpg($file_name) {
		if(WT_PROJECT_STATE == 1)
			return 'repository/images/'.$file_name.'.jpg';
		else if(WT_PROJECT_STATE == 2)
			return 'repository/images/'.$file_name.'.jpg';
	}


	function render_empty($name) {
		require_once('pages/empty/empty-'.$name.'.php');
	}
	function render_list($name) {	
		require_once('pages/list/'.$name.'-list.php');
	}
	function render_navbar($name) {
		require_once('pages/navbar/'.$name.'-navbar.php');
	}
	function render_footer($name) {
		require_once('pages/footer/'.$name.'-footer.php');
	}
	function render_sidebar($name) {
		require_once('pages/sidebar/'.$name.'-sidebar.php');
	}

   	/**
   	 * This method is used to read the given session but it's not required
   	 * It will return NULL if the given session name can't find the session.
   	 * 
   	 * @param $session_name The session name
   	 * @return session This returns session
   	 * 
   	 */

	function not_required_session($session_name) {
		if(issetsession($session_name))
			return session($session_name);
		else
			return NULL;
	}

	/**
   	 * This method is used to read the given session and it's required
   	 * It will authenticate to the given page if the given session name can't find the session.
   	 * 
   	 * @param $session_name The session name
   	 * @param $page_name The authentication page
   	 * @return session This returns session
   	 * 
   	 */

	function auth_session($session_name, $page_name) {
		if(issetsession($session_name)) {
			return $session_name = session($session_name);
		} else {
			header('location: '.$page_name.'.php');
			exit();
		}
	}

	function auth_post($name, $page_name) {
		if(issetpost($name)) {
			return post($name);
		} else {
			header('location: '.$page_name.'.php');
			exit();
		}
	}

	function auth_get($name, $page_name) {
		if(issetget($name)) {
			return get($name);
		} else {
			header('location: '.$page_name.'.php');
			exit();
		}
	}

	function builtinerror() {
		GLOBAL $error;
		if(!is_empty($error) || issetsession('session_error')) {
	
			echo '<div class="th-alert error">';
			echo '    <a type="button" class="th-alert-minimize">';
			echo '        <i class="fas fa-minimize"></i>';
			echo '    </a>';
			echo '    <div class="th-alert-wrapper">';
			echo '        <i class="fas fa-exclamation-triangle"></i>';
			echo '    </div>';
			echo '    <div class="th-alert-message">';
			echo '        <ul>';
			foreach($error as $key => $value) {
				if(!empty($value))
					echo '    <li>'.$value.'</li>';
			}
			if(issetsession('session_error')) {
	
				$session_error = session('session_error');
	
				foreach($session_error as $key => $value) {
					if(!empty($value))
			echo '        <li>'.$value.'</li>';	
				}
				
				unset($_SESSION['session_error']);
	
			}
			echo '        </ul>';
			echo '    </div>';
			echo '</div>';
		}
	}
	
	function builtinsuccess() {
		if(issetsession('success')) {
			$success = session('success');
			if(!is_empty($success)) {
				echo '<div class="th-alert success">';
				echo '    <a type="button" class="th-alert-minimize">';
				echo '        <i class="fas fa-minimize"></i>'; 
				echo '    </a>';
				echo '    <div class="th-alert-wrapper">';  
				echo '        <i class="fas fa-check-circle"></i>';
				echo '    </div>';
				echo '    <div class="th-alert-message">';
				echo '        <ul>';
					foreach($success as $value) {
						if(!empty($value))
							echo '    <li class="ui-alert-message-list-item">'.$value.'</li>';	
					}
				echo '        </ul>';
				echo '    </div>';
				echo '</div>';
			}
			unset($_SESSION['success']);
		}
	
	}

	function generate_error($name) {
		GLOBAL $error;
		$result = '';
		foreach($error as $key => $value)
			if($key == $name)
				if(!empty($value)) {
					$result = 'error';
					break;
				}
		return $result;
	}

	/*

	function builtinerror() {
		GLOBAL $error;
		if(!is_empty($error) || issetsession('session_error')) {
			echo '<div class="ui-alert ui-alert-danger">';
			echo '    <div class="ui-alert-message">';
			echo '        <h1 class="ui-alert-message-title">Error!</h1>';
			echo '        <p class="ui-alert-message-text">Your input has an error, please check it</p>';
			echo '    	  <ul class="ui-alert-message-list">';
			foreach($error as $key => $value) {
				if(!empty($value))
					echo '    <li class="ui-alert-message-list-item">'.$value.'</li>';
			}
			if(issetsession('session_error')) {

				$session_error = session('session_error');

				foreach($session_error as $key => $value) {
					if(!empty($value))
			echo '        <li class="ui-alert-message-list-item">'.$value.'</li>';	
				}
				
				unset($_SESSION['session_error']);

			}
			echo '        </ul>';
			echo '	  </div>';
			echo '</div>';
		}
	}

	function builtinsuccess() {
		if(issetsession('success')) {
			$success = session('success');
			if(!is_empty($success)) {
				echo '<div class="ui-alert ui-alert-success">';
				echo '    <div class="ui-alert-message">';
				echo '        <h1 class="ui-alert-message-title">Success!</h1>';
				echo '        <p class="ui-alert-message-text">System Message</p>';
				echo '    	  <ul class="ui-alert-message-list">';
				foreach($success as $value) {
					if(!empty($value))
						echo '    <li class="ui-alert-message-list-item">'.$value.'</li>';	
				}
				echo '        </ul>';
				echo '	  </div>';
				echo '</div>';
			}
			unset($_SESSION['success']);
		}
	}

	*/

	/**
   	 * Check if the given array has a empty string value.
   	 * 
   	 * @param $arr an 1 dimensional array with values of string.
   	 * @return boolean This will return true if the array has no empty string otherwise false.
   	 *
   	 */
	
	function is_empty($arr) {
		
		$result = true;

		foreach($arr as $value => $key)
			if(!empty($arr[$value])) {
				$result = false;
				break;
			}

		return $result;
	}

	function fix_user() {

		GLOBAL $user;

		foreach($user as $key => $value) {
			if($key == 'password') {
				$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
			}
		}
	}

	function is_not_zero($arr) {
		
		$result = true;

		foreach($arr as $key => $value)
			if($value == 0) {
				$result = false;
				break;
			}

		return $result;
	}

	function safe_to_zero($arg) {
		if($arg == 0)
			$arg = 1;
		return $arg;
	}
	
	function arr_division_safe(&$arr) {
		foreach($arr as $key => $value)
			if($value == 0)
				$arr[$key] = 1;
	}

	function condition($value, $target, $return_value) {
		if($value == $target)
			return $return_value;
		return "";
	}

	function analyze_arr($arr) {
		$str = "";
		foreach($arr as $key => $value) {
			$str .= "$key: $value, ";
		}
		return $str;
	}

	//SQL

	function sql_unique_key($table) {
		$array = sql_get_results("SELECT * FROM $table");
		if(is_null($array)) {
			throw new Exception("The table name '$table' is undefined");
			exit();
		}
		$unique_key = hash('sha256', random_int(1, 100000));
		$is_same = false;
		do {
			$unique_key = hash('sha256', random_int(1, 100000));
			foreach($array as $item) {
				if($item['unique_key'] == $unique_key) {
					$is_same = true;
					break;
				}
			}
		} while($is_same);
		return $unique_key;
	}
	
	function sql_execute($sql) {
		GLOBAL $link;
		return mysqli_query($link, $sql);;
	}

	function sql_is_exist($table, $column, $data) {
		GLOBAL $link;

		$sql = "SELECT * FROM {$table} WHERE {$column} = '{$data}'";

		if($result = mysqli_query($link, $sql)) {
			if(mysqli_num_rows($result) != 0) {
				return true;
			}
		}

		return false;
	}

	/**
   	 * Get the row of sql result and return as array.
   	 * 
   	 * @param $arr SQL statement
   	 * @return array An array object.
   	 *
   	 */

	function sql_get_result($sql) {
		GLOBAL $link;
		$data = NULL;
		if($result = mysqli_query($link, $sql)) {
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$data = $row;
			}
		}
		return $data;
	}

	/**
   	 * Get the rows of sql result and return as array.
   	 * 
   	 * @param $arr SQL statement
   	 * @return array An associative array object.
   	 *
   	 */

	function sql_get_results($sql) {
		GLOBAL $link;

		$arr = array();

		if($result = mysqli_query($link, $sql)) {
			$arr = array();
			while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			    $arr[] = $row;
			}
		}

		return $arr;
	}

	function sql_count_results($sql) {
		GLOBAL $link;

		if($result = mysqli_query($link, $sql))
			return mysqli_num_rows($result);

		return 0;
	}

	function sqlcountresults($sql) {
		GLOBAL $link;

		if($result = mysqli_query($link, $sql))
			return mysqli_num_rows($result);

		return 0;
	}

	function sql_count_rows($sql) {
		GLOBAL $link;

		if($result = mysqli_query($link, $sql))
			return mysqli_num_rows($result);

		return 0;
	}

	function sqlcountrows($sql) {
		GLOBAL $link;

		if($result = mysqli_query($link, $sql))
			return mysqli_num_rows($result);

		return 0;
	}

	function get($arg) {
		return $_GET[$arg];
	}

	function safe_str($str, $ret="N/A") {
		if(empty($str))
			return $ret;
		return $str;
	}

	function issetrequest($arg) {
		return isset($_REQUEST[$arg]);
	}

	function issetget($arg) {
		return isset($_GET[$arg]);
	}

	function issetpost($arg) {
		return isset($_POST[$arg]);
	}

	function issetsession($arg) {
		return isset($_SESSION[$arg]);
	}

	//create get request session

	function cgs($name, $error_page) {
		if(!issetsession($name))
			$_SESSION[$name] = auth_get($name, $error_page);
	}

	function post($arg) {
		return $_POST[$arg];
	}

	function session($session_name) {
		return $_SESSION[$session_name];
	}

	function server($arg) {
		return $_SERVER[$arg];
	}

	function reqaction() {
		if(issetrequest('req_action'))
			return trim($_REQUEST['req_action']);
		else
			return '';
	}

	function getaction() {
		if(issetget('req_action'))
			return trim($_GET['req_action']);
		else
			return '';
	}

	function postaction() {
		if(issetpost('req_action'))
			return trim($_POST['req_action']);
		else
			return '';
	}

	function trimget($arg) {
		if($arg !== 'req_action')
			return trim($_GET[$arg]);
	}

	function trimpost($arg) {
		if($arg !== 'req_action')
			return trim($_POST[$arg]);
	}

	function trimsession($arg) {
		return trim($_SESSION[$arg]);
	}

	function is_assoc($arr) {
		if (array() === $arr) return false;
			return array_keys($arr) !== range(0, count($arr) - 1);
	}

	/**
   	 * This method is used to validate the form data
   	 * 
   	 * @return void
   	 * 
   	 */

	function validator() {
		GLOBAL $user;
		GLOBAL $input;
		GLOBAL $error;
		GLOBAL $validator_list;

		foreach($input as $key => $value) {	
			$validate = $validator_list[$key];

			switch($validate['type']) {	

				case WT_INTEGER:

					if($validate['min'] == 0 && empty($value)) {
						$error[$key] = '';
						$user[$key] = 0;
					} elseif($validate['min'] > 0 && empty($value)) {
						$error[$key] = 'Please input atleast '.$validate['min'].' quantity at ['.$key.']';
					} elseif(!preg_match('/^[-+]?\d+$/', $value)) {
						$error[$key] = 'Integer values only'.' at ['.$key.']';
					} elseif($value < $validate['min']) {
						$error[$key] = 'Please input atleast '.$validate['min'].' characters at ['.$key.']';
					} elseif($validate['max'] != 'INFINITY' && $value > $validate['max']) {
						$error[$key] = 'Input doesn\'t exceed at '.$validate['max'].' characters at ['.$key.']';
					} else {
						$error[$key] = '';
						$user[$key] = $value;
					}

					break;

				case WT_NUMERICAL:

					if($validate['min'] == 0 && empty($value)) {
						$error[$key] = '';
						$user[$key] = 0;
					} elseif($validate['min'] > 0 && empty($value)) {
						$error[$key] = 'Please input atleast '.$validate['min'].' quantity at ['.$key.']';
					} elseif(!preg_match('/^[0-9]+(\\.[0-9]+)?$/', $value)) {
						$error[$key] = 'Numerical values only'.' at ['.$key.']';
					} elseif($value < $validate['min']) {
						$error[$key] = 'Please input atleast '.$validate['min'].' characters at ['.$key.']';
					} elseif($validate['max'] != 'INFINITY' && $value > $validate['max']) {
						$error[$key] = 'Input doesn\'t exceed at '.$validate['max'].' characters at ['.$key.']';
					} else {
						$error[$key] = '';
						$user[$key] = $value;
					}

					break;

				case WT_STRING:

					if(strlen($value) < $validate['min']) {
						$error[$key] = 'Please input atleast '.$validate['min'].' characters at ['.$key.']';
					} elseif(strlen($value) > $validate['max']) {	
						$error[$key] = 'Input doesn\'t exceed at '.$validate['max'].' characters at ['.$key.']';
					} elseif(($validate['regexp'] !== 'none') && !preg_match($validate['regexp'], $value)) {
						$error[$key] = $validate['definition'].' at ['.$key.']';
					} else {
						$error[$key] = '';
						$user[$key] = htmlspecialchars($value);
					}

					break;

			}

		}

	}

	function upload_file($path, $parameter) {
		GLOBAL $user;
		GLOBAL $error;

		try {

			if(!isset($_FILES[$parameter]['error']) || is_array($_FILES[$parameter]['error'])) {
				$error[$parameter] = "Please upload a valid file at [$parameter]";
				return false;
			}

			switch($_FILES[$parameter]['error']) {

				case UPLOAD_ERR_OK:
					break;
				
				case UPLOAD_ERR_NO_FILE:
					$error[$parameter] = "No file uploaded at [$parameter]";
					return false;

				case UPLOAD_ERR_INI_SIZE:
				case UPLOAD_ERR_FORM_SIZE:
					$error[$parameter] = "Exceed filesize limit at [$parameter]";
					return false;

				default:
					$error[$parameter] = "Error file at [$parameter]";
					return false;

			}

			if($_FILES[$parameter]['size'] > 9000000) {
				$error[$parameter] = "Exceed filesize limit 9MB at [$parameter]";
				return false;
			}

			$info = new finfo(FILEINFO_MIME_TYPE);
			if(false === $extension = array_search(
				$info->file($_FILES[$parameter]['tmp_name']), 
					array(
						'jpg' => 'image/jpeg',
						'png' => 'image/png',
						'gif' => 'image/gif',
						'doc' => 'application/msword',
						'docx' => 'application/application/vnd.openxmlformats-officedocument.wordprocessingml.document',
						'pdf' => 'application/pdf'
					), true)) {

				$error[$parameter] = "Invalid file format at [$parameter]";
				return false;

			}

			if(!file_exists($path))
				mkdir($path, 0777, true);

			if(!move_uploaded_file($_FILES[$parameter]['tmp_name'], sprintf("$path/%s", $_FILES[$parameter]['name']))) {
				$error[$parameter] = "Failed to upload file at [$parameter]";
				return false;
			}

			$user[$parameter] = sprintf("$path/%s", $_FILES[$parameter]['name']);

		} catch(RuntimeException $e) {

			$error[$parameter] = $e->getMessage()." at [$parameter]";
			return false;

		}

		return true;

	}

	function upload_image($param) {
		GLOBAL $user;
		GLOBAL $error;
   		try {
		    // Undefined | Multiple Files | $_FILES Corruption Attack
		    // If this request falls under any of them, treat it invalid.
		    if (!isset($_FILES[$param]['error']) || is_array($_FILES[$param]['error'])) {

		        $error['image_location'] = 'Please upload a valid image. ' . '['.$param.']';
		        return FALSE;

		    }
		    // Check $_FILES['upfile']['error'] value.
		    switch ($_FILES[$param]['error']) {

		        case UPLOAD_ERR_OK:
		             break;

		        case UPLOAD_ERR_NO_FILE:

		       		 $error['image_location'] = 'No file sent. ' . '['.$param.']';
		             return FALSE;

		        case UPLOAD_ERR_INI_SIZE:
		        case UPLOAD_ERR_FORM_SIZE:

		             $error['image_location'] ='Exceeded filesize limit. ' . '['.$param.']';
		             return FALSE;

		        default:

		            $error['image_location'] ='Error File. ' . '['.$param.']';
		            return FALSE;

		    }

		    // You should also check filesize here.
		    if ($_FILES[$param]['size'] > 9000000) {

		        $error['image_location'] = 'Exceed filesize limit. ' . '['.$param.']';
		        return FALSE;

		    }

		    // DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
		    // Check MIME Type by yourself.
		    $finfo = new finfo(FILEINFO_MIME_TYPE);

		    if (false === $ext = array_search(
		        $finfo->file($_FILES[$param]['tmp_name']),
		        array(
		            'jpg' => 'image/jpeg',
		            'png' => 'image/png',	
		            'gif' => 'image/gif'
		        ),
		        true
		    )) {
		         $error['image_location'] = 'Invalid file format. ' . '['.$param.']';
		         return FALSE;
		    }
		    // You should name it uniquely.
		    // DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
		    // On this example, obtain safe unique name from its binary data.
		    if (!move_uploaded_file($_FILES[$param]['tmp_name'], sprintf('uploads/%s', $_FILES[$param]['name']))){
		        $error[$param] = 'Failed to move upload. ' . '['.$param.']';
		        return FALSE;
		    }
		    $user[$param] = sprintf('uploads/%s', $_FILES[$param]['name']);
		} catch (RuntimeException $e) {
			$error[$param] = $e->getMessage() . ' ['.$param.']';
			return FALSE;
		}
		return TRUE;
   	}

	function not_bound_number($num, $bound, $format) {
		if($num == 0)
			return "0";
		else if($num > $bound)
			return $bound . $format;
		else
			return $num;
		
	}

	function not_bound_string($arg, $bound, $format) {
		$len = strlen($arg);

		if($len == 0) {
			return "";
		} else if($len > $bound) {
			$diff = $len - $bound;
			$result = substr($arg, 0, -$diff);
			return $result . $format;
		} else {
			return $arg;
		}

	}

	function split_value($value, $pos=2) {
		$str = $value;
		$str = trim($str);
		if($pos==2)
			$str = preg_replace('(([a-zA-Z_\d]+\s?)#)', '', $str);
		elseif($pos==1)
			$str = preg_replace('(#([a-zA-Z_\d]+\s?))', '', $str);


		return $str;
	}

	function get_validate() {
		
		GLOBAL $user;
		GLOBAL $input;
		GLOBAL $error;

		foreach($user as $key => $value)
			if(issetget($key))
				$input[$key] = trimget($key);

		validator();

	}

	function post_validate() {
		
		GLOBAL $user;
		GLOBAL $input;
		GLOBAL $error;

		foreach($user as $key => $value)
			if(issetpost($key))
				$input[$key] = trimpost($key);

		validator();

	}

	function is_get() {

		return server('REQUEST_METHOD') === 'GET';

	}

	function is_post() {

		return server('REQUEST_METHOD') === 'POST';

	}

	function render_get($arg) {
		if(isset($_GET[$arg]))
			return $_GET[$arg];
		
		return "";
	}

	function render_post($arg) {
		if(isset($_POST[$arg]))
			return $_POST[$arg];

		return "";
	}

	function render_option($name, $arr, $default_key='name', $src='default') {
		if($src == 'default') {
			if($default_key === 'number') {
				foreach($arr as $key => $value) {
					if(render_post($name) == $key)
						echo "<option value='$key' selected>$arr[$key]</option>";
					else
						echo "<option value='{$key}'>$arr[$key]</option>";
				}
			} else {
				foreach($arr as $item) {
					if(render_post($name) == $item['id'])
						echo "<option value='{$item['id']}' selected>$item[$default_key]</option>";
					else
						echo "<option value='{$item['id']}'>$item[$default_key]</option>";
				}
			}
		} else {
			if($default_key === 'number') {
				foreach($arr as $key => $value) {
					if($src[$name] == $key)
						echo "<option value='$key' selected>$arr[$key]</option>";
					else
						echo "<option value='{$key}'>$arr[$key]</option>";
				}
			} else {
				foreach($arr as $item) {
					if($src[$name] == $item['id'])
						echo "<option value='{$item['id']}' selected>$item[$default_key]</option>";
					else
						echo "<option value='{$item['id']}'>$item[$default_key]</option>";
				}
			}
		}
	}

	function redirect_to($name) {
		header("location: $name");
		exit();
	}

	function session_stamp($name, $pages) {
		$scope = 0;
		$curr_page = basename($_SERVER["SCRIPT_FILENAME"], '.php');
		foreach($pages as $value) {
			if($curr_page == $value) {
				$scope = 1;	
				break;
			}
		}
		if($scope == 0)
			unset($_SESSION[$name]);
	}

	function htmljson($data) {
		return htmlentities(json_encode($data));
	}

	function self() {
		return htmlspecialchars($_SERVER['PHP_SELF']);
	}

	function send_email($sender, $receiver) {

		$result = true;
		
		try {

		$mail = new PHPMailer(true);

		$mail->isSMTP();
		$mail->SMTPDebug = 2;
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = 'tls';
		$mail->Username = $sender['email'];
		$mail->Password = $sender['password'];
		$mail->Port = 587;

		$mail->isHTML(true);
		$mail->setFrom($sender['email'], $sender['from']);
		$mail->addAddress($receiver);
		$mail->Subject = ($sender['subject']);
		$mail->Body = $sender['message'];
		
		if(!$mail->send()) {
		   $result = false;
		   $mail->smptpClose();
		}
		
		} catch(Exception $e) {
		   die($e);
		   $mail->smptpClose();
		}

		return $result;

	}

	function get_gender($object) {
		$gender = array(
			1 => 'Male',
			2 => 'Female'
		);
		return $gender[$object['gender']];
	}

	function get_age($date_of_birth) {
		return diff_years($date_of_birth, date("m/d/Y"));
	}

	function to_name($arr, $type=1) {
		if($type == 1)
			return $arr['first_name'] . ' ' . $arr['middle_name'] . ' ' . $arr['last_name'];
	}

	function to_associative($arr) {
		$associative = NULL;

		for($i = 0; $i < count($arr); $i++)
			$associative[$arr[$i]] = '';

		return $associative;
	}

	function strtoassoc($str) {
		$con = preg_replace('/\s+/', '', $str);
        $arr = explode(",", $con); //normal array
        $assoc = array(); //associative array
        foreach($arr as $key)  {
            $assoc[$key] = ''; //normal array to associate array
        }
        return $assoc;
    }

	function hash_str($str) {
		return password_hash($str, PASSWORD_DEFAULT);
	}

	function normal_time($arg) {
		return date('h:i a', strtotime($arg));
	}
	function military_time($arg) {
		return date('H:i:s', strtotime($arg));
	}

	function get_date() {
		return date('F j, Y');
	}

	function get_time() {
		return date('g:i a');
	}

	function diff_minutes($date_from, $date_to) {

		$a = strtotime($date_from);
		$b = strtotime($date_to);

		return round(abs($a - $b) / 60, 2);

	}

	function diff_days($date_from, $date_to) {

		$a = strtotime($date_from);
		$b = strtotime($date_to);

		return round(abs($a - $b) / (60 * 60 * 24), 2);

	}

	function diff_years($date_from, $date_to) {

		$a = strtotime($date_from);
		$b = strtotime($date_to);

		return intval(round(abs($a - $b) / (365*60*60*24), 2));

	}

	function is_night_shift($time_start, $time_end) {

		$indecator_1 = date('a', strtotime($time_start));
		$indecator_2 = date('a', strtotime($time_end));

		if($indecator_1 == 'am' && $indecator_2 == 'pm')
			return true;
		elseif($indecator_1 == 'pm' && $indecator_2 == 'pm')
			return true;

		return false;

	}

	function time_ago($datetime, $full=false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}

	function is_day_shift($time_start, $time_end) {

		$indecator_1 = date('a', strtotime($time_start));
		$indecator_2 = date('a', strtotime($time_end));

		if($indecator_1 == 'pm' && $indecator_2 == 'am')
			return true;
		elseif($indecator_1 == 'am' && $indecator_2 == 'am')
			return true;

		return false;

	}

	
	function is_day_to_night($time_start, $time_end) {

		$indecator_1 = date('a', strtotime($time_start));
		$indecator_2 = date('a', strtotime($time_end));

		if($indecator_1 == 'am' && $indecator_2 == 'pm')
			return true;

		return false;

	}

	function is_night_to_day($time_start, $time_end) {

		$indecator_1 = date('a', strtotime($time_start));
		$indecator_2 = date('a', strtotime($time_end));

		if($indecator_1 == 'pm' && $indecator_2 == 'am')
			return true;

		return false;

	}

	function is_am($time) {

		return strtolower(date('a', strtotime($time))) == 'am';

	}

	function is_pm($time) {

		return strtolower(date('a', strtotime($time))) == 'pm';

	}

	function peso_format($str) {

		$count = strlen($str) - 3;

		if($count > 0) {
			for($i = $count; $i > 0; $i-=3) {
				$str = substr_replace($str, ",", $i, 0);
			}
		}

		return $str.".00";
	}

?>