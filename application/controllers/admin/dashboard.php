<?php
/**
 * 
 * 做为微信公众号的管理员（客户）的登录后主Controller
 * @author qing
 *
 */
class Dashboard extends CI_Controller{
	
	
	function __construct(){
		parent::__construct();

		session_start();		
	
		$this->load->model('admin_m');
	}
	
	function index(){
	
		redirect('admin/users','refresh');
	}
	
	function login(){

		
		
		if($this->input->post('username')){
			
			$u = $this->input->post('username');
			$pw = $this->input->post('password');
	
			$this->admin_m->login($u,$pw);		

			if (isset($_SESSION['username'])){
				//登录成功
				redirect('admin/users/index','refresh');
			
			}
			else{
				$this->session->set_flashdata('error','抱歉，用户名或密码错误');
			}
			

		}
		
		$data['title'] = '甜甜圈后台管理平台登陆';
		$data['main'] = 'admin_login';

		$this->load->view('templates/bootstrap',$data);
	}
	
	function logout(){
		
		unset($_SESSION['weixinid']);
		unset($_SESSION['username']);
		
		$this->session->set_flashdata('error','您已经登出系统');
		redirect('admin/dashboard/login','refresh');
	}
	

}