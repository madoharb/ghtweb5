<?php
/**
 * @var NewsController $this
 * @var CActiveDataProvider $dataProvider
 * @var News[] $data
 */

$title_ = Yii::t('backend', 'Новости');
$this->pageTitle = $title_;
$this->breadcrumbs = array($title_);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => array('/backend/' . $this->getId() . '/index'),
)) ?>

    <?php echo HTML::link(Yii::t('backend', 'Создать'), array('/backend/' . $this->getId() . '/add'), array('class' => 'btn btn-primary')) ?>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th><?php echo $model->getAttributeLabel('title') ?></th>
                <th width="10%"><?php echo $model->getAttributeLabel('status') ?></th>
                <th width="20%"><?php echo $model->getAttributeLabel('created_at') ?></th>
                <th width="12%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $form->textField($model, 'id', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'title', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->dropDownList($model, 'status', array('' => Yii::t('backend', '-- select --')) + $model->getStatusList(), array('class' => 'form-control input-sm')) ?></td>
                <td></td>
                <td>
                    <button type="submit" class="btn btn-primary glyphicon glyphicon-search" title="<?php echo Yii::t('backend', 'Искать') ?>" rel="tooltip"></button>
                    <?php echo HTML::link('', array('/backend/news/index'), array('class' => 'btn btn-default glyphicon glyphicon-ban-circle', 'title' => Yii::t('backend', 'Сбросить'), 'rel' => 'tooltip')) ?>
                </td>
            </tr>
            <?php if($data = $dataProvider->getData()) { ?>
                <?php foreach($data as $row) { ?>
                    <tr>
                        <td><?php echo $row->id ?></td>
                        <td><?php echo e($row->title) ?></td>
                        <td><span class="label <?php echo ($row->isStatusOn() ? 'label-success' : 'label-default') ?>"><?php echo $row->getStatus() ?></span></td>
                        <td><?php echo $row->getCreatedAt() ?></td>
                        <td>
                            <ul class="actions list-unstyled">
                                <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/form', 'id' => $row->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                                <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/allow', 'id' => $row->id), array('class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? Yii::t('backend', 'Выключить') : Yii::t('backend', 'Включить')), 'rel' => 'tooltip')) ?></li>
                                <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/del', 'id' => $row->id), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
                            </ul>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5"><?php echo Yii::t('backend', 'Нет данных.') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
