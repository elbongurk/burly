<?php

namespace Burly;

abstract class Content
{
	abstract protected function content($key, $type, $options);	

	public function text($key, $options = array())
	{
		$values = $this->content($key, 'text', $options);
		
		if (!empty($values)) {
			return $values[0];
		}
		
		return "";
	}
	
	public function textarea($key, $options = array())
	{
		$values = $this->content($key, 'textarea', $options);
	
		if ( !empty( $values ) ) {
			return new \Handlebars\SafeString(wpautop($values[0]));
		}
		
		return "";		
	}
	
	public function editor($key, $options = array())
	{
		$values = $this->content($key, 'wysiwyg', $options);
	
		if ( !empty( $values ) ) {
			return new \Handlebars\SafeString(apply_filters('the_content', $values[0]));
		}
		
		return "";		
	}
	
	public function image($key, $options = array())
	{
		$size_options = array();
		
		if (array_key_exists('width', $options)) {
			$size_options['width'] = $options['width'];
			unset($options['width']);
		}
		if (array_key_exists('height', $options)) {
			$size_options['height'] = $options['height'];
			unset($options['height']);
		}
		
		if (!empty($size_options)) {
			$size_options['crop'] = 1;
			$options['size'] = http_build_query($size_options);
			$options['show_size'] = true;
		}			
	
		$values = $this->content($key, 'image', $options);

		if ( !empty( $values ) ) {
			return wp_get_attachment_url($values[0]);
		}
		
		return "";
	}
	
	public function file($key, $options = array())
	{
		$values = $this->content($key, 'file', $options);
	
		if ( !empty( $values ) ) {
			return wp_get_attachment_url($values[0]);
		}
		
		return "";
	}
}