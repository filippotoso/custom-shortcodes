<?php

class FTCustomShortcodesAdmin {

    use FTCustomShortcodeSetup;

    /**
     * Here the plugin is initialized.
     * Include in this method all the required registrations
     * for actions, filters and so on.
     * @method load
     * @return void
     */
    public function init() {
        add_action('admin_menu', function() {
            add_options_page('Custom Shortcodes Configuration', 'Custom Shortcodes', 'manage_options', 'ftcs-plugin', [$this, 'admin_page']);
        });
        add_action('admin_action_ftcs_save_config', [$this, 'action_save_config']);
	}

    /**
     * Render the admin page
     * @method admin_page
     * @return Void
     */
    public function admin_page() {

        $params = [
            'config' => $this->plugin->get_config(),
            'status' => isset($_GET['status']) ? $_GET['status'] : FALSE,
        ];

        $this->plugin->render(__DIR__ . '/../pages/admin.php', $params);

    }

    public function action_save_config() {

        $settings = isset($_POST['config']) ? $_POST['config'] : [];

        $this->plugin->set_config($settings);

        wp_redirect(admin_url('admin.php?page=ftcs-plugin&status=saved'));

        exit();

    }

}
