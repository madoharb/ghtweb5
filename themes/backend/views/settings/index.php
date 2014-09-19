<?php
$this->pageTitle = 'Настройки';
$this->breadcrumbs = array('Настройки');

$assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('webroot.themes.' . themeName() . '.assets'), FALSE, -1, YII_DEBUG);

js($assetsUrl . '/js/config.js', CClientScript::POS_END);
js($assetsUrl . '/js/serializeForm.js', CClientScript::POS_END);

// jQuery UI
js($assetsUrl . '/libs/jquery-ui.min.js', CClientScript::POS_END);

// jQuery storage
js($assetsUrl . '/js/jquery.storageapi.min.js', CClientScript::POS_END);

clientScript()->registerScript('jqueryUiSortable', '
$(function(){

    var storage, $tabBlock;

    storage   = $.localStorage;
    $tabBlock = $(".tab-pane");

    $tabBlock.sortable({
        axis: "y",
        handle: ".glyphicon-align-justify",
        update: function(){
            var data    = [],
                groupId = 0;
            $.each($(this).find(".form-group"), function(i){
                var $label   = $(this),
                    _order   = ++i,
                    _id      = parseInt($label.data("id")) || 0,
                    _groupId = parseInt($label.data("group")) || 0;

                if(_order > 0 && _id > 0 && _groupId > 0) {
                    if(groupId == 0) {
                        groupId = _groupId;
                    }
                    data.push(_id + "-" + _order);
                }
            });
            // Save to BD
            APP.globalAjaxLoading("start");
            $.getJSON("' . app()->createAbsoluteUrl('backend/config/sort') . '", {groupId: groupId, data: data.join(",")}, function(res) {
                APP.globalAjaxLoading("stop");
            });
        },
        start: function(){
            $("[rel=\"tooltip\"]").tooltip("destroy");
        },
        stop: function(){
            $("[rel=\"tooltip\"]").tooltip();
        }
    });
});', CClientScript::POS_END);
?>

<style>
    .glyphicon-align-justify
    {
        display: inline-block;
        cursor: move;
        color: #DADADA;
        margin-right: 7px;
        font-size: 12px;
    }
</style>

<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
)) ?>

    <ul class="nav nav-tabs">
        <?php foreach($model as $i => $group) { ?>
            <li<?php echo ($i == 0 ? ' class="active"' : '') ?>><a href="#config-<?php echo $i ?>" data-toggle="tab"><?php echo e($group->name) ?></a></li>
        <?php } ?>
    </ul>

    <div class="tab-content">
        <?php foreach($model as $i => $group) { ?>
            <div class="tab-pane fade<?php echo ($i == 0 ? ' in active' : '') ?>" id="config-<?php echo $i ?>">
                <?php foreach($group->config as $config) { ?>
                    <div class="form-group" data-group="<?php echo $group->getPrimaryKey() ?>" data-id="<?php echo $config->getPrimaryKey() ?>">
                        <label class="control-label">
                            <span class="glyphicon glyphicon-align-justify" rel="tooltip" title="<?php echo Yii::t('backend', 'Сортировка (зажмите кнопку и тащите настройку вверх или вниз)') ?>"></span>
                            <?php echo e($config->label) ?>
                            <a class="glyphicon glyphicon-retweet" title="<?php echo Yii::t('main', 'Отменить') ?>" rel="tooltip" data-fieldname="<?php echo $config->param ?>"></a>
                        </label>
                        <?php echo $config->getField() ?>
                    </div>
                <?php } ?>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><?php echo Yii::t('main', 'Сохранить') ?></button>
                </div>
            </div>
        <?php } ?>
    </div>

<?php $this->endWidget() ?>
