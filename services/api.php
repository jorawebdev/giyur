<?php

require_once('ChromePhp.php');
ChromePhp::log('in app.php');

//ChromePhp::warn("This is a warning message " ) ;
//ChromePhp::error("This is an error message" ) ;
//ChromePhp::log($_SERVER);

require_once("Rest.inc.php");
//require_once("SimpleExcel.php");
//require_once("Exceptions/SimpleExcelException.php");

//use SimpleExcel\SimpleExcel;
// using labels

/*
foreach ($_SERVER as $key => $value) {
    //ChromePhp::log($key, $value);
}
*/

	class API extends REST {

		public $data = "";


		const DB_SERVER = "localhost";

		/* prod
		const DB_USER = 'zelement_dataviz'; //"zelement_az";
		const DB_PASSWORD = "d3zelement";
		const DB = "zelement_dataviz";
		*/

		/* old dev
		const DB_USER = "echarts";
		const DB_PASSWORD = "bluebottle";
		const DB = "eCharts_database";
		*/

		/* dev */
		//const DB_USER = "zelement_az";
		//const DB_PASSWORD = "d3zelement";
		const DB_USER = "zelement_dataviz";
		const DB_PASSWORD = "d3zelement";
		const DB = "GIYUR";
		/* */

		private $db = NULL;
		private $mysqli = NULL;
		public function __construct(){
			parent::__construct();				// Init parent contructor
			$this->dbConnect();					// Initiate Database connection
		}

		/*
		 *  Connect to Database
		*/
		private function dbConnect(){
			ChromePhp::log('in db', DB_USER);
			$this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
			if (mysqli_connect_errno()) {
			  echo "Failed to connect to MySQL: " . mysqli_connect_error();
				$msg = "failed to connect";
			  ChromePhp::log($msg);
			  $this->response($msg,500); // If the method not exist with in this class "Page not found".
			}
			ChromePhp::log("connected?");
		}

		/*
		 * Dynmically call the method based on the query string
		 */
		public function processApi(){
			$func = strtolower(trim(str_replace("/","",$_REQUEST['x'])));
			if((int)method_exists($this,$func) > 0)
				$this->$func();
			else
				$this->response('',404); // If the method not exist with in this class "Page not found".
		}
/*
		private function login(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$email = $this->_request['email'];
			$password = $this->_request['pwd'];
			if(!empty($email) and !empty($password)){
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					$query="SELECT uid, name, email FROM users WHERE email = '$email' AND password = '".md5($password)."' LIMIT 1";
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

					if($r->num_rows > 0) {
						$result = $r->fetch_assoc();
						// If success everythig is good send header as "OK" and user details
						$this->response($this->json($result), 200);
					}
					$this->response('', 204);	// If no records "No Content" status
				}
			}

			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password");
			$this->response($this->json($error), 400);
		}
*/
		private function signin(){
		    ChromePhp::log('in signin');
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
		    $postdata = file_get_contents("php://input");
		    $request = json_decode($postdata);
		    @$email = $request->email;
		    @$pass = $request->pwd;
		    ChromePhp::log(@$email,@$pass);
			if(!empty($email) and !empty($pass)){
				//ChromePhp::log('not empty');
				if(filter_var($email, FILTER_VALIDATE_EMAIL)){
					//$query="SELECT customerName FROM customers WHERE email = '$email' AND password = '".md5('$pass')."' LIMIT 1";
					$query="SELECT name, email, roles FROM users WHERE email = '$email' AND password = '".md5($pass)."' LIMIT 1";
					//ChromePhp::log('query: ',$query);
					$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
					//ChromePhp::log('r: ',$r);
					if($r->num_rows > 0) {
						$result = $r->fetch_assoc();
						// If success everythig is good send header as "OK" and user details
						session_start();
						//$qPwd="SELECT password FROM customers WHERE email = '$email'";
						//$rPwd = $this->mysqli->query($qPwd);
						//$resPwd = $rPwd->fetch_assoc();
						//$res = array_merge_recursive( $result, $resPwd );
						$this->response($this->json($result), 200);
					}
					$this->response('', 204);	// If no records "No Content" status
				} else {
					$error = array('status' => "Failed", "msg" => "Invalid Email address or Password,1");
					//ChromePhp::log('bad email');
				}
			} else {
				$error = array('status' => "Failed", "msg" => "Invalid Email address or Password,2");
				//ChromePhp::log('empty');
			}
			$error = array('status' => "Failed", "msg" => "Invalid Email address or Password,3");
			$this->response($this->json($error), 204);
    	}

    	private function signout(){
    		if (isset($_GET["signout"])){
            	session_destroy();
        	}
        	$this->response('', 200);	// If no records "No Content" status
    	}

		private function customers(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			//$query="SELECT distinct c.customerNumber, c.customerName, c.email, c.address, c.city, c.state, c.postalCode, c.country, c.password, c.registered FROM customers c order by c.customerNumber desc";
			$query="SELECT distinct c.id, c.name, c.email, c.password, c.register, c.roles FROM users c order by c.id desc";
			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

			if($r->num_rows > 0){
				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result), 200); // send user details
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function customer(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){
				//$query="SELECT distinct c.customerNumber, c.customerName, c.email, c.address, c.city, c.state, c.postalCode, c.country, c.password, c.registered FROM customers c where c.customerNumber=$id";
				$query="SELECT distinct c.id, c.name, c.email, c.password, c.register FROM users c where c.id=$id";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = $r->fetch_assoc();
					$this->response($this->json($result), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function userDataSize(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$userId = urldecode($_GET["user"]);
			if(!empty($userId)){
				//ChromePhp::log('in userDataSize: ',$userId);
				$query="SELECT created, SUM(size) as totalDataSize FROM chartData WHERE user = '$userId' ORDER BY created";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				if($r->num_rows > 0) {
					$result = $r->fetch_assoc();
					//ChromePhp::log('in userDataSize: ',$result);
					$this->response($this->json($result), 200); // send user details
				}
			}
			$this->response('',204);	// If no records "No Content" status
		}

/*
		private function insertCustomer(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$customer = json_decode(file_get_contents("php://input"),true);
			ChromePhp::log('$customer:', $customer);
			$id = (int)$customer['id'];
			$column_names = array('customerName', 'email', 'city', 'address', 'country', 'password', 'registered', 'roles');
			//$column_names = array('customerName', 'email', 'password', 'registered');
			$keys = array_keys($customer['customer']);
			ChromePhp::log('$keys', $keys);
			$columns = '';
			$values = '';
			foreach($column_names as $desired_key){ // Check the customer received. If blank insert blank into the array.
			   ChromePhp::log('gettingAkey:', $customer['customer'][$desired_key]);
			   if(!in_array($desired_key, $keys)) {
			   		$$desired_key = '';
				}else{
					$$desired_key = $customer['customer'][$desired_key];
				}
				$columns = $columns.$desired_key.',';
				if($desired_key=='password'){
					$$desired_key = md5($customer['customer'][$desired_key]);
				}
				$values = $values."'".$$desired_key."',";
			}
			$query = "INSERT INTO customers(".trim($columns,',').") VALUES(".trim($values,',').")";
			if(!empty($customer)){
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Customer ".$id." Customer Created Successfully.", "data" => $customer);
				$this->response($this->json($success),200);
			}else
				$this->response('',true, 204);	//"No Content" status
		}
*/

		private function insertCustomer(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}

			$user = json_decode(file_get_contents("php://input"),true);
			//ChromePhp::log('$user:', $user);
			$id = (int)$user['id'];
			$column_names = array('name', 'email', 'password', 'roles');
			//$column_names = array('customerName', 'email', 'password', 'registered');
			$keys = array_keys($user['user']);
			//ChromePhp::log('$keys', $keys);
			$columns = '';
			$values = '';
			$uName = $user['user']['name'];
			$userEmail = $user['user']['email'];
			foreach($column_names as $desired_key){ // Check the customer received. If blank insert blank into the array.
			   //ChromePhp::log('gettingAkey:', $user['user'][$desired_key]);
			   if(!in_array($desired_key, $keys)) {
			   		$$desired_key = '';
				}else{
					$$desired_key = $user['user'][$desired_key];
				}
				$columns = $columns.$desired_key.',';
				if($desired_key=='password'){
					$$desired_key = md5($user['user'][$desired_key]);
				}
				$values = $values."'".$$desired_key."',";

			}

			$query = "INSERT INTO users(".trim($columns,',').") VALUES(".trim($values,',').")";

			if(!empty($user)){
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "User ".$id." User Created Successfully.", "data" => $user);
				$this->student_confirmation($id,$uName,'mylastname','rand',$userEmail);
				$this->response($this->json($success), 200);
			}else
				$this->response('',true, 204);	//"No Content" status
		}

/*		private function updateCustomer(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$customer = json_decode(file_get_contents("php://input"),true);
			$id = (int)$customer['id'];
			$column_names = array('customerName', 'email', 'city', 'address', 'country', 'password');
			$keys = array_keys($customer['customer']);
			$columns = '';
			$values = '';
			foreach($column_names as $desired_key){ // Check the customer received. If key does not exist, insert blank into the array.
			   if(!in_array($desired_key, $keys)) {
			   		$$desired_key = '';
				}else{
					$$desired_key = $customer['customer'][$desired_key];
				}
				if($desired_key=='password'){
					$$desired_key = md5($customer[$desired_key]);
				}
				$columns = $columns.$desired_key."='".$$desired_key."',";
			}
			$query = "UPDATE customers SET ".trim($columns,',')." WHERE customerNumber=$id";
			if(!empty($customer)){
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Customer ".$id." Updated Successfully.", "data" => $customer);
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// "No Content" status
		}*/

		private function updateCustomer(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
			$user = json_decode(file_get_contents("php://input"),true);
			$id = (int)$user['id'];
			$column_names = array('name', 'email', 'password');
			$keys = array_keys($user['user']);
			$columns = '';
			$values = '';
			foreach($column_names as $desired_key){ // Check the customer received. If key does not exist, insert blank into the array.
			   if(!in_array($desired_key, $keys)) {
			   		$$desired_key = '';
				}else{
					$$desired_key = $user['user'][$desired_key];
				}
				if($desired_key=='password'){
					$$desired_key = md5($user[$desired_key]);
				}
				$columns = $columns.$desired_key."='".$$desired_key."',";
			}
			$query = "UPDATE users SET ".trim($columns,',')." WHERE id=$id";
			if(!empty($user)){
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Customer ".$id." Updated Successfully.", "data" => $user);
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// "No Content" status
		}

/*		private function deleteCustomer(){
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){
				$query="DELETE FROM customers WHERE customerNumber = $id";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Successfully deleted one record.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// If no records "No Content" status
		}*/

		private function deleteCustomer(){
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$id = (int)$this->_request['id'];
			if($id > 0){
				$query="DELETE FROM users WHERE id = $id";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Successfully deleted one record.");
				$this->response($this->json($success),200);
			}else
				$this->response('',204);	// If no records "No Content" status
		}

		private function upload(){
			//$userId = urldecode($_GET["user"]);
			//$chartType = urldecode($_GET["ctype"]);
			//$chartName = urldecode($_GET["cname"]);

			$userId = urldecode($_POST["user"]);
			$chartType = urldecode($_POST["ctype"]);
			$chartName = urldecode($_POST["cname"]);
			ChromePhp::log('in upload: ', $_FILES, $userId, $chartType, $chartName);
			if ( !empty( $_FILES ) ) {
			    $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
					ChromePhp::log('1');
			    $uploadPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];
					ChromePhp::log('2', $tempPath, $uploadPath);
					move_uploaded_file( $tempPath, $uploadPath );
					ChromePhp::log('3', $userId, $chartType, $chartName);
			    $this->convertFile($userId, $chartType, $chartName, $_FILES);
					ChromePhp::log('4');
			    $answer = array( 'answer' => 'File transfer completed' );
					ChromePhp::log('5');
			    $json = json_encode( $answer );
					ChromePhp::log('success: ');
			    echo $json;
			    //$this->convertFile($_FILES);
			} else {
				$err = array('status' => "Error", "msg" => "File is not supported. At the moment we only support .csv files.");
				//$err = json_encode( $answer );
				$this->response($this->json($err),200);
					ChromePhp::log('error: ');
			    echo 'No files';
			}
		}

		private function convertFile($userId, $chartType, $chartName, $file){
			ChromePhp::log('in covertFile: ', $file);
			ChromePhp::log('file type: ', $file['file']['type']);

			$fileType = '';
			$finalFileName = uniqid();
			if($file['file']['type']==='application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'){
				$fileType='XLSX';
				$err = array('status' => "Error", "msg" => "File type ".$fileType." is not supported.");
				$this->response($this->json($err),200);
				echo 'converted failed';
			} else if( //allow
			    $file['file']['type']==='text/csv'){
    				$fileType='CSV';
    				ChromePhp::log('1', $fileType);

						$filePath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR .$file['file']['name'];
						$targetDir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
						$fileMetaData = array($file['file']['name'], $file['file']['tmp_name'], $file['file']['size'], $file['file']['type']);
						$fh = fopen($filePath, "r");
						$csvData = array();
						ChromePhp::log('2', $file['file']['name'], $file['file']['tmp_name'], $file['file']['size'], $file['file']['type']);

						while (($row = fgetcsv($fh, ",")) !== FALSE) {
								ChromePhp::log('not false');
						    //$csvData[] = $row;
						    //$csvData[] = array($row);
								ChromePhp::log('done');
						}

						ChromePhp::log('json');
						$json = json_encode($csvData);

						ChromePhp::log('$json:');

						$dataFile = fopen($targetDir.'data_'.$finalFileName.'.json', 'w');
						fwrite($dataFile, $json);
						//print_r($json);

    				/*
    				$excel = new SimpleExcel($fileType);
    				ChromePhp::log('2', $excel);

    				$filePath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR .$file['file']['name'];
    				$targetDir = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;
    				ChromePhp::log('$filePath: ', $filePath);
    				$fileMetaData = array($file['file']['name'], $file['file']['tmp_name'], $file['file']['size'], $file['file']['type']);
    				$excel->parser->loadFile($filePath);
    				//echo $excel->parser->getCell(1, 1);
    				$excel->convertTo('JSON');
    				//$excel->writer->addRow(array('add', 'another', 'row'));
    				$dataFile = $targetDir.'data_'.$finalFileName.'.json';
    				$excel->writer->saveFile($targetDir,$dataFile);

    				unlink($filePath);
                    */

    				//$this->insertData($userId, $chartType, $chartName, $fileMetaData, $dataFile);
    				//$fp = fopen($dataFile, 'r');
    				//$content = fread($fp, filesize($content));
    				//$cleanData = stripslashes($dataFile);
    				//$success = array('status' => "Success", "msg" => "File transfer completed");
    				$success = array('status' => "Success", "msg" => "Data Uploaded Successfully", "data" => $json, "chartType" => $chartType, "chartName" => $chartName, "fileMeta" =>$fileMetaData);
    				$this->response($this->json($success),200);
    				echo 'converted';
			} else {
				$fileType!='CSV';
				$err = array('status' => "Error", "msg" => "File type ".$fileType." is not supported. At the moment we only support .csv files.");
				$this->response($this->json($err),200);
				echo 'converted failed';
			}
		}

		//private function insertData($userId, $chartType, $chartName, $fileMetaData, $dataFile){
		private function insertData(){
			if($this->get_request_method() != "POST"){
				$this->response('',406);
			}
		  $postdata = file_get_contents("php://input");
		  $request = json_decode($postdata);
			$chartType = $request->type;
			$chart = $request->chart;
			$chartName = $request->chartName;
			$custom=$request->custom;
			$custom=json_encode($custom);
			$userId = $request->user;
			$fileName=$request->fileName;
			$fileSize=$request->fileSize;
			$fileType=$request->fileType;

			//ChromePhp::log('in insertData: ', $chartType, $chart, $chartName, $custom, $fileName, $fileSize, $fileType);
			$fileName = current(explode('.', $fileName));
			$dId = 'd'.intval($fileName,36);
			date_default_timezone_set('America/Los_Angeles');
			$dateCreated = date('Y-m-m h:m:s');
			$cId = md5($dateCreated);
			$cId = 'c'.base_convert($cId, 8, 36);
			//ChromePhp::log('dId: ', $cId);

			$content = $chart;
			if(!get_magic_quotes_gpc()){
			    $fileName = addslashes($fileName);
			}

			$query = "INSERT INTO chartData SET user='$userId', chartType='$chartType', chartId='$cId', dataId='$dId', name='$chartName', type='$fileType', size=$fileSize, custom='$custom', content='$content' ON DUPLICATE KEY UPDATE chartId = VALUES(chartId)";
			//ChromePhp::log('date: ', $dateCreated);

			$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
			$cleanData = stripslashes($content);
			$success = array('status' => "Success", "msg" => "Data Added Successfully", "data" => $cleanData, "dataSetId" => $dId, "chartId" => $cId, "chartType" => $chartType);
			//json_encode($reponse, JSON_UNESCAPED_SLASHES);
			$this->response($this->json($success, JSON_UNESCAPED_SLASHES),200);

		}

		private function charts(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$userId = urldecode($_GET["user"]);
			if($userId != NULL){
				//ChromePhp::log('$userId: ', $userId);
				$query="SELECT name, chartId, content, created, chartType, dataId FROM chartData where user='$userId' ORDER BY created DESC LIMIT 0,18446744073709551615";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				//$result = $r->fetch_assoc();
				//$this->response($this->json($result), 200); // send user details

				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result), 200); // send user details


			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function chart(){
			if($this->get_request_method() != "GET"){
				$this->response('',406);
			}
			$chartId = urldecode($_GET["chartId"]);

			//ChromePhp::log('$chartId: ', $chartId);
			if($chartId != NULL){
				//ChromePhp::log('$chartId: ', $chartId);
				$query="SELECT name, chartId, custom, content, created, chartType FROM chartData where chartId='$chartId'";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);

				$result = array();
				while($row = $r->fetch_assoc()){
					$result[] = $row;
				}
				$this->response($this->json($result), 200); // send user details


			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function delete(){
			if($this->get_request_method() != "DELETE"){
				$this->response('',406);
			}
			$chartId = urldecode($_GET["chartId"]);
			if($chartId != NULL){
				//ChromePhp::log('$chartId: ', $chartId);
				$query="DELETE FROM chartData WHERE chartId='$chartId' limit 1";
				$r = $this->mysqli->query($query) or die($this->mysqli->error.__LINE__);
				$success = array('status' => "Success", "msg" => "Record Removed Successfully", "chartId"=>"$chartId");
				//json_encode($reponse, JSON_UNESCAPED_SLASHES);
				$this->response($this->json($success, JSON_UNESCAPED_SLASHES),200);

			}
			$this->response('',204);	// If no records "No Content" status
		}

		private function student_confirmation($id,$fname,$lname,$rand,$email){
			//ChromePhp::log('in student_confirmation');
			$subject = "Welcome to zElement";
			$headers = "From: support@dataviz.zelement.com \r\n";
			$headers .= "Reply-To: support@dataviz.zelement.com \r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

			$message = '<html><body>';
			$message.='<div style="width:550px; background-color:#f7f7f7; padding:15px; font-weight:bold;">';
			$message.='Welcome to zElement';
			$message.='</div>';
			$message.='<div style="font-family: Verdana;">';
			$message.='<h4>Dear ';
			$message.=$fname;
			$message.=',</h4>';
			$message.='<p>Thank you for joining zElement.</p>';
			$message.='<p>We\'re excited to have you join us and start visualizing your data.</p>';
			$message.='<p>Please, keep in mind that there\'s a long list of improvements on its way.</p>';
			$message.='<p>Your feedback is very important in shaping this application, however. Please email us with any ';
			$message.='suggestion, report of issues, technical or business related question.</p>';
			$message.='<p>Kind regards,</p>';
			//$message.="<a href='http://dataviz.zelement.com/user-confirmation.php?id=$id&email=$email&confirmation_code=$rand'>click</a>";
			$message.='<p>zElement support team</p>';
			$message.='<p><a href="http://zelement.com">zelement.com</a></p>';
			$message.='<p>support@dataviz.zelement.com</p>';
			$message.='</div>';
			$message.='</body></html>';

			mail($email,$subject,$message,$headers);
		}

		/*
		 *	Encode array into JSON
		*/
		private function json($data){
			if(is_array($data)){
				return json_encode($data);
			}
		}
	}

	// Initiiate Library

	$api = new API;
	$api->processApi();
?>
