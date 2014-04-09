<?php

namespace Burly\Admin;

class Editor
{
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

	public function removePagePermalinkEdit($return, $id, $new_title, $new_slug)
	{
		global $post;
		
		if($post && $post->post_type == 'page') {
		    $return = preg_replace('/<span id="edit-slug-buttons">.*<\/span>/i', '', $return);
		}
		
		return $return;
	}
}