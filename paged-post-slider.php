<?php
/*
Plugin Name: Paged Post Slider
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Automagically turns multi-page posts into an ajax-based slideshow. Simply activate, choose the display options for your slider, and go! For best results, please be sure that the single.php file in your theme does <strong>not</strong> contain the <em>wp_link_pages</em> tag.
Version: 1.5.2
Author: Josiah Spence
Author URI: josiahspence.com
License: WTFPL
*/

//Enqueue Scripts and Styles
function paged_post_scripts() {
	if(is_single() || is_page() ){
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-paged-post',plugins_url( 'paged-post.js' , __FILE__ ), 'jquery', '', true);
		$pps_options_array = array( 'scroll_up' => get_option( 'pps_scroll_up') );
		wp_localize_script( 'jquery-paged-post', 'pps_options_object', $pps_options_array );
		if(get_option( 'pps_style_sheet')){
			wp_enqueue_style('paged-post-style',plugins_url( 'paged-post.css' , __FILE__ ));
		}
	}
}

add_action( 'wp_enqueue_scripts', 'paged_post_scripts' ); // wp_enqueue_scripts action hook to link only on the front-end

//Add NextPage Button to TinyMCE
function paged_post_tinymce($mce_buttons) {
	$pos = array_search('wp_more', $mce_buttons, true);
	if ($pos !== false) {
		$buttons = array_slice($mce_buttons, 0, $pos + 1);

		$buttons[] = 'wp_page';

		$mce_buttons = array_merge($buttons, array_slice($mce_buttons, $pos + 1));
	}
	return $mce_buttons;
}

add_filter('mce_buttons', 'paged_post_tinymce');

//Set defaults to wp_link_pages
function paged_post_link_pages($r) {
	$args = array(
		'before'			=> '',
		'after'				=> '',
		'next_or_number'	=> 'next',
		'nextpagelink'		=> __('<span class="pps-next">Next</span>'),
		'previouspagelink'	=> __('<span class="pps-prev">Previous</span>'),
		'echo' => 0
	  );
	  return wp_parse_args($args, $r);

}
add_filter('wp_link_pages_args','paged_post_link_pages');

// Add wrapper and nav to the_content
function paged_post_the_content_filter( $content ) {

	global $multipage, $numpages, $page;

	//Show Full Post If Full Post Option
	if($_GET['pps'] == 'full_post'){
		global $post;
		$ppscontent .= wpautop($post->post_content);
		if(get_option( 'pps_show_all_link')){
			$ppscontent .=  '<p class="pps-fullpost-link"><a href="'.get_permalink().'" title="View as Slideshow">View as Slideshow</a></p>';
		}

	//Else Show Slideshow
	} else {

		//If is Paginated, Work Slideshow Magic
		if ( (is_single() && $multipage) || (is_page() && $multipage) ){

			$sliderclass = 'pagination-slider';
			$slidecount = '<span class="pps-slide-count">'.$page.' of '.$numpages.'</span>';
			if($page == $numpages){
				$slideclass = 'pps-last-slide';
			} elseif ($page == 1){
				$slideclass = 'pps-first-slide';
			} else{
				$slideclass = 'pps-middle-slide';
			}

			//What to Display For Content
			$ppscontent = '<div class="pps-wrap-content"><div class="pps-the-content '.$slideclass.'">';

			//Top Slider Navigation
			if((get_option( 'pps_nav_position' ) == 'top')||(get_option( 'pps_nav_position' ) == 'both')){
				$ppscontent .= '<nav class="pps-slider-nav pps-clearfix">';

				$ppscontent .= wp_link_pages();

				// If Loop Option Selected, Loop back to Beginning
				if(get_option( 'pps_loop_slides')){
					if($page == $numpages){
						$ppscontent .= '<a href="'.get_permalink().'"><span class="pps-next">Next</span></a>';
					}
				}

				// Top Slide Counter
				if((get_option( 'pps_count_position' ) == 'top')||(get_option( 'pps_count_position' ) == 'both')){
					$ppscontent .= $slidecount;
				}

				$ppscontent .= '</nav>';
			}

			//Top Slide Counter Without Top Nav
			if(((get_option( 'pps_count_position' ) == 'top')||(get_option( 'pps_count_position' ) == 'both')) && ((get_option( 'pps_nav_position' ) != 'top')&&(get_option( 'pps_nav_position' ) != 'both'))){
					$ppscontent .= $slidecount;
			}

			// Slide Content
			$ppscontent .= '<div class="pps-content pps-clearfix">'.$content.'</div>';

			// Bottom Slider Navigation
			if((get_option( 'pps_nav_position' ) == 'bottom')||(get_option( 'pps_nav_position' ) == 'both')){
				$ppscontent .= '<nav class="pps-slider-nav pps-bottom-nav pps-clearfix">';
				$ppscontent .= wp_link_pages();

				// If Loop Option Selected, Loop back to Beginning
				if(get_option( 'pps_loop_slides')){
					if($page == $numpages){
						$ppscontent .= '<a href="'.get_permalink().'"><span class="pps-next">Next</span></a>';
					}
				}

				// Bottom Slide Counter
				if((get_option( 'pps_count_position' ) == 'bottom')||(get_option( 'pps_count_position' ) == 'both')){
					$ppscontent .= $slidecount;
				}

				$ppscontent .= '</nav>';
			}

			// Bottom Slide Counter Without Bottom Nav
			if(((get_option( 'pps_count_position' ) == 'bottom')||(get_option( 'pps_count_position' ) == 'both')) && ((get_option( 'pps_nav_position' ) != 'bottom')&&(get_option( 'pps_nav_position' ) != 'both'))){
					$ppscontent .= $slidecount;
				}

			// End Slider Div
			$ppscontent .= '</div></div>';

			// Show Full Post Link
			if(get_option( 'pps_show_all_link')){
				$ppscontent .=  '<p class="pps-fullpost-link"><a href="'.add_query_arg( 'pps', 'full_post', get_permalink() ).'" title="View Full Post">View Full Post</a></p>';
			}

		// Else It Isn't Pagintated, Don't Show Slider
		} else {
			$ppscontent .= $content;
			}
	}
	// Returns the content.
	return $ppscontent;
}

add_filter( 'the_content', 'paged_post_the_content_filter' );

//Plugin Settings Page
//First use the add_action to add onto the WordPress menu.
add_action('admin_menu', 'pps_add_options');
//Make our function to call the WordPress function to add to the correct menu.
function pps_add_options() {
	add_options_page('Paged Post Slider Options', 'Paged Post Slider', 'manage_options', 'ppsoptions', 'pps_options_page');
}

function pps_options_page() {
	// variables for the field and option names
	$opt_name = array('nav_position' =>'pps_nav_position',
					'count_position' => 'pps_count_position',
					'style_sheet' => 'pps_style_sheet',
					'show_all_link' => 'pps_show_all_link',
					'loop_slides' => 'pps_loop_slides',
					'scroll_up' => 'pps_scroll_up');
	$hidden_field_name = 'pps_submit_hidden';

	// Read in existing option value from database
	$opt_val = array('nav_position' => get_option( $opt_name['nav_position'] ),
				'count_position' => get_option( $opt_name['count_position'] ),
				'style_sheet' => get_option( $opt_name['style_sheet'] ),
				'show_all_link' => get_option( $opt_name['show_all_link'] ),
				'loop_slides' => get_option( $opt_name['loop_slides'] ),
				'scroll_up' => get_option( $opt_name['scroll_up'] ));

	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if(isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
		// Read their posted value
		$opt_val = array('nav_position' => $_POST[ $opt_name['nav_position'] ],
					'count_position' => $_POST[ $opt_name['count_position'] ],
					'style_sheet' => $_POST[ $opt_name['style_sheet'] ],
					'show_all_link' => $_POST[ $opt_name['show_all_link'] ],
					'loop_slides' => $_POST[ $opt_name['loop_slides'] ],
					'scroll_up' => $_POST[ $opt_name['scroll_up'] ]);

		// Save the posted value in the database
		update_option( $opt_name['nav_position'], $opt_val['nav_position'] );
		update_option( $opt_name['count_position'], $opt_val['count_position'] );
		update_option( $opt_name['style_sheet'], $opt_val['style_sheet'] );
		update_option( $opt_name['show_all_link'], $opt_val['show_all_link'] );
		update_option( $opt_name['loop_slides'], $opt_val['loop_slides'] );
		update_option( $opt_name['scroll_up'], $opt_val['scroll_up'] );

		// Put an options updated message on the screen
		?>
		<div id="message" class="updated fade">
			<p><strong>
				<?php _e('Options saved.', 'pps_trans_domain' ); ?>
			</strong></p>
		</div>
	<?php
		}

	//Options Form
	?>
	<div class="wrap">
		<h2><?php _e( 'Paged Post Slider Options', 'pps_trans_domain' ); ?></h2>

		<form name="pps_img_options" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

			<table class="form-table">
				<tbody>

					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $opt_name['nav_position']; ?>">Slider Navigation Position:</label>
						</th>
						<td>
							<select name="<?php echo $opt_name['nav_position']; ?>">
								<option value="top" <?php echo ($opt_val['nav_position'] == "top") ? 'selected="selected"' : ''; ?> >Top</option>
								<option value="bottom" <?php echo ($opt_val['nav_position'] == "bottom") ? 'selected="selected"' : ''; ?> >Bottom</option>
								<option value="both" <?php echo ($opt_val['nav_position'] == "both") ? 'selected="selected"' : ''; ?> >Both</option>
							</select>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $opt_name['count_position']?>">Slider Count (e.g. "2 of 4") Position:</label>
						</th>
						<td>
							<select name="<?php echo $opt_name['count_position']; ?>">
								<option value="top" <?php echo ($opt_val['count_position'] == "top") ? 'selected="selected"' : ''; ?> >Top</option>
								<option value="bottom" <?php echo ($opt_val['count_position'] == "bottom") ? 'selected="selected"' : ''; ?> >Bottom</option>
								<option value="both" <?php echo ($opt_val['count_position'] == "both") ? 'selected="selected"' : ''; ?> >Both</option>
								<option value="none" <?php echo ($opt_val['count_position'] == "none") ? 'selected="selected"' : ''; ?> >Do Not Display</option>
							</select>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $opt_name['style_sheet']?>">Use Style Sheet?</label>
						</th>
						<td>
								<input name="<?php echo $opt_name['style_sheet']; ?>" type="checkbox" value="1" <?php checked( '1', $opt_val['style_sheet'] ); ?> /> If unchecked, no styles will be added.
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $opt_name['show_all_link']?>">Display link to <em>View Full Post</em>?</label>
						</th>
						<td>
								<input name="<?php echo $opt_name['show_all_link']; ?>" type="checkbox" value="1" <?php checked( '1', $opt_val['show_all_link'] ); ?> /> If unchecked, the <em>View Full Post</em> link will not be displayed.
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $opt_name['loop_slides']?>">Loop slides?</label>
						</th>
						<td>
								<input name="<?php echo $opt_name['loop_slides']; ?>" type="checkbox" value="1" <?php checked( '1', $opt_val['loop_slides'] ); ?> /> Creates an infinite loop of the slides.
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">
							<label for="<?php echo $opt_name['scroll_up']?>">Scroll to top of page after slide load?</label>
						</th>
						<td>
								<input name="<?php echo $opt_name['scroll_up']; ?>" type="checkbox" value="1" <?php checked( '1', $opt_val['scroll_up'] ); ?> /> Scrolls up to the top of the page after each slide loads.
						</td>
					</tr>

				</tbody>
			</table>

			<p>
				<input type="submit" class="button button-primary" value="Save Settings">
			</p>

		</form>


<?php }

//Add Settings Link To Plugins Page
function pps_plugin_meta($links, $file) {
	$plugin = plugin_basename(__FILE__);
	// create link
	if ($file == $plugin) {
		return array_merge(
			$links,
			array( sprintf( '<a href="options-general.php?page=ppsoptions">Settings</a>', $plugin, __('Settings') ) )
		);
	}
	return $links;
}

add_filter( 'plugin_row_meta', 'pps_plugin_meta', 10, 2 );
?>