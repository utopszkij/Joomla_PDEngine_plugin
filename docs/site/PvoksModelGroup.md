#EDE-IDE Joomla_PDEngine_plugin
software documentation



Adatmodellek az EDE_IDE szavazó rendszerhez

Group

Licence: GNU/GPL

Author: Fogler Tibor tibor.fogler@gmail.com

##class PvoksModelGroup extends [extends](extends.md)

protected $_parent;

public $id; [ID](ID)      

public $parent_id;

public $title; // group short name

public $desc; // group description

public $state; // group state PROPOSED | ACTIVE | CLOSED

public $groupType; [DUMBER](DUMBER) | DUMBER_META | ADHOC | ADHOC_META

public $memberPolicy; [Policy](Policy)      

public $rankPolicy; [Policy](Policy)      

public $subGroupPolicy; [Policy](Policy)      

public $votePolicy; [Policy](Policy)      

public $commentPolicy; [Policy](Policy)      

public $_ranks; [RecordSet_of_PvoksModelRank](RecordSet_of_PvoksModelRank)      

public $_members; [RecordSet_of_Pv);](RecordSet_of_Pv);)      

public $_subGroups; [RecordSet_of_PvoksModelGroup](RecordSet_of_PvoksModelGroup)      

public $_votes; [RecordSet_of_PvoksModelVote](RecordSet_of_PvoksModelVote)      

public $_comments; [RecordSet_of_PvoksModelComment](RecordSet_of_PvoksModelComment)      

public $_endorses; [RecordSet_of_PvoksModelEndorse](RecordSet_of_PvoksModelEndorse)      


**Methods**

[__construct](items/PvoksModelGroup___construct.md)

[load](items/PvoksModelGroup_load.md)

[newRecord](items/PvoksModelGroup_newRecord.md)

[store](items/PvoksModelGroup_store.md)

[isSubMember](items/PvoksModelGroup_isSubMember.md)

[isMember](items/PvoksModelGroup_isMember.md)

[isMemberRank](items/PvoksModelGroup_isMemberRank.md)

[getMembersByRank](items/PvoksModelGroup_getMembersByRank.md)

[getVoksCountByUser](items/PvoksModelGroup_getVoksCountByUser.md)



[source](../../site/models/groupModel.php)

[sw.docs root](./)

*created by documentCreator*

