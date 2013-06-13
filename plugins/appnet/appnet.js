	function shareArticleToAppNet(id) {
	try {
		var query = "?op=pluginhandler&plugin=appnet&method=getInfo&id=" + param_escape(id);

		console.log(query);

		var d = new Date();
		var ts = d.getTime();

		var w = window.open('backend.php?op=backend&method=loading', 'adn_post',
			"status=0,toolbar=0,location=0,width=500,height=450,scrollbars=1,menubar=0");

		new Ajax.Request("backend.php",	{
			parameters: query,
			onComplete: function(transport) {
				var ti = JSON.parse(transport.responseText);

				var share_url = "https://alpha.app.net/intent/post?text=" + param_escape(ti.title) + " " + param_escape(ti.link);

				w.location.href = share_url;

			} });


	} catch (e) {
		exception_error("tweetArticle", e);
	}
	}

