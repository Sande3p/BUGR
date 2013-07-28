<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <link type="image/x-icon" rel="shortcut icon" href="/i/favicon.ico" />
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
        <title><?php 
        /*
         * Print the <title> tag based on what is being viewed.
         */
        global $page, $paged;
		// Add the blog name.
        bloginfo( 'name' );
        wp_title( '|', true, 'left' );
    
        
    
        // Add the blog description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            echo " | $site_description";
    ?></title>

        

<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
<link type="text/css" rel="stylesheet" href="<?php bloginfo("stylesheet_directory");?>/css/ui-lightness/jquery-ui-1.9.2.custom.css" />
<link type="text/css" rel="stylesheet" href="<?php bloginfo("stylesheet_directory");?>/css/style_basic.css" />
<link type="text/css" rel="stylesheet" href="<?php bloginfo("stylesheet_directory");?>/css/style_help.css" />
<link type="text/css" rel="stylesheet" href="<?php bloginfo("stylesheet_directory");?>/css/wordpress-default.css" />

<!--[if IE 7]>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo("stylesheet_directory");?>/css/studio-ie7.css" />
<![endif]-->
<!--[if IE 6]>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo("stylesheet_directory");?>/css/studio-ie6.css" />
<![endif]-->



        <!--[if lt IE 7]>
                <script type="text/javascript" src="js/pngfix/unitpngfix.js"></script>
        <![endif]-->         
	
        <script type="text/javascript" language="javascript" src="<?php bloginfo("stylesheet_directory");?>/js/jquery-1.8.3.js"></script>
		<script type="text/javascript" language="javascript" src="<?php bloginfo("stylesheet_directory");?>/js/jquery-ui-1.9.2.custom.js"></script>
		<script src="<?php bloginfo("stylesheet_directory");?>/js/flowplayer-3.2.6.min.js" type="text/javascript"></script>
		<script type="text/javascript" language="javascript" src="<?php bloginfo("stylesheet_directory");?>/js/script.js"></script>
		<script type="text/javascript" language="javascript">
			$(document).ready(function(){
				var availableTags = [
				<?php 
						$post_type = ( is_page() or $_GET[type] == 'help' ) ? "page":"post"; 
						wp_reset_query();
						$man=query_posts(array(
								'post_type' => $post_type,
								'order'		=> 'ASC',
								'post_status'=> 'publish',
								'posts_per_page'=> '-1'
							));
	
						$postCount = count($man);
						$i=0;
						if(have_posts())
						while(have_posts()): the_post();
							if($i==0)
								echo '"'.str_replace('"','',$post->post_title).'"';
							else
								echo ',"'.str_replace('"','',$post->post_title).'"';
							$i++;	
						endwhile;
						wp_reset_query();
					?>
				];				
				$( "#searchInput" ).autocomplete({
					source: availableTags
				});
			});
		</script>

    </head>
<?php
	global $currentUser;
	global $currentUserUrl;
	global $currentUserId;
	global $currentUserHandle;
	
	$currentUser = wp_get_current_user();
	if($currentUser!=null) {
		$currentUserId = $currentUser->ID;
		$currentUserHandle = $currentUser->user_login;
	}
?>
    
     <body>
      <div id="page-wrap">
 
<!-- #header -->
<div id="header">
    <div class="headerInner">
        <h1><a href="http://studio.topcoder.com" title="Topcoder Studio"><img src="<?php bloginfo("stylesheet_directory");?>/i/v4/logo-topcoder-studio.png" alt="Topcoder Studio" /></a></h1>
        <!-- #userPanel -->
        <div id="userPanel">
            <div class="userPanelR">
                <div class="userPanelC">
                    
                        
                            <?php 
							$meta = get_cookie();
							?>
							
							Hello, 
							<?php if( $meta->handle_name != null ) : ?>
								<strong><?php echo $meta->handle_name;?></strong> | <a href="http://studio.topcoder.com/?module=Logout">Log out</a>
							<?php else: ?>
								<strong>Guest</strong> | <a href="http://studio.topcoder.com/?module=Login">login</a> 
							<?php endif; ?>
							| <a href="http://www.topcoder.com/reg/">Register</a>
                        
                        
                    
                </div>
            </div>
        </div>
        <!-- end #userPanel  -->
		
<?php
	global $wpdb;
	global $pageId;

	$pageId = $posts[0]->ID;
	
	$menu_slug = "top";
	$queryMenuId = " SELECT term_id FROM $wpdb->terms WHERE name = '".$menu_slug."'";
	$menu_id = $wpdb->get_var($queryMenuId);
	$allNav = wp_get_nav_menu_items( $menu_id );
	$arrLevelMenu = sortArrMenu($allNav,&$activeParentLevel1,&$activeParentLevel2,$pageId);
	
	$currentTopMenuId;
	$i=0;
?>
        <!-- #nav  -->
        <ul id="nav">
<?php
	if($arrLevelMenu!=null) 
	foreach($arrLevelMenu as $nav) :
		$ulClass = "";
		$liClass = "";
		$id = $nav->ID;
		$liClass .= $nav->classes[0]!=null ? $nav->classes[0] : "";
		$url = $nav->url=="" ? "javascript:;" : $nav->url;
		$title = $nav->title;
		$isActive = $nav->object_id == $pageId ? true : false;
		if($isActive) {
			$ulClass = "current";
		}
?>	
            <li  class="<?php echo $ulClass;?>">
                <span class="navMenuR"><span class="navMenuC">
                    <a href="<?php echo $url;?>"><?php echo $title;?></a>
                </span></span>
				<?php
					if($nav->child!=null) : 
				?>
				<div class="subNav">
                    <div class="subNavHead">
                        <div class="subNavHeadR">
                            <div class="subNavHeadC">
                                <div class="subNavHeadInner"></div>
                            </div>
                        </div>
                    </div>
                    <div class="subNavContent">
                        <div class="subNavContentR">
                            <div class="subNavContentC">
                                <ul>
				<?php
					foreach($nav->child as $subMenu) : 
						$id = $subMenu->ID;
						$ulClass = $subMenuInc==0 ? "first" : "";
						$liClass = "";
						$liClass .= $subMenu->classes[0]!=null ? " ".$subMenu->classes[0] : "";
						$url = $subMenu->url=="" ? "javascript:;" : $subMenu->url;
						$title = $subMenu->title;
						$isActive = $subMenu->object_id == $pageId ? true : false;
						if($isActive) { 
							$liClass .= "current";
						}
				?>		
						<li><a href="<?php echo $url;?>"><?php echo $title;?></a></li>
				<?php endforeach;?>
								</ul>
                            </div>
                        </div>
                    </div>
                    <!-- bottom part -->
                    <div class="subNavFoot">
                        <div class="subNavFootR">
                            <div class="subNavFootC">
                                <div class="subNavFootInner"></div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php endif; ?>
            </li>
<?php endforeach; ?>
        </ul>
        <!-- end #nav  -->
        
    </div>
</div>
<!-- end #header -->