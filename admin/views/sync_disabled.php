<div class="notice" style="margin-left: 0; max-width: 40%; min-width: 320px;">
    <p>
        <?php _e('This plugin can automatically pull episodes of one or more Podiant podcasts, and add them to the WordPress database. Episodes are kept separate from your blog posts, but you can add them to the main homepage loop (the page that displays your latest blog posts) if you like.', '', 'podiant'); ?>
    </p>
</div>

<p>
    <a href="<?php echo wp_nonce_url(admin_url('edit.php?post_type=episode&page=settings&process=enable_sync&tab=podcasts'), 'process'); ?>" class="button button-primary">
        <?php _e('Enable automatic episode syncing', 'enable sync button', 'podiant'); ?>
    </a>
</p>
