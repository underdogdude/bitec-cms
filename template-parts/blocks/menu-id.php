<?php 
    $menu_id = get_field("menu_id");
    $custom_class = isset($block['className']) ? $block['className'] : '';
?>
<div class="block-nav-menu _desktop <?php echo esc_attr($custom_class); ?>">
    <?php 
        wp_nav_menu( array( 'menu' => $menu_id ) );
    ?>
</div>
<div class="block-nav-menu _mobile <?php echo esc_attr($custom_class); ?>" id="mobile_menu">
    <?php 
        wp_nav_menu( array( 'menu' => $menu_id ) );
    ?>
</div>