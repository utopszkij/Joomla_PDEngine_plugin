#EDE-IDE Joomla_PDEngine_plugin
software documentation



accesRight policy

store in dataStorage: field of group by json_encoded

by Group

$quota1 activate, $quote2 close, $quota3 archive, $quota4 delete

by Member, by Rank

$quota1 activate, $quote2 exlude

by Vote

$quota1 activate, $quote2 abort, $quota3 archive, $quota4 delete,

$quota5 dis1Close, $quota6 voksvalid

##class Policy extends [](.md)

protected $parent; [Group](Group) or Vote    

protected $quota1; // number | number% | (expression) | MANUAL | PLUGIN

protected $quota2; // number | number% | (expression) | MANUAL | PLUGIN

protected $quota3; // number | number% | (expression) | MANUAL | PLUGIN

protected $quota4; // number | number% | (expression) | MANUAL | PLUGIN

protected $quota5; // number | number% | (expression) | MANUAL | PLUGIN

protected $quota6; // number | number% | (expression) | MANUAL | PLUGIN

public $config; [[{"state":str,]([{"state":str,) "action":str,     

**Methods**

[__construct](items/Policy___construct.md)

[canDo](items/Policy_canDo.md)



[source](../../site/models/model.php)

[sw.docs root](./)

*created by documentCreator*

