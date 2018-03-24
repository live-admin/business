<?php

require_once(dirname(__FILE__) . '/../../vendor/autoload.php');
require(dirname(__FILE__) . '/../libs/globals.php');
require(dirname(__FILE__) . '/../extensions/yii-environment/Environment.php');

/**
 * This is an example Environment, for when you want to use a custom $envVar
 * or $configDir, or want to use other than the predefined modes.
 *
 * If you use the extended class, don't forget to modify your bootstrap file as well
 * to call this class.
 */
class ColourLifeEnvironment extends Environment
{
    protected $sitePath;
    static protected $instance = null;

    /**
     * Extend Environment class and merge parent array if you want to modify/extend these
     * @return array list of valid modes
     */
    function getValidModes()
    {
        return array(
            100 => 'DEVELOPMENT',
            200 => 'TEST',
            300 => 'STAGING',
            400 => 'PRODUCTION',
        );
    }

    /**
     * Initilizes the Environment class with the given mode
     * @param constant $mode used to override automatically setting mode
     */
    public function __construct($sitePath, $mode = null)
    {
        self::$instance = $this;
        $this->sitePath = $sitePath;
        parent::__construct($mode);
    }

    public static function getInstance()
    {
        return self::$instance;
    }

    /**
     * Load and merge config files into one array.
     * @return array $config array to be processed by setEnvironment.
     */
    protected function getDefine()
    {

        // If one exists, load local define
        $fileLocalDefine = $this->getConfigDir() . 'define_local.php';
        if (file_exists($fileLocalDefine)) {
            require($fileLocalDefine);
        } else {
            // Load specific define
            $fileSpecificDefine = $this->getConfigDir() . 'define_' . strtolower($this->mode) . '.php';
            if (!file_exists($fileSpecificDefine))
                throw new Exception('Cannot find mode specific define file "' . $fileSpecificDefine . '".');
            require($fileSpecificDefine);
        }

        // Load main define
        $fileMainDefine = $this->getConfigDir() . 'define.php';
        if (!file_exists($fileMainDefine))
            throw new Exception('Cannot find main define file "' . $fileMainDefine . '".');
        require($fileMainDefine);
    }

    /**
     * Load and merge config files into one array.
     * @return array $config array to be processed by setEnvironment.
     */
    protected function getConfig()
    {
        $config = parent::getConfig();

        // Load main config
        $fileMainConfig = $this->sitePath . 'config/main.php';
        if (!file_exists($fileMainConfig))
            throw new Exception('Cannot find main config file "' . $fileMainConfig . '".');
        $configMain = require($fileMainConfig);
        $config = self::mergeArray($config, $configMain);

        // Load specific config
        $fileSpecificConfig = $this->sitePath . 'config/mode_' . strtolower($this->mode) . '.php';
        if (file_exists($fileSpecificConfig)) {
            $configSpecific = require($fileSpecificConfig);
            // Merge specific config into main config
            $config = self::mergeArray($config, $configSpecific);
        }

        // If one exists, load and merge local config
        $fileLocalConfig = $this->sitePath . 'config/local.php';
        if (file_exists($fileLocalConfig)) {
            $configLocal = require($fileLocalConfig);
            $config = self::mergeArray($config, $configLocal);
        }

        // Return
        return $config;
    }

    /**
     * Sets the environment and configuration for the selected mode.
     */
    protected function setEnvironment()
    {
        $this->getDefine();
        parent::setEnvironment();
    }

    public function getMode()
    {
        return $this->mode;
    }

}