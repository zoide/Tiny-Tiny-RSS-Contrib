<?php
class Feed_SN extends Plugin {

	private $host;

	function about() {
		return array(1.0,
			"Salzburger Nachrichten feed plugin",
			"rangerer");
	}

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_FEED_PARSED, $this);
	}

	function hook_feed_parsed($feed) {
		if (strpos($feed->get_link(), 'www.salzburg.com') !== FALSE) {

			_debug("feed_sn: Processing feed items");

			$items = $feed->get_items();
			foreach ($items as $item) {
				$link = $item->get_link();
				$id = preg_replace('/artikel\/.*-(\d+)\//', 'artikel/$1/', $link);
				if (!$item->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, 'guid')) {
					$item->data['child'][SIMPLEPIE_NAMESPACE_RSS_20]['guid'] = array(array( 'data' => $id ));
				}
			}
		}
	}

	function api_version() {
		return 2;
	}
}
?>
