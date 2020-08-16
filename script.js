jQuery(function(){
    /*
    * Show Disqus-Section
    */
    function disqus_show(event) {
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//disqus.com/forums/' + event.data.shortname + '/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();

        // Delete the description
        jQuery('#disqusActivateButton').remove();
    }
    
    jQuery('#disqusActivateButton').click({ shortname: jQuery('#disqusActivateButton').data('shortname') }, disqus_show);
});
