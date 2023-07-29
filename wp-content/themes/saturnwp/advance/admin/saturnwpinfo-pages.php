<?php
function saturnwp_sktwb_info() {
	saturnwp_theme_pages_header();

	global $saturnwp_a13;
	echo '<h2>'.esc_html__( 'What\'s next?', 'saturnwp' ).'</h2>';
	//check for companion plugin
	if( saturnwp_is_companion_plugin_ready( esc_html__( 'This Theme requires an additional plugin before you will be able to use it. ', 'saturnwp' ) ) ){
		echo '<p class="import_text">'.esc_html__( 'Import your SaturnWP Template to complete the installation.', 'saturnwp' ).
		     ' <a class="button import_button" href="'.esc_url( admin_url( 'admin.php?page=skt_template_directory' ) ).'">'.esc_html__( 'Go to SKT Templates', 'saturnwp').'</a>'.
		     '</p>';

		echo '<p>'.esc_html__( 'SaturnWP theme options help you with making your site beautiful.', 'saturnwp' ).
		     ' <a class="button" href="'.esc_url( admin_url( 'customize.php') ).'">'.esc_html__( 'Go to Customizer', 'saturnwp').'</a>'.
		     '</p>';
	}

	saturnwp_theme_pages_footer();
}

function saturnwp_sktwb_help() {
	saturnwp_theme_pages_header();
	global $saturnwp_a13;
	?>

	<h2><?php echo esc_html__( 'Where to get help?', 'saturnwp' ); ?></h2>

	<h3 class="center"><a href="<?php echo esc_url('https://www.sktthemesdemo.net/documentation/saturnwp-doc');?>" target="_blank"><?php echo esc_html__( 'Online Documentation', 'saturnwp' ); ?></a></h3>
	<p><?php echo
		esc_html__( 'Online documentation is always most up to date if it comes to explaining how to work with the theme. It will come handy as the first source when you are trying to work out problematic topics.', 'saturnwp' );
		?></p>

	<h2><?php echo esc_html__( 'Theme requirements:', 'saturnwp' ); ?></h2>
	<div class="feature-section one-col">
		<div class="col">
			<?php saturnwp_theme_requirements_table(); ?>
		</div>
	</div>

	<?php
	saturnwp_theme_pages_footer();
}

function saturnwp_theme_pages_header(){
	if(!current_user_can('install_plugins')){
		wp_die(esc_html__('Sorry, you are not allowed to access this page.', 'saturnwp'));
	}
	$pages = array(
		'info' => esc_html__( 'Info', 'saturnwp' ),
		'help' => esc_html__( 'Get Help', 'saturnwp' ),
	);

	//check for current tab
	$current_subpage = isset( $_GET['subpage'] ) ? sanitize_text_field( wp_unslash( $_GET['subpage'] ) ) : 'info';
?>
<div class="wrap sktwb-page <?php echo esc_attr( $current_subpage ); ?> about-wrap">
	<h1><?php
		echo sprintf( esc_html__( 'Welcome to SaturnWP Theme', 'saturnwp' ), esc_html( SATURNWP_OPTIONS_NAME_PART ) );
		?></h1>

	<div class="about-text">
		<?php echo esc_html__( 'Thanks for using SaturnWP! We are glad that you have decided to use SaturnWP theme. We hope it will help you with making your site beautiful!', 'saturnwp' ); ?><br />
	</div>
	<h2 class="nav-tab-wrapper wp-clearfix">
		<?php
		foreach($pages as $subpage => $title){
			$query_args = array(
				'page' => 'saturnwpinfopage',
				'subpage' => $subpage
			);

			$is_current = $current_subpage === $subpage;

			echo '<a href="'.esc_url( add_query_arg( $query_args, admin_url( 'themes.php') ) ).'" class="nav-tab'.esc_attr( $is_current ? ' nav-tab-active' : '').'">'.esc_html( $title ).'</a>';
		}
		?>
	</h2>
	<?php
}

function saturnwp_theme_pages_footer(){
	echo '</div>';
}