<?php
  $customlink = EX_WPFood_customlink(get_the_ID());
  global $number_excerpt,$img_size,$hide_atc;
  if($img_size==''){$img_size = 'exwoofood_400x400';}
  $custom_price = get_post_meta( get_the_ID(), 'exwoofood_custom_price', true );
  $price = exwoofood_price_with_currency();
  if ($custom_price != '') {
    $price = $custom_price;
  }
?>
<figure class="exstyle-2 tppost-<?php the_ID();?>">
  <div class="exstyle-2-image">
    <a class="exfd_modal_click" href="<?php echo esc_url($customlink); ?>">
      <?php echo get_the_post_thumbnail(get_the_ID(),$img_size);
      exwf_icon_color(); ?>
    </a>
    <?php exwoofood_sale_badge(); ?>
  </div><figcaption>
    <h3><a class="exfd_modal_click" href="<?php echo esc_url($customlink); ?>"><?php the_title(); ?></a></h3>
    <h5>
      <span><?php echo wp_kses_post($price);?></span>
    </h5>
    <?php 
      if(has_excerpt(get_the_ID())){
        echo '<div class="exwf-shdes">';
        if($number_excerpt=='full'){
          $excerpt = get_the_excerpt();
          ?><p><?php echo wp_kses_post($excerpt); ?></p><?php
        }else if($number_excerpt!='0'){
          $excerpt = wp_trim_words(get_the_excerpt(),$number_excerpt,'...');
          ?><p><?php echo wp_kses_post($excerpt); ?></p><?php
        }
        echo '</div>';
      }
      do_action('exwf_sc_after_shortdes');
      exwoofood_booking_button_html(2,$hide_atc);
    ?>
    
  </figcaption>
</figure>