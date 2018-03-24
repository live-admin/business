<?php

class ApiUser extends CApplicationComponent implements IWebUser
{
    const STATES_VAR = '__states';
    const AUTH_TIMEOUT_VAR = '__timeout';

    public $authTimeout;

    private $_auth = null;
    private $_keyPrefix;
    private $_access = array();

    public function getAuth()
    {
        return $this->_auth;
    }

    public function setAuth($auth)
    {
        $this->_auth = $auth;
    }

    public function __get($name)
    {
        if ($this->hasState($name))
            return $this->getState($name);
        else
            return parent::__get($name);
    }

    public function __set($name, $value)
    {
        if ($this->hasState($name))
            $this->setState($name, $value);
        else
            parent::__set($name, $value);
    }

    public function __isset($name)
    {
        if ($this->hasState($name))
            return $this->getState($name) !== null;
        else
            return parent::__isset($name);
    }

    public function __unset($name)
    {
        if ($this->hasState($name))
            $this->setState($name, null);
        else
            parent::__unset($name);
    }

    public function init()
    {
        parent::init();
        $this->updateAuthStatus();
    }

    public function login($identity, $duration = 0)
    {
        $id = $identity->getId();
        $states = $identity->getPersistentStates();
        $this->changeIdentity($id, $identity->getName(), $states);
        return !$this->getIsGuest();
    }

    public function logout($destroySession = true)
    {
        $this->clearStates();
        $this->_access = array();
    }

    public function getIsGuest()
    {
        return $this->getState('__id') === null;
    }

    public function getId()
    {
        return $this->getState('__id');
    }

    public function setId($value)
    {
        $this->setState('__id', $value);
    }

    public function getName()
    {
        if (($name = $this->getState('__name')) !== null)
            return $name;
        else
            return 'Guest';
    }

    public function setName($value)
    {
        $this->setState('__name', $value);
    }

    public function loginRequired()
    {
        throw new CHttpException(403, Yii::t('yii', 'Login Required'));
    }

    public function getStateKeyPrefix()
    {
        if ($this->_keyPrefix !== null)
            return $this->_keyPrefix;
        else
            return $this->_keyPrefix = md5('Yii.' . get_class($this) . '.' . Yii::app()->getId());
    }

    public function setStateKeyPrefix($value)
    {
        $this->_keyPrefix = $value;
    }

    public function getState($key, $defaultValue = null)
    {
        $key = $this->getStateKeyPrefix() . $key;
        $auth = $this->getAuth();
        if ($auth === null) {
            return null;
        }
        return $auth->getData($key, $defaultValue);
    }

    public function setState($key, $value, $defaultValue = null)
    {
        $key = $this->getStateKeyPrefix() . $key;
        $auth = $this->getAuth();
        if ($auth === null) {
            return null;
        }
        $auth->setData($key, $value, $defaultValue);
    }

    public function hasState($key)
    {
        $key = $this->getStateKeyPrefix() . $key;
        $auth = $this->getAuth();
        if ($auth === null) {
            return false;
        }
        return $auth->hasData($key);
    }

    public function clearStates()
    {
        $auth = $this->getAuth();
        if ($auth === null) {
            return false;
        }
        $keys = $auth->getDataKeys();
        $prefix = $this->getStateKeyPrefix();
        $n = strlen($prefix);
        foreach ($keys as $key) {
            if (!strncmp($key, $prefix, $n)) {
                $auth->setData($key, null, null);
            }
        }
        $auth->save();
    }

    protected function changeIdentity($id, $name, $states)
    {
        $this->setId($id);
        $this->setName($name);
        $names = array();
        if (is_array($states)) {
            foreach ($states as $name => $value) {
                $this->setState($name, $value);
                $names[$name] = true;
            }
        }
        $this->setState(self::STATES_VAR, $names);
        $auth = $this->getAuth();
        $auth->save();
    }

    protected function updateAuthStatus()
    {
        if ($this->authTimeout !== null && !$this->getIsGuest()) {
            $expires = $this->getState(self::AUTH_TIMEOUT_VAR);
            if ($expires !== null && $expires < time())
                $this->logout(false);
            else
                $this->setState(self::AUTH_TIMEOUT_VAR, time() + $this->authTimeout);
        }
    }

    public function checkAccess($operation, $params = array(), $allowCaching = true)
    {
        if ($allowCaching && $params === array() && isset($this->_access[$operation]))
            return $this->_access[$operation];

        $access = Yii::app()->getAuthManager()->checkAccess($operation, $this->getId(), $params);
        if ($allowCaching && $params === array())
            $this->_access[$operation] = $access;

        return $access;
    }

}