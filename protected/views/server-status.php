<?php if(config('server_status.allow')) { ?>
    <?php if($content) { ?>
        <ul>
            <?php foreach($content as $gsId => $row) { ?>
                <li class="<?php echo $row['gs_status'] ?>">
                    <?php if(isset($row['error'])) { ?>
                        <?php echo $row['error'] ?>
                    <?php } else { ?>
                        <span class="count"><?php echo $row['online'] ?></span>
                        <span class="name">
                            <?php echo CHtml::link($row['gs']->name, array('/stats/default/index', 'gs_id' => $row['gs']->id)) ?>
                        </span>
                    <?php } ?>
                </li>
            <?php } ?>
            <?php if(count($content) > 1) { ?>
                <?php echo $totalOnline ?>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <?php echo Yii::t('main', 'Нет данных.') ?>
    <?php } ?>
<?php } else { ?>
    <?php echo Yii::t('main', 'Модуль отключен.') ?>
<?php } ?>