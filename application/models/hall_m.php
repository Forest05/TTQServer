<?php


class Hall_m extends MY_Model{
	
	
	public $_table = 'hall';
	protected $return_type = 'array';
	

	
	public function __construct(){
		
		parent::__construct();
	
	}

	public function get_api_lang($id,$lang){
	
		// 根据lang的不同返回不同的数据
		if($lang == 'zh'){
		
			$this->db->select('id,name,address,imgUrl,uuid,phone,description');
		
		}
		else{

			$this->db->select('id,name_en,address_en,imgUrl,uuid,phone,description_en');
		
		}
			
		$result = $this->get($id);
	
		
		return $result;

		
	}
	
	function func($matches) {
    // 通常：$matches[0] 是完整的匹配项
    // $matches[1] 是第一个括号中的子模式的匹配项
    // 以此类推
    	return iconv('UCS-2BE', 'UTF-8', pack('H4', $matches[0]));
 	 }
}