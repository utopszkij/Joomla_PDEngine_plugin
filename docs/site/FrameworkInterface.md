#EDE-IDE Joomla_PDEngine_plugin
software documentation



Framework Interface a likvid demokrácia szoftverhez

Joomla változat

Licensz: GNU/GPL

szerző: Fogler Tibor tibor.fogler@gmail.com_addref

##class FrameworkInterface extends [](.md)

protected $secret; // string site secret key

protected $templateName; // string template name

protected $language; // string language code example: 'hu-HU'

protected $uri; // string site root URI

protected $model; [Model](Model) data model object   

protected $view; [View](View) viewer object    

protected $helper; [Helper](Helper) helper object    

protected $translate; // array_of_string translate strings key is token

public $defTask; // string default task

public $dataStorage; [DataStorage](DataStorage) data stroge object   

public $ds; [DataStorage](DataStorage) sort name of this->dataStorage  

public $input; [Input](Input) input object    

public $session; [Session](Session) session object    

public $user; [User](User) logged user object   

**Methods**

[__construct](items/FrameworkInterface___construct.md)

[remoteCall](items/FrameworkInterface_remoteCall.md)

[loadLanguage](items/FrameworkInterface_loadLanguage.md)

[_](items/FrameworkInterface__.md)

[login](items/FrameworkInterface_login.md)

[logout](items/FrameworkInterface_logout.md)



[source](../../site/joomlaFrameworkInterface.php)

[sw.docs root](./)

*created by documentCreator*

