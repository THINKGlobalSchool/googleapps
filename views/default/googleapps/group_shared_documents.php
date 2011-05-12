<?php
/**
 * Elgg googleapps group documents list
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */

//check to make sure this groups Documents has been activated
if($vars['entity']->shared_doc_enable != 'no') {

	?>
<div class="group_tool_widget google_shared_doc">
	<span class="group_widget_link"><a
		href="<?php echo $vars['url'] . "googleapps/docs/" . elgg_get_page_owner_entity()->username; ?>"><?php echo elgg_echo('link:view:all')?>
	</a> </span>
	<h3>
	<?php echo elgg_echo("googleapps:label:google_docs"); ?>
	</h3>

	<?php
	$docs = elgg_get_entities(array(
		'type' => 'object',
		'subtype' => 'shared_doc',
		'container_guid' => $vars['entity']->getGUID(),
		'limit' => 6
	));

	//if there are some Documents, go get them
	if ($docs) {
		//display in list mode
		foreach($docs as $d){

			$mime = $d->mimetype;
			echo "<div class='entity_listing clearfloat'>";
			echo "<div class='entity_listing_icon'>" . elgg_view('profile/icon',array('entity' => $d->getOwnerEntity(), 'size' => 'tiny')) . "</div>";
			echo "<div class='entity_listing_info'>";
			echo "<p class='entity_title'><a href=\"{$d->getURL()}\">" . $d->title . "</a></p>";
			echo "<p class='entity_subtext'>" . friendly_time($d->time_created) . "</p>";
			echo "</div></div>";

		}
	} else {

		$share_document = $vars['url'] . "googleapps/docs/share?container_guid=" . elgg_get_page_owner_guid();
		echo "<p><a href=\"{$share_document}\">" . elgg_echo("googleapps:label:shareadoc") . "</a></p>";

	}

	?>
</div>

	<?php
}//end of activate check statement
