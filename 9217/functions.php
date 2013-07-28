<?php
// global variable
define("MENU_TOP","Top");
 
// page slug
define("HOME_PAGE","home");
define("MORE_THAN_JUST_LOGO","more-than-just-logo");
define("MULTIPLE_WAYS_TO_WIN","multiple-ways-to-win");
define("UNIQUE_COMPETITION_MODEL","unique-competition-model");
define("BIG_CLIENTS","big-clients");

define("COMPETITOR_BONUSES","competitor-bonuses");
define("REFERRAL_BONUS","referral-bonus");
define("NEW_MEMBER_BONUS","new-member-bonus");

/* Page ID of 'help' */
define("TOPIC_ID",get_ID_by_slug('help'));
// categories
define("HELP","help");
define("HELP_URL",get_permalink(get_ID_by_slug('help')));

// post type
define("VIDEO","video");
define("DOWNLOAD","download");

// feed 
$latestNewsPerPage = get_option("omicronLatestNewsPerPage") != null ? get_option("omicronLatestNewsPerPage") : 3;

define("LATEST_NEWS_MAX",$latestNewsPerPage);

/**
 * function to sort by description
 */
function sortByDescription($c1, $c2) {
	return ($c1->description > $c2->description);
}

/* get category id */
function getCategoryId($slug) {
  $idObj = get_category_by_slug($slug); 
  $id = $idObj->term_id;
  return $id;
} 

/* get category by slug */
function getCategoryNameBySlug($slug){
	return (get_term_by('slug',$slug,'category')->name);
}

function new_excerpt_more($more) {
    global $post;
	return ' ... <br /><a class="readmore" href="'. get_permalink($post->ID) . '">(More...)</a>';
}
add_filter('excerpt_more', 'new_excerpt_more');

function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {	
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}

function get_ID_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}

function get_post_ID_by_slug($post_slug) {
	global $wpdb;
    $id = $wpdb->get_var("SELECT ID FROM ".$wpdb->posts." WHERE post_name = '$post_slug' AND post_status='publish';");
	return $id;
}

//[example1]
function example1_func( $atts ){
 return "<img src='".get_bloginfo('stylesheet_directory')."/i/content-image/example1.png"."' alt='' />";
}
//[example2]
function example2_func( $atts ){
 return "<img src='".get_bloginfo('stylesheet_directory')."/i/content-image/example2.png"."' alt='' />";
}
//[new_member_bonus_milestone]
function new_member_bonus_milestone_func( $atts ){
 return "<img src='".get_bloginfo('stylesheet_directory')."/i/content-image/new-member-bonus1.png"."' class='newMemberBonus' alt='' width='315' />";
}
//[new_member_bonus_placement]
function new_member_bonus_placement_func( $atts ){
 return "<img src='".get_bloginfo('stylesheet_directory')."/i/content-image/new-member-bonus2.png"."' class='newMemberBonus' alt='' width='315'  />";
}

function register_shortcodes(){ 
	add_shortcode( 'example1', 'example1_func' );
	add_shortcode( 'example2', 'example2_func' );
	add_shortcode( 'new_member_bonus_milestone', 'new_member_bonus_milestone_func' );
	add_shortcode( 'new_member_bonus_placement', 'new_member_bonus_placement_func' );
	register_taxonomy_for_object_type('post_tag', 'page');
}
add_action( 'init', 'register_shortcodes');


function sortArrMenu($arrMenu,$activeParentLevel1,$activeParentLevel2,$pageId) {
	$arrMenuTemp;
	$arrMenuLeveling;
	$parentMenuIndex;
	$i=0;
	if($arrMenu!=null)
	foreach($arrMenu as $menu) {
		$arrMenuTemp[$menu->ID] = $menu;
	}
	if($arrMenu!=null)
	foreach($arrMenu as $menu) {
		$menuLevel = 0;
		$menuId = $menu->ID;
		if($menu->menu_item_parent!=0) {
			$parentId = $menu->menu_item_parent;
			if($arrMenuSorted!=null) {
				if(array_key_exists($parentId, $arrMenuSorted)) {
					$menuLevel = $arrMenuSorted[$parentId]->level + 1;
				}
			}
			else if(array_key_exists($parentId, $arrMenuTemp)) {
				$menuLevel = 1;
			}
		}
		$menu->level=$menuLevel;
		$arrMenuSorted[$menuId] = $menu;
	}
	$arrMenuLeveling;
	if($arrMenuSorted!=null) {
		foreach($arrMenuSorted as $menu) {
			$menuId = $menu->ID;
			if($menu->level==0) {
				$arrMenuLeveling[$menuId] = $menu;
				$lastMenuId = $menuId;
			}
			else if($menu->level==1) {
				$arrMenuLeveling[$lastMenuId]->child[$menuId] = $menu;
				$secondLastMenuId = $menuId;
				if($menu->object_id==$pageId)  { 
					$activeParentLevel2 = $menu->menu_item_parent;
					$activeParentLevel1 = $arrMenuSorted[$activeParentLevel2]->ID;
				}
			}else if($menu->level==2) {
				$arrMenuLeveling[$lastMenuId]->child[$secondLastMenuId]->child[$menuId] = $menu;
				if($menu->object_id==$pageId)  { 
					$activeParentLevel2 = $menu->menu_item_parent;
					$activeParentLevel1 = $arrMenuSorted[$activeParentLevel2]->ID;
				}
			}	
		}
	}
	return $arrMenuLeveling;
}	

/**
 * 
 * get page link
 * @param string $path
 */
function get_page_link_by_path($path) {
    $p = get_page_by_path($path);
    if ($p == NULL) {
        return '#';
    } else {
        return get_page_link($p->ID);
    }
}
/* get category by slug */
function get_category_id($cat_name){
	$term = get_term_by('slug', $cat_name, 'category');
	return $term->term_id;
}
/* get category slug */
function get_category_slug($cat_id){
	$term = get_term_by('term_id', $cat_id, 'category');
	return $term->slug;
}


function get_help_breadcumb(){
	global $post;
	krsort($post->ancestors);
	foreach ( $post->ancestors  as $pID ){
		$page = get_page($pID);
		if ( $pID != TOPIC_ID ) // if not 'HELP'
		echo "<a class='breadcrump' href='".get_permalink($page)."'>".get_page($pID)->post_title."</a>";
	}	
}

function generate_tabs(){
	global $post;
	$parentID = $post->post_parent;
	$depth = count($post->ancestors);
	/* if current page is as main topic,so the parent is itselp */
	$parentID = ( $parentID == TOPIC_ID ) ? $post->ID : $parentID;
	/* if depth > 2, then force to parent upper level */
	$parentID = ( $depth > 2 ) ? $post->ancestors[$depth-2] : $parentID;
	
	$args = array(
		'order'=> 'ASC',
		'post_parent' => $parentID,
		'post_type' => 'page'
	);
	$ret['active_tab'] =  ( $parentID == TOPIC_ID ) ? $parentID : $post->ID;
	
	/* if depth > 2, then force to parent upper level */
	$ret['active_tab'] =  ( $depth <= 2 ) ? $ret['active_tab'] : $post->ancestors[$depth-3];
	
	/* debug 
	print_r($post->ancestors);
	echo 'depth:'.$depth."<br/>";
	echo 'parent:'.$parentID."<br/>";
	echo 'active:'.$ret['active_tab']."<br/>";
	*/
	
	/* force to add to returned data */
	$firstTab = get_page($parentID);
	$ret['tabs'][$parentID]->ID = $firstTab->ID;
	$ret['tabs'][$parentID]->post_title = $firstTab->post_title;
	$ret['tabs'] = array_merge($ret['tabs'],get_children($args));
	return $ret;
}

/* wrap content */
function wrap_content($string,$length=160){
	 return substr($string,0,$length).((strlen($string)>$length)?" ...":"");
}

/**
 * wrap content to $len length content, and add '...' to end of wrapped conent
 */
function wrap_content_strip_html($content, $len, $strip_html = false, $sp = '\n\r', $ending = '...') {
	if ($strip_html) {
		$content = strip_tags($content);
	}
	$c_title_wrapped = wordwrap($content, $len, $sp);
	$w_title = explode($sp, $c_title_wrapped);
    if (strlen($content) <= $len) { $ending = ''; }
	return $w_title[0].$ending;
}


add_theme_support( 'post-thumbnails');

/**
 * add concept costom post type
 */
add_action('init', 'create_post_types');

add_post_type_support( 'page', 'excerpt' );

/**
 * function to create project custom post type and cutomer custom post type
 */
function create_post_types() {
	
	// Video
    register_post_type( VIDEO,
        array(
            'labels' => array(
                'name' => __( 'Video' ),
                'singular_name' => __( 'Video' ),
        		'add_new' => _x('Add New', 'Video'),
        		'add_new_item' => __('Add New Video'),
        		'edit_item' => __('Edit Video'),
        		'new_item' => __('new Video'),
        		'view_item' => __('View Video'),
        		'search_item' => __('Search Video'),
        		'not_found' => __('No Video found'),
        		'menu_name' => __('Video')
            ),
            'public' => true,
            'has_archive' => true,
            'taxonomies' => array('post_tag','category'),
            'supports' => array('title','editor','author','thumbnail','excerpt','comments','custom-field','page-attributes','revision')
        )
    );
	
	// Download
    register_post_type( DOWNLOAD,
        array(
            'labels' => array(
                'name' => __( 'Download' ),
                'singular_name' => __( 'Download' ),
        		'add_new' => _x('Add New', 'Download'),
        		'add_new_item' => __('Add New Download'),
        		'edit_item' => __('Edit Download'),
        		'new_item' => __('new Download'),
        		'view_item' => __('View Download'),
        		'search_item' => __('Search Download'),
        		'not_found' => __('No Download found'),
        		'menu_name' => __('Download')
            ),
            'public' => true,
            'has_archive' => true,
            'taxonomies' => array('post_tag','category'),
            'supports' => array('title','editor','author','thumbnail','excerpt','comments','custom-field','page-attributes','revision')
        )
    );

    flush_rewrite_rules( false );
}


function get_cookie(){
	global $_COOKIE;
	#$_COOKIE['main_user_id_1'] = '22760600|2c3a1c1487520d9aaf15917189d5864';
	$hid = explode("|",$_COOKIE['main_tcsso_1']);
	$handleName = $_COOKIE['handleName'];
	//print_r($hid);
	$hname = explode("|",$_COOKIE['direct_sso_user_id_1']);
	$meta->handle_id = $hid[0];	
	$meta->handle_name = $handleName;
	return $meta;

}


function view_count_inc($postId) {
	$viewCountKey = "view_count";
	$viewCount = get_post_meta($postId,$viewCountKey,true);
	if($viewCount==null) {
		add_post_meta($postId, $viewCountKey, '1');
	}else {
		$viewCount++;
		update_post_meta($postId, $viewCountKey, $viewCount);
	}
}



if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h2 class="widgettitle">',
        'after_title' => '</h2>',
    ));

function kubrick_head() {
	$head = "<style type='text/css'>\n<!--";
	$output = '';
	if ( kubrick_header_image() ) {
		$url =  kubrick_header_image_url() ;
		$output .= "#header { background: url('$url') no-repeat bottom center; }\n";
	}
	if ( false !== ( $color = kubrick_header_color() ) ) {
		$output .= "#headerimg h1 a, #headerimg h1 a:visited, #headerimg .description { color: $color; }\n";
	}
	if ( false !== ( $display = kubrick_header_display() ) ) {
		$output .= "#headerimg { display: $display }\n";
	}
	$foot = "--></style>\n";
	if ( '' != $output )
		echo $head . $output . $foot;
}

add_action('wp_head', 'kubrick_head');

function kubrick_header_image() {
	return apply_filters('kubrick_header_image', get_option('kubrick_header_image'));
}

function kubrick_upper_color() {
	if (strpos($url = kubrick_header_image_url(), 'header-img.php?') !== false) {
		parse_str(substr($url, strpos($url, '?') + 1), $q);
		return $q['upper'];
	} else
		return '69aee7';
}

function kubrick_lower_color() {
	if (strpos($url = kubrick_header_image_url(), 'header-img.php?') !== false) {
		parse_str(substr($url, strpos($url, '?') + 1), $q);
		return $q['lower'];
	} else
		return '4180b6';
}

function kubrick_header_image_url() {
	if ( $image = kubrick_header_image() )
		$url = get_template_directory_uri() . '/images/' . $image;
	else
		$url = get_template_directory_uri() . '/images/kubrickheader.jpg';

	return $url;
}

function kubrick_header_color() {
	return apply_filters('kubrick_header_color', get_option('kubrick_header_color'));
}

function kubrick_header_color_string() {
	$color = kubrick_header_color();
	if ( false === $color )
		return 'white';

	return $color;
}

function kubrick_header_display() {
	return apply_filters('kubrick_header_display', get_option('kubrick_header_display'));
}

function kubrick_header_display_string() {
	$display = kubrick_header_display();
	return $display ? $display : 'inline';
}

add_action('admin_menu', 'kubrick_add_theme_page');

function kubrick_add_theme_page() {
	if ( $_GET['page'] == basename(__FILE__) ) {
		if ( 'save' == $_REQUEST['action'] ) {
			check_admin_referer('kubrick-header');
			if ( isset($_REQUEST['njform']) ) {
				if ( isset($_REQUEST['defaults']) ) {
					delete_option('kubrick_header_image');
					delete_option('kubrick_header_color');
					delete_option('kubrick_header_display');
				} else {
					if ( '' == $_REQUEST['njfontcolor'] )
						delete_option('kubrick_header_color');
					else {
						$fontcolor = preg_replace('/^.*(#[0-9a-fA-F]{6})?.*$/', '$1', $_REQUEST['njfontcolor']);
						update_option('kubrick_header_color', $fontcolor);
					}
					if ( preg_match('/[0-9A-F]{6}|[0-9A-F]{3}/i', $_REQUEST['njuppercolor'], $uc) && preg_match('/[0-9A-F]{6}|[0-9A-F]{3}/i', $_REQUEST['njlowercolor'], $lc) ) {
						$uc = ( strlen($uc[0]) == 3 ) ? $uc[0]{0}.$uc[0]{0}.$uc[0]{1}.$uc[0]{1}.$uc[0]{2}.$uc[0]{2} : $uc[0];
						$lc = ( strlen($lc[0]) == 3 ) ? $lc[0]{0}.$lc[0]{0}.$lc[0]{1}.$lc[0]{1}.$lc[0]{2}.$lc[0]{2} : $lc[0];
						update_option('kubrick_header_image', "header-img.php?upper=$uc&lower=$lc");
					}

					if ( isset($_REQUEST['toggledisplay']) ) {
						if ( false === get_option('kubrick_header_display') )
							update_option('kubrick_header_display', 'none');
						else
							delete_option('kubrick_header_display');
					}
				}
			} else {

				if ( isset($_REQUEST['headerimage']) ) {
					check_admin_referer('kubrick-header');
					if ( '' == $_REQUEST['headerimage'] )
						delete_option('kubrick_header_image');
					else {
						$headerimage = preg_replace('/^.*?(header-img.php\?upper=[0-9a-fA-F]{6}&lower=[0-9a-fA-F]{6})?.*$/', '$1', $_REQUEST['headerimage']);
						update_option('kubrick_header_image', $headerimage);
					}
				}

				if ( isset($_REQUEST['fontcolor']) ) {
					check_admin_referer('kubrick-header');
					if ( '' == $_REQUEST['fontcolor'] )
						delete_option('kubrick_header_color');
					else {
						$fontcolor = preg_replace('/^.*?(#[0-9a-fA-F]{6})?.*$/', '$1', $_REQUEST['fontcolor']);
						update_option('kubrick_header_color', $fontcolor);
					}
				}

				if ( isset($_REQUEST['fontdisplay']) ) {
					check_admin_referer('kubrick-header');
					if ( '' == $_REQUEST['fontdisplay'] || 'inline' == $_REQUEST['fontdisplay'] )
						delete_option('kubrick_header_display');
					else
						update_option('kubrick_header_display', 'none');
				}
			}
			//print_r($_REQUEST);
			wp_redirect("themes.php?page=functions.php&saved=true");
			die;
		}
		add_action('admin_head', 'kubrick_theme_page_head');
	}
	add_theme_page(__('Customize Header'), __('Header Image and Color'), 'edit_themes', basename(__FILE__), 'kubrick_theme_page');
}

function kubrick_theme_page_head() {
?>
<script type="text/javascript" src="../wp-includes/js/colorpicker.js"></script>
<script type='text/javascript'>
// <![CDATA[
	function pickColor(color) {
		ColorPicker_targetInput.value = color;
		kUpdate(ColorPicker_targetInput.id);
	}
	function PopupWindow_populate(contents) {
		contents += '<br /><p style="text-align:center;margin-top:0px;"><input type="button" value="<?php echo attribute_escape(__('Close Color Picker')); ?>" onclick="cp.hidePopup(\'prettyplease\')"></input></p>';
		this.contents = contents;
		this.populated = false;
	}
	function PopupWindow_hidePopup(magicword) {
		if ( magicword != 'prettyplease' )
			return false;
		if (this.divName != null) {
			if (this.use_gebi) {
				document.getElementById(this.divName).style.visibility = "hidden";
			}
			else if (this.use_css) {
				document.all[this.divName].style.visibility = "hidden";
			}
			else if (this.use_layers) {
				document.layers[this.divName].visibility = "hidden";
			}
		}
		else {
			if (this.popupWindow && !this.popupWindow.closed) {
				this.popupWindow.close();
				this.popupWindow = null;
			}
		}
		return false;
	}
	function colorSelect(t,p) {
		if ( cp.p == p && document.getElementById(cp.divName).style.visibility != "hidden" )
			cp.hidePopup('prettyplease');
		else {
			cp.p = p;
			cp.select(t,p);
		}
	}
	function PopupWindow_setSize(width,height) {
		this.width = 162;
		this.height = 210;
	}

	var cp = new ColorPicker();
	function advUpdate(val, obj) {
		document.getElementById(obj).value = val;
		kUpdate(obj);
	}
	function kUpdate(oid) {
		if ( 'uppercolor' == oid || 'lowercolor' == oid ) {
			uc = document.getElementById('uppercolor').value.replace('#', '');
			lc = document.getElementById('lowercolor').value.replace('#', '');
			hi = document.getElementById('headerimage');
			hi.value = 'header-img.php?upper='+uc+'&lower='+lc;
			document.getElementById('header').style.background = 'url("<?php echo get_template_directory_uri(); ?>/images/'+hi.value+'") center no-repeat';
			document.getElementById('advuppercolor').value = '#'+uc;
			document.getElementById('advlowercolor').value = '#'+lc;
		}
		if ( 'fontcolor' == oid ) {
			document.getElementById('header').style.color = document.getElementById('fontcolor').value;
			document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value;
		}
		if ( 'fontdisplay' == oid ) {
			document.getElementById('headerimg').style.display = document.getElementById('fontdisplay').value;
		}
	}
	function toggleDisplay() {
		td = document.getElementById('fontdisplay');
		td.value = ( td.value == 'none' ) ? 'inline' : 'none';
		kUpdate('fontdisplay');
	}
	function toggleAdvanced() {
		a = document.getElementById('jsAdvanced');
		if ( a.style.display == 'none' )
			a.style.display = 'block';
		else
			a.style.display = 'none';
	}
	function kDefaults() {
		document.getElementById('headerimage').value = '';
		document.getElementById('advuppercolor').value = document.getElementById('uppercolor').value = '#69aee7';
		document.getElementById('advlowercolor').value = document.getElementById('lowercolor').value = '#4180b6';
		document.getElementById('header').style.background = 'url("<?php echo get_template_directory_uri(); ?>/images/kubrickheader.jpg") center no-repeat';
		document.getElementById('header').style.color = '#FFFFFF';
		document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value = '';
		document.getElementById('fontdisplay').value = 'inline';
		document.getElementById('headerimg').style.display = document.getElementById('fontdisplay').value;
	}
	function kRevert() {
		document.getElementById('headerimage').value = '<?php echo js_escape(kubrick_header_image()); ?>';
		document.getElementById('advuppercolor').value = document.getElementById('uppercolor').value = '#<?php echo js_escape(kubrick_upper_color()); ?>';
		document.getElementById('advlowercolor').value = document.getElementById('lowercolor').value = '#<?php echo js_escape(kubrick_lower_color()); ?>';
		document.getElementById('header').style.background = 'url("<?php echo js_escape(kubrick_header_image_url()); ?>") center no-repeat';
		document.getElementById('header').style.color = '';
		document.getElementById('advfontcolor').value = document.getElementById('fontcolor').value = '<?php echo js_escape(kubrick_header_color_string()); ?>';
		document.getElementById('fontdisplay').value = '<?php echo js_escape(kubrick_header_display_string()); ?>';
		document.getElementById('headerimg').style.display = document.getElementById('fontdisplay').value;
	}
	function kInit() {
		document.getElementById('jsForm').style.display = 'block';
		document.getElementById('nonJsForm').style.display = 'none';
	}
	addLoadEvent(kInit);
// ]]>
</script>
<style type='text/css'>
	#headwrap {
		text-align: center;
	}
	#kubrick-header {
		font-size: 80%;
	}
	#kubrick-header .hibrowser {
		width: 780px;
		height: 260px;
		overflow: scroll;
	}
	#kubrick-header #hitarget {
		display: none;
	}
	#kubrick-header #header h1 {
		font-family: 'Trebuchet MS', 'Lucida Grande', Verdana, Arial, Sans-Serif;
		font-weight: bold;
		font-size: 4em;
		text-align: center;
		padding-top: 70px;
		margin: 0;
	}

	#kubrick-header #header .description {
		font-family: 'Lucida Grande', Verdana, Arial, Sans-Serif;
		font-size: 1.2em;
		text-align: center;
	}
	#kubrick-header #header {
		text-decoration: none;
		color: <?php echo kubrick_header_color_string(); ?>;
		padding: 0;
		margin: 0;
		height: 200px;
		text-align: center;
		background: url('<?php echo kubrick_header_image_url(); ?>') center no-repeat;
	}
	#kubrick-header #headerimg {
		margin: 0;
		height: 200px;
		width: 100%;
		display: <?php echo kubrick_header_display_string(); ?>;
	}
	#jsForm {
		display: none;
		text-align: center;
	}
	#jsForm input.submit, #jsForm input.button, #jsAdvanced input.button {
		padding: 0px;
		margin: 0px;
	}
	#advanced {
		text-align: center;
		width: 620px;
	}
	html>body #advanced {
		text-align: center;
		position: relative;
		left: 50%;
		margin-left: -380px;
	}
	#jsAdvanced {
		text-align: right;
	}
	#nonJsForm {
		position: relative;
		text-align: left;
		margin-left: -370px;
		left: 50%;
	}
	#nonJsForm label {
		padding-top: 6px;
		padding-right: 5px;
		float: left;
		width: 100px;
		text-align: right;
	}
	.defbutton {
		font-weight: bold;
	}
	.zerosize {
		width: 0px;
		height: 0px;
		overflow: hidden;
	}
	#colorPickerDiv a, #colorPickerDiv a:hover {
		padding: 1px;
		text-decoration: none;
		border-bottom: 0px;
	}
</style>
<?php
}

function kubrick_theme_page() {
	if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.__('Options saved.').'</strong></p></div>';
?>
<div class='wrap'>
	<div id="kubrick-header">
	<h2><?php _e('Header Image and Color'); ?></h2>
		<div id="headwrap">
			<div id="header">
				<div id="headerimg">
					<h1><?php bloginfo('name'); ?></h1>
					<div class="description"><?php bloginfo('description'); ?></div>
				</div>
			</div>
		</div>
		<br />
		<div id="nonJsForm">
			<form method="post" action="">
				<?php wp_nonce_field('kubrick-header'); ?>
				<div class="zerosize"><input type="submit" name="defaultsubmit" value="<?php echo attribute_escape(__('Save')); ?>" /></div>
					<label for="njfontcolor"><?php _e('Font Color:'); ?></label><input type="text" name="njfontcolor" id="njfontcolor" value="<?php echo attribute_escape(kubrick_header_color()); ?>" /> <?php printf(__('Any CSS color (%s or %s or %s)'), '<code>red</code>', '<code>#FF0000</code>', '<code>rgb(255, 0, 0)</code>'); ?><br />
					<label for="njuppercolor"><?php _e('Upper Color:'); ?></label><input type="text" name="njuppercolor" id="njuppercolor" value="#<?php echo attribute_escape(kubrick_upper_color()); ?>" /> <?php printf(__('HEX only (%s or %s)'), '<code>#FF0000</code>', '<code>#F00</code>'); ?><br />
				<label for="njlowercolor"><?php _e('Lower Color:'); ?></label><input type="text" name="njlowercolor" id="njlowercolor" value="#<?php echo attribute_escape(kubrick_lower_color()); ?>" /> <?php printf(__('HEX only (%s or %s)'), '<code>#FF0000</code>', '<code>#F00</code>'); ?><br />
				<input type="hidden" name="hi" id="hi" value="<?php echo attribute_escape(kubrick_header_image()); ?>" />
				<input type="submit" name="toggledisplay" id="toggledisplay" value="<?php echo attribute_escape(__('Toggle Text')); ?>" />
				<input type="submit" name="defaults" value="<?php echo attribute_escape(__('Use Defaults')); ?>" />
				<input type="submit" class="defbutton" name="submitform" value="&nbsp;&nbsp;<?php _e('Save'); ?>&nbsp;&nbsp;" />
				<input type="hidden" name="action" value="save" />
				<input type="hidden" name="njform" value="true" />
			</form>
		</div>
		<div id="jsForm">
			<form style="display:inline;" method="post" name="hicolor" id="hicolor" action="<?php echo attribute_escape($_SERVER['REQUEST_URI']); ?>">
				<?php wp_nonce_field('kubrick-header'); ?>
	<input type="button" onclick="tgt=document.getElementById('fontcolor');colorSelect(tgt,'pick1');return false;" name="pick1" id="pick1" value="<?php echo attribute_escape(__('Font Color')); ?>"></input>
		<input type="button" onclick="tgt=document.getElementById('uppercolor');colorSelect(tgt,'pick2');return false;" name="pick2" id="pick2" value="<?php echo attribute_escape(__('Upper Color')); ?>"></input>
		<input type="button" onclick="tgt=document.getElementById('lowercolor');colorSelect(tgt,'pick3');return false;" name="pick3" id="pick3" value="<?php echo attribute_escape(__('Lower Color')); ?>"></input>
				<input type="button" name="revert" value="<?php echo attribute_escape(__('Revert')); ?>" onclick="kRevert()" />
				<input type="button" value="<?php echo attribute_escape(__('Advanced')); ?>" onclick="toggleAdvanced()" />
				<input type="hidden" name="action" value="save" />
				<input type="hidden" name="fontdisplay" id="fontdisplay" value="<?php echo attribute_escape(kubrick_header_display()); ?>" />
				<input type="hidden" name="fontcolor" id="fontcolor" value="<?php echo attribute_escape(kubrick_header_color()); ?>" />
				<input type="hidden" name="uppercolor" id="uppercolor" value="<?php echo attribute_escape(kubrick_upper_color()); ?>" />
				<input type="hidden" name="lowercolor" id="lowercolor" value="<?php echo attribute_escape(kubrick_lower_color()); ?>" />
				<input type="hidden" name="headerimage" id="headerimage" value="<?php echo attribute_escape(kubrick_header_image()); ?>" />
				<p class="submit"><input type="submit" name="submitform" class="defbutton" value="<?php echo attribute_escape(__('Update Header &raquo;')); ?>" onclick="cp.hidePopup('prettyplease')" /></p>
			</form>
			<div id="colorPickerDiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;visibility:hidden;"> </div>
			<div id="advanced">
				<form id="jsAdvanced" style="display:none;" action="">
					<?php wp_nonce_field('kubrick-header'); ?>
					<label for="advfontcolor"><?php _e('Font Color (CSS):'); ?> </label><input type="text" id="advfontcolor" onchange="advUpdate(this.value, 'fontcolor')" value="<?php echo attribute_escape(kubrick_header_color()); ?>" /><br />
					<label for="advuppercolor"><?php _e('Upper Color (HEX):');?> </label><input type="text" id="advuppercolor" onchange="advUpdate(this.value, 'uppercolor')" value="#<?php echo attribute_escape(kubrick_upper_color()); ?>" /><br />
					<label for="advlowercolor"><?php _e('Lower Color (HEX):'); ?> </label><input type="text" id="advlowercolor" onchange="advUpdate(this.value, 'lowercolor')" value="#<?php echo attribute_escape(kubrick_lower_color()); ?>" /><br />
					<input type="button" name="default" value="<?php echo attribute_escape(__('Select Default Colors')); ?>" onclick="kDefaults()" /><br />
					<input type="button" onclick="toggleDisplay();return false;" name="pick" id="pick" value="<?php echo attribute_escape(__('Toggle Text Display')); ?>"></input><br />
				</form>
			</div>
		</div>
	</div>
</div>
<?php } ?>
