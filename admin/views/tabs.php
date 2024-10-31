<ul class="subsubsub">
    <?php foreach($tabs as $i => $tab) {
        if ($i) {
            echo '|';
        } ?>

        <li class="<?php echo esc_attr($tab['id']); ?>">
            <a href="?post_type=episode&page=settings&tab=<?php echo urlencode($tab['id']); ?>"<?php if ($tab['active']) { ?> class="current"<?php } ?>>
                <?php echo esc_html($tab['title']); ?>
            </a>
        </li>
    <?php } ?>
</ul>
