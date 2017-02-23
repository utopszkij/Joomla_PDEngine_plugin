#EDE-IDE Joomla_PDEngine_plugin
software documentation



Adatmodellek az EDE_IDE szavazó rendszerhez

Vote

Licence: GNU/GPL

Author: Fogler Tibor tibor.fogler@gmail.com

##class PvoksModelVote extends [extends](extends.md)

public $id; [ID](ID)      

public $group_id;

public $title; // vote short name

public $desc; // vote description

public $state; // vote state PROPOSED | DISQ1 | DISQ2 | VOKS | CLOSED

public $choicePolicy; [Policy](Policy)      

public $choiceActiveQuota; // szám | szám% | (expression) | MANUAL

public $voksPolicy; [Policy](Policy)      

public $disq1CloseQuota; // szám | szám% | (expression) | MANUAL

public $disq2CloseQuota; // szám | szám% | (expression) | MANUAL

public $voksValidQuota; // szám | szám% | (expression) | MANUAL

public $resultPolicy; [Policy](Policy)      

public $commentPolicy; [Policy](Policy)      

public $_group; //

public $_choices; [RecordSet_of_PvoksModelChoise);](RecordSet_of_PvoksModelChoise);)      

public $_vokses; [RecordSet_of_PvoksModelVoks);](RecordSet_of_PvoksModelVoks);)      

public $_comments; [RecordSet_of_PvoksModelComment](RecordSet_of_PvoksModelComment)      

public $_endorses; [RecordSet_of_PvoksModelEndorse](RecordSet_of_PvoksModelEndorse)      


**Methods**

[__construct](items/PvoksModelVote___construct.md)

[load](items/PvoksModelVote_load.md)

[newRecord](items/PvoksModelVote_newRecord.md)

[store](items/PvoksModelVote_store.md)

[isSubMember](items/PvoksModelVote_isSubMember.md)

[isMember](items/PvoksModelVote_isMember.md)

[isMemberRank](items/PvoksModelVote_isMemberRank.md)

[getVoksCountByUser](items/PvoksModelVote_getVoksCountByUser.md)



[source](../../site/models/voteModel.php)

[sw.docs root](./)

*created by documentCreator*

