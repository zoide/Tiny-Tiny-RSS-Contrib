<?php
class Af_Orf extends Plugin {

	private $host;

	function about() {
		return array(1.0,
			"retrieve article content for news.orf.at",
			"rangerer");
	}

	function init($host) {
		$this->host = $host;

		$host->add_hook($host::HOOK_ARTICLE_FILTER, $this);
	}

	function hook_article_filter($article) {
		$owner_uid = $article["owner_uid"];

		if (strpos($article['link'], 'news.orf.at') !== FALSE) {
			if (strpos($article["plugin_data"], "orf,$owner_uid:") === FALSE) {
				if (!$article["content"]) {

					_debug("af_orf: Processing article without content");

					/* bugfix: loadHTML does not handle utf-8 properly
						we need to give it a hint by passing a specific XML declaration */

					$doc = new DOMDocument();
					$doc->loadHTML('<?xml encoding="utf-8">'.fetch_file_contents($article['link']));

					if ($doc) {
						$xpath = new DOMXPath($doc);
						$entries = $xpath->query('//div[contains(@class, "storyText")]');
						if ($entries->length > 0) {
							$basenode = $entries->item(0);
							$cleanup = array();
							array_push($cleanup, $xpath->query('//h1', $basenode));
							array_push($cleanup, $xpath->query('//div[contains(@class, "storyMeta")]', $basenode));
							foreach ($cleanup as $nodelist) {
								foreach ($nodelist as $node) {
									$node->parentNode->removeChild($node);
								}
							}
							$article["content"] = $doc->saveXML($basenode);
							$article["plugin_data"] = "orf,$owner_uid:" . $article["plugin_data"];
						}
					}
				}
			} else if (isset($article["stored"]["content"])) {
				$article["content"] = $article["stored"]["content"];
			}
		}

		return $article;
	}

	function api_version() {
		return 2;
	}
}
?>
