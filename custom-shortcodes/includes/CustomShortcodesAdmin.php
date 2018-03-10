<?php

class FTCustomShortcodesAdmin {

    use FTCustomShortcodesSetup;

    // The GET parameter to execute  a specific action
    public static $action = 'ftcs-action';

    // The GET parameter to select a specific page to show
    public static $page = 'ftcs-plugin';


    /**
     * Here the plugin is initialized.
     * Include in this method all the required registrations
     * for actions, filters and so on.
     * @method load
     * @return void
     */
    public function init() {

        // Only administrators can create plugins
        if (current_user_can('manage_options')) {

            add_action('admin_menu', function() {
                add_management_page('Custom Shortcodes Configuration', 'Custom Shortcodes', 'manage_options', static::$page, [$this, 'admin_page']);
            });

            add_action('admin_action_ftcs_manage_shortcode', [$this, 'action_manage_shortcode']);

        }

	}

    protected function shortcodesList($type) {

        $shortcodes = glob(sprintf('%s/../shortcodes/%s/*.php', __DIR__, $type));

        $results = [];
        foreach ($shortcodes as $shortcode) {
            $name = basename($shortcode, '.php');
            $results[] = [
                'name' => $name,
                'type' => $type,
                'modified_at' => filemtime($shortcode),
                'actions' => [
                    'edit' => admin_url('admin.php?page=' . static::$page . '&' . static::$action . '=edit&shortcode=' . $name),
                    'activate' => admin_url('admin.php?page=' . static::$page . '&' . static::$action . '=activate&shortcode=' . $name),
                    'deactivate' => admin_url('admin.php?page=' . static::$page . '&' . static::$action . '=deactivate&shortcode=' . $name),
                    'delete' => admin_url('admin.php?page=' . static::$page . '&' . static::$action . '=delete&shortcode=' . $name),
                ],
            ];

        }
        return $results;

    }

    /**
     * Render the admin page
     * @method admin_page
     * @return Void
     */
    public function admin_page() {

        $action = $this->request(static::$action, 'index', ['index', 'create', 'edit', 'delete', 'activate', 'deactivate']);

        $status = FALSE;

        $shortcode = basename($this->request('shortcode'));

        if (in_array($action, ['delete', 'activate', 'deactivate'])) {

            if ($action == 'delete') {

                $this->unlinkShortcode($shortcode);
                $status = 'deleted';

            }

            if ($action == 'activate') {

                $from = sprintf('%s/../shortcodes/inactive/%s.php', __DIR__, $shortcode);
                $to = sprintf('%s/../shortcodes/active/%s.php', __DIR__, $shortcode);

                if (file_exists($from)) {
                    rename($from, $to);
                    $status = 'activated';
                } else {
                    $status = 'not-found';
                }

            }

            if ($action == 'deactivate') {

                $from = sprintf('%s/../shortcodes/active/%s.php', __DIR__, $shortcode);
                $to = sprintf('%s/../shortcodes/inactive/%s.php', __DIR__, $shortcode);

                if (file_exists($from)) {
                    rename($from, $to);
                    $status = 'deactivated';
                } else {
                    $status = 'not-found';
                }

            }

            $action = 'index';

        }

        $params = [
            'page' => static::$page,
            'action' => static::$action,
            'config' => $this->plugin->getConfig(),
            'status' => isset($_GET['status']) ? $_GET['status'] : $status,
            'shortcode' => [
                'name' => '',
                'type' => 'inactive',
                'content' => '',
            ],
            'shortcodes' => [
                'active' => [],
                'inactive' => [],
            ]
        ];

        if ($action == 'edit') {

            $active = sprintf('%s/../shortcodes/active/%s.php', __DIR__, $shortcode);
            $inactive = sprintf('%s/../shortcodes/inactive/%s.php', __DIR__, $shortcode);

            if (file_exists($active)) {
                $params['shortcode']['name'] = $shortcode;
                $params['shortcode']['type'] = 'active';
                $params['shortcode']['content'] = file_get_contents($active);
            } elseif (file_exists($inactive)) {
                $params['shortcode']['name'] = $shortcode;
                $params['shortcode']['type'] = 'inactive';
                $params['shortcode']['content'] = file_get_contents($inactive);
            } else {
                wp_redirect(admin_url('admin.php?page=' . static::$page . '&' . static::$action . '=index&status=' . $status));
                exit();
            }

        }

        if ($action == 'index') {
            $params['shortcodes']['active'] = $this->shortcodesList('active');
            $params['shortcodes']['inactive'] = $this->shortcodesList('inactive');
        }

        echo($this->plugin->render(__DIR__ . '/../pages/' . $action . '.php', $params));

    }

    public function request($name, $default = null, $valid = null) {

        // Find the right request array
        $input = null;
        if (isset($_GET[$name])) {
            $input = $_GET;
        } elseif (isset($_POST[$name])) {
            $input = $_POST;
        }

        // If the value has been found
        if (!is_null($input)) {

            // If the provided value must be an element of $input
            if (is_array($valid)) {

                // If it's a valid value
                if (in_array($input[$name], $valid)) {
                    return $input[$name];
                } else {
                    return $default;
                }

            }

            return $input[$name];

        }

        return $default;

    }

    protected function unlinkShortcode($shortcode) {

        $active = sprintf('%s/../shortcodes/active/%s.php', __DIR__, $shortcode);
        if (file_exists($active)) {
            unlink($active);
        }

        $inactive = sprintf('%s/../shortcodes/inactive/%s.php', __DIR__, $shortcode);
        if (file_exists($inactive)) {
            unlink($inactive);
        }

    }

    public function action_manage_shortcode() {

        $action = $this->request(static::$action, 'index', ['store', 'update']);

        $shortcode = basename($this->request('name'));

        $status = FALSE;

        if ($action == 'store') {

            $active = sprintf('%s/../shortcodes/active/%s.php', __DIR__, $shortcode);
            $inactive = sprintf('%s/../shortcodes/inactive/%s.php', __DIR__, $shortcode);

            if (!file_exists($active) && !file_exists($inactive)) {

                // basename() => better paranoid than sorry
                $type = basename($this->request('type', 'inactive', ['active', 'inactive']));
                $content = $this->request('content', '');

                $file = sprintf('%s/../shortcodes/%s/%s.php', __DIR__, $type, $shortcode);
                file_put_contents($file, $content);

                $status = 'stored';

            } else {
                $status = 'already-exists';
            }

        }

        if ($action == 'update') {

            $original = basename($this->request('original-name'));

            if ($original != $shortcode) {
                $this->unlinkShortcode($original);
            } else {
                $this->unlinkShortcode($shortcode);
            }

            $active = sprintf('%s/../shortcodes/active/%s.php', __DIR__, $shortcode);
            $inactive = sprintf('%s/../shortcodes/inactive/%s.php', __DIR__, $shortcode);

            if (!file_exists($active) && !file_exists($inactive)) {

                $type = $this->request('type', 'inactive', ['active', 'inactive']);
                $content = $this->request('content', '');

                $file = sprintf('%s/../shortcodes/%s/%s.php', __DIR__, basename($type), $shortcode);
                file_put_contents($file, $content);

                $status = 'updated';

            } else {
                $status = 'already-exists';
            }

        }

        wp_redirect(admin_url('admin.php?page=' . static::$page . '&' . static::$action . '=index&status=' . $status));

        exit();

    }


}
