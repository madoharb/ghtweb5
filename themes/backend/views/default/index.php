<?php
$this->breadcrumbs = array();

clientScript()->registerScript('updateTables', '
$(function(){
    $(".js-update").on("click", function(e){
        e.preventDefault();
        var $self = $(this);
        APP.globalAjaxLoading("start");
        $.getJSON($self[0].href, function(res){
            APP.globalAjaxLoading("stop");
            $(".update-info-block").empty().html("<code>" + res.msg + "</code>");
            if(res.status) {
                $self.remove();
                $(".alert-danger").remove();
                $(".js-old-version").text($(".js-new-version").text());
            }
        });
    });
    $(".js-update-tables").on("click", function(e){
        e.preventDefault();
        var $self = $(this);
        APP.globalAjaxLoading("start");
        $.get($self[0].href, function(res){
            APP.globalAjaxLoading("stop");
            $(".update-info-block").empty().html("<pre>" + res + "</pre>");
        });
    });
});
', CClientScript::POS_END);
?>

<h4>Информация о лицензии</h4>
Домен: <code><?php echo $this->license_info['domain'] ?></code><br>
Дата окончания лицензии: <code><?php echo date('Y-m-d H:i:s', $this->license_info['timeEnd']) ?></code>, <a href="http://cp.ghtweb.ru/cabinet/" target="_blank">продлить</a>
<br/><br/>
<h4>Информация о обновлении</h4>
Установленная версия: <code class="js-old-version"><?php echo $this->getVersion() ?></code><br>
Актуальная версия: <code class="js-new-version"><?php echo $this->checkNewVersion() ?></code>

<?php
$mv = str_replace('.', '', $this->getVersion());
$nv = str_replace('.', '', $this->checkNewVersion());

if($mv < $nv)
{
    echo CHtml::link('обновить до последней версии', array('/backend/default/update'), array('class' => 'js-update')) . '<br><br>';
    echo '<div class="alert alert-danger">
        Для того чтобы обновить сайт автоматически (кнопка выше) нужно дать всем папкам права на запись.
        При обновлении идет копирование новых файлов
    </div>';
}
?>

<div class="update-info-block"></div>

<br/>
<?php echo CHtml::link(Yii::t('main', 'Удалить кэш'), array('/backend/default/clearCache'), array('class' => 'btn btn-warning btn-sm js-clear-cache')) ?> &nbsp;
<?php echo CHtml::link(Yii::t('main', 'Установить миграции'), array('/backend/default/updateTables'), array('class' => 'btn btn-warning btn-sm js-update-tables')) ?>