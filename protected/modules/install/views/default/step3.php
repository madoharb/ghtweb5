<div class="page-header">
    <h1><?php echo Yii::t('install', 'Шаг 3, установка таблиц в БД') ?></h1>
</div>

<pre><?php echo $res ?></pre>

<?php echo CHtml::link(Yii::t('install', 'Далее'), array('/install/default/step4'), array('class' => 'btn btn-primary')) ?>