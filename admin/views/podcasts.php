<?php
/**
 * @link https://podiant.co/
 * @since 1.0
 * @package Podiant
 * @subpackage Podiant/admin
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!count($podcasts)) { ?>
    <div class="error notice" style="margin: 0 0 15px 0;">
        <h2><?php _e('You don&rsquo;t have any podcasts setup.', 'no pull key notice', 'podiant'); ?></h2>
        <p><?php _e('The good news is, it&rsquo;s easy to get setup.', 'no pull key setup intro', 'podiant'); ?></p>
        <ol>
            <li>
                <?php printf(
                    _x('Login to your %sPodiant dashboard%s and select your podcast.', 'pull key setup step 1', 'podiant'),
                    '<a href="https://app.podiant.co/" target="_blank">',
                    '</a>'
                ); ?>
            </li>
            <li>
                <?php _e('Select <i>Apps and Integrations</i> from the menu.', 'pull key setup step 2', 'podiant'); ?>
            </li>
            <li>
                <?php _e('From the <i>Automation</i> section, click the text labeled "Pull key". This will copy a special key into your clipboard.', 'pull key setup step 3', 'podiant'); ?>
            </li>
            <li>
                <?php _e('Paste your pull key into the box below.', 'pull key setup step 4', 'podiant'); ?>
            </li>
        </ol>
    </div>

    <form method="post" novalidate>
        <label class="podiant-settings-label">
            <span class="screen-reader-text"><?php _e('Pull Key', 'pull key', 'podiant'); ?></span>
            <input name="pull_key" type="password" placeholder="aBcd1e2fghiJ3KLm4Np5pq67RSTUVw89XYZaBcdeFGhiJ01kLmnOpQRStuVwxYZa" size="64" class="regular-text" required>
        </label>

        <?php if (isset($errors['pull_key'])) { ?>
           <div class="error notice">
               <p>
                   <strong><?php _e('Pull Key', 'pull key', 'podiant'); ?>:</strong>
                   <?php echo esc_html($errors['pull_key']); ?>
               </p>
           </div>
       <?php } ?>

        <input type="submit" name="submit" class="button button-primary" value="<?php _e('Add Podcast', 'add podcast button', 'podiant'); ?>">

        <p>
            <?php _e('If you want to add multiple podcasts, you&rsquo;ll be able to do that shortly.', 'pull key multiple podcast note', 'podiant'); ?>
        </p>

        <?php wp_nonce_field('podiant_settings_update', 'podiant_settings'); ?>
    </form>
<?php } else { ?>
    <table class="wp-list-table widefat fixed striped podcasts">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-id"><span><?php _e('ID', 'id', 'podiant'); ?></span></th>
                <th scope="col" class="manage-column column-blogname column-primary"><span><?php _e('Podcast name', 'podcast name', 'podiant'); ?></span></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($podcasts as $podcast) { ?>
                <tr>
                    <td class="id">
                        <?php echo esc_html($podcast->id); ?>.podiant.co
                    </td>
                    <td class="blogname">
                        <a href="//<?php echo esc_html($podcast->id); ?>.podiant.co/admin/" target="_blank">
                            <?php echo esc_html($podcast->name); ?>
                        </a>
                    </td>
                </li>
            <?php } ?>
        </tbody>
    </table>

    <hr>

    <form method="post" novalidate>
        <h2><?php _e('Add Another Podcast', 'add another podcast', 'podiant'); ?></h2>
        <p><?php _e('To get the Pull Key for another podcast:', 'additional pull key setup intro', 'podiant'); ?></p>

        <ol>
            <li>
                <?php printf(
                    _x('Login to your %sPodiant dashboard%s and select the next podcast you want to add.', 'pull key addition step 1', 'podiant'),
                    '<a href="https://app.podiant.co/" target="_blank">',
                    '</a>'
                ); ?>
            </li>
            <li>
                <?php _e('Select <i>Apps and Integrations</i> from the menu.', 'pull key addition step 2', 'podiant'); ?>
            </li>
            <li>
                <?php _e('From the <i>Automation</i> section, click the text labeled "Pull key". This will copy a special key into your clipboard.', 'pull key addition step 3', 'podiant'); ?>
            </li>
            <li>
                <?php _e('Paste your pull key into the box below.', 'pull key addition step 4', 'podiant'); ?>
            </li>
        </ol>

        <label class="podiant-settings-label">
            <span class="screen-reader-text"><?php _e('Pull Key', 'pull key', 'podiant'); ?></span>
            <input name="pull_key" type="password" placeholder="aBcd1e2fghiJ3KLm4Np5pq67RSTUVw89XYZaBcdeFGhiJ01kLmnOpQRStuVwxYZa" size="64" class="regular-text" required>
        </label>

        <?php if (isset($errors['pull_key'])) { ?>
           <div class="error notice">
               <p>
                   <strong><?php _e('Pull Key', 'pull key', 'podiant'); ?>:</strong>
                   <?php echo esc_html($errors['pull_key']); ?>
               </p>
           </div>
       <?php } ?>

        <input type="submit" name="submit" class="button button-primary" value="<?php _e('Add Podcast', 'add podcast button', 'podiant'); ?>">

        <p>
            <?php _e('If you want to add multiple podcasts, you&rsquo;ll be able to do that shortly.', 'pull key multiple podcast note', 'podiant'); ?>
        </p>

        <?php wp_nonce_field('podiant_settings_update', 'podiant_settings'); ?>
    </form>
<?php }
