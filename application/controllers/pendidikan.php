<?php
	
	if (!defined('BASEPATH'))
    exit('No direct script access allowed');
	
	class Pendidikan extends CI_Controller {
		
		/**
			* @author : Caberawit
		* */
		public function __construct() {
			parent::__construct();
			$this->load->model('pendidikan_model');
			$this->load->library('Datatables');
			$this->load->library('table');
			$this->load->database();
		}
		
		public function index() {
			$id_pegawai = $this->uri->segment(3);
			
			if (is_admin() && !empty($id_pegawai)) {
				
				$d['title'] = $this->config->item('nama_aplikasi');
				$d['judul_halaman'] = "Tabel Riwayat Pendidikan Pegawai";
				$d['breadcumb'] = "Pengolahan Riwayat Pendidikan";
				
				$d['riwayat_pendidikan'] = $this->pendidikan_model->get_all_pendidikan_pegawai($id_pegawai);
				
				$d['id_pegawai'] = $id_pegawai;
				
				$d['content'] = $this->load->view('pendidikan/view', $d, true);
				
				$this->load->view('home', $d);
				} else {
				$this->load->view('error_404');
			}
		}
		
		public function tambah() {
			$id_pegawai = $this->uri->segment(3);
			
			if (is_admin()) {
				
				$d['title'] = $this->config->item('nama_aplikasi');
				$d['judul_halaman'] = "Tambah Data Pendidikan Pegawai";
				$d['breadcumb'] = "Pengolahan Data Pendidikan Pegawai";
				
				$d['Nama_Pegawai'] = '';
				$d['ID_Pendidikan'] = '';
				$d['Nama_Instansi'] = '';
				$d['Jurusan'] = '';
				$d['Nomor_Ijazah'] = '';
				$d['Tanggal_Ijazah'] = '';
				$d['Tingkat_Pendidikan_ID_Tingkat_Pendidikan'] = '';
				$d['Pegawai_ID_Pegawai'] = $id_pegawai;
				
				$query = "SELECT nama_pegawai FROM Pegawai where ID_Pegawai = ".$id_pegawai;
				$nama_pegawai = $this->pendidikan_model->manualQuery($query)->row()->nama_pegawai;
				
				$d['Nama_Pegawai'] = $nama_pegawai;
				
				$d['list_tingkat'] = $this->pendidikan_model->get_tingkat_pendidikan();
				
				$d['content'] = $this->load->view('pendidikan/form', $d, true);
				
				$this->load->view('home', $d);
				} else {
				header('location:' . base_url());
			}
		}
		
		public function simpan() {
			$id_pegawai = $this->uri->segment(3);
			
			if (is_admin()) {
				
				$id['ID_Pendidikan'] = $this->input->post('id');
				$up['Nama_Instansi'] = $this->input->post('nama_instansi');
				$up['Jurusan'] = $this->input->post('jurusan');
				$up['Nomor_Ijazah'] = $this->input->post('no_ijazah');
				$up['Tanggal_Ijazah'] = date('Y/m/d', strtotime($this->input->post('tanggal_ijazah')));
				$up['Tingkat_Pendidikan_ID_Tingkat_Pendidikan'] = $this->input->post('tingkat_pendidikan');
				$up['Pegawai_ID_Pegawai'] = $id_pegawai;
				
				$data = $this->pendidikan_model->getSelectedData("pendidikan",$id);
				echo($data->num_rows());
				if($data->num_rows()>0){
					$this->pendidikan_model->updateData("pendidikan",$up,$id);
					}else{
					$this->pendidikan_model->insertData("pendidikan",$up);
				}
				
				//redirect('pendidikan/index/'.$id_pegawai);
				redirect('pegawai/ubah/'.$id_pegawai);
				} else {
				header('location:' . base_url());
			}
		}
		
		public function test() {
			$data = $this->pendidikan_model->get_tingkat_pendidikan();
			
			foreach ($data as $list)
			{
				echo ($list['ID_Tingkat_Pendidikan']." - ".$list['Nama_Tingkat_Pendidikan']."<br/>");
			}
		}
		
		public function ubah() {
			$id_pegawai = $this->uri->segment(3);
			$id_pendidikan = $this->uri->segment(4);
			$cek = $this->session->userdata('logged_in');
			
			if (!empty($cek) && !empty($id_pegawai) && !empty($id_pendidikan)) {
				
				$d['title'] = $this->config->item('nama_aplikasi');
				$d['judul_halaman'] = "Ubah Data Detail Pendidikan Pegawai";
				$d['breadcumb'] = "Pengolahan Data Pendidikan Pegawai";
				
				$query = "SELECT nama_pegawai FROM Pegawai where ID_Pegawai = ".$id_pegawai;
				$nama_pegawai = $this->pendidikan_model->manualQuery($query)->row()->nama_pegawai;
				
				//$nama_pegawai = $query['nama_pegawai'];
				
				$data = $this->pendidikan_model->get_detail_pendidikan_pegawai($id_pegawai, $id_pendidikan);
				if ($data->num_rows() > 0) {
					foreach ($data->result() as $db) {
						$d['Nama_Pegawai'] = $nama_pegawai;
						$d['ID_Pendidikan'] = $db->ID_Pendidikan;
						$d['Nama_Instansi'] = $db->Nama_Instansi;
						$d['Jurusan'] = $db->Jurusan;
						$d['Nomor_Ijazah'] = $db->Nomor_Ijazah;
						$d['Tanggal_Ijazah'] = date('d-m-Y', strtotime($db->Tanggal_Ijazah));
						$d['Tingkat_Pendidikan_ID_Tingkat_Pendidikan'] = $db->Tingkat_Pendidikan_ID_Tingkat_Pendidikan;
						$d['Pegawai_ID_Pegawai'] = $db->Pegawai_ID_Pegawai;
					}
				} 
				else 
				{
					$d['Nama_Pegawai'] = '';
					$d['ID_Pendidikan'] = '';
					$d['Nama_Instansi'] = '';
					$d['Jurusan'] = '';
					$d['Nomor_Ijazah'] = '';
					$d['Tanggal_Ijazah'] = '';
					$d['Tingkat_Pendidikan_ID_Tingkat_Pendidikan'] = '';
					$d['Pegawai_ID_Pegawai'] = '';
				}
				
				$d['list_tingkat'] = $this->pendidikan_model->get_tingkat_pendidikan();
				
				$d['content'] = $this->load->view('pendidikan/form', $d, true);
				
				$this->load->view('home', $d);
				} else {
				header('location:' . base_url());
			}
		}
		
		public function hapus() {
			if (is_admin()) {
				$id_pegawai = $this->uri->segment(3);
				$id_pendidikan = $this->uri->segment(4);
				$this->pendidikan_model->manualQuery("DELETE FROM pendidikan WHERE ID_Pendidikan='$id_pendidikan'");
				//echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "pendidikan/index/$id_pegawai'>";
				echo "<meta http-equiv='refresh' content='0; url=" . base_url() . "pegawai/ubah/$id_pegawai'>";
				} else {
				header('location:' . base_url());
			}
		}
		
	}
	
	/* End of file pegawai.php */
	/* Location: ./application/controllers/pegawai.php */
