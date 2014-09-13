<?php 
 
class NewsSocials extends CWidget
{
    public $url;
    public $title;



    public function init()
    {
        Yii::beginProfile(__CLASS__);

        $path = Yii::getPathOfAlias('app.widgets.NewsSocials.libs.share42');

        $assetsUrl = app()->getAssetManager()->publish($path, FALSE, -1, YII_DEBUG);

        js($assetsUrl . '/share42.js', CClientScript::POS_END);

        echo '<div class="share42init" data-url="' . $this->url . '" data-title="' . e($this->title) . '" data-path="' . app()->createAbsoluteUrl($assetsUrl) . '"></div>';

        Yii::endProfile(__CLASS__);
    }
}
 