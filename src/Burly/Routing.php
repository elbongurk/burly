<?php

namespace Burly;

class Routing
{
	public function __construct() 
	{
		if (is_child_theme()) {
			add_action('pre_get_posts', array($this, 'checkRoute'));
			add_action('template_redirect', array($this, 'locateTemplate'));
	
			add_action('generate_rewrite_rules', array($this, 'generateRules'));
			add_filter('mod_rewrite_rules', array($this, 'modifyRules'), '', 1);
			add_action('admin_init', array($this, 'writeRules'));
		}	
	}
	
	public function writeRules()
	{
		global $pagenow;
	
		if (isset($_GET['activated']) && $pagenow == "themes.php") {
			flush_rewrite_rules();		
		}
	}
		
	public function generateRules()
	{
		$theme_dir = ltrim(get_stylesheet_directory_uri(), '/');
		add_rewrite_rule("(.+)$(?<!\.html|\.php)", $theme_dir . "/$1", "top");
	}
	
	public function modifyRules($rules)
	{
		$theme_dir = get_stylesheet_directory();
		$pos = strpos($rules, "RewriteRule ^(.+)$(?<!\.html|\.php)");
		
		$conditions = "";
		$conditions .= "RewriteCond %{REQUEST_FILENAME} !-f\n";
		$conditions .= "RewriteCond %{REQUEST_FILENAME} !-d\n";
		$conditions .= "RewriteCond " . $theme_dir . "/%{REQUEST_URI} -f\n";
		
		return substr_replace($rules, $conditions, $pos, 0);
	}
	
	public function checkRoute($query)
	{
		if ('posts' == get_option( 'show_on_front') && $query->is_home()) {		
			$theme_dir = get_stylesheet_directory() . "/";			
			$template = "index";
			$template_path = $theme_dir . $template . ".html";
			
			if (file_exists($template_path)) {
				$page_id = 0;
				$page = get_page_by_title("Home");
				if (!empty($page)) {
					$page_id = $page->ID;
				}
				else {
					$page_id = wp_insert_post(array(
						'post_type' => "page", 
						'post_name' => "front-page", 
						'post_title' => "Home",
						'post_status' => 'publish'
					));
				}
				
				update_option( 'show_on_front', 'page' );			
				update_option( 'page_on_front', $page_id );
			}
		}		
	}
	
	public function locateTemplate()
	{
		global $post;
	
		if ($_SERVER['REQUEST_METHOD'] != "GET") {
			return;
		}
	
		if (is_404()) {
			$slug = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), 1);
		
			$templates = array();
			
			if (preg_match("/\.html$/i", $slug)) {
				$slug = substr($slug, 0, -5);	
				array_push($templates, $slug);
			}
			else {
				if (substr($slug, -1) == "/") {
					$slug = substr($slug, 0, -1);
				}
				array_push($templates, $slug);
				array_push($templates, $slug . "/index");
			}
	
			$theme_dir = get_stylesheet_directory() . "/";
	
			foreach($templates as $template) {
				$template_path = $theme_dir . $template . ".html";
				
				if (file_exists($template_path)) {
					$page_id = $this->createTemplate($slug);
					wp_redirect('/' . get_page_uri($page_id));
					exit();
				}
			}				
		}
		else if (is_page()) {
			$slug = get_page_uri($post->ID);
	
			$templates = array();
			
			array_push($templates, $slug);
			array_push($templates, $slug . "/index");			
	
			if (is_front_page()) {
				array_push($templates, "index");
			}
			
			$theme_dir = get_stylesheet_directory() . "/";

			foreach($templates as $template) {
				$template_path = $theme_dir . $template . ".html";
	
				if (file_exists($template_path)) {
					$this->renderTemplate($template);
					exit();
				}
			}			
		}
	}
	
	private function createTemplate($slug)
	{
		if (preg_match("/\/index$/", $slug)) {
			$slug = substr($slug, 0, -6);
		}

		$page_id = 0;
		$slug_path = "";

		foreach(explode("/", $slug) as $slug_part) {
			$slug_path .= $slug_part;
		
			$page = get_page_by_path($slug_path);
			if (!empty($page)) {
				$page_id = $page->ID;
			}
			else {
				$page_id = wp_insert_post(array(
					'post_type' => 'page', 
					'post_name' => $slug_part, 
					'post_title' => ucfirst($slug_part),
					'post_parent' => $page_id,
					'post_status' => 'publish'
				));
			}
			
			$slug_path .= "/";
		}
		
		return $page_id;
	}
	
	private function renderTemplate($template)
	{
		global $post;
	
		$options = array(
			'extension' => '.html'
		);
		
		$theme_dir = get_stylesheet_directory() . "/";

		$engine = new \Handlebars\Handlebars(array(
			'helpers' => new Helpers(),
		    'loader' => new \Handlebars\Loader\FilesystemLoader($theme_dir, $options),
		    'partials_loader' => new \Handlebars\Loader\FilesystemLoader($theme_dir, $options)
		));	

		$page = new Page($post);
		
		echo $engine->render($template, $page);       
	}
}