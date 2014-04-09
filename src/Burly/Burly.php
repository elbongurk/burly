<?php

namespace Burly;

class Burly
{
	private $router;
	private $rewriter;
	private $editor;
	private $updater;

	public function __construct(array $options = array())
	{
		if (isset($options['router'])) {
			$this->setRouter($options['router']);
		}
		
		if (isset($options['rewriter'])) {
			$this->setRewriter($options['rewriter']);
		}

		if (isset($options['editor'])) {
			$this->setEditor($options['editor']);
		}

		if (isset($options['updater'])) {
			$this->setUpdater($options['updater']);
		}		
	}

	public function load()
	{
		$this->removeHeadActions();

		$this->loadRouter();
		$this->loadRewriter();
		$this->loadEditor();
		$this->loadUpdater();
	}
	

	public function getRouter()
	{
		if (!isset($this->router)) {
			$this->router = new Router();
		}
		
		return $this->router;
	}
	
	public function setRouter(Router $router)
	{
		$this->router = $router;
	}
	
	public function getRewriter()
	{
		if (!isset($this->rewriter)) {
			$this->rewriter = new Admin\Rewriter();
		}
		
		return $this->rewriter;
	}
	
	public function setRewriter(Admin\Rewriter $rewriter)
	{
		$this->rewriter = $rewriter;
	}

	public function getEditor()
	{
		if (!isset($this->editor)) {
			$this->editor = new Admin\Editor();
		}
		
		return $this->editor;
	}
	
	public function setEditor(Admin\Editor $editor)
	{
		$this->editor = $editor;
	}
	
	public function getUpdater()
	{
		if (!isset($this->updater)) {
			$this->updater = new Admin\Updater();
		}
		
		return $this->updater;
	}

	public function setUpdater(Admin\Updater $updater)
	{
		$this->updater = $updater;
	}

	protected function removeHeadActions()
	{
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
	
	protected function loadRouter()
	{
		add_action('pre_get_posts', array($this->getRouter(), 'checkRoute'));
		add_action('template_redirect', array($this->getRouter(), 'locateTemplate'));
	}
	
	protected function loadUpdater()
	{
		add_filter('pre_set_site_transient_update_themes', array($this->getUpdater(), 'setTransitent'));		
	}
	
	protected function loadRewriter()
	{
		add_action('generate_rewrite_rules', array($this->getRewriter(), 'generateRules'));
		add_filter('mod_rewrite_rules', array($this->getRewriter(), 'modifyRules'), '', 1);
		add_action('admin_init', array($this->getRewriter(), 'writeRules'));
		add_action('admin_notices', array($this->getRewriter(), 'checkForPermalinks'));						
	}
	
	protected function loadEditor()
	{
		add_filter('cmb_meta_boxes', array($this->getEditor(), 'setupMetaBoxes'));
		add_filter('cmb_show_on', array($this->getEditor(), 'showMetaBox'), '', 2);
		add_filter('get_sample_permalink_html', array($this->getEditor(), 'removePagePermalinkEdit'), '', 4);
		add_action('admin_head', array($this->getEditor(), 'removePageEditor'));
	}
}