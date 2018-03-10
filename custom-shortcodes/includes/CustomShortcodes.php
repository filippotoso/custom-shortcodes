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

        if (!is_dir(__DIR__ . '/../shortcodes/active')) {
            mkdir(__DIR__ . '/../shortcodes/active', 0777);
        }

        if (!is_dir(__DIR__ . '/../shortcodes/active')) {
            mkdir(__DIR__ . '/../shortcodes/inactive', 0777);
        }

        if (file_exists(__DIR__ . '/../assets/hello-world.php') && !file_exists(__DIR__ . '/../shortcodes/active/hello-world.php')) {
            rename(__DIR__ . '/../assets/hello-world.php', __DIR__ . '/../shortcodes/active/hello-world.php');
        }

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

        $this->config = $this->getConfig();
        FTCustomShortcodesAdmin::start($this);

    }

    public function registerShortcode($shortcode) {

        if (file_exists($shortcode)) {

            $shortcode = basename($shortcode, '.php');

            add_shortcode($shortcode , function($atts, $content = '') use ($shortcode) {
                return $this->shortcode($shortcode, $atts, $content);
            });

        }

    }

    /**
     * Here the plugin is initialized.
     * Include in this method all the required registrations
     * for actions, filters and so on.
     * @method load
     * @return void
     */
    public function init() {

        $shortcodes = glob(__DIR__ . '/../shortcodes/active/*.php');

        foreach ($shortcodes as $shortcode) {
            $this->registerShortcode($shortcode);
        }

        add_action('admin_enqueue_scripts', [$this, 'action_enqueue_scripts']);

	}

    /**
     * Action for wp_enqueue_scripts
     * @method action_enqueue_scripts
     * @return void
     */
     public function action_enqueue_scripts($hook) {

        if (substr($hook, - strlen(FTCustomShortcodesAdmin::$page)) != FTCustomShortcodesAdmin::$page) {
            return;
        }

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-dialog');
        wp_enqueue_style ('wp-jquery-ui-dialog');

        wp_enqueue_style('ftcs-custom-css', plugins_url('assets/custom.css', __DIR__));

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

        // $config['custom_option'] = isset($config['custom_option']) ? $config['custom_option'] : $default['custom_option'];
        // $config['default_option'] = $default['default_option'];

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

    /**
     * Render a shortcode
     * @method shortcode
     * @param  string   $name Name of the shortcode
     * @return string
     */
    public function shortcode($shortcode, $atts, $content = '') {

        $file = sprintf('%s/../shortcodes/active/%s.php', __DIR__, $shortcode);

        if (!file_exists($file)) {

            if (is_admin() && isset($_GET[FTCustomShortcodesAdmin::$action]) && ($_GET[FTCustomShortcodesAdmin::$action] == 'preview')) {
                $inactive = sprintf('%s/../shortcodes/inactive/%s.php', __DIR__, $shortcode);
                if (!file_exists($inactive)) {
                    throw new Exception(sprintf('Missing %s file', $file));
                }
                $file = $inactive;
            }

        }

        $params = [
            'atts' => $atts,
            'content' => $content,
        ];

        return do_shortcode($this->render($file, $params));

    }

    /**
     * Render the specified template
     * @method render
     * @param  String  $template The path of the template to be rendered
     * @param  array   $params   An associative array of variables to be used in the template
     * @return String            The rendered template
     */
    public function render() {

        // Using func_num_args() and func_get_arg() to avoid
        // injectiong external variables in the shortcode inclusion.

        if (func_num_args() == 0) {
            throw new Exception(sprintf('Missing parameters to %s::%s', __CLASS__, __METHOD__));
        }

        ob_start();

        if (func_num_args() >= 2) {
            extract(func_get_arg(1));
        }

        include(func_get_arg(0));

        return ob_get_clean();

    }

}
