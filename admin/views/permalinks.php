<form method="post" novalidate>
    <table class="form-table" role="presentation">
        <tr>
            <th>
                <label for="episodes_prefix"><?php _e('Episode Prefix', 'podiant'); ?></label>
            </th>
            <td>
                <input name="episodes_prefix" id="episodes_prefix" value="<?php echo esc_attr($episodes_prefix); ?>">

                <?php if (isset($errors['episodes_prefix'])) { ?>
                    <div class="error notice">
                        <p><?php echo esc_html($errors['episodes_prefix']); ?></p>
                    </div>
                <?php } ?>

                <p>
                    <?php _e('This forms the basis of all episode-related URLs, for example:', 'podiant'); ?><br>
                    <code>/<?php echo esc_html($episodes_prefix); ?>/episode-four-a-new-hope/</code>
                </p>
            </td>
        </tr>

        <?php if ($podcast_count > 1) { ?>
            <tr>
                <th>
                    <label for="podcasts_prefix"><?php _e('Podcast Prefix', 'podiant'); ?></label>
                </th>
                <td>
                    <input name="podcasts_prefix" id="podcasts_prefix" value="<?php echo esc_attr($podcasts_prefix); ?>">

                    <?php if (isset($errors['podcasts_prefix'])) { ?>
                        <div class="error notice">
                            <p><?php echo esc_html($errors['podcasts_prefix']); ?></p>
                        </div>
                    <?php } ?>

                    <p>
                        <?php _e('This forms the basis of all podcast-related URLs, for example:', 'podiant'); ?><br>
                        <code>/<?php echo esc_html($podcasts_prefix); ?>/the-startrek-show/</code>,
                        <code>/<?php echo esc_html($podcasts_prefix); ?>/the-starwars-show/</code>
                    </p>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php if ($podcast_count < 1) { ?>
        <input name="podcasts_prefix" value="<?php echo esc_attr($podcasts_prefix); ?>" type="hidden">
    <?php } ?>

    <div class="submit">
        <input type="submit" name="submit" class="button button-primary" value="<?php _e('Save Changes', 'save changes button', 'podiant'); ?>">
    </div>

    <?php wp_nonce_field('podiant_settings_update', 'podiant_settings'); ?>
</form>
