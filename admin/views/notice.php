<div class="<?php echo $kind; ?> notice">
    <?php if (isset($title)) { ?>
        <h2><?php echo esc_html($title); ?></h2>
    <?php }

    if (isset($body)) {
        echo wpautop(esc_html($body));
    }

    if (isset($cta) || isset($suppress)) { ?>
        <p>
            <?php if (isset($cta)) { ?>
                <a class="button button-primary" href="<?php echo esc_attr($cta['url']); ?>">
                    <?php echo esc_html($cta['title']); ?>
                </a>
            <?php } ?>
            <?php if (isset($suppress)) { ?>
                <a class="button button-secondary" href="<?php echo esc_attr($suppress['url']); ?>">
                    <?php echo esc_html($suppress['title']); ?>
                </a>
            <?php } ?>
        </p>
    <?php } ?>
</div>
