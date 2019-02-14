<?php
require APPPATH . '/libraries/REST_Controller.php';

class Data extends REST_Controller {

	private function tableName() {
		return "data";
	}

	private function showResponse($status, $message, $data) {
		if($data == null) {
			return array(
				'status' => $status,
				'message' => $message
				);
		} else {
			return array(
				'status' => $status,
				'message' => $message,
				'data' => $data
				);
		}
		// return array(
		// 	'status' => $status,
		// 	'message' => $message,
		// 	'data' => $data
		// 	);
	}

	function index_get(){
		$row = $this->db->get("data");
		$status = false;

		if($row->num_rows() > 0){
			$getData = $row->result();
			foreach ($getData as $value) {
				$newArray[] = array('id_data' => $value->id, 
									'name' => $value->nama);
			}
			$status = true;
			$message = "no message";
		} else {
			$message = $this->db->_error_message();
		}

		$this->response($this->showResponse($status, $message, $newArray), 200);
	}

	function edit_post($id) {
		$status = false;
		$message = "no message";
		$name = $this->input->post('name');
		$data = array('nama' => $name);
		$code = 200;

		if($id == null) {
			$message = "tidak ada parameter";
			$code = 400;
		} else {
			$this->db->where('id', $id);
			$this->db->set($data);
			$status = $this->db->update($this->tableName());
		}

		$this->response($this->showResponse($status, $message, null), $code);
	}

	function delete_post($id) {
		$status = false;
		$code = 200;
		$message = "no message";
		if($id == null) {
			$message = "tidak ada parameter";
			$code = 400;
		} else {
			$this->db->where('id', $id);
			$status = $this->db->delete($this->tableName());
		}

		$this->response($this->showResponse($status, $message, null), $code);
	}

	function add_post() {
		$message = "no message";
		$name = $this->input->post('name');
		$data = array('nama' => $name);

		$this->db->set($data);
		$status = $this->db->insert($this->tableName());

		$this->response($this->showResponse($status, $message, null), 200);
	}

	function uploadimage_post() {
		$message = "no message";
		$status = false;

		$filePath = './assets/';
		$config['upload_path'] = $filePath;
		$config['allowed_types'] = 'jpg|png';
		$config['overwrite'] = true;
		$this->upload->initialize($config);
		if (!empty($_FILES['image'])) {
			if (!$this->upload->do_upload('image')) {
				$message = $this->upload->display_errors();
				$this->response($this->showResponse($status, $message, null), 404);
				// $this->response($error, 404);
			} else {
				$status = true;
				$this->response($this->showResponse($status, $message, null), 200);
				// $upload_data = $this->upload->data();
				// echo $upload_data['file_name'];
			}
		}
		
		// $config['upload_path'] = 'assets/';
		// $config['allowed_types'] = 'jpg|png';
		// $config['max_size'] = 8000;
		// $config['max_width'] = 1024;
		// $config['max_height'] = 768;

		// $this->load->library('upload', $config);
		// if (!$this->upload->do_upload('image')) {
		// 	$error = array('error' => $this->upload->display_errors());
		// 	$this->response($error, 404);
		// 	// $this->load->view('upload_form', $error);
		// } else {
		// 	$data = array('data' => $this->upload->data());
		// 	$this->response($data, 200);
		// }
	}
}