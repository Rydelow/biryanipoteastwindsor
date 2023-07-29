<?php
if(!function_exists('saturnwp_password_form')){
	/**
	 * Modify password form
	 *
     * @return string new HTML
     */
    function saturnwp_password_form() {
        //copy of function get_the_password_form() from \wp-includes\post-template.php ~1570
        //with small changes
        return
            '<form action="' . esc_url( home_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
            <p class="inputs"><input name="post_password" type="password" size="20" placeholder="' . esc_attr__( 'password', 'saturnwp' ) . '" /><input type="submit" name="Submit" value="' . esc_attr__( 'Submit', 'saturnwp' ) . '" /></p>
            </form>
            ';
    }
}



if(!function_exists('saturnwp_custom_password_form')){
	/**
	 * Print password page template
	 *
     * @return string HTML
     */
    function saturnwp_custom_password_form() {
        //we get template to buffer and return it so other filters can do something with it
        ob_start();
        get_template_part('password-template');
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
add_filter( 'the_password_form', 'saturnwp_custom_password_form');



if(!function_exists('saturnwp_custom_password_form')){
	/**
	 * Print password page template
	 *
     * @return string HTML
     */
    function saturnwp_custom_password_form() {
        //we get template to buffer and return it so other filters can do something with it
        ob_start();
        get_template_part('password-template');
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
}
add_filter( 'the_password_form', 'saturnwp_custom_password_form');



if(!function_exists('saturnwp_add_password_form_to_template')) {
    function saturnwp_add_password_form_to_template( $content ) {
        return $content . saturnwp_password_form();
    }
}