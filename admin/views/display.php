<form method="post" novalidate>
    <h3><?php _e('Player Style', 'podiant'); ?></h3>

    <table class="form-table" role="presentation">
        <tr>
            <th>
                <label for="player_style_list"><?php _e('Episode Lists', 'podiant'); ?></label>
            </th>
            <td>
                <select name="player_style_list" id="player_style_list">
                    <option value="default"<?php if ($player_style_list === 'default') { echo ' selected'; } ?>><?php _e('Default', 'podiant'); ?></option>
                    <option value="mini"<?php if ($player_style_list === 'mini') { echo ' selected'; } ?>><?php _e('Mini player', 'podiant'); ?></option>
                    <option value="button"<?php if ($player_style_list === 'button') { echo ' selected'; } ?>><?php _e('Button', 'podiant'); ?></option>
                </select>

                <?php if (isset($errors['player_style_list'])) { ?>
                    <div class="error notice">
                        <p><?php echo esc_html($errors['player_style_list']); ?></p>
                    </div>
               <?php } ?>
            </td>
        </tr>

        <tr>
            <th>
                <label for="player_style_single"><?php _e('Single Episodes', 'podiant'); ?></label>
            </th>
            <td>
                <select name="player_style_single" id="player_style_single">
                    <option value="default"<?php if ($player_style_single === 'default') { echo ' selected'; } ?>><?php _e('Default', 'podiant'); ?></option>
                    <option value="mini"<?php if ($player_style_single === 'mini') { echo ' selected'; } ?>><?php _e('Mini player', 'podiant'); ?></option>
                    <option value="button"<?php if ($player_style_single === 'button') { echo ' selected'; } ?>><?php _e('Button', 'podiant'); ?></option>
                </select>

                <?php if (isset($errors['player_style_single'])) { ?>
                    <div class="error notice">
                        <p><?php echo esc_html($errors['player_style_single']); ?></p>
                    </div>
               <?php } ?>
            </td>
        </tr>

        <tr>
            <th>
                <label for="player_position"><?php _e('Playser Position', 'podiant'); ?></label>
            </th>
            <td>
                <select name="player_position" id="player_position">
                    <option value="none"<?php if (!$player_position) { echo ' selected'; } ?>><?php _e('None (No Player)', 'podiant'); ?></option>
                    <option value="top"<?php if ($player_position === 'top') { echo ' selected'; } ?>><?php _e('Before Post Content', 'podiant'); ?></option>
                    <option value="bottom"<?php if ($player_position === 'bottom') { echo ' selected'; } ?>><?php _e('After Post Content', 'podiant'); ?></option>
                </select>

                <?php if (isset($errors['player_position'])) { ?>
                    <div class="error notice">
                        <p><?php echo esc_html($errors['player_position']); ?></p>
                    </div>
               <?php } ?>
            </td>
        </tr>
    </table>

    <h3><?php _e('Site Settings', 'podiant'); ?></h3>

    <table class="form-table" role="presentation">
        <tr>
            <th>
                <label for="main_query"><?php _e('Include in Post List', 'podiant'); ?></label>
            </th>
            <td>
                <label>
                    <input name="main_query" id="main_query" type="checkbox" value="on"<?php if ($main_query) { echo 'checked'; } ?>>
                    <?php _e('Include episodes in the list of posts on the homepage', 'podiant'); ?>
                </label>
            </td>
        </tr>
    </table>

    <div class="submit">
        <input type="submit" name="submit" class="button button-primary" value="<?php _e('Save Changes', 'save changes button', 'podiant'); ?>">
    </div>

    <?php wp_nonce_field('podiant_settings_update', 'podiant_settings'); ?>
</form>
