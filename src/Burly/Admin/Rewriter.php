<?php

namespace Burly\Admin;

class Rewriter
{
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
	
	public function checkForPermalinks()
	{
		if ( !get_option('permalink_structure') ) {
		    echo '<div class="error"><p>Burly requires you to enable <a href="' . admin_url('options-permalink.php') . '">Permalinks</a>.</p></div>';
		}
	}		
}