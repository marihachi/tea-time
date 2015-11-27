<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CoreAPI_Status
{
	public function show($get)
	{
		header("Content-Type: application/json; charset=utf-8");
		$this->load->model('Status_model', 'StatusModel', TRUE);
		$res = "";

		if (!ApiParamValidate($get, ['status_id']))
			return;

		$statusId = $get["status_id"];

		if ($status = $this->StatusModel->FindById($statusId))
			$res = BuildSuccessResponse([
				"message" => "successful.",
				"status" => $status
			]);
		else
			$res = BuildErrorResponse(500, 105, 'Failed to execute.');

		return $res;
	}

	public function update($meUserId, $post)
	{
		header("Content-Type: application/json; charset=utf-8");
		$this->load->model('Status_model', 'StatusModel', TRUE);
		$res = "";

		if (!ApiParamValidate($post, ['text']))
			return;

		$text = urldecode($post["text"]);

		if ($status = $this->StatusModel->Create($meUserId, $text, 0))
			$res = BuildSuccessResponse([
				"message" => "successful.",
				'status' => $status
			]);
		else
			$res = BuildErrorResponse(500, 105, 'Failed to execute.');

		return $res;
	}
}