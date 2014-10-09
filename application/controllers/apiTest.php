<?php
/*
 * @author qing
 *
 */




class ApiTest extends CI_Controller{

	/**
	 * 
	 * Enter description here ...
	 * @var Coupon_m
	 */
	var $coupon_m;
  
	
	/**
	 * 
	 * Enter description here ...
	 * @var User_m
	 */
	var $user;
	
	
	function __construct(){
		parent::__construct();
		
		$this->load->model('user_m','user');
	}
	

	function index() {

		echo 'kqtest';
		
	}
	
	function test(){
		
//		$url = ''
	}
	
	function user(){
		
	
		$result = $this->user->get(2);
		
		
		
		if(empty($result)){
		
		}
		else{
		
		}
		
		var_dump($result);
	}
	
	
	function user_post(){
		
//		$this->article_model->insert(array('body'=>'Woot!', 'title'=>'My thoughts'), FALSE);
		
		$array = array('username'=>'131111112','password'=>'111');

		$count = $this->user->count_by('username',$array['username']);
		
		if($count>0){
			//用户名已经有了
			
		}
		else{
			$id = $this->user->insert($array);
			
			$result = $this->user->get($id);
			
		
		}
		

	}
	
//
//	function testReg(){
//		
//		header("Content-type: text/html; charset=utf-8");
//		
//		$url = 'http://localhost/kq/index.php/kqunionapi/couponAccepted/';
//		
//		$post = array('username'=>'sss','password'=>'111');
//	
//		$post = json_encode($post);
//		
//		
//		$response = $this->post($url,$post);
//		
//		echo 'response'.$response;
//	
//		
//	}
	
	function test(){
//		$url = HOST."/users?keys=phone,username";
//		echo $this->avoslibrary->get($url);
		
//	$data = array('shop'=>avosPointer('Shop', '53bbece4e4b0dea5ac1c8907'));
//		
//		echo $this->updateObject('Coupon','539e8dfce4b023daacbd6fa3',json_encode($data));
	
//		echo $this->addShopBranchesToShop(array('539e8c51e4b0c92f1847dc23','539e8c52e4b0c92f1847dc24'), '539e8c52e4b0c92f1847dc25');

//		echo $this->addCouponToShop('539d8cd9e4b0a98c8733f8dc', '539d8817e4b0a98c8733f287');

//		echo decodeUnicode($this->coupon_m->addInShop('539d8cd9e4b0a98c8733f8dc', '539d8817e4b0a98c8733f287'))	;
	}
	
	
	private	function post($url='',$objJson=''){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
		curl_setopt($ch, CURLOPT_POST, 1);
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $objJson);	
		$output = curl_exec($ch);
		curl_close($ch);
		
		return $output;
	}
	
	private	function post2($url='',$objJson=''){
		
		
		return $objJson;
	}
}