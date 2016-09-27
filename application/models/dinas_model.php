<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dinas_Model extends CI_Model {

	/**
     * @author : Tim CA-BKN
	 * @keterangan : Model untuk menangani semua query database aplikasi
	 **/

	public function getSelectedData($table,$data)
	{
		return $this->db->get_where($table, $data);
	}

	function updateData($table,$data,$field_key)
	{
		$this->db->update($table,$data,$field_key);
	}
	function deleteData($table,$data)
	{
		$this->db->delete($table,$data);
	}
	
	function insertData($table,$data)
	{
		$this->db->insert($table,$data);
	}

	function manualQuery($q)
	{
		return $this->db->query($q);
	}

	function get_all_dinas(){
        $this->db->select('*');
        $this->db->from('tbl_dinas');

        $query = $this->db->get();
        return $query->result_array();
    }

}

/* End of file lampiran_model.php */
/* Location: ./application/models/lampiran_model.php */