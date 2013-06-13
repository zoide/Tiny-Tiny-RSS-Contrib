<?php
class feed_error_btn extends Plugin {

	private $host;

	function about() {
		return array(1.0,
			"Toolbar button for easy accessing the list of feeds with update errors",
			"Heiko Adams", false);
	}

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_TOOLBAR_BUTTON, $this);
	}

	function HOOK_TOOLBAR_BUTTON() {
		echo stylesheet_tag("plugins/feed_error_btn/button.css");
		echo '<button class="err_btn_nav" title="'.__("Feeds with update errors").'" onclick="showFeedsWithErrors()">
			<img src="images/alert.png" alt="'.__("Feeds with update errors").'"></button>';
	}

	function api_version() {
		return 2;
	}

}
?>
