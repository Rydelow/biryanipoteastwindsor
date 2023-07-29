<?php
add_action( 'admin_enqueue_scripts', 'exwf_ocal_admin_scripts' );
function exwf_ocal_admin_scripts(){
    wp_enqueue_style('exwf-adm-ocal', EX_WOOFOOD_OCAL_PATH . 'admin/css/style.css','','1.0');
    wp_enqueue_script('exwf-adm-ocal-js', EX_WOOFOOD_OCAL_PATH . 'admin/js/admin.js', array( 'jquery' ),'1.0' );
}

add_action( 'cmb2_admin_init', 'exwf_ocal_register_setting_options', );
function exwf_ocal_register_setting_options() {
    /**
     * Registers main options page menu item and form.
     */
    $args = array(
        'id'           => 'exwf_ocal_options_page',
        'title'        => esc_html__('Orders Calendar','exwf-order-calendar'),
        'object_types' => array( 'options-page' ),
        'option_key'   => 'exwf_ocal_options',
        //'parent_slug'  => 'edit.php?post_type=product',
        'tab_group'    => 'exwf_ocal_options',
        'capability'    => 'manage_woocommerce',
        //'tab_title'    => esc_html__('General','woocommerce-food'),
        'message_cb'      => 'exwoofood_options_page_message_',
    );
    // 'tab_group' property is supported in > 2.4.0.
    if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
        $args['display_cb'] = 'exwoofood_options_display_with_tabs';
    }
    $exwf_ocal_options = new_cmb2_box( $args );
    $arr_methods = exwf_get_method_enable();
    $arr_meth = array();
    foreach ($arr_methods as $key => $value) {
        $user_odmethod = $value=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $value=='dinein' ? esc_html__('Dine-in','woocommerce-food') : esc_html__('Delivery','woocommerce-food'));
        $arr_meth[$value] = $user_odmethod;
    }
    $exwf_ocal_options->add_field( array(
        'name'       => esc_html__( 'Order Methods', 'exwf-order-calendar' ),
        'desc'       => esc_html__('Select only special Methods visible on calendar, default: all','exwf-order-calendar'),
        'id'         => 'exwf_ocal_method',
        'type'       => 'multicheck_inline',
        'select_all_button' => false,
        'default' => '',
        'options' => $arr_meth
    ) );
    $exwf_ocal_options->add_field( array(
        'name'       => esc_html__( 'Order Status', 'exwf-order-calendar' ),
        'desc'       => esc_html__('Select only special Status visible on calendar, default: all','exwf-order-calendar'),
        'id'         => 'exwf_ocal_status',
        'type'       => 'multicheck_inline',
        'select_all_button' => false,
        'default' => '',
        'options' => wc_get_order_statuses()
    ) );
    $exwf_ocal_options->add_field( array(
        'name'       => esc_html__( 'Show only upcoming date', 'exwf-order-calendar' ),
        'desc'       => esc_html__('Select yes to show only upcoming order date on calendar, default: all','exwf-order-calendar'),
        'id'         => 'exwf_ocal_upcom',
        'type'       => 'select',
        'default' => '',
        'options'          => array(
            '' => esc_html__( 'No', 'exwf-order-calendar' ),
            'yes'   => esc_html__( 'Yes', 'exwf-order-calendar' ),
        ),
        //'classes'        => 'column-2',
    ) );
    $exwf_ocal_options->add_field( array(
        'name'       => esc_html__( 'Enable Ical feed', 'exwf-order-calendar' ),
        'desc'       => esc_html__('If you want to sync order to google calendar, you can enable this option and add ical link to your google calendar','exwf-order-calendar'),
        'id'         => 'exwf_ocal_ical',
        'type'       => 'select',
        'default' => '',
        'options'          => array(
            '' => esc_html__( 'No', 'exwf-order-calendar' ),
            'yes'   => esc_html__( 'Yes', 'exwf-order-calendar' ),
        ),
        //'classes'        => 'column-2',
        'escape_cb' => 'exwf_ical_render_code_html',
    ) );
}
add_action( 'cmb2_after_form', 'exwf_ocal_admn_show_cal', );
function exwf_ical_render_code_html( $original_value, $args, $cmb2_field ) {
    if($original_value=='yes'){
        $vr_code = get_option('exwf_ical_code');
        if($vr_code==''){
            update_option('exwf_ical_code', rand(10000000,9999999999999999).'_'.exwf_generateRandomString());
        }
    }
    return $original_value;
}
function exwf_generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function exwf_ocal_admn_show_cal() {
    if(isset($_GET['page']) && $_GET['page']  == 'exwf_ocal_options'){
        echo '<h2>'.esc_html__('Orders Calendar','exwf-order-calendar').'</h2>';
        $ical = exwoofood_get_option('exwf_ocal_ical','exwf_ocal_options');
        echo do_shortcode('[exwf_order_calendar show_ical="'.($ical=='yes' ? 'yes' : '').'"]');
    }
    
}
// Ical in calendar
function exwf_ical_events() {
    $vr_code = get_option('exwf_ical_code');
    $ical_code = isset($_GET['code']) ? $_GET['code'] : '';
    if($vr_code=='' || $ical_code=='' || $ical_code != $vr_code){
        return;
    }
    if(isset($_GET['ical_orders']) && $_GET['ical_orders']=='yes'){
        // - start collecting output -
        $time_now =  strtotime("now");
        $gmt_offset = get_option('gmt_offset');
        if($gmt_offset!=''){
            $time_now = $time_now + ($gmt_offset*3600);
        }
        $arr_status = exwoofood_get_option('exwf_ocal_status','exwf_ocal_options');
        if(!is_array($arr_status) || is_array($arr_status) && empty($arr_status)){
            $arr_status = array_keys( wc_get_order_statuses() );
        }
        $args = array(
              'post_type' => 'shop_order',
              'posts_per_page' => -1,
              'meta_key' => 'exwfood_datetime_deli_unix',
              'post_status'       =>  $arr_status,
              'orderby' => 'meta_value_num',
              'meta_query' => array(
              array('key'  => 'exwfood_datetime_deli_unix',
                   'value' => $time_now,
                   'compare' => '>'),
              ),
              'suppress_filters' => false 
        );
        if(isset($_GET['location']) && $_GET['location']!=''){
            $location = $_GET['location'];
            $args['meta_query'][] =  array(
                'key'  => 'exwoofood_location',
                'value' => $location,
                'compare' => '='
            );
        }
        $arr_method = exwoofood_get_option('exwf_ocal_method','exwf_ocal_options');
        if(is_array($arr_method) && !empty($arr_method)){
            $args['meta_query'][] =  array(
                'key'  => 'exwfood_order_method',
                'value' => $arr_method,
                'compare' => 'IN',
            );
        }

        $the_query = get_posts( $args );
        if(!empty($the_query)){
            // - file header -
            header('Content-type: text/calendar');
            header('Content-Disposition: attachment; filename="'.esc_attr(get_bloginfo('name')).' - ical.ics"');
            // - content header -
            $content = "BEGIN:VCALENDAR\r\n";
            $content .= "VERSION:2.0\r\n";
            $content .= 'PRODID:-//'.get_bloginfo('name')."\r\n";
            $content .= "CALSCALE:GREGORIAN\r\n";
            $content .= "METHOD:PUBLISH\r\n";
            $content .= apply_filters( 'exwf_ical_cal_name', 'X-WR-CALNAME:'.get_bloginfo('name')."\r\n");// Remove this code to disable create new calendar outlook
            $content .= 'X-ORIGINAL-URL:'.home_url()."\r\n";
            $content .= 'X-WR-CALDESC:'.esc_attr(get_bloginfo('description'))."\r\n";
            foreach ( $the_query as $post ){
                $content .= exwf_ical_event_generate($post->ID);
            }
            $content .= "END:VCALENDAR\r\n";
            echo ($content);
        }else{
            echo esc_html__('No Orders Found','exwf-order-calendar');
        }
        exit;
    }else{ return;}
}
add_action('init','exwf_ical_events');
if(!function_exists('exwf_ical_event_generate')){
    function exwf_ical_event_generate($id){
        $startdate = get_post_meta($id,'exwfood_datetime_deli_unix', true );
        if($startdate==''){ return;}        
        $date_format = get_option('date_format');
        $hour_format = get_option('time_format');
        if($startdate){
            $startdate = gmdate("Ymd\THis", $startdate);// convert date ux
        }
        $enddate = get_post_meta($id,'exwfood_datetime_deli_unix', true );
        if($enddate){
            $enddate = gmdate("Ymd\THis", $enddate);
        }
        $gmts = get_gmt_from_date($startdate); // this function requires Y-m-d H:i:s, hence the back & forth.
        $gmts = strtotime($gmts);
        // - grab gmt for end -
        //$gmte = date('Y-m-d H:i:s', $conv_enddate);
        $gmte = get_gmt_from_date($enddate); // this function requires Y-m-d H:i:s, hence the back & forth.
        $gmte = strtotime($gmte);
        // - Set to UTC ICAL FORMAT -
        $stime = date('Ymd\THis', $gmts);
        $etime = date('Ymd\THis', $gmte);
        
        $gmt_offset = get_option('gmt_offset');
        $we_time_zone = $gmt_offset;
        $we_time_zone = $we_time_zone * 3600;
        $tz = timezone_name_from_abbr('', $we_time_zone, 0);
        if( $tz ==''){ 
            $tz = timezone_name_from_abbr('', ($we_time_zone + 1800), 0);
        }
        if($tz!=''){
            $tz = ';TZID='.$tz;
        }
        $title = wp_strip_all_tags(html_entity_decode(get_the_title($id), ENT_COMPAT, 'UTF-8'));
        // - item output -
        $order = wc_get_order($id);
        $order_method = get_post_meta( $id, 'exwfood_order_method', true );
        $order_method = $order_method=='takeaway' ? esc_html__('Takeaway','woocommerce-food') : ( $order_method=='dinein' ? esc_html__('Dine-in','woocommerce-food') : ( $order_method=='delivery' ? esc_html__('Delivery','woocommerce-food') : '') );
        $order_status = $order->get_status();
        $text_datedel = exwf_date_time_text('date',$order);
        $time_html = get_post_meta($id,'exwfood_time_deli', true );
        $dt_fm = date_i18n( $date_format, $startdate).' '.$time_html;
        $order_details = wp_strip_all_tags(esc_html__('Order Method: ','exwf-order-calendar').$order_method).'\n'.$text_datedel.': '.$dt_fm.'\n'.'URL:'.get_permalink($id).'\n'.esc_html__('Order Status: ','exwf-order-calendar').wc_get_order_status_name( $order_status );

        $content = '';
        $content .= "BEGIN:VEVENT\r\n";
        $content .= 'DTSTART'.$tz.':'.$startdate."\r\n";
        $content .= 'DTEND'.$tz.':'.$enddate."\r\n";
        $content .= 'SUMMARY:'.$title."\r\n";
        $content .= 'DESCRIPTION:'.$order_details."\r\n";
        $content .= 'URL:'.get_permalink($id)."\r\n";
        $content .= 'LOCATION:'.get_post_meta($id, 'we_adress', true )."\r\n";
        $content .= "END:VEVENT\r\n";
        return $content;
    }
}