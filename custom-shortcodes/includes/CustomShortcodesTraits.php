<?php

trait FTCustomShortcodesSetup {

    // The instance of the class (to avoid global variables)
    protected static $instance = NULL;

    // The reference to the plugin
    protected $plugin = NULL;

    /**
     * The static method that initialize the plugin
     * @method start
     * @param  Object $plugin The reference to the plugin
     */
    static public function start($plugin) {
        add_action('init', [self::instance($plugin), 'init']);
    }

    /**
     * The static method that returns the current instance of the plugin
     * @method instance
     * @param  Object|null  $plugin  The plugin reference
     */
    static public function instance($plugin = null) {
        if (is_null(self::$instance)) {
            self::$instance = new self($plugin);
        }
        return self::$instance;
    }

    /**
     * Public constructor
     * @method __construct
     * @param  String $config_file The config file path
     */
    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

}
