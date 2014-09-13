<div class="page-header">
    <h1><?php echo Yii::t('install', 'Шаг 7, Завершение') ?></h1>
</div>

<p>Залейте в БД сайта этот дамп <code>/protected/data/ghtweb_all_items.sql</code></p>
<p>Удалите папку <code>/protected/modules/install</code></p>
<p>Удалите файл <code>/protected/data/ghtweb_all_items.sql</code></p>

<?php echo CHtml::link('Перейти на сайт', array('/index/default/index'), array('class' => 'btn btn-primary')) ?>