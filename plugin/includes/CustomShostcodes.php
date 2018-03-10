<?php

class FTCustomShortcodes {

    // The name of the option for the plugin
    protected static $option = 'ftcs_plugin_config';

    // The instance of the class (to avoid global variables)
    protected static $instance = NULL;

    // Configuration variable
    public $config = [];

    /**
     * The static method that initialize the plugin
     * @method start
     */
    static public function start() {
        add_action('init', [self::instance(), 'init']);
    }

    /**
     * The static method that returns the current instance of the plugin
     * @method instance
     */
    static public function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Activation hook
     * @method activate
     * @return void
     */
    public function activate() {

    }

    /**
     * Deactivation hook
     * @method deactivate
     * @return void
     */
    public function deactivate() {

    }

    /**
     * Public constructor
     * @method __construct
     */
     public function __construct() {

        $this->config = $this->get_config();
        FTCustomShortcodesAdmin::start($this);

    }

    /**
     * Here the plugin is initialized.
     * Include in this method all the required registrations
     * for actions, filters and so on.
     * @method load
     * @return void
     */
    public function init() {

	}

    /**
     * Normalize the configuration for safe use
     * @method normalizeConfig
     * @param  Array    $config An associative array of settings
     * @return Array              The normalized settings
     */
    protected function normalizeConfig($config) {

        $default = include(__DIR__ . '/../config.php');

        $config = is_array($config) ? $config : [];

        // $config['default_language'] = isset($config['default_language']) ? $config['default_language'] : $default['default_language'];
        // $config['language_cookie'] = $default['language_cookie'];

        return $config;
    }

    /**
     * Get the plugin settings
     * @method getConfig
     * @return Array       The plugin settings
     */
    public function getConfig() {
        return $this->normalizeConfig(get_option(self::$option));
    }

    /**
     * Set the plugin configuration
     * @method setConfig
     * @param  Array       $config The settings to be saved
     */
    public function setConfig($config) {
        update_option(self::$option, $this->normalizeConfig($config));
    }


}
