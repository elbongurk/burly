<?php

namespace Burly;

use ICanBoogie\Inflector;

class Page extends BaseObject
{
	private $wp_post;

	public function __construct($wp_post) 
	{
		$this->wp_post = $wp_post;
		
		delete_post_meta($this->wp_post->ID, '_burly_meta_def');
	}
	
	public function title()
	{
		return wp_title( '|', false, 'right' );
	}
	
	public function id()
	{
		return $this->wp_post->ID;
	}
	
	public function slug()
	{
		$uri = $this->wp_post->post_name;

		foreach ( $this->wp_post->ancestors as $parent ) {
			$uri = get_post( $parent )->post_name . '/' . $uri;
		}

		return $uri;
	}

	public function group($key, $options = array())
	{
		$data = get_post_meta($this->id(), $key, true);
		
		if (is_string($data)) {
			$data = array();
		}
	
		return new Group($key, $options, $data);
	}
	
	public function groups($key, $options = array())
	{
		$dataList = get_post_meta($this->id(), $key);
		$groups = array();
		
		foreach ($dataList as $data)
		{
			array_push($groups, new Group($key, $options, $data));
		}

		return $groups;
	}

	protected function content($key, $type, $options = array())
	{
		$options['id'] = $key;
		$options['type'] = $type;

		if (!array_key_exists('name', $options)) {
			$options['name'] = Inflector::get()->titleize($key);
		}
		
		add_post_meta($this->id(), '_burly_meta_def', $options);
		
		return get_post_meta($this->id(), $key);
	}
}