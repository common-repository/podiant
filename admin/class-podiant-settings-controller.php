<?php

/**
 * Frontend view and backend handler for plugin settings.
 *
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 */

require_once dirname(__FILE__) . '/../includes/class-podiant-validation-exception.php';

/**
 * Frontend view and backend handler for plugin settings.
 *
 * This class defines the display and handling of the plugin's settings form
 *
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/includes
 * @author Mark Steadman <mark@podiant.co>
 */
class Podiant_Settings_Controller {
    /**
     * Handles GET and POST requests.
     *
     * @since 1.0
     */
    public function dispatch() {
        if (isset($_POST['submit'])) {
            return $this->post();
        }

        return $this->get();
    }

    /**
     * Renders the settings view with the supplied variables.
     *
     * @since 1.0
     */
    private function render($context=array()) {
        if (!podiant_option('sync_enabled')) {
            include dirname(__FILE__) . '/views/sync_disabled.php';
            return;
        }

        echo '<div class="wrao">';
        echo '<h2>' . __('Podcast Settings', 'settings page title', 'podiant') . '</h2>';

        $messages = (isset($context['messages']) && is_array($context['messages'])) ? $context['messages'] : array();
        foreach($messages as $message) { ?>
            <div class="<?php echo esc_attr($message['kind']); ?> notice">
                <?php if (isset($message['title'])) { ?>
                    <h2><?php echo esc_html($message['title']); ?></h2>
                <?php } ?>

                <p><?php echo esc_html($message['body']); ?></p>
            </div>
        <?php }

        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'display';
        $tabs = array(
            array(
                'id' => 'display',
                'title' => __('Display', 'podiant'),
                'active' => $tab === 'display'
            ),
            array(
                'id' => 'podcasts',
                'title' => __('Podcasts', 'podiant'),
                'active' => $tab === 'podcasts'
            ),
            array(
                'id' => 'permalinks',
                'title' => __('Permalinks', 'podiant'),
                'active' => $tab === 'permalinks'
            )
        );

        include dirname(__FILE__) . '/views/tabs.php';
        unset($tabs);

        extract($context);
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'display';

        echo '<div style="clear: left;"></div>';
        if (file_exists(dirname(__FILE__) . "/views/{$tab}.php")) {
            include dirname(__FILE__) . "/views/{$tab}.php";
        } else {
            echo '<p>Tab not found.</p>';
        }

        echo '</div>';
        include dirname(__FILE__) . '/views/sync_enabled.php';
    }

    /**
     * Shows the settings form for GET requests, or passes POSTs to the
     * post method.
     *
     * @since 1.0
     */
    private function get($defaults=array()) {
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'display';
        $context = array_merge(array(), $defaults);
        $plugin = podiant_plugin();

        switch ($tab) {
            case 'display':
                $context['player_style_list'] = $plugin->option('player_style_list');
                $context['player_style_single'] = $plugin->option('player_style_single');
                $context['player_position'] = $plugin->option('player_position');
                $context['main_query'] = $plugin->option('main_query');
                break;

            case 'podcasts':
                $context['podcasts'] = Podiant_Podcast::list();
                break;

            case 'permalinks':
                $context['episodes_prefix'] = isset($_POST['episodes_prefix']) ? sanitize_text_field($_POST['episodes_prefix']) : $plugin->option('episodes_prefix');
                $context['podcasts_prefix'] = isset($_POST['podcasts_prefix']) ? sanitize_text_field($_POST['podcasts_prefix']) : $plugin->option('podcasts_prefix');
                $context['podcast_count'] = Podiant_Podcast::count();
                break;
        }

        return $this->render($context);
    }

    /**
     * Checks that pull keys are supplied.
     *
     * @since 1.0
     * @param array $data The data to validate.
     * @return mixed
     */
    private function validate_pull_key($data) {
        $errors = array();
        $pull_key = isset($data['pull_key']) ? $data['pull_key'] : '';

        if (!$pull_key) {
            $errors['pull_key'] = _x('This field is required.', 'field required', 'podiant');
        }

        if (count($errors)) {
            return $errors;
        }

        return true;
    }

    /**
     * Processes form submissions.
     *
     * @since 1.0
     */
    private function post($defaults=array()) {
        if (!isset($_POST['podiant_settings']) || !wp_verify_nonce($_POST['podiant_settings'], 'podiant_settings_update')) {
            include dirname(__FILE__) . '/views/bad-nonce.php';
            exit;
        }

        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'display';
        switch ($tab) {
            case 'display':
                return $this->update_display_prefs();
                break;

            case 'podcasts':
                return $this->update_podcasts();
                break;

            case 'permalinks':
                return $this->update_permalinks();
                break;
        }

        wp_die($tab);
    }

    /**
     * Updates display preferences.
     *
     * @since 1.0
     */
    private function update_display_prefs() {
        $fields = array(
            'player_style_list',
            'player_style_single',
            'player_position'
        );

        $plugin = podiant_plugin();
        $errors = array();
        $cleaned_data = array();

        foreach($fields as $field) {
            try {
                $cleaned_data[$field] = $plugin->validate_option($field, isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : null);
            } catch (Exception $ex) {
                if (is_a($ex, 'Podiant_Validation_Exception')) {
                    $errors[$field] = $ex->getMessage();
                } else {
                    wp_die($ex);
                }
            }
        }

        if (count($errors)) {
            return $this->get(
                array('errors' => $errors)
            );
        }

        foreach($cleaned_data as $field => $value) {
            $plugin->option($field, $value, false);
        }

        $main_query = isset($_POST['main_query']) && sanitize_text_field($_POST['main_query']) === 'on';
        $plugin->option('main_query', $main_query, false);

        return $this->get(
            array(
                'messages' => array(
                    array(
                        'kind' => 'success',
                        'body' => __('Display preferences updated', 'podiant')
                    )
                )
            )
        );
    }

    /**
     * Updates permalink preferences.
     *
     * @since 1.0
     */
    private function update_permalinks() {
        $fields = array(
            'episodes_prefix',
            'podcasts_prefix'
        );

        $plugin = podiant_plugin();
        $errors = array();
        $cleaned_data = array();

        foreach($fields as $field) {
            try {
                $cleaned_data[$field] = $plugin->validate_option($field, isset($_POST[$field]) ? sanitize_text_field($_POST[$field]) : null);
            } catch (Exception $ex) {
                if (is_a($ex, 'Podiant_Validation_Exception')) {
                    $errors[$field] = $ex->getMessage();
                } else {
                    wp_die($ex);
                }
            }
        }

        if (isset($cleaned_data['episodes_prefix']) && isset($cleaned_data['podcasts_prefix'])) {
            if ($cleaned_data['episodes_prefix'] == $cleaned_data['podcasts_prefix']) {
                if (Podiant_Podcast::count() > 1) {
                    $errors['episodes_prefix'] = __('Episodes and podcasts cannot share the same permalink structure.', 'podiant');
                } else {
                    $errors['episodes_prefix'] = __('This is a reserved value.', 'podiant');
                }
            }
        }

        if (count($errors)) {
            return $this->get(
                array('errors' => $errors)
            );
        }

        $changed = false;
        foreach($cleaned_data as $field => $value) {
            if ($value !== $plugin->option($field)) {
                $changed = true;
                $plugin->option($field, $value, false);
            }
        }

        if ($changed) {
            podiant_do_action_async('flush_rewrite_rules');
        }

        return $this->get(
            array(
                'messages' => array(
                    array(
                        'kind' => 'success',
                        'body' => __('Display preferences updated', 'podiant')
                    )
                )
            )
        );
    }

    /**
     * Updates podcast settings.
     *
     * @since 1.0
     */
    private function update_podcasts() {
        $pull_key = sanitize_text_field($_POST['pull_key']);
        $errors = $this->validate_pull_key(
            array('pull_key' => $pull_key)
        );

        if ($errors !== true) {
            return $this->get(
                array('errors' => $errors)
            );
        }

        $api = new Podiant_API($pull_key);

        try {
            $response = $api->get();
        } catch (Exception $ex) {
            return $this->get(
                array(
                    'errors' => array(
                        'pull_key' => $ex->getMessage()
                    )
                )
            );
        }

        $attrs = $response['attributes'];
        $podcast = Podiant_Podcast::create(
            array(
                'id' => $attrs['podcast-id'],
                'name' => $attrs['podcast-name'],
                'pull_key' => $pull_key
            )
        );

        return $this->get(
            array(
                'messages' => array(
                    array(
                        'kind' => 'success',
                        'body' => _x("{$podcast->name} has been added to your installation. Episodes will be synced shortly.", 'pull key added successfully message', 'podiant')
                    )
                )
            )
        );
    }
}
