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
	public function EscapeStatus($status)
	{
		$status["text"] = html_escape($status["text"]);
		return $status;
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
			return $this->EscapeStatus($query->result_array()[0]);
		else
			return false;
	}
	public function Find($userIds, $limit = null, $sinceId = null, $untilId = null)
	{
		$where = [];

		if ($userIds !== null && is_array($userIds))
		foreach ($userIds as $userId)
			$where["userId"] = $userId;

		$limit = ($limit === null) ? 20 : $limit;

		if ($sinceId !== null)
			$where["id >"] = $sinceId;
		else if ($untilId !== null)
			$where["id <"] = $untilId;

		$this->db->from("tea_time_statuses")->where($where)->limit($limit)->order_by("id", "desc");
		$query = $this->db->get();
		if ($query->num_rows() > 0)
		{
			return array_map(function($s) {
				return $this->EscapeStatus($s);
			}, $query->result_array());
		}
		else
			return false;
	}
}