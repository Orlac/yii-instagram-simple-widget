yii-instagram-simple-widget
===========================
INSTALLATION
------------

config.php:

    YiiBase::setPathOfAlias('instagramWidget', 'path/to/ext/instagramWidget'));


view.php


    <? $this->widget('instagramWidget\Widget', array('userName' => '...', 'clientId'=>'...', 'count'=>20) ); ?>

