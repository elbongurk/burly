<?php

namespace Burly;

class Updater
{
	private $theme;

	public function __construct()
	{
		if (is_admin()) {
			add_filter('pre_set_site_transient_update_themes', array( $this, "setTransitent" ) );
		}
	}
	
	private function releaseVersion()
	{
		$url = "https://api.github.com/repos/elbongurk/burly/releases";
		$result = wp_remote_get( $url );
		$result = wp_remote_retrieve_body( $result );

		if ( !empty( $result ) ) {
			$result = @json_decode( $result );			
		}
		if ( is_array( $result ) ) {
			$result = empty($result) ? null : $result[0];
		}
		
		return $result;
	}
	
	public function setTransitent( $transient )
	{
		if ( empty( $transient->checked ) ) {
			return $transient;
		}
		
		$release = $this->releaseVersion();
		
		$update = version_compare( $release->tag_name, $transient->checked['burly'] );
		
		if ($update > 0) {
			$transient->response['burly'] = array(
				'new_version' => $release->tag_name,
				'url' => $release->html_url,
				'package' => $release->assets[0]->url			
			);
		}

		return $transient;
	}
}