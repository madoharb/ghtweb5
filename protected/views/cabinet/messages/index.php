<?php
/**
 * @var CActiveDataProvider $dataProvider
 * @var UserMessages[] $data
 */

$title_ = Yii::t('main', 'Личные сообщения');
$this->pageTitle = $title_;

$this->breadcrumbs=array($title_);
?>

<div class="entry">
    <div class="scroll-pane">
        <table class="table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th><?php echo Yii::t('main', 'Сообщение') ?></th>
                    <th width="10%"><?php echo Yii::t('main', 'Новое') ?></th>
                    <th width="25%"><?php echo Yii::t('main', 'Дата') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if($data = $dataProvider->getData()) { ?>
                    <?php foreach($data as $i => $row) { ?>
                        <tr>
                            <td><?php echo ++$i ?></td>
                            <td><?php echo CHtml::link($row->getShortMessage(5), array('/cabinet/messages/detail', 'id' => $row->getPrimaryKey())) ?></td>
                            <td><?php echo ($row->read == UserMessages::STATUS_NOT_READ ? Yii::t('main', 'Да') : Yii::t('main', 'Нет')) ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($row->created_at)) ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4"><?php echo Yii::t('main', 'Нет сообщений') ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php $this->widget('CLinkPager', array(
            'pages' => $dataProvider->getPagination(),
        )) ?>
    </div>
</div>


