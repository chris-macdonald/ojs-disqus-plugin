{**
 * plugins/generic/disqus/disqus.tpl
 *
 * Copyright (c) 2013 Simon Fraser University Library
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Disqus comments.
 *
 *}
<!-- start Disqus -->
<div class="separator"></div>
<h3>{translate key="plugins.generic.disqus.title"}</h3>
<div id="disqus_thread"></div>
<script type="text/javascript">
		var disqus_shortname = '{$disqusShortname}';
    var disqus_identifier = '{$articleId}';
    var disqus_title = '{$article->getLocalizedTitle()|strip_unsafe_html}';
    var disqus_url = '{$currentUrl}';
    (function() {ldelim}
        var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
        dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
        (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
    {rdelim})();
</script>
<noscript>{translate key="plugins.generic.disqus.jswarning"} <a href="http://disqus.com/?ref_noscript">Disqus.</a></noscript>
<!-- end Disqus -->
