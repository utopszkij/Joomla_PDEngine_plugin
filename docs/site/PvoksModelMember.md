#EDE-IDE Joomla_PDEngine_plugin
software documentation



Adatmodel az EDE_IDE szavazó rendszerhez

Member csoport tagok

Licence: GNU/GPL

Author: Fogler Tibor tibor.fogler@gmail.com

##class PvoksModelMember extends [extends](extends.md)

protected $_group = false;

protected $_user = false;

public $id [0;](0;) // egyedi azonositó   

public $group_id [0;](0;) // csoport azonosító   

public $user_id [0;](0;) // user azonosító   

public $state [0;](0;) // tagság státusza PROPOSED | ACTIVE

public $ranks = array(); // key:rankTitle, value:rankState


**Methods**

[__construct](items/PvoksModelMember___construct.md)

[check](items/PvoksModelMember_check.md)

[canDelete](items/PvoksModelMember_canDelete.md)

[canDelete](items/PvoksModelMember_canDelete.md)

[newRecord](items/PvoksModelMember_newRecord.md)

[__construct](items/PvoksModelMember___construct.md)

[load](items/PvoksModelMember_load.md)



[source](../../site/models/memberModel.php)

[sw.docs root](./)

*created by documentCreator*

