<?php
/**
 * Googleapps shared document object listing
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright THINK Global School 2010
 * @link http://www.thinkglobalschool.org
 */


// Get entity info
$owner = $vars['entity']->getOwnerEntity();
$friendlytime = elgg_view_friendly_time($vars['entity']->time_created);
$address = $vars['entity']->href;
$title = $vars['entity']->title;
$parsed_url = parse_url($address);

//sort out the access level for display
$object_acl = get_readable_access_level($vars['entity']->access_id);

// Function above works sometimes.. its weird. So load ACL name if any
if (!$object_acl) {
	$acl = get_access_collection($vars['entity']->access_id);
	$object_acl = $acl->name;
}

//files with these access level don't need an icon
$general_access = array('Public', 'Logged in users', 'Friends');

//set the right class for access level display - need it to set on groups and shared access only
$is_group = get_entity($vars['entity']->container_guid);

if($is_group instanceof ElggGroup){
	//get the membership type open/closed
	$membership = $is_group->membership;
	//we decided to show that the item is in a group, rather than its actual access level
	$object_acl = "Group: " . $is_group->name;
	if($membership == 2)
		$access_level = "class='access_level group_open'";
	else
		$access_level = "class='access_level group_closed'";
} elseif ($object_acl == 'Private'){
	$access_level = "class='access_level private'";
} else {
	if(!in_array($object_acl, $general_access))
		$access_level = "class='access_level shared_collection'";
	else
		$access_level = "class='access_level entity_access'";
}

if($vars['entity']->description != '')
	$view_desc = "| <a class='link' onclick=\"elgg_slide_toggle(this,'.entity_listing','.note');\">" . elgg_echo('description') . "</a>";
else
	$view_desc = '';


$icon = elgg_view("profile/icon", array('entity' => $owner,'size' => 'tiny',));

//delete
if($vars['entity']->canEdit()){
	$delete .= "<span class='delete_button'>" . elgg_view('output/confirmlink',array(
				'href' => "action/googleapps/delete_shared_document?guid=" . $vars['entity']->guid,
				'text' => elgg_echo("delete"),
				'confirm' => elgg_echo("googleapps:label:deleteconfirm"),
				)) . "</span>";
}

$info = "<div class='entity_metadata'><span {$access_level}>{$object_acl}</span>";

// include a view for plugins to extend
$info .= elgg_view("googlapps/options",array('entity' => $vars['entity']));

// Add favorites and likes
$info .= elgg_view("favorites/form",array('entity' => $vars['entity']));
$info .= elgg_view_likes($vars['entity']); // include likes

// include delete
if($vars['entity']->canEdit()){
	$info .= $delete;
}

$info .= "</div>";

$info .= "<p class='entity_title'><a href=\"{$address}\" target=\"_blank\">{$title}</a></p>";
$info .= "<p class='entity_subtext'>" . elgg_echo('googleapps:label:shared_by', array("<a href=\"".elgg_get_site_url()."pg/googleapps/docs/{$owner->username}\">{$owner->name}</a>")) . " {$friendlytime} {$view_desc}</p>";

$tags = elgg_view('output/tags', array('tags' => $vars['entity']->tags));
if (!empty($tags)) {
	$info .= '<p class="tags">' . $tags . '</p>';
}
if($view_desc != ''){
	$info .= "<div class='note hidden'>". $vars['entity']->description . "</div>";
}

//display
echo elgg_view_listing($icon, $info);