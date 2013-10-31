<?php
/*------------------------------------------------------------------------
# shortcodes/tweetable.php - Inceptive Tweetable Content Plugin
# ------------------------------------------------------------------------
# version   1.0
# author    Inceptive Design Labs
# copyright Copyright (C) 2013 Inceptive Design Labs. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   http://extend.inceptive.gr
-------------------------------------------------------------------------*/

//no direct accees
defined ('_JEXEC') or die('resticted aceess');

$document = JFactory::getDocument();

$path = strstr(realpath(dirname(__FILE__)), 'plugins');
$path = str_replace("plugins", "", $path);
$path = str_replace("shortcodes", "", $path);
$path = JURI::root(true).'/plugins'.$path;

$document->addStyleSheet($path.'css/style.css');
$document->addScript($path.'js/jquery.tweetable.js');

jimport('joomla.registry.registry');

//[tweetable]
if(!function_exists('tweetable_sc')){
	function tweetable_sc($atts, $content=''){
		 extract(shortcode_atts(array(
        "type" => '',
		"style" =>'',
        "close" => true
     ), $atts));
	 $data = '<span data-tweetable>'. do_shortcode( $content ) . '</span>';
	 
	$plugin = JPluginHelper::getPlugin('content', 'incptvtweetable');    
	$pluginParams = new JRegistry();
	$pluginParams->loadString($plugin->params, 'JSON');
	$via = $pluginParams->get('via');
	$related = $pluginParams->get('related');
	$shortingservice = $pluginParams->get('shortingservice');
	$bitlyusername = $pluginParams->get('bitlyusername');
	$bitlyapikey = $pluginParams->get('bitlyapikey');
	
	$options = "";
	if($via != "")
		$options .= 'via: \''.$via.'\',';
	if($related != "")
		$options .= 'related: \''.$related.'\',';
		
	$baseurl = JURI::getInstance()->toString();
	
	if($shortingservice == "bitly")
	    $shorturl = make_bitly_url($baseurl, $bitlyusername, $bitlyapikey);
	else if ($shortingservice == "tinyurl")
	    $shorturl = make_tiny_url($baseurl);
	else
	    $shorturl = $baseurl;
	
	if($shorturl != "")
	    $options .= 'url: \''.$shorturl.'\'';
	 
	 $data .= '	<script>
					var $incptv = jQuery.noConflict();
					$incptv(document).ready(
						$incptv(\'[data-tweetable]\').tweetable({
							'.$options.'
						})
					);
				</script>
				';
	 
	 return $data;
	}
	add_shortcode('tweetable','tweetable_sc');
}

/* make a URL small */
function make_bitly_url($url,$login,$appkey,$format = 'txt')
{
	$connectURL = 'http://api.bit.ly/v3/shorten?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($url).'&format='.$format;
	return trim(file_get_contents($connectURL));
}

//gets the data from a URL  
function make_tiny_url($url)  {  
    $connectURL = 'http://tinyurl.com/api-create.php?url='.$url;
    return trim(file_get_contents($connectURL))	;
}

