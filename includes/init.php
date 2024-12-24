<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include required files
include_once(plugin_dir_path(__FILE__) . 'settings-structure.php');
include_once(plugin_dir_path(__FILE__) . 'render-field.php');

class APL_Quick_View_Settings
{
    private $settings;

    public function __construct()
    {
        $this->settings = algo_wc_get_settings_structure();
        // Add WordPress hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_notices', array($this, 'admin_notices'));
    }

    // Add menu in admin dashboard
    public function add_admin_menu()
    {
        add_menu_page(
            __('APL Quick View Settings', 'apl-quick-view'),
            __('APL Quick View', 'apl-quick-view'),
            'manage_options',
            'apl-quick-view-settings',
            array($this, 'settings_page'),
            'dashicons-visibility',
            40
        );
    }

    // Render the settings page
    public function settings_page()
    {
        // Ensure the user has the right capability
        if (!current_user_can('manage_options')) {
            wp_die(message: esc_attr_e('You are not allowed to access this page.', 'apl-quick-view'));
        }
        $active_tab = 'general';
        if (
            isset($_GET['_wpnonce']) && 
            wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'apl_quick_view_settings_action') && 
            isset($_GET['tab'])
        ) {
            $active_tab = sanitize_key(wp_unslash($_GET['tab']));
        }
        ?>
        <div class="wrap algo-quick-view-wrap">
            <h2><?php esc_attr_e('APL Quick View', 'apl-quick-view'); ?></h2>            
            <h2 class="nav-tab-wrapper">
                <?php foreach ($this->settings as $tab_key => $tab_data): ?>
                    <a href="<?php echo esc_url(wp_nonce_url(
                        add_query_arg(
                            array(
                                'page' => 'apl-quick-view-settings',
                                'tab' => $tab_key,
                            )
                        ),
                        'apl_quick_view_settings_action' // Action name for nonce
                    )); ?>" class="nav-tab<?php echo $active_tab === $tab_key ? ' nav-tab-active' : ''; ?>">
                        <?php echo esc_html($tab_data['title']); ?>
                    </a>
                <?php endforeach; ?>
            </h2>

            <form method="post" action="options.php">
                <?php
                settings_fields("algo_wc_{$active_tab}_settings");
                do_settings_sections("algo_wc_{$active_tab}_settings");
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    // Register settings and sections dynamically
    public function register_settings()
    {
        foreach ($this->settings as $tab_key => $tab_data) {
            // Ensure settings group name is correct
            $settings_group = "algo_wc_{$tab_key}_settings";

            // Register each setting individually
            foreach ($tab_data['fields'] as $field_key => $field_data) {
                register_setting(
                    $settings_group, // Settings group name
                    $field_key,      // Option name
                    array(           // Arguments (optional, but recommended)
                        'type' => $field_data['data_type'] ? $field_data['data_type'] : 'string',             // Data type
                        'sanitize_callback' => $field_data['sanitize_callback'] ? $field_data['sanitize_callback'] : 'sanitize_text_field', // Sanitization callback
                        'default' => $field_data['default'] ? $field_data['default'] : ""                // Default value
                    )
                );
            }

            // Add a settings section
            add_settings_section(
                "{$settings_group}_section",
                $tab_data['title'],
                '__return_null',
                $settings_group
            );

            // Add settings fields
            foreach ($tab_data['fields'] as $field_key => $field_data) {
                $title = $field_data['title'];
                if ($field_data['type'] === "heading") {
                    $title = '<h3 class="awp-wc-admin-heading">' . $field_data['title'] . '</h3>';
                }
                add_settings_field(
                    $field_key,
                    $title, //$field_data['title'],
                    function () use ($field_data, $field_key) {
                        $field_data['id'] = $field_key;
                        awp_wc_render_field($field_data);
                    },
                    $settings_group,
                    "{$settings_group}_section",
                    array(
                        'id' => $field_key,
                        'type' => isset($field_data['type']) ? $field_data['type'] : 'text',
                        'options' => isset($field_data['options']) ? $field_data['options'] : array(),
                        'default' => isset($field_data['default']) ? $field_data['default'] : '',
                    )
                );
            }
        }
    }

    // Display admin notices
    public function admin_notices()
    {
        // Ensure the user has the right capability
        if (!current_user_can('manage_options')) {
            wp_die(esc_attr_e('You are not allowed to access this page.', 'apl-quick-view'));
        }
        if (
            isset($_GET['_wpnonce']) &&
            wp_verify_nonce(sanitize_text_field(wp_unslash($_GET['_wpnonce'])), 'apl_quick_view_settings_action') &&
            isset($_GET['settings-updated'])
        ) {
            add_settings_error(
                'algo_wc_messages',
                'algo_wc_message',
                __('Settings have been updated successfully.', 'apl-quick-view'),
                'updated'
            );
        }
        settings_errors('algo_wc_messages');
    }
}

// Instantiate the class
if (is_admin()) {
    new APL_Quick_View_Settings();
}