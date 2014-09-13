<?php
$title_ = Yii::t('main', 'Персонажи');
$this->pageTitle = $title_;

$this->breadcrumbs = array($title_);
?>


<?php if(is_string($error)) { ?>
    <div class="alert alert-danger">
        <?php echo $error ?>
    </div>
<?php } ?>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php if($error === FALSE) { ?>
    
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th><?php echo Yii::t('main', 'Имя') ?></th>
                <th><?php echo Yii::t('main', 'Клан') ?></th>
                <th><?php echo Yii::t('main', 'Статус') ?></th>
                <th><?php echo Yii::t('main', 'Время в игре') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if($characters) { ?>
                <?php foreach($characters as $i => $char) { ?>
                    <tr class="<?php echo $i % 2 == 0 ? 'odd' : 'even' ?>">
                        <td><?php echo ++$i ?></td>
                        <td><?php echo e($char['char_name']) ?><br><p class="class-name" style="margin: 0;"><?php echo Lineage::getClassName($char['base_class']) ?> <span>(<?php echo $char['level'] ?>)</span></p></td>
                        <td><?php echo ($char['clan_name'] ? e($char['clan_name']) : Yii::t('main', 'нет')) ?></td>
                        <td><?php echo ($char['online'] ? '<span style="color: green;">' . Yii::t('main', 'В игре') . '</span>' : '<span style="color: red;">' . Yii::t('main', 'Не в игре') . '</span>') ?></td>
                        <td><?php echo Lineage::getOnlineTime($char['onlinetime']) ?></td>
                        <td>
                            <ul class="actions">
                                <li><?php echo CHtml::link('', array('/cabinet/characters/view', 'char_id' => $char['char_id']), array('class' => 'glyphicon glyphicon-eye-open', 'title' => Yii::t('main', 'Просмотр'), 'rel' => 'tooltip')) ?></li>
                                <?php if($char['online'] == 0) { ?>
                                    <li><?php echo CHtml::link('', array('/cabinet/characters/teleport', 'char_id' => $char['char_id']), array('class' => 'glyphicon glyphicon-cloud-upload', 'title' => Yii::t('main', 'Телепорт в город'), 'rel' => 'tooltip')) ?></li>
                                <?php } ?>
                            </ul>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="6"><?php echo Yii::t('main', 'Нет персонажей.') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php } ?>