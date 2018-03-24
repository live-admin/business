<?php
/**
 * Created by PhpStorm.
 * User: austin
 * Date: 3/4/16
 * Time: 11:02 上午
 */

class CustomerChat extends CActiveRecord{

	public $customer_id;
	public $username;
	public $nickname;
	public $uuid;
	public $isactive;
	public $created;


	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function tableName()
	{
		return 'customer_chat';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('customer_id, uuid, username', 'required'),
			array('customer_id, isactive', 'numerical', 'integerOnly'=>true),
			array('username,nickname,uuid', 'length', 'max'=>100),
			array('id, customer_id, username, nickname, uuid, isactive, created', 'safe', 'on'=>'search'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'customer_id'=>'用户编号',
			'username'=>'用户名',
			'nickname'=>'昵称',
			'uuid'=>'环信用户编号',
			'isactive'=>'是否激活',
			'created'=>'创建时间',
		);
	}


	/**
	 * 根据主键查找单个用户
	 * @param int $id
	 * @return array|CActiveRecord|CActiveRecord[]|mixed|null
	 */
	public function getUserByPk($id=0){
		if(!$id){
			return NULL;
		}

		return CustomerChat::model()->findByPk($id,'isactive=1');
	}

	/**
	 * 根据customer_id查找单个用户
	 * @param int $customer_id
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getUserByCustomerID($customer_id=0){
		if(!$customer_id){
			return NULL;
		}

		return CustomerChat::model()->find('customer_id=:customer_id AND isactive=1',array(':customer_id'=>$customer_id));
	}

	/**
	 * 根据username查找单个用户
	 * @param string $username
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getUserByUsername($username=''){
		if(!$username){
			return NULL;
		}

		return CustomerChat::model()->find('username=:username AND isactive=1',array(':username'=>$username));
	}

	/**
	 * 根据uuid查找单个用户
	 * @param string $uuid
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getUserByUUID($uuid=''){
		if(!$uuid){
			return NULL;
		}

		return CustomerChat::model()->find('uuid=:uuid AND isactive=1',array(':uuid'=>$uuid));
	}

	/**
	 * 插入记录
	 * @param array $user
	 */
	public function addUserChat($user = array()){
		if($user&&isset($user['customer_id'])){

//			var_dump($user);
			$chat_user = new CustomerChat();
			$chat_user->customer_id = $user['customer_id'];
			$chat_user->username = $user['username'];
			$chat_user->nickname = $user['nickname'];
			$chat_user->uuid = $user['uuid'];
			$chat_user->isactive=1;
			$chat_user->created=time();

			$suc = $chat_user->save();

		}
	}

	/**
	 * 获取环信uuid
	 * @param null $customer
	 * @param int  $customer_id
	 * @return bool|string
	 */
	public function getUserUUID($customer = NULL,$customer_id=0){

		if((!$customer)&&(!$customer_id)){
			return false;
		}
		$exit_customer = NULL;
		if($customer){
			$exit_customer = $customer;
		}
		else if($customer_id){
			$exit_customer = Customer::model()->findByPk($customer_id);
		}

		if(!$exit_customer){
			return false;
		}

		$chat_customer = $this->getUserByCustomerID($exit_customer->id);

		if(!$chat_customer){

			$nickname = '';
			if($exit_customer->name){
				$nickname = $exit_customer->name;
			}
			else if($exit_customer->nickname){
				$nickname = $exit_customer->nickname;
			}

			$huanxin = new ApiEasemobService(NULL);
			$huanxin_user =  $huanxin->addUser($exit_customer->id,$exit_customer->password,$nickname);

			if($huanxin_user&&is_array($huanxin_user)){

				if(isset($huanxin_user['error'])){
					return false;
				}
				if(isset($huanxin_user['entities'])){
					$entities = $huanxin_user['entities'];
					if(count($entities)){
						$single = $entities[0];
						if($single&&isset($single['uuid'])){
							$uuid = $single['uuid'];

							$data = array(
								'customer_id'=>$exit_customer->id,
								'username'=>$exit_customer->username,
								'nickname'=>$nickname,
								'uuid'=>$uuid,
							);

							$this->addUserChat($data);

							return $uuid;

						}
					}
				}
			}

			return FALSE;


		}
		else{

			$uuid = $chat_customer->uuid;
			if($uuid){
				return $uuid;
			}

			return FALSE;

		}





	}

	/**
	 * 修改用户密码
	 * @param null $customer
	 * @param int  $customer_id
	 * @return bool
	 */
	public function changeUserPassword($customer = NULL,$customer_id=0){

		if((!$customer)&&(!$customer_id)){
			return FALSE;
		}
		$exit_customer = NULL;
		if($customer){
			$exit_customer = $customer;
		}
		else if($customer_id){
			$exit_customer = Customer::model()->findByPk($customer_id);
		}

		if(!$exit_customer){
			return FALSE;
		}

		$chat_customer = $this->getUserByCustomerID($exit_customer->id);

		if($chat_customer){
			$huanxin = new ApiEasemobService(NULL);
			$result =  $huanxin->editPassword($exit_customer->id,$exit_customer->password);

			if($result){

				if(isset($result['error'])){
					return false;
				}
				return TRUE;
			}
		}

		return FALSE;

	}

	/**
	 * 上传文件
	 * @param $file  文件物理路径
	 * @return bool
	 */
	public function uploadFile($file){

		$huanxin = new ApiEasemobService(NULL);
		$result = $huanxin->uploadFile($file);
		if($result){
			$data=json_decode($result,true);

			if($data){

				if(isset($data['error'])){
					return false;
				}

				if(isset($data['entities'])){
					$entities = $data['entities'];
					if($entities&&is_array($entities)&&count($entities)>0){
						$item = $entities[0];
						if($item&&isset($item['uuid'])){
							return $item;
						}
					}
				}

			}


		}
		return FALSE;
	}

	/**
	 * 后台以彩多多的身份向用户发送信息
	 * @param int    $page
	 * @param string $content
	 * @param        $huanxin_file
	 * @return bool
	 */
	public function sendMsgToCustomer($page=1,$content='',$huanxin_file){

		if(!$huanxin_file){
			return FALSE;
		}
		if(!is_array($huanxin_file)){
			return FALSE;
		}
		if(!isset($huanxin_file['uuid'])){
			return FALSE;
		}

		if($page==0){
			$page = 1;
		}
		$page = $page-1;
		$page_size = 20;//最多二十
		$criteria=new CDbCriteria();
		$criteria->select='id,customer_id,nickname,username,uuid,isactive,created';
		$criteria->compare("`isactive`",1);
		$criteria->order('id ASC');

		$criteria->offset=$page*$page_size;
		$criteria->limit = $page_size;

		$customer_chat= CustomerChat::model()->findAll($criteria);

		if(count($customer_chat)==0){
			return FALSE;
		}
		else{

			$send_customers  = array();
			foreach($customer_chat as $item){
				//$send_customers[] = $item->username;
				$send_customers[] = $item->customer_id;
			}

			//彩多多头像
			$avatar = 'v30/caiduoduo.jpg';//后期需要修改为正确的图片
			$res = new PublicFunV30();
			$avatar = $res->setAbleUploadImg($avatar);

			$huanxin = new ApiEasemobService(NULL);
			if($content){
				$huanxin->yy_hxSend('admin',$send_customers,$huanxin_file,'users',array('name'=>'彩多多','avatar'=>$avatar));
			}
			else if($huanxin_file&&is_array($huanxin_file)&&isset($huanxin_file['uuid'])){
				$huanxin->yy_hxSendImage('admin',$send_customers,$huanxin_file,'users',array('name'=>'彩多多','avatar'=>$avatar));
			}
		}

		return TRUE;
	}

} 
