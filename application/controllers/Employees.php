<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class Employees extends CI_Controller
{
	
	public function __construct()
    {
        parent::__construct();
	}

	function sign_up() {
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST') {
			json_output(400, array(400, array('status'=>400, 'message'=>'Bad Request.')));
		} else {
			$this->load->model('MyModel');

			//$params = json_decode(file_get_contents('php://input'), TRUE);
			$params = $_POST;
			
			if( ($params['username'] != '') AND ($params['firstname'] != '') AND ($params['lastname'] != '') AND ($params['email'] != '') ) {
				
				if(strlen($params['password']) >3) {
					$params['password'] = sha1($params['password']);
					$params['date_created'] = time();
					$this->MyModel->create_user($params);
					//echo 'sdfsdf';die;
					json_output(200, array('status'=>200, 'message'=>'Successfully Added Record'));

				}
			} else {
				json_output(400, array('status'=>400, 'message'=>'Fields Can\'t Be Empty'));
			}
		}
	}

	function get_employees() {
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET') {
			json_output(400, array('status'=>400,'message'=>'Bad Request.'));
		} else {
			$this->load->model('MyModel');
			$query = $this->MyModel->get_employees()->result();

			json_output(200, $query);
		}
	}

	function get_employee() {
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'GET'|| $this->uri->segment(3) == '' || is_numeric($this->uri->segment(3)) == FALSE) {
			json_output(400, array('status'=>400,'message'=>'Bad Request.'));
		} else {
			$this->load->model('MyModel');
			$id = $this->uri->segment(3);
			$query = $this->MyModel->get_employee($id)->result();
			json_output(200, $query);
		}
	}

	function add_employee() {
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'POST') {
			json_output(400, array('status'=>400,'message'=>'Bad Request.'));
		} else {
			$params = json_decode(file_get_contents('php://input'), TRUE);
			
			if(($params['fname'] == '') AND ($params['lname'] == '') AND ($params['email'] == '') AND ($params['function'] == '')) {
				$respStatus = 401;
				$resp = array('status'=>400, 'message'=>'Fields Can\'t empty');
			} else {
				$this->load->model('MyModel');
				$respStatus = 200;
				$this->MyModel->employee_create_data($params);
				$resp = array('status'=>200, 'message'=>'Successfully Added');
			}

			json_output($respStatus, $resp);
		}
	}

	function update_employee($update_id) {
		$method = $_SERVER['REQUEST_METHOD'];
		if($method != 'PUT') {
			json_output(400, array('status'=>400,'message'=>'Bad Request.'));
		} else {
			$params = json_decode(file_get_contents('php://input'), TRUE);
			$update_id = $this->uri->segment(3);
			if(($update_id == '') AND ($params['fname'] == '') AND ($params['lname'] == '') AND ($params['email'] == '') AND ($params['function'] == '')) {
				$respStatus = 401;
				$resp = array('status'=>400, 'message'=>'Fields Can\'t empty');
			} else {
				$this->load->model('MyModel');
				$respStatus = 200;
				$this->MyModel->employee_update_data($update_id, $params);
				$resp = array('status'=>200, 'message'=>'Successfully Added');
			}

			json_output($respStatus, $resp);
		}
	}
}