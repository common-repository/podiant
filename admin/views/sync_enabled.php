<hr>
<p>
    <a href="<?php echo wp_nonce_url(admin_url('edit.php?post_type=episode&page=settings&process=disable_sync'), 'process'); ?>" class="button">
        <?php _e('Disable automatic episode syncing', 'disable sync button', 'podiant'); ?>
    </a>
</p>
