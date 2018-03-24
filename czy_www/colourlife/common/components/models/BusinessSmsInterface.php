<?php
class BusinessSmsInterface extends SmsInterface
{
    public $simulate;
    public $eprId;
    public $userId;
    public $passwd;
    public $valueAttributes = array (
        'eprId',
        'userId',
        'passwd',
    );

    public static function model ( $className = __CLASS__ )
    {
        return parent::model ( $className );
    }

    public function rules ()
    {
        return array_merge ( parent::rules (), array (
            array ( 'eprId, userId, passwd', 'checkSmsParam', 'on' => 'update' ),
        ) );
    }

    public function attributeLabels ()
    {
        return array_merge ( parent::attributeLabels (), array (
            'eprId' => '企业 ID',
            'userId' => '帐号',
            'passwd' => '密码',
        ) );
    }

    public function attributes ()
    {
        return array_merge ( parent::attributes (), array (
            'eprId',
            'passwd',
        ) );
    }

    public function checkSmsParam ( $attribute, $params )
    {
        if ( ! $this->hasErrors () && ! $this->simulate && empty( $this->$attribute ) ) {
            $this->addError ( $attribute, $this->getAttributeLabel ( $attribute ) . '不能为空' );
        }
    }

    protected function afterFind ()
    {
        $this->config = self::getVal ();
	    if( empty( $this->config ) ){
		    return parent::afterFind();
	    }
        foreach ( $this->config as $key => $val ) {
            if ( in_array ( $key, $this->valueAttributes ) ) {
                $this->$key = $val;
            }
        }
        return parent::afterFind ();
    }

    protected function beforeSave ()
    {
        self::setVal ( $this->getAttributes ( $this->valueAttributes ) );
        return parent::beforeSave ();
    }
} 