<?php
$rootDir = Yii::getPathOfAlias('webroot');
$error   = FALSE;
?>
<div class="page-header">
    <h1><?php echo Yii::t('install', 'Шаг 1, проверка системы') ?></h1>
</div>

<h3><?php echo Yii::t('install', 'Проверка необходимых папок') ?></h3>

<ul>
    <?php
    foreach(array('assets', 'protected/runtime') as $folder)
    {
        $isDir = is_dir($rootDir . DIRECTORY_SEPARATOR . $folder);

        if(!$isDir && $error == FALSE)
        {
            $error = TRUE;
        }

        echo '<li>' . $folder . ' <span class="label label-' . ($isDir ? 'success' : 'danger') . '">' . ($isDir ? 'OK' : 'Создайте папку')  . '</span></li>';
    }
    ?>
</ul>

<?php if(!$error) { ?>

    <h3><?php echo Yii::t('install', 'Проверка прав на запись') ?></h3>

    <ul>
        <?php
        foreach(array('assets', 'protected/config', 'protected/runtime', 'uploads/images/gallery', 'uploads/images/shop/', 'uploads/images/shop/packs') as $folder)
        {
            $isWritable = is_writable($rootDir . DIRECTORY_SEPARATOR . $folder);

            if(!$isWritable && $error == FALSE)
            {
                $error = TRUE;
            }

            echo '<li>' . $folder . ' <span class="label label-' . ($isDir ? 'success' : 'danger') . '">' . ($isDir ? 'OK' : 'Установите права на запись 0777')  . '</span></li>';
        }
        ?>
    </ul>

<?php } ?>

<?php
if(!$error)
{
    echo CHtml::link(Yii::t('install', 'Шаг 2'), array('/install/default/step2'), array('class' => 'btn btn-primary'));
}
?>