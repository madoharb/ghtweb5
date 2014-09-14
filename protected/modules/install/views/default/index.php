<?php
$rootDir = Yii::getPathOfAlias('webroot');
$error   = FALSE;
?>
<div class="page-header">
    <h1><?php echo Yii::t('install', 'Шаг 1, проверка системы') ?></h1>
</div>

<div class="alert alert-danger">
    <h4>Внимание!</h4>
    - Убедитесь что в БД куда будет установлен сайт не содержатся таблицы с префиксом <b>ghtweb_</b><br>
    - Пользователь от БД должен имень права на <b>SELECT, UPDATE, INSERT ,DELETE, TRUNCATE</b><br>
    - Если по какой-либо причине Вы хотите переустановить CMS то очистите таблицу куда будет установлен сайт и верните папку <b>install</b>
</div>

<h3><?php echo Yii::t('install', 'Проверка прав на запись') ?></h3>

<ul>
    <?php
    $folders = array('assets', 'protected/config', 'protected/runtime', 'uploads/images/gallery', 'uploads/images/shop/', 'uploads/images/shop/packs', 'protected/config/database.php');

    foreach($folders as $folder)
    {
        $isWritable = HTML::isWritable($rootDir . DIRECTORY_SEPARATOR . $folder);

        if($isWritable === FALSE)
        {
            $error = TRUE;
        }

        echo '<li>/' . $folder . ' <span class="label label-' . ($isWritable ? 'success' : 'danger') . '">' . ($isWritable ? 'OK' : 'Установите права на запись 0777')  . '</span></li>';
    }
    ?>
</ul>

<?php
if($error === FALSE)
{
    echo CHtml::link(Yii::t('install', 'Шаг 2'), array('step2'), array('class' => 'btn btn-primary'));
}
?>