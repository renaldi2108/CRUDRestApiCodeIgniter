<?php
require APPPATH . '/libraries/REST_Controller.php';

class MainRoutes extends REST_Controller {

	function index_get(){
		$row = $this->db->get("data");

		if($row->num_rows() > 0){
			$getData = $row->result();
			foreach ($getData as $value) {
				$newArray[] = array('id_data' => $value->id, 
									'name' => $value->nama);
			}
			$status = true;
			$message = "no message";
		} else {
			$status = false;
			$message = $this->db->_error_message();
		}
		
		$result = array(
			'status' => $status,
			'message' => $message,
			'data' => $newArray);

		$this->response($result, 200);
	}

	function hayhay_get() {
		$this->response('hello', 200);
	}
}