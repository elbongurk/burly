<?php

namespace Burly;

class Admin
{
	public function __construct()
	{
		if (is_child_theme()) {
			add_filter('cmb_meta_boxes', array($this, 'setupMetaBoxes'));
			add_filter('cmb_show_on', array($this, 'showMetaBox'), '', 2);
			add_filter('get_sample_permalink_html', array($this, 'removePagePermalinkEdit'), '', 4);
			add_action('admin_notices', array($this, 'checkForPermalinks'));				
			add_action('admin_head', array($this, 'removePageEditor'));
			
			remove_action('wp_head', 'feed_links', 2);
			remove_action('wp_head', 'feed_links_extra', 3);
			remove_action('wp_head', 'rsd_link');
			remove_action('wp_head', 'rel_canonical');
			remove_action('wp_head', 'wlwmanifest_link');
			remove_action('wp_head', 'index_rel_link');
			remove_action('wp_head', 'start_post_rel_link', 10, 0);
			remove_action('wp_head', 'parent_post_rel_link', 10, 0);
			remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
			remove_action('wp_head', 'wp_generator');
			remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
		}	
	}

	public function showMetaBox($display, $meta_box)
	{
		$post_id = isset($_GET['post']) ? $_GET['post'] : null;
		
		if (!$post_id) {
			$post_id = isset($_POST['post_id']) ? $_POST['post_id'] : null;
		}
			
		if (!$post_id && isset($meta_box['show_on']['id'])) {
			return false;
		}
		
		return $display;
	}

	public function setupMetaBoxes(array $meta_boxes)
	{	
		global $wpdb;
	
		$results = $wpdb->get_results( 'SELECT post_id, meta_key, meta_value FROM ' . $wpdb->prefix . 'postmeta WHERE meta_key = "_burly_meta_def" ORDER BY meta_id ASC' );
	
		foreach ($results as $result)
		{
			$field = (array) unserialize($result->meta_value);
			
			$meta_boxes[] = array(
				'id' => 'box-' . $field['id'],
				'title' => $field['name'],
				'pages' => 'page',
				'show_on' => array('id' => array($result->post_id)),
				'context' => 'normal',
				'fields' => array(
					$field				
				)				
			);
		}
		
		return $meta_boxes;
	}

	public function removePageEditor() 
	{
		global $post;
	
		if ($post && $post->post_type == 'page') {
			$burly_defs = get_post_meta($post->ID, '_burly_meta_def');
			if (!empty($burly_defs)) {
			    remove_post_type_support( 'page', 'editor' );				
			}
		}
	}   

	public function checkForPermalinks()
	{
		if ( !get_option('permalink_structure') ) {
		    echo '<div class="error"><p>Burly requires you to enable <a href="' . admin_url('options-permalink.php') . '">Permalinks</a>.</p></div>';
		}
	}
	
	public function removePagePermalinkEdit($return, $id, $new_title, $new_slug)
	{
		global $post;
		
		if($post && $post->post_type == 'page') {
		    $return = preg_replace('/<span id="edit-slug-buttons">.*<\/span>/i', '', $return);
		}
		
		return $return;
	}
}