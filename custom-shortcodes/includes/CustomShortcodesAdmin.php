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
            add_action('wp_ajax_ftcs_validate_name', [$this, 'action_validate_name']);

        }

	}

    protected function shortcodesList($type) {

        $shortcodes = glob($this->plugin->getShortcodeDir($type) . '/*.php');

        usort($shortcodes, function ($a, $b) {
            return strcmp(basename($a), basename($b));
        });

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
                    'preview' => admin_url('admin.php?page=' . static::$page . '&' . static::$action . '=preview&shortcode=' . $name),
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

        $action = $this->request(static::$action, 'index', ['index', 'create', 'edit', 'preview', 'delete', 'activate', 'deactivate']);

        $status = FALSE;

        $shortcode = basename($this->request('shortcode'));

        if (in_array($action, ['delete', 'activate', 'deactivate'])) {

            if ($action == 'delete') {

                $this->unlinkShortcode($shortcode);
                $status = 'deleted';

            }

            if ($action == 'activate') {

                $from = $this->plugin->getShortcodePath('inactive', $shortcode);
                $to = $this->plugin->getShortcodePath('active', $shortcode);

                if (file_exists($from)) {
                    rename($from, $to);
                    $status = 'activated';
                } else {
                    $status = 'not-found';
                }

            }

            if ($action == 'deactivate') {

                $from = $this->plugin->getShortcodePath('active', $shortcode);
                $to = $this->plugin->getShortcodePath('inactive', $shortcode);

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
                'name' => $shortcode,
                'type' => 'inactive',
                'content' => '',
            ],
            'shortcodes' => [
                'active' => [],
                'inactive' => [],
            ],
            'code' => '',
        ];

        if ($action == 'edit') {

            $active = $this->plugin->getShortcodePath('active', $shortcode);
            $inactive = $this->plugin->getShortcodePath('inactive', $shortcode);

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

        if ($action == 'preview') {

            // Temporary register an inactive shortcode for preview
            $inactive = $this->plugin->getShortcodePath('inactive', $shortcode);
            if (file_exists($inactive)) {
                $this->plugin->registerShortcode($inactive);
            }

            $code = $this->request('code');
            $params['code'] = is_null($code) ? sprintf('[%s]', $shortcode) : $code;

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

        $active = $this->plugin->getShortcodePath('active', $shortcode);
        if (file_exists($active)) {
            unlink($active);
        }

        $inactive = $this->plugin->getShortcodePath('inactive', $shortcode);
        if (file_exists($inactive)) {
            unlink($inactive);
        }

    }

    protected function santize($string) {
        $string = preg_replace('#[^a-z0-9\-_]#si', ' ', $string);
        $string = trim(preg_replace('#\s+#si', ' ', $string));
        $string = str_replace(' ', '-', $string);
        return $string;
    }

    public function action_validate_name() {

        $shortcode = basename($this->request('name'));
        $original_name = basename($this->request('original-name'));
        $action = basename($this->request(static::$action, 'store', ['store', 'update']));

        $sanitized = $this->santize($shortcode);

        $result = [
            'valid' => TRUE,
            'message' => 'Valid name',
            'sanitized' => $sanitized,
        ];

        // If is a new shortcode or it's renaming an existing one...
        if (($action == 'store') || ($shortcode != $original_name)) {

            $active = $this->plugin->getShortcodePath('active', $shortcode);
            $inactive = $this->plugin->getShortcodePath('inactive', $shortcode);
            if (file_exists($active) || file_exists($inactive)) {
                $result = [
                    'valid' => FALSE,
                    'message' => 'WARNING: Shortcode already present!',
                    'sanitized' => $sanitized,
                ];
            }
        }

        echo(json_encode($result));
        wp_die();

    }

    public function action_manage_shortcode() {

        $_POST = stripslashes_deep($_POST);
        $_GET = stripslashes_deep($_GET);

        $action = $this->request(static::$action, 'index', ['store', 'update', 'download']);

        $shortcode = basename($this->request('name'));

        $status = FALSE;

        if ($action == 'download') {

            include_once(ABSPATH . '/wp-admin/includes/class-pclzip.php');

            $zip = get_temp_dir() . '/custom-short-codes.zip';

            if (file_exists($zip)) {
                unlink($zip);
            }

            $archive = new PclZip($zip);

            $folders = [
                'active' => $this->plugin->getShortcodeDir('active'),
                'inactive' => $this->plugin->getShortcodeDir('inactive'),
            ];

            foreach ($folders as $folder) {
                $files = glob($folder . '/*.php');
                foreach ($files as $file) {
                    $archive->add($file, PCLZIP_OPT_REMOVE_PATH, dirname($file), PCLZIP_OPT_ADD_PATH, basename(dirname($file)));
                }
            }

            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="custom-short-codes.zip"');
            readfile($zip);
            unlink($zip);

            exit();

        }

        if ($action == 'store') {

            $active = $this->plugin->getShortcodePath('active', $shortcode);
            $inactive = $this->plugin->getShortcodePath('inactive', $shortcode);

            if (!file_exists($active) && !file_exists($inactive)) {

                // basename() => better paranoid than sorry
                $type = basename($this->request('type', 'inactive', ['active', 'inactive']));
                $content = $this->request('content', '');

                $file = $this->plugin->getShortcodePath($type, $shortcode);
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

            $active = $this->plugin->getShortcodePath('active', $shortcode);
            $inactive = $this->plugin->getShortcodePath('inactive', $shortcode);

            if (!file_exists($active) && !file_exists($inactive)) {

                $type = $this->request('type', 'inactive', ['active', 'inactive']);
                $content = $this->request('content', '');

                $file = $this->plugin->getShortcodePath(basename($type), $shortcode);
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
