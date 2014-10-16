<?php
require(APPPATH.'libraries/REST_Controller.php'); 


/**
 * 
 * 从测试服务器中获取数据
 * 如果不用RESTController，那么参数的传递就会很麻烦了
 * @author Forest
 *
 */
class Api1 extends REST_Controller
{


	/**
	 * 
	 * Enter description here ...
	 * @var User_m;
	 */
	var $user_m;
	
	/**
	 * 
	 * Enter description here ...
	 * @var Hall_m
	 */
	var $hall_m;
	
	/**
	 * 
	 * Enter description here ...
	 * @var ImageText_m
	 */
	var $imageText_m;
	function __construct(){
		parent::__construct();
		
		header("Content-type: application/json; charset=utf-8");
		
		$this->load->model('hall_m');
		$this->load->model('image_text_m','imageText');
	}
	
	function firstTimeOpened_get(){
	
		$hall = $this->hall_m->get(1);
		$data['hall']=$hall;		
		
		// imageTexts
		$imageTexts = $this->imageText->get_many_by('hallId',1);
		$data['imageTexts']=$imageTexts;
		
		
		$exhibitionId = $hall['defExhibitionId'];
		//exhibition

		$query = $this->db->query("
		select * 
from exhibition 
where id=$exhibitionId");
		
		$results = $query->result_array();
		
		$data['exhibition'] = $results[0];
		
		
		// arts

		
		$query = $this->db->query("
		select art.*, author.name as aname, author.name_en as aname_en,author.description as adescription, author.description_en as adescription_en, author.avatarUrl
from art 
left join author
on art.authorId = author.id
where exhibitionId=$exhibitionId");
		
		$arts = $query->result_array();
		
		$data['arts'] = $arts;
		
		return $this->output_results($data);
	}
	
	public function updatedTime_get(){
	
//		$date = ''
	$query = $this->db->query("
select *
from updateInfo");
	//
		
		$results = $query->result_array();
		
		return $this->output_results($results[0]);
		
		
	}
	
	
	/**
	 * 
	 * 用户登录, 用的是login命令
	 * 
	 * @param
	 * username
	 * password
	 * 
	 * @return
	 * objectId
	 * sessionToken
	 * 
	 */
	public function login_get(){
		$username = $this->get('username');
		$password = $this->get('password');
		
		if(empty($username) || empty($password)){

   			$status = 401;
			$msg = '参数不全';
			return $this->output_error($status,$msg);
		}

		$this->load->model('user_m','user');
		
		$results = $this->user->get_by(array('username'=>$username,'password'=>$password));
		//用户名或密码错误
		if(empty($results)){
			$status = 1001;
			$msg = '用户名或密码错误';
			return $this->output_error($status,$msg);
		}
		
		
		return $this->output_results($results);
	
	}
	
	/**
	 * 
	 * 返回用户信息，如果是多用户会有Cache
	 */
   public function user_get(){
   	
   		$this->load->model('user_m','user');
   		
   		$id = $this->get('id');
   		
   		if(!empty($id)){

   			$user = $this->user->get($id);
   			
   			$query = $this->db->query("SELECT artId FROM favArt WHERE uid = $id");
			$results = $query->result_array();	
   			
			$user['favArts'] = $results;
   			return $this->output_results($user);

   		}
   		else{
   			return $this->output_results(-1,'missing id');
   		}

   }
   
   /**
    * 
    * 注册新用户
    * 
    * @param string username
    * @param string password
    * 
    *  @return
    *  status: 1: 成功
    * 
    *  
    *  status: 202
    *  msg: username is taken
    */
   public function user_post(){
   	
   		$this->load->model('user_m','user');
   	
   		$inputKeys = array('username','password');
   		
   		foreach ($inputKeys as $key) {
   			$inputs[$key] = $this->post($key);
   		}   		

		$count = $this->user->count_by('username',$inputs['username']);
		
		if($count>0){
			$status = 401;
			$msg = '用户名已经注册';
			return $this->output_error($status,$msg);
			
		}
		else{
			$id = $this->user->insert($inputs);
			
			$result = $this->user->get($id);
			
			return $this->output_results($result);
		}
  			
   }
   
   public function updateAvatar_post(){
   
   	$uid = $this->post('uid');
   	$base64Str = $this->post('avatar');
   	
//   	$img = base64_decode($avatar);
//	file_put_contents('public/uploads/1.jpg', $img);
   	
   	$this->load->model('user_m','user');
   	$this->user->update($uid,array('avatar'=>$base64Str));
   	
   	
   	return $this->output_success();
   }
   
   /**
    * 
    * hall 
    */
   public function newestHall_get(){
   	
   		

   		$lang = $this->input->get('lang');
   		if (empty($lang))
   			$lang = 'zh';
   		
   		$result = $this->hall_m->get_api_lang(1,$lang);
   			
   		 $this->output_results($result);
   		 
   		 $this->output->enable_profiler($this->config->item('profile_enabled'));
 
   	
   }
   
   public function exhibition_get(){
   	
   		$id = $this->get('id');
   	
   }
   
   
   /** 
    * 返回优惠券的详情, 包括shop和shopBranches
    * @param id
    * 
    * @return coupon: coupon的数据
    * 
    * 
   */
   public function coupon_get(){
   		$id = $this->get('id');

   		
   		if(!empty($id)){
		//优惠券id不为空
   			
   			$result = $this->coupon_m->get($id,'shop,shop.shopBranches');
   		
  			return $this->output_results($result,'获得对象失败');
   		}
   		else{
		//优惠券id为空
  			
   			return $this->output_results(-1,'确实优惠券id');
  		
  		}

   }
   
   /**
    * 
    * 返回最新的快券
    * @param skip
    * @param limit optional
    */
	function newestCoupons_get(){
	  	
	  	$skip = intval($this->input->get('skip'));
	  	$limit = intval($this->input->get('limit'));
	  	
	  	
	  	if (empty($skip))
	  		$skip = 0;
	  	
	  	if (empty($limit))
	  		$limit = 30;
	  	
	  		
	  	$results = $this->coupon_m->get_newest_coupons($skip,$limit);
	  	
	  	if ($results<0){
	  		return output_response(Error_Retrieve_Object,'获取最新快券失败');

	  	}
	  	else{
	  		$array = array('status'=>1,'data'=>$results);

	  		$response = json_encode($array);
			
			$data['response']=$response;
			$this->load->view('response',$data);
			
			return $response;
	  	}
	  }
   
   /**
    * 返回快券: dictionary: location -> array of coupons 
    * 先搜子商户
    * 返回的快券是有重复的，（因为不同的子商户会有同样的主商户，因此会返回重复的快券，需要客户端处理）
    * @param districtId
    * @param subDistrictId
    * @param couponTypeId
    * @param subTypeId
    * @param districtKeyword
    * @param couponTypeKeyword
    * @param latitude
    * @param longitude
    * @param skip
    * @param limit
    */
	public function searchCoupons_get(){
	
  	 	$districtId = $this->get('districtId');
  	 	$subDistrictId = $this->get('subDistrictId');
   		$couponTypeId = $this->get('couponTypeId');
   		$subTypeId = $this->get('subTypeId');
   		
   		$latitude = doubleval($this->get('latitude'));
   		$longitude = doubleval($this->get('longitude'));
   		
   		$districtKeyword = $this->get('districtKeyword');
   		$couponTypeKeyword = $this->get('couponTypeKeyword');
   		
   		$skip = $this->get('skip');
   		$limit = $this->get('limit');
   		if(empty($skip)) $skip = 0;
   		if(empty($limit))  $limit = 50;
   		
   		
   		$where['parent'] = array('$exists'=>true);
   		
		if(!empty($districtId)){

			$where['district'] = avosPointer('District', $districtId);
		}
   		else if(!empty($subDistrictId)){
   			$where['subDistrict'] = avosPointer('District',$subDistrictId);
   		}
		
   		if(!empty($couponTypeId)){
   			$where['couponType'] = avosPointer('CouponType', $couponTypeId);
   		}
   		else if(!empty($subTypeId)){
   			$where['subType'] = avosPointer('CouponType', $subTypeId);
   		}
   		
   		if (!empty($latitude)) {
   		 	$where['location'] = array('$nearSphere'=>avosGeoPoint($latitude,$longitude));	
   		}
		
   		if (!empty($couponTypeKeyword)){
   			$where['couponType']=array('$inQuery'=>array('where'=>array('title'=>array('$regex'=>$couponTypeKeyword)),'className'=>'CouponType'));
   		}
   		else if(!empty($districtKeyword)){
   			$where['district']=array('$inQuery'=>array('where'=>array('title'=>array('$regex'=>$districtKeyword)),'className'=>'District'));
   		}
   		
  
   		
   		
   		$where = json_encode($where);

   		$coupons = $this->coupon_m->search($where,$skip,$limit);
   		
   		return $this->output_results($coupons,'搜索商户失败');

			
	}
   
  
//   
//   /**
//    * 
//    * APP没有使用shop
//    */
//   public function shop_get(){
//   		
//   	
//   		$id = $this->get('id');
//   		
//   		if(!empty($id)){
//
////   			$url = HOST.'/classes/Shop/'.$id;
////   			
////   			$json = $this->kq->get($url);
////   			
////   			$error = checkResponseError($json);
////			if(!empty($error))
////				return $error;
////   				/// 只返回一个obj 
////
////   			$result = json_decode($json,true);
////
////   			$result = array_slice_keys($result, array('title','objectId','phone','address','openTime','location'));
////   				
////   			if (!isLocalhost())
////				$this->output->cache(CacheTime);
////   			
////			return $this->output_results($result);
//
//   		}
//   		else{
//   			$url = HOST."/classes/Shop?";
//   		
//   			$json = $this->jsonWithGETUrl($url);
//
//   			$error = checkResponseError($json);
//			if(!empty($error))
//				return $error;
//   			
//   			$results = resultsWithJson($json);
//		
//  		   foreach ($results as $result) {
//				$array[] = array_slice_keys($result, array('title','objectId','phone','address','openTime','location'));
//			
//  		   }		
//   			
//   			
//			if (!isLocalhost())
//				$this->output->cache(CacheTime);
//			
//			return $this->output_results($array);
//
//	   		
//   		}
//	}

	
	/**
	 * 
	 * 返回总店的所有分店信息
	 * param: parentId
	 */
	public function shopbranches_get(){
			$url = HOST."/classes/Shop?";
   		
   			$parentId = $this->get('parentId');
   			
   			if(empty($parentId)){
				outputError(-1,'没有总店信息');
   			}
   	
   			
   			$where = array('parent'=>avosPointer('Shop',$parentId));

   			$url.='where='.json_encode($where);
   			
   			$json = $this->kq->get($url);
   			
   			$error = checkResponseError($json);
			if(!empty($error))
				return $error;

			$results = resultsWithJson($json);
		
  		   foreach ($results as $result) {
				$array[] = array_slice_keys($result, array('title','objectId','phone','address','openTime','location'));
			
  		   }		

  		   
			if (!isLocalhost())
				$this->output->cache(CacheTime);
				
			

			return $this->output_results($array);
	}
	

	public function headDistricts_get(){
		
		
		$results = $this->district_m->get_all_headDistrict();	

		return $this->output_results($results);
	
	}

	public function headCouponTypes_get(){
	
		$results = $this->ctype_m->get_all_headType();
		
		return $this->output_results($results);
	}
	


	/**
	 * 
	 * 返回最热的搜索
	 */
	public function hotSearch_get(){
		
	}
	

	
	
	/**
	 * *************        My     ****************
	 */
	
	
	/**
    * 
    * 返回用户绑定的银行卡
    * @param string uid
    * @return array
    */
     public function myCard_get(){
   		
			$uid = $this->get('uid');

			$results = $this->user_m->get_user_cards($uid);
			
			return $this->output_results($results,'获取银行卡失败');
   }
   
   /**
    * 
    * 用户绑定银行卡
    */
   public function myCard_post(){
  		 $uid = $this->post('uid');
  		 $cardNumber = $this->post('cardNumber');

   		$result = $this->my_m->add_my_card($uid, $cardNumber);
  		
  		if ($result == -1){

			return $this->output_results(-1,'银行卡已经被添加');
  		
  		}
  		else{

  			return $this->output_results($result,'银行卡添加失败');
  		
  		}
  		
   }
   
	/**
	 * 
	 * 获得用户所有downloadedcoupon信息, join Table: DownloadedCoupon
	 * 
	 * @return 
	 */
	public function myDownloadedCoupon_get(){
		
		$uid = $this->get('uid');

		$results = $this->my_m->get_my_downloaded_coupons($uid);
   			
		//重新处理results
		if (empty($results['error'])){
			foreach ($results as $result) {
				$array[] = $result['coupon'];
			
	  	    }
	  	    $results = $array;
		}
	
		return $this->output_results($results);
		
	}
	
	
	/**
	 * 
	 * uid新增downloadedcoupon
	 * 即使uid和couponId是错误的也会新建，所以这里需要对uid和couponid做一个validate，是否是有效的
	 * 
	 */
	public function myDownloadedCoupon_post(){
		$uid = $this->post('uid');
		$couponId = $this->post('couponId');	
		
		if(empty($uid) || empty($couponId)){

   			return $this->output_results(-1,'没有用户信息或优惠券信息');
   		}
   		
   		$result = $this->my_m->add_my_downloaded_coupon($uid, $couponId);
   		
		return $this->output_results(nil,1);
	}
	
	
	/**
	 * 
	 * 返回用户收藏的优惠券
	 * @return array: (coupon)
	 */
	public function myFavoritedCoupon_get(){
		$uid = $this->get('uid');
		
		
		if(empty($uid)){

			return $this->output_results(-1,'没有用户信息');
   		}
		

		$results = $this->user_m->get($uid,'favoritedCoupons','favoritedCoupons');
	
   		if(is_array($results)){
   			//没有error
   			
   			$results = $results['favoritedCoupons'];

			return $this->output_results($results,'获取用户信息出错');
   		}
	
	}
	
	
	/**
	 * 
	 *  用户收藏优惠券, _User.favoritedCoupons
	 *  
	 *  @return 
	 *  
	 */
	public function myFavoritedCoupon_post(){
	
		$uid = $this->post('uid');
		$couponId = $this->post('couponId');
		$sessionToken = $this->post('sessionToken');
		
		if(empty($uid) || empty($couponId) || empty($sessionToken)){

   			return output_response(-1,'没有用户信息或优惠券信息');

		}
	
		$result = $this->my_m->add_my_favorited_coupon($uid, $sessionToken, $couponId);

		return $this->output_results($results);
		
	}
	
	/**
	 * 
	 * 用户取消收藏快券
	 * 
	 * @return
	 * 正常： status:1
	 * 异常： status: -1, input 不全
	 */
	public function myFavoritedCoupon_delete(){

		$uid = $this->get('uid');
		$couponId = $this->get('couponId');
		$sessionToken = $this->get('sessionToken');
		
		if(empty($uid) || empty($couponId) || empty($sessionToken)){

   			return output_response(-1,'没有用户信息或优惠券信息');
		}
		

		$result = $this->my_m->remove_my_favorited_coupon($uid, $sessionToken, $couponId);
		
		return $this->output_results($results);
		
	}
	/**
	 * 
	 * 返回用户收藏的商户
	 * @param string uid
	 * 	@return array: shop
	 */
	public function myFavoritedShop_get(){
		$uid = $this->get('uid');
		
		if(empty($uid)){
			return outputError(-1,'没有用户信息');
   		}
	
   		$results = $this->user_m->get($uid,'favoritedShops','favoritedShops');
	
   		if(is_array($results)){
   			//没有error
   			
   			$results = $results['favoritedShops'];

			return $this->output_results($results,'获取用户信息出错');
   		}
   		
	}
	
	
	/**
	 * 
	 *  用户收藏优惠券
	 */
	public function myFavoritedShop_post(){
	
		$uid = $this->post('uid');
		$shopId = $this->post('shopId');
		$sessionToken = $this->post('sessionToken');
		if(empty($uid) || empty($shopId) || empty($sessionToken)){

			return outputError(-1,'没有用户信息或商户信息');
   		
		}

		$result = $this->my_m->add_my_favorited_shop($uid, $sessionToken, $shopId);
		
		return $this->output_results($result);
	}

	public function myFavoritedShop_delete(){

		$uid = $this->get('uid');
		$shopId = $this->get('shopId');
		$sessionToken = $this->get('sessionToken');
		if(empty($uid) || empty($shopId) || empty($sessionToken)){

			return outputError(-1,'没有用户信息或商户信息');
   		
		}
		
//		$json = $this->kq->removePointerInArrayForUser($uid,$sessionToken,'favoritedShops',avosPointer('Shop',$shopId));
		$result = $this->my_m->remove_my_favorited_shop($uid, $sessionToken, $shopId);
		
		return $this->output_results($result);
	
		
	}

	
	//----------------------Private----------------------
  
 

   /**
    * 负责echo 和return error<br>
    * 如果有error，output error<br>
    * 如果没有error，就load到view里
    * 
    * 如果error： status & msg
    * 如果成功:   status & data
    * @param id $results
    */
 private function output_results($results,$errorMsg=''){
   	
   	if ($results<0){
   			$error = array('status'=>$results,'msg'=>$errorMsg);
   			$response = json_encode($error);
			echo $response;
			return $response;
   	}
   	else{
   		    $array = array('status'=>1,'data'=>$results);
			$response = json_encode($array);
			
			$data['response']=$response;
			$this->load->view('response',$data);
			
			return $response;
   	}
   	
   }
   
   //
   private function output_success(){
   		 $array = array('status'=>1,'data'=>(object)array());
			$response = json_encode($array);
			
			$data['response']=$response;
			$this->load->view('response',$data);
			
			return $response;
   }
   
   private function output_error($status,$errorMsg=''){
  			 $error = array('status'=>$status,'msg'=>$errorMsg);
   			$response = json_encode($error);
			echo $response;
			return $response;
   }
	 

   
   // --------------- TEST -----------------
   public function test_get(){
   		
   		$result = array('1'=>'bb');
   		
   		$this->response($result);
   }
   
   public function test_post(){
   		$id = $this->post('id');
   		$response = 'response: '.$id;
   		$this->response($response);
   }

}