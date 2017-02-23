#EDE-IDE Joomla_PDEngine_plugin
software documentation



pvoks component nezet view data model

@author Fogler Tibor

@copyrightNincs

@licenseGNU/GPL

replace 'pvoks' --> controllerName

'Pvoks' --> ControllerName

'ALAP' --> CONTROLLER_NAME

##class PvoksModel extends [extends](extends.md)

protected $componentName ['pvoks';]('pvoks';) // controllers/pvoks.php -ben PvoksControllerNezet class 

protected $viewName;    //views/view.html.php -ben PvoksViewNezet class, és js/nezet.js és models/forms/nezet.xml

protected $lngPre;     // leng pre

protected $dbTabla ['#__nezet';]('#__nezet';)      


**Methods**

[__create](items/PvoksModel___create.md)

[getListQuery](items/PvoksModel_getListQuery.md)

[canDelete](items/PvoksModel_canDelete.md)

[check](items/PvoksModel_check.md)

[getItem](items/PvoksModel_getItem.md)

[getTable](items/PvoksModel_getTable.md)

[save](items/PvoksModel_save.md)

[delete](items/PvoksModel_delete.md)



[source](../../admin/models/model.php)

[sw.docs root](./)

*created by documentCreator*

