<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Halls extends CI_Controller{
	
	
	
	/**
	 * 
	 * Enter description here ...
	 * @var Hall_m;
	 */
	var $model;
	
	
	
	function __construct(){
		parent::__construct();
		
		session_start();
	
		check_admin_session();
		
		$this->load->model('hall_m','model');
		
	}
	
	
	function index($skip=0) {
		$limit = 5;
		
		$this->load->library('pagination');
		
		$this->db->limit($limit,$skip);
		$models = $this->model->get_all();
	
	
		$config['base_url'] = site_url('admin/halls/');
		$config['total_rows'] = $this->model->count_all();
		$config['per_page'] = $limit; 
		
		$this->pagination->initialize($config); 
		
		$data['title'] = '展馆管理';
		$data['main'] = 'admin_halls_home';
		$data['models'] = $models;
		
		$this->load->view($this->config->item('admin_template'),$data);
		
		
		$this->output->enable_profiler($this->config->item('profile_enabled'));
		
	}
	
	function edit($id){

		$this->load->model('exhibition_m');
		
		$model = $this->model->get($id);
		
		$exhibitions = $this->exhibition_m->get_many_by('hallId',$id);
		
//		print_r($exhibitions);
			
		$data['title'] = '展馆';
		$data['main'] = 'admin_halls_edit';
		$data['model'] = $model;
		$data['exhibitions'] = $exhibitions;

		
		$this->load->view($this->config->item('admin_template'),$data);
	
		$this->output->enable_profiler($this->config->item('profile_enabled'));
	
	}
	
	
	
}