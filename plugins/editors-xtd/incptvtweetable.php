
<?php
/*------------------------------------------------------------------------
# incptvtweetablebutton.php - Inceptive Tweetable Editor Button
# ------------------------------------------------------------------------
# author    Inceptive Design Labs
# copyright Copyright (C) 2013 Inceptive Design Labs. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   http://extend.inceptive.gr
-------------------------------------------------------------------------*/

defined('_JEXEC') or die;
class plgButtonIncptvTweetable extends JPlugin
{
    function onDisplay($name)
    {
        $document = JFactory::getDocument();
	$document->addStyleSheet(JURI::base(true).'/../plugins/editors-xtd/incptvtweetable/css/style.css');
        $jsCode = "
                function insertShortCode(nameOfEditor) {
                    jInsertEditorText('[tweetable]This is an easily tweetable text![/tweetable]', nameOfEditor);
                }
            ";
        $document->addScriptDeclaration($jsCode);
        $button = new JObject();
	$button->set('modal',false);
	$button->set('class','btn');
        $button->set('text','Tweetable');
        $button->set('onclick', 'insertShortCode(\''.$name.'\')');
        $button->set('name', 'tweetable');
        return $button;

    }

}
?>

