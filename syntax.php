<?php
/**
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Andreas Gohr <andi@splitbrain.org>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');


/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_disqus extends DokuWiki_Syntax_Plugin {

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }

    function getPType(){
        return 'block';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 160;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
      $this->Lexer->addSpecialPattern('~~DISQUS\b.*?~~',$mode,'plugin_disqus');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, Doku_Handler $handler){

        $match = substr($match, 8, -2);         //strip ~~DISQUS from start and ~~ from end
        $shortname = strtolower(trim($match));  //strip spaces

        if (!$shortname) $shortname = $this->getConf('shortname');
        return $shortname;
    }

    /**
     * Create output
     */
    function render($mode, Doku_Renderer $R, $data) {
        if($mode != 'xhtml') return false;
        $R->doc .= $this->_disqus($data);
        return true;
    }

    function _disqus($shortname = ''){
        global $ID;
        global $INFO;

        if (!$shortname === '') $shortname = $this->getConf('shortname');

        $doc = '';
        $doc .= '<script charset="utf-8" type="text/javascript">
                    <!--//--><![CDATA[//><!--'."\n";
        if($this->getConf('devel'))
            $doc .= 'var disqus_developer = '.$this->getConf('devel').";\n";
        $doc .= "var disqus_url     = '".wl($ID,'',true)."';\n";
        $doc .= "var disqus_title   = '".addslashes($INFO['meta']['title'])."';\n";
        $doc .= "var disqus_message = '".addslashes($INFO['meta']['abstract'])."';\n";
        $doc .= 'var disqus_container_id = \'disqus__thread\';
                    //--><!]]>
                    </script>';
        $doc .= '<div id="disqus__thread"></div>';
        if($this->getConf('button')) {
            $doc .= '<div id="disqusActivate">';
            $doc .= '<button id="disqusActivateButton" data-shortname="'.hsc($shortname).'">'.$this->getLang('buttontext').'</button>';
	    $doc .= '</div>';
        } else $doc .= '<script type="text/javascript" src="//disqus.com/forums/'.hsc($shortname).'/embed.js"></script>';
        $doc .= '<noscript><a href="//'.hsc($shortname).'.disqus.com/?url=ref">View the discussion thread.</a></noscript>';

        return $doc;
    }

}

//Setup VIM: ex: et ts=4 enc=utf-8 :
