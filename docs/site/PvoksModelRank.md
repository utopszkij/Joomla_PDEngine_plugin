#EDE-IDE Joomla_PDEngine_plugin
software documentation



Adatmodel az EDE_IDE szavazó rendszerhez

Rank az egyes csoportokban használt tisztségek definiálása

Licence: GNU/GPL

Author: Fogler Tibor tibor.fogler@gmail.com

##class PvoksModelRank extends [extends](extends.md)

protected $_group; // ehez a csoporthoz tartozik

public $group_id; // ehez a csoporthoz tartozik

public $id [0;](0;) // egyedi azonosító   

public $title ['';]('';) // rövid megnevezés   

public $desc ['';]('';) // hosszabb leírás   

public $inMember [0;](0;) // NOT_REQUED | MEMBER | SUBMEMBER

public $userGroups = array(); // userGroup lista


**Methods**

[__construct](items/PvoksModelRank___construct.md)

[item](items/PvoksModelRank_item.md)

[save](items/PvoksModelRank_save.md)

[check](items/PvoksModelRank_check.md)

[canDelete](items/PvoksModelRank_canDelete.md)

[newRecord](items/PvoksModelRank_newRecord.md)



[source](../../site/models/rankModel.php)

[sw.docs root](./)

*created by documentCreator*

