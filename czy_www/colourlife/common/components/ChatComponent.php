<?php
/*
CREATE TABLE ofUser (
  username              VARCHAR(64)     NOT NULL,
  plainPassword         VARCHAR(32),
  encryptedPassword     VARCHAR(255),
  name                  VARCHAR(100),
  email                 VARCHAR(100),
  creationDate          CHAR(15)        NOT NULL,
  modificationDate      CHAR(15)        NOT NULL,
  PRIMARY KEY (username),
  INDEX ofUser_cDate_idx (creationDate)
);
CREATE TABLE ofRoster (
  rosterID              BIGINT          NOT NULL  AUTO_INCREMENT,
  username              VARCHAR(64)     NOT NULL,
  jid                   VARCHAR(1024)   NOT NULL,
  sub                   TINYINT         NOT NULL,
  ask                   TINYINT         NOT NULL,
  recv                  TINYINT         NOT NULL,
  nick                  VARCHAR(255),
  PRIMARY KEY (rosterID),
  INDEX ofRoster_unameid_idx (username),
  INDEX ofRoster_jid_idx (jid)
);
CREATE TABLE ofID (
  idType INT NOT NULL,
  id INT NOT NULL,
  PRIMARY KEY (idType)
);
CREATE TABLE `ofProperty` (
    `name` VARCHAR (300),
    `propValue` BLOB 
);
INSERT INTO `ofProperty` (`name`, `propValue`) VALUES ('passwordKey', '9e4fjLl50UOBz4l');
 */

class ChatComponent extends CApplicationComponent
{
    const DB_CLASS = 'CDbConnection';
    const BATCH_SIZE = 50;

    static protected $users = array(
        'employee' => 'e',
        'customer' => 'c',
        'shop' => 's',
    );

    public $db = null;
    public $user_table = 'ofUser';
    public $friend_table = 'ofRoster';
    public $id_table = 'ofID';
    public $property_table = 'ofProperty';
    public $friend_id = 18;
    public $key_name = 'passwordKey';
    public $hostname = 'voip.colourlife.com';
    public $password = 'test';

    /**
     * 使用指定的 DB
     */
    public function init()
    {
        parent::init();
        if (!empty($this->db)) {
            if (is_string($this->db)) {
                $this->db = Yii::app()->getComponent($this->db);
            } else if (is_array($this->db)) {
                $this->db['class'] = isset($this->db['class']) ? $this->db['class'] : self::DB_CLASS;
                $this->db = Yii::createComponent($this->db);
                $this->db->init();
            }
            if (!(is_a($this->db, self::DB_CLASS)))
                throw new CException('ChatComponent db is not a object of class ' . self::DB_CLASS . '.');
        }
        
    }

    public function saveChatUser($model)
    {
        if (empty($this->db)) { //DB 连接失败
            return false;
        }
        $username = $this->getUsername($model->tableName(), $model->id);
        return $this->saveUser($username, $model->username);
    }

    public function findChatUser($model)
    {
        if (empty($this->db)){ //DB 连接失败
            return false;
        }

        $username = $this->getUsername($model->tableName(), $model->id);
        if ($this->findUser($username)) {
            return $username;
        } else {
            return '';
        }

    }

    public function findChatJid($model, $fModel)
    {
        if (empty($this->db)){ //DB 连接失败
             return false;
        }

        if (!isset($fModel)) {
            throw new CHttpException(400, '行聊天对象不存在');
        }
        $name = $this->getUsername($model->tableName(), $model->id);
        $fName = $this->getUsername($fModel->tableName(), $fModel->id);

        if ($this->saveFriend2($name, $this->getNickNames($model), $fName, $this->getNickNames($fModel))) {
            return array(
                'userJid' => $this->getJid($name),
                'friendJid' => $this->getJid($fName),
            );
        } else
            throw new CHttpException(400, '暂时不能进行聊天');
    }

    private function getNickNames($model)
    {
        $result = '';
        switch ($model->tableName()) {
            case 'employee':
                $result = $model->name;
                break;
            case 'customer':
                $result = $model->buildName . $model->room;
                break;
            case 'shop':
                $result = $model->name;
                break;
        }

        return $result;
    }

    public function syncUser($start = '', $verbose = true)
    {
        if (empty($this->db))
            return false;
        if (!empty($start)) {
            $start = strtotime($start);
            if ($verbose) {
                $startTime = date('Y 年 n 月 j 日 H:i:s', $start);
                echo "处理 {$startTime} 后的数据\n";
            }
        }
        if (empty($start))
            $start = 0;
        foreach (self::$users as $table => $unused) {
            $command = Yii::app()->db->createCommand();
            $command->select('COUNT(id)')->from($table);
            $command->where('create_time>=:start', array(':start' => $start));
            $count = $command->queryScalar();
            $page = ceil($count / self::BATCH_SIZE);
            if ($verbose)
                echo "处理 {$table} 的 {$count} 条数据：\n";
            for ($i = 0; $i < $page; $i++) {
                $command = Yii::app()->db->createCommand();
                $begin = $i * self::BATCH_SIZE;
                $command->where('create_time>=:start', array(':start' => $start));
                $command->select('id, username')->from($table)->limit(self::BATCH_SIZE)->offset($begin)->order('id ASC');
                if ($verbose) {
                    $end = min($count, $begin + self::BATCH_SIZE);
                    $begin++;
                    echo "第 {$begin} 到 {$end} 条数据";
                }
                foreach ($command->queryAll() as $data) {
                    $this->saveUser($this->getUsername($table, $data['id']), $data['username']);
                }
                if ($verbose)
                    echo "完成。\n";
            }
        }
    }

    protected function getUsername($type, $id)
    {
        if (isset(self::$users[$type]))
            return self::$users[$type] . $id;
        return $type . '_' . $id;
    }

    protected function getJid($username)
    {
        return $username . '@' . $this->hostname;
    }

    protected function findUser($username)
    {
        if (empty($this->db))
            return true;  // 没有 OpenFire 数据库时直接返回
        $command = $this->db->createCommand();
        $command->select('COUNT(username)')->from($this->user_table)->where("`username`=:username", array(':username' => $username));
        if ($command->queryScalar())
            return true;
        return false;
    }

    /**
     * @param $username 用户名
     * @param $name     昵称
     * @return bool
     */
    protected function saveUser($username, $name)
    {
        if (empty($this->db))
            return true;  // 没有 OpenFire 数据库时直接返回
        if ($this->findUser($username))
            return true;
        try {
            /*
             * username              VARCHAR(64)     NOT NULL,  // 用户名（ID）
             * plainPassword         VARCHAR(32),               // NULL
             * encryptedPassword     VARCHAR(255),              // 加密的密码数据（85ce9a61bbf38f42bbc2ef9cd0961c0e1beaa25b678f2810）
             * name                  VARCHAR(100),              // 昵称
             * email                 VARCHAR(100),              // 用户名@colourlife.com
             * creationDate          CHAR(15)        NOT NULL,  // 创建日期（例如：001367159051405）毫秒
             * modificationDate      CHAR(15)        NOT NULL,  // 最后修改日期，同上
             */
            $time = str_pad(strval(floor(microtime(true) * 1000)), 15, '0', STR_PAD_LEFT);
            $command = $this->db->createCommand();
            $command->insert($this->user_table, array(
                'username' => $username,
                'plainPassword' => null,
                'encryptedPassword' => $this->encryptedPassword(),
                'name' => substr($name, 0, 100),
                'email' => $this->getJid($username),
                'creationDate' => $time,
                'modificationDate' => $time,
            ));
        } catch (CDbException $e) {
            Yii::log("保存 '{$username}' 到 '{$this->user_table}' 出错。", CLogger::LEVEL_ERROR, 'colourlife.core.chat.ChatComponent');
            return false;
        }
        return true;
    }

    protected function saveFriend2($username1, $nick1, $username2, $nick2)
    {
        return $this->saveFriend($username1, $username2, $nick2) && $this->saveFriend($username2, $username1, $nick1);
    }

    protected function findFriend($username, $jid)
    {
        if (empty($this->db))
            return true;  // 没有 OpenFire 数据库时直接返回
        $command = $this->db->createCommand();
        $command->select('COUNT(rosterID)')->from($this->friend_table)->where("`username`=:username AND jid=:jid", array(':username' => $username, ':jid' => $jid));
        if ($command->queryScalar())
            return true;
        return false;
    }

    protected function saveFriend($username, $friend, $nick)
    {
        if (empty($this->db))
            return true;  // 没有 OpenFire 数据库时直接返回
        $jid = $this->getJid($friend);
        if ($this->findFriend($username, $jid))
            return true;
        try {
            /*
             * rosterID              BIGINT          NOT NULL  AUTO_INCREMENT,  // 编号名册（主键）
             * username              VARCHAR(64)     NOT NULL,                  // 用户名（id）
             * jid                   VARCHAR(1024)   NOT NULL,                  // 地址名册（对方username@colourlife.com）
             * sub                   TINYINT         NOT NULL,                  // 认购地位（3）
             * ask                   TINYINT         NOT NULL,                  // 卖出地位（-1）
             * recv                  TINYINT         NOT NULL,                  // 检举表明进入名册收到请求(-1)
             * nick                  VARCHAR(255),                              // 昵称分配给这个名册(昵称)
             */
            $command = $this->db->createCommand();
            $command->insert($this->friend_table, array(
                'username' => $username,
                'jid' => $jid,
                'sub' => 3,
                'ask' => -1,
                'recv' => -1,
                'nick' => $nick,
            ));
//            $id = $this->db->getLastInsertID() + 1;
//            $command = $this->db->createCommand();
//            $command->update($this->id_table, array(
//                'id' => $id,
//            ), 'idType=:idType', array(
//                ':idType' => $this->friend_id,
//            ));
        } catch (CDbException $e) {
            Yii::log("保存 '{$username}', '{$friend}' 到 '{$this->friend_table}' 出错。", CLogger::LEVEL_ERROR, 'colourlife.core.chat.ChatComponent');
            return false;
        }
        return true;
    }

    private $_password;

    protected function getPasswordKey()
    {
        if (!isset($this->_password)) {
            $command = $this->db->createCommand();
            $command->select('propValue')->from($this->property_table)->where("`name`=:name", array(':name' => $this->key_name));
            $this->_password = $command->queryScalar();
        }
        return $this->_password;
    }

    protected function encryptedPassword()
    {
        $key = $this->getPasswordKey();
        $password = $this->password;
        $cipher = mcrypt_module_open('blowfish', '', 'cbc', '');
        $ks = mcrypt_enc_get_key_size($cipher);
        $key = pack('H*', sha1($key));
        $iv = bin2hex(F::random(8));
        // if (empty($iv))
        //     $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($cipher));
        // else
        $iv = pack("H*", $iv);
        mcrypt_generic_init($cipher, $key, $iv);
        $bs = mcrypt_enc_get_block_size($cipher); // get block size
        $password = mb_convert_encoding($password, 'UTF-16BE'); // set to 2 byte, network order
        $pkcs = $bs - (strlen($password) % $bs); // get pkcs5 pad length
        $pkcs = str_repeat(chr($pkcs), $pkcs); // create padding string
        $password = $password . $pkcs; // append pkcs5 padding to the data
        $result = mcrypt_generic($cipher, $password);
        mcrypt_generic_deinit($cipher);
        return bin2hex($iv . $result);
    }

}