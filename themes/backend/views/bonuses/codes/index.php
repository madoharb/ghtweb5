<?php
$title_ = Yii::t('backend', 'Бонус - коды');
$this->pageTitle = $title_;
$this->breadcrumbs = array($title_);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php echo HTML::link(Yii::t('backend', 'Создать'), array('/backend/bonuses/codeAdd'), array('class' => 'btn btn-primary')) ?>

<table class="table">
    <thead>
        <tr>
            <th width="5%">ID</th>
            <th width="20%"><?php echo Yii::t('backend', 'Код') ?></th>
            <th><?php echo Yii::t('backend', 'Бонус') ?></th>
            <th width="10%"><?php echo Yii::t('backend', 'Лимит') ?></th>
            <th width="14%"><?php echo Yii::t('backend', 'Кол-во использований') ?></th>
            <th width="14%"><?php echo Yii::t('backend', 'Статус') ?></th>
            <th width="10%"></th>
        </tr>
    </thead>
    <tbody>
        <?php if($data = $dataProvider->getData()) { ?>
            <?php foreach($data as $row) { ?>
                <tr>
                    <td><?php echo $row->id ?></td>
                    <td><?php echo $row->code ?></td>
                    <td><?php echo CHtml::link(CHtml::encode($row->bonusInfo->title), array('/backend/bonuses/form', 'id' => $row->bonusInfo->getPrimaryKey()), array('target' => '_blank')) ?></td>
                    <td><?php echo $row->limit ?></td>
                    <td><?php echo count($row->bonusLog) ?></td>
                    <td><?php echo $row->getStatus() ?></td>
                    <td>
                        <ul class="actions list-unstyled">
                            <li><?php echo HTML::link('', array('/backend/bonuses/codeEdit', 'code_id' => $row->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/bonuses/codeAllow', 'code_id' => $row->id), array('class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? Yii::t('backend', 'Выключить') : Yii::t('backend', 'Включить')), 'rel' => 'tooltip')) ?></li>
                            <li><?php echo HTML::link('', array('/backend/bonuses/codeDel', 'code_id' => $row->id), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
                        </ul>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="5"><?php echo Yii::t('backend', 'Нет данных') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
