<?php

namespace Burly;

use Burly\Model;

class Helpers extends \Handlebars\Helpers
{
	private function parseArgs($args, $defaultOptions = array())
	{
		$xml = simplexml_load_string("<" . $args . "/>");
		$key = $xml->getName();
		$options = $defaultOptions;
		
		foreach($xml->attributes() as $name => $value) {
			$options[$name] = (string)$value;
		}
		
		return array($key, $options);
	}
	
	private function content($type, $context, $args)
	{
		$callable = array($context->last(), $type);
		$params = $this->parseArgs($args);
	
		return call_user_func_array($callable, $params);
	}
	
	protected function addDefaultHelpers()
	{
		parent::addDefaultHelpers();
		
		$this->add('burlyHead', function($template, $context, $args, $source) {
			ob_start();
			wp_head();
			
			$head = ob_get_clean();
			
			return new \Handlebars\SafeString($head);
		});	

		$this->add('burlyFooter', function($template, $context, $args, $source) {
			ob_start();
			wp_footer();
			$footer = ob_get_clean();
			
			return new \Handlebars\SafeString($footer);
		});	
		
		$this->add('burlyTitle', function ($template, $context, $args, $source) {
			return $context->last()->title();
		});
		
		$this->add('burlyText', function($template, $context, $args, $source) {
			return $this->content('text', $context, $args);
		});
		
		$this->add('burlyTextArea', function($template, $context, $args, $source) {
			return $this->content('textarea', $context, $args);
		});
		
		$this->add('burlyEditor', function($template, $context, $args, $source) {
			return $this->content('editor', $context, $args);
		});
		
		$this->add('burlyImage', function($template, $context, $args, $source) {
			return $this->content('image', $context, $args);
		});
		
		$this->add('burlyFile', function($template, $context, $args, $source) {
			return $this->content('file', $context, $args);
		});
		
		$this->add('burlyIsPage', function($template, $context, $args, $source) {
			$page = $context->last();
			$buffer = "";

			if ($page->slug() == $args) {
				$buffer .= $template->render($context);
			}
			
			return $buffer;
		});
		
		$this->add('burlyGroup', function($template, $context, $args, $source) {
			$page = $context->last();
			$buffer = "";

			$group = $this->content('group', $context, $args);
			
			$context->push($group);
			$buffer = $template->render($context);
			$context->pop($group);
			
			$group->flushMeta($page->id());
			
			return $buffer;
		});
		
		$this->add('burlyRepeater', function($template, $context, $args, $source) {
			$page = $context->last();
			$buffer = "";
			
			list($key, $options) = $this->parseArgs($args, array('repeatable' => true));
			$testGroup = new Content\Group($key, $options);
			
			$context->push($testGroup);
			$template->render($context);
			$context->pop($testGroup);
			
			$testGroup->flushMeta($page->id());
			
			$groups = $this->content('groups', $context, $args);
			
			foreach($groups as $group) {
				$context->push($group);
				$buffer .= $template->render($context);
				$context->pop($group);	
			}
			
			return $buffer;		
		});
	}
}