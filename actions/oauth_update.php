<?php
/**
 * Googleapps oauth_update action
 * - Returns google data
 *
 * @package googleapps
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright FlatSourcing 2010
 * @link http://www.thinkglobalschool.org
 */

require_once (dirname(dirname(__FILE__)) . '/lib/functions.php');
global $CONFIG;
$result = googleapps_get_oauth_data(true);
echo $result;
exit;
?>
