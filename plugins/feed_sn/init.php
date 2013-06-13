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

		$host->add_hook($host::HOOK_FEED_FETCHED, $this);
	}

	function hook_feed_fetched($feed_data, $fetch_url, $owner_uid, $feed) {
		if (strpos($fetch_url, 'www.salzburg.com') !== FALSE) {

			_debug("feed_sn: Processing feed data");

			$doc = new DOMDocument();
			$doc->loadXML($feed_data);
			$xpath = new DOMXPath($doc);
			$articles = $xpath->query("//channel/item");

			// add guid retrieved from link
			foreach ($articles as $article) {
				$link = $article->getElementsByTagName('link')->item(0);
				$id = preg_replace('/artikel\/.*-(\d+)\//', 'artikel/$1/', $link);
				$article->appendChild($doc->createElement('guid', $id));
			}

			return $doc->saveXML();
		}
	}

	function api_version() {
		return 2;
	}
}
?>
