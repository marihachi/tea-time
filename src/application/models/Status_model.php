<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status_model extends CI_Model
{
	public function GenerateStatusId()
	{
		$baseTime = 1448722800000;
		$nowTime = floor(microtime(true) * 1000);

		return $nowTime - $baseTime;
	}
	
	public function Create($userId, $text, $imageCount, $replyToId = null)
	{
		$data = [];
		$data["id"] = $this->GenerateStatusId();
		$data["userId"] = $userId;
		$data["text"] = $text;
		$data["imageCount"] = $imageCount;
		if ($replyToId !== null)
			$data["replyToId"] = $replyToId;

		if ($this->db->insert('tea_time_statuses', $data))
			return $this->FindById($data["id"]);
		else
			return false;
	}
	public function Destroy($id)
	{
		$data = ["id" => $id];
		return !!$this->db->delete('tea_time_statuses', $data);
	}
	public function FindById($id)
	{
		$data = ["id" => $id];
		$query = $this->db->get_where('tea_time_statuses', $data, 1);
		if ($query->num_rows() > 0)
			return (array)$query->result()[0];
		else
			return false;
	}
}