<?php
if(!function_exists('exwf_query_by_menu_loca')){
    function exwf_query_by_menu_loca($args){
    	$loc = WC()->session->get( 'ex_userloc' );
    	if($loc!=''){
    		if(!isset($args['tax_query']) || !is_array($args['tax_query'])){
				$args['tax_query'] = array();
			}
			$args['tax_query']['relation'] = 'AND';
			$args['tax_query'][] = 
		        array(
		            'taxonomy' => 'exwoofood_loc',
		            'field'    => 'slug',
		            'terms'    => $loc,
		    );
		}
        return $args;
     }
}
add_filter( 'exwoofood_query', 'exwf_query_by_menu_loca',21 );
add_filter( 'exwf_ajax_query_args', 'exwf_query_by_menu_loca',21 );
add_filter( 'exwf_ajax_filter_query_args', 'exwf_query_by_menu_loca',21 );