<?php
!defined('WPINC') && die;

define('FLUENTSUPPORTCUSTOMAI', 'fluent-support-custom-ai');
define('FLUENTSUPPORTCUSTOMAI_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FLUENTSUPPORTCUSTOMAI_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('FLUENTSUPPORTCUSTOMAI_PLUGIN_VERSION', '1.8.9');

spl_autoload_register(function ($class) {

    $match = 'FluentSupportCustomAI';
    if (!preg_match("/\b{$match}\b/", $class)) {
        return;
    }

    $path = plugin_dir_path(__FILE__);
    $file = str_replace(
        ['FluentSupportCustomAI', '\\', '/App/', 'Database'],
        ['', DIRECTORY_SEPARATOR, 'app/', 'database'],
        $class
    );
    require(trailingslashit($path) . trim($file, '/') . '.php');
});


class FLUENTSUPPORTCUSTOMAI_Dependency
{
    public function init()
    {
        $this->injectDependency();
    }

    /**
     * Notify the user about the FluentSupport dependency and instructs to install it.
     */
    protected function injectDependency()
    {
        add_action('admin_notices', function () {
            $pluginInfo = $this->getInstallationDetails();

            $class = 'notice notice-error';

            $install_url_text = 'Click Here to Install the Plugin';

            if ($pluginInfo->action == 'activate') {
                $install_url_text = 'Click Here to Activate the Plugin';
            }

            $message = 'Fluent Support SiteMigration  Requires Fluent Support Base Plugin, <b><a href="' . $pluginInfo->url
                . '">' . $install_url_text . '</a></b>';

            printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
        });
    }

    /**
     * Get the FluentSupport plugin installation information e.g. the URL to install.
     *
     * @return \stdClass $activation
     */
    protected function getInstallationDetails()
    {
        $activation = (object)[
            'action' => 'install',
            'url' => ''
        ];

        $allPlugins = get_plugins();

        if (isset($allPlugins['fluent-support/fluent-support.php'])) {
            $url = wp_nonce_url(
                self_admin_url('plugins.php?action=activate&plugin=fluent-support/fluent-support.php'),
                'activate-plugin_fluent-support/fluent-support.php'
            );

            $activation->action = 'activate';
        } else {
            $api = (object)[
                'slug' => 'fluent-support'
            ];

            $url = wp_nonce_url(
                self_admin_url('update.php?action=install-plugin&plugin=' . $api->slug),
                'install-plugin_' . $api->slug
            );
        }

        $activation->url = $url;

        return $activation;
    }
}

add_action('init', function () {
    if (!defined('FLUENT_SUPPORT_VERSION')) {
        (new FLUENTSUPPORTCUSTOMAI_Dependency())->init();
    }
});
