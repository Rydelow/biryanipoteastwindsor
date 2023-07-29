<?php global $saturnwp_a13; ?>
<?php if(!empty($saturnwp_a13->get_option('customslide')) || is_customize_preview() ) { ?>
<div class="slidercus">
<?php echo do_shortcode($saturnwp_a13->get_option('customslide')); ?>
</div> 
<?php } ?>