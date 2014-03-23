<?php

namespace Burly;

use ICanBoogie\Inflector;

class Group extends BaseObject
{
	private $options;
	private $data;
	
	public function __construct($key, $options = array(), $data = array())
	{
		$options['id'] = $key;
		$options['type'] = "group";

		if (!array_key_exists('name', $options)) {
			$options['name'] = Inflector::get()->titleize($key);
		}
		
		$options['fields'] = array();

		$this->options = $options;
		$this->data = (array)$data;
	}
	
	public function flushMeta($id)
	{
		if (!empty($this->options['fields'])) {
			add_post_meta($id, '_burly_meta_def', $this->options);
		}
	}
	
	protected function content($key, $type, $options = array())
	{	
		$options['id'] = $key;
		$options['type'] = $type;
		
		if (!array_key_exists('name', $options)) {
			$options['name'] = Inflector::get()->titleize($key);
		}
		
		array_push($this->options['fields'], $options);
		
		$results = array();
		
		if (array_key_exists($key, $this->data)) {
			array_push($results, $this->data[$key]);
		}
		
		return $results;
	}

}