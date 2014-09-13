<?php 
 
class DatetimePicker extends CWidget
{
    /**
     * Поля к которым будет применен виджет
     * @var array
     */
    public $fields = array();



    public function run()
    {
        $assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.widgets.DatetimePicker.assets'), FALSE, -1, YII_DEBUG);

        js($assetsUrl . '/js/moment.min.js', CClientScript::POS_END);
        css($assetsUrl . '/css/bootstrap-datetimepicker.min.css');
        js($assetsUrl . '/js/bootstrap-datetimepicker.min.js', CClientScript::POS_END);

        clientScript()->registerScript('bootstrap-datepicker', '
        $("' . implode(', ', $this->fields) . '").datetimepicker({
            useMinutes: true,
            useSeconds: true
        });
        ');
    }
}
 