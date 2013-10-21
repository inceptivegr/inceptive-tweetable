<?php
/*------------------------------------------------------------------------
# installation/script.incptvtweetable.php - Inceptive Tweetable Content Plugin
# ------------------------------------------------------------------------
# version   1.0
# author    Inceptive Design Labs
# copyright Copyright (C) 2013 Inceptive Design Labs. All Rights Reserved
# license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
# website   http://extend.inceptive.gr
-------------------------------------------------------------------------*/

// No Direct Access
defined( '_JEXEC' ) or die;

// Script
class plgContentIncptvTweetableInstallerScript
{
	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function install( $parent )
	{
	}
	
	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */
	function uninstall( $parent )
	{
		$db = JFactory::getDBO();
        $status = new stdClass;
        $status->plugins = array();
        $manifest = $parent->getParent()->manifest;
        $plugins = $manifest->xpath('plugins/plugin');
        foreach ($plugins as $plugin)
        {
            $name = (string)$plugin->attributes()->plugin;
            $group = (string)$plugin->attributes()->group;
            $query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = ".$db->Quote($name)." AND folder = ".$db->Quote($group);
            $db->setQuery($query);
            $extensions = $db->loadColumn();
            if (count($extensions))
            {
                foreach ($extensions as $id)
                {
                    $installer = new JInstaller;
                    $result = $installer->uninstall('plugin', $id);
                }
                $status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
            }
            
        }
        $this->uninstallationResults($status);

	}
	
	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function update( $parent )
	{		
	}
	
	/**
	 * Called before any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function preflight( $type, $parent )
	{
	}
	
	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function postflight( $type, $parent )
	{
	    $app	=	JFactory::getApplication();
	    $db		=	JFactory::getDBO();

	    $db->setQuery( 'UPDATE #__extensions SET enabled = 1 WHERE folder="content" AND element = "incptvtweetable"' );
	    $db->execute();

	    $status = new stdClass;
	    $status->plugins = array();
	    $src = $parent->getParent()->getPath('source');
	    $manifest = $parent->getParent()->manifest;
	    $plugins = $manifest->xpath('plugins/plugin');
	    foreach ($plugins as $plugin)
	    {
			$name = (string)$plugin->attributes()->plugin;
			$group = (string)$plugin->attributes()->group;
			$path = $src.'/plugins/'.$group;
			if (JFolder::exists($src.'/plugins/'.$group.'/'.$name))
			{
				$path = $src.'/plugins/'.$group.'/'.$name;
			}
			$installer = new JInstaller;
			$result = $installer->install($path);
			
			$query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=".$db->Quote($name)." AND folder=".$db->Quote($group);
			$db->setQuery($query);
			$db->query();
			$status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
	    }
	    //echo "<p>Installed</p>";
	    $this->installationResults($status);
	}
	
	private function installationResults($status)
	{
	    $language = JFactory::getLanguage();
	    $languagePath = JPATH_PLUGINS.DS.'content'.DS.'incptvtweetable';
	    $rows = 0; ?>
	    <img src="<?php echo JURI::root(true); ?>/plugins/content/incptvtweetable/images/incptvtweetable173x48.jpg" alt="Inceptive Tweetable" align="right" />
	    <h2>Installation status</h2>
		<div>
			<p>
				<a href="http://extend.inceptive.gr/shop/joomla-extensions/inceptive-tweetable/" title="Inceptive Tweetable">Inceptive Tweeetable</a> by <a href="http://extend.inceptive.gr" title="Extend by Inceptive Design Labs">Extend by Inceptive Design Labs</a> is a Joomla plugin that by the use of a shortcode lets you make site content easily tweetable.<br />
			</p>
			<p>
				Based on the jQuery plugin <a href="" title="jquery.tweetable.js">jquery.tweetable.js</a> developed by <a href="http://jmduke.com" title="Justin Duke a.k.a jmduke">Justin Duke a.k.a jmduke</a>. Originaly found <a href="https://github.com/jmduke/jquery.tweetable.js" title="plugin page on github">here</a>.
			</p>
			<p>
				The shortcode functionality is heavily based on the shortcode functionality of the <a href="http://www.joomshaper.com/helix" title="Helix Framework">Helix Framework</a>. The plugin, actually includes a stripped version of the System Plugin that comes with the <a href="http://www.joomshaper.com/helix" title="Helix Framework">Helix Framework</a>.
			</p>
			<p>
				Please be kind enough to review our plugins on <a href="https://extensions.joomla.org/extensions/owner/Inceptive-Design-Labs" title="">JED</a>!
			</p>
		</div>
	    <table class="adminlist table table-striped">
		<thead>
		    <tr>
			<th class="title" colspan="2">Extension</th>
			<th width="30%">Status</th>
		    </tr>
		</thead>
		<tfoot>
		    <tr>
			<td colspan="3"></td>
		    </tr>
		</tfoot>
		<tbody>
				    <tr>
			<th>Plugin</th>
			<th>Group</th>
			<th></th>
		    </tr>
				    <tr class="row0">
			<td class="key">Inceptive Tweetable</td>
					    <td class="key">Content</td>
			<td><strong>Installed</strong></td>
		    </tr>
		    <?php if (count($status->plugins)): ?>
		    <?php foreach ($status->plugins as $plugin): ?>
		    <tr class="row<?php echo(++$rows % 2); ?>">
			<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
			<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
			<td><strong><?php echo ($plugin['result'])?"Installed":"Not Installed"; ?></strong></td>
		    </tr>
		    <?php endforeach; ?>
		    <?php endif; ?>
		</tbody>
	    </table>
	<?php
	    }
	
	private function uninstallationResults($status)
	{
	    $rows = 0;
	    ?>
		<h2>Removal status</h2>
		<div>
			<p>
				<a href="http://extend.inceptive.gr/shop/joomla-extensions/inceptive-tweetable/" title="Inceptive Tweetable">Inceptive Tweeetable</a> by <a href="http://extend.inceptive.gr" title="Extend by Inceptive Design Labs">Extend by Inceptive Design Labs</a> is a Joomla plugin that by the use of a shortcode lets you make site content easily tweetable.<br />
			</p>
			<p>
				Based on the jQuery plugin <a href="" title="jquery.tweetable.js">jquery.tweetable.js</a> developed by <a href="http://jmduke.com" title="Justin Duke a.k.a jmduke">Justin Duke a.k.a jmduke</a>. Originaly found <a href="https://github.com/jmduke/jquery.tweetable.js" title="plugin page on github">here</a>.
			</p>
			<p>
				The shortcode functionality is heavily based on the shortcode functionality of the <a href="http://www.joomshaper.com/helix" title="Helix Framework">Helix Framework</a>. The plugin, actually includes a stripped version of the System Plugin that comes with the <a href="http://www.joomshaper.com/helix" title="Helix Framework">Helix Framework</a>.
			</p>
			<p>
				Please be kind enough to review our plugins on <a href="https://extensions.joomla.org/extensions/owner/Inceptive-Design-Labs" title="">JED</a>!
			</p>
		</div>
		<table class="adminlist table table-striped">
			<thead>
				<tr>
					<th class="title" colspan="2">Extension</th>
					<th width="30%">Status</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="3"></td>
				</tr>
			</tfoot>
			<tbody>
				<tr>
					<th>Plugin</th>
					<th>Group</th>
					<th></th>
				</tr>
				<tr class="row0">
					<td class="key">Inceptive Tweetable</td>
					<td class="key">Content</td>
					<td><strong>Removed</strong></td>
				</tr>
				<?php if (count($status->plugins)): ?>
				<?php foreach ($status->plugins as $plugin): ?>
				<tr class="row<?php echo(++$rows % 2); ?>">
					<td class="key"><?php echo ucfirst($plugin['name']); ?></td>
					<td class="key"><?php echo ucfirst($plugin['group']); ?></td>
					<td><strong><?php echo ($plugin['result'])?"Removed":"Not removed"; ?></strong></td>
				</tr>
				<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	<?php
	}	
}
?>