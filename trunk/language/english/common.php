<?php
/**
* English language constants commonly used in the module
*
* @copyright	
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		MekDrop <i.know@mekdrop.name>
* @package		imsources
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// type
define("_CO_IMSOURCES_TYPE_NAME", "Type name");
define("_CO_IMSOURCES_TYPE_NAME_DSC", " ");
define("_CO_IMSOURCES_TYPE_CODE", "Code");
define("_CO_IMSOURCES_TYPE_CODE_DSC", " ");
define("_CO_IMSOURCES_TYPE_DGINTERVAL", "Data gather interval");
define("_CO_IMSOURCES_TYPE_DGINTERVAL_DSC", "How frequency try to gather data (in hours)?");
define("_CO_IMSOURCES_TYPE_DGNTIME", "Next run time");
define("_CO_IMSOURCES_TYPE_DGNTIME_DSC", "Do not run sooner as this date and time value!");
// exporter
define("_CO_IMSOURCES_EXPORTER_NAME", "Exporter name");
define("_CO_IMSOURCES_EXPORTER_NAME_DSC", " ");
define("_CO_IMSOURCES_EXPORTER_TYPE_ID", "Type");
define("_CO_IMSOURCES_EXPORTER_TYPE_ID_DSC", " How to gather data?");
define("_CO_IMSOURCES_EXPORTER_DEFAULT_TITLE", "Default title");
define("_CO_IMSOURCES_EXPORTER_DEFAULT_TITLE_DSC", " This will be used if there is no title in gathered data.");
define("_CO_IMSOURCES_EXPORTER_ENABLED", "Enabled");
define("_CO_IMSOURCES_EXPORTER_ENABLED_DSC", " Select &quot;Yes&quot; if this exporter is enabled.");
define("_CO_IMSOURCES_EXPORTER_DEFAULT_AUTHOR", "Default author name");
define("_CO_IMSOURCES_EXPORTER_DEFAULT_AUTHOR_DSC", " This will be used if there is no author name in gathered data.");
define("_CO_IMSOURCES_EXPORTER_MODULE", "Module");
define("_CO_IMSOURCES_EXPORTER_MODULE_DSC", " Where export collected data?");
define("_CO_IMSOURCES_EXPORTER_OBJECT", "Object");
define("_CO_IMSOURCES_EXPORTER_OBJECT_DSC", " Object in module where export data.");
define("_CO_IMSOURCES_EXPORTER_FIELD_AUTHOR", "Field name");
define("_CO_IMSOURCES_EXPORTER_FIELD_AUTHOR_DSC", " ");
define("_CO_IMSOURCES_EXPORTER_FIELD_TITLE", "Field title");
define("_CO_IMSOURCES_EXPORTER_FIELD_TITLE_DSC", " ");
define("_CO_IMSOURCES_EXPORTER_FIELD_CONTENT", "Field content");
define("_CO_IMSOURCES_EXPORTER_FIELD_CONTENT_DSC", " ");
define("_CO_IMSOURCES_EXPORTER_FIELD_DATE", "Field date");
define("_CO_IMSOURCES_EXPORTER_FIELD_DATE_DSC", " ");
define("_CO_IMSOURCES_EXPORTER_POST_USER", "Who will post collected data to module?");
define("_CO_IMSOURCES_EXPORTER_POST_USER_DSC", " ");
define("_CO_IMSOURCES_EXPORTER_TEMPLATE", "Template");
define("_CO_IMSOURCES_EXPORTER_TEMPLATE_DSC", "Post template");
define("_CO_IMSOURCES_EXPORTER_MAX_TIME", "Max time");
define("_CO_IMSOURCES_EXPORTER_MAX_TIME_DSC", "Max. time to seek for data for export (in days)");