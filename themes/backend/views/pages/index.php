<?php
/**
 * @var PagesController $this
 * @var CActiveDataProvider $dataProvider
 * @var Pages[] $data
 */

$title_ = Yii::t('backend', 'Страницы');
$this->pageTitle = $title_;
$this->breadcrumbs = array($title_);
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>


<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => array('/backend/' . $this->getId() . '/index'),
)) ?>

    <?php echo HTML::link(Yii::t('backend', 'Создать'), array('/backend/' . $this->getId() . '/form'), array('class' => 'btn btn-primary')) ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th><?php echo $model->getAttributeLabel('title') ?></th>
                <th width="20%"><?php echo $model->getAttributeLabel('page') ?></th>
                <th width="10%"><?php echo $model->getAttributeLabel('status') ?></th>
                <th width="20%"><?php echo $model->getAttributeLabel('created_at') ?></th>
                <th width="10%"><?php echo $model->getAttributeLabel('lang') ?></th>
                <th width="12%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $form->textField($model, 'id', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'title', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'page', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->dropDownList($model, 'status', $model->getStatusList(), array('class' => 'form-control input-sm', 'empty' => '-- select --')) ?></td>
                <td></td>
                <td><?php echo $form->dropDownList($model, 'lang', app()->params['languages'], array('class' => 'form-control input-sm', 'empty' => '-- select --')) ?></td>
                <td>
                    <button type="submit" class="btn btn-primary glyphicon glyphicon-search" title="<?php echo Yii::t('backend', 'Искать') ?>" rel="tooltip"></button>
                    <?php echo HTML::link('', array('/backend/' . $this->getId() . '/index'), array('class' => 'btn btn-default glyphicon glyphicon-ban-circle', 'title' => Yii::t('backend', 'Сбросить'), 'rel' => 'tooltip')) ?>
                </td>
            </tr>
            <?php if($data = $dataProvider->getData()) { ?>
                <?php foreach($data as $row) { ?>
                    <tr>
                        <td><?php echo $row->getPrimaryKey() ?></td>
                        <td><?php echo e($row->title) ?></td>
                        <td><?php echo HTML::link($row->page, array('/page/default/index', 'page_name' => $row->page), array('target' => '_blank')) ?></td>
                        <td><span class="label <?php echo ($row->isStatusOn() ? 'label-success' : 'label-default') ?>"><?php echo $row->getStatus() ?></span></td>
                        <td><?php echo $row->getCreatedAt() ?></td>
                        <td><?php echo (isMultiLang() ? $row->getLangText() : '-') ?></td>
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
                    <td colspan="6"><?php echo Yii::t('backend', 'Нет данных.') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>
