<?php
/**
 * @var LoginServersController $this
 * @var CActiveDataProvider $dataProvider
 * @var Ls[] $data
 * @var ActiveForm $form
 */

$title_ = Yii::t('backend', 'Логин сервера');
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

    <table class="table">
        <thead>
            <tr>
                <th width="5%">ID</th>
                <th><?php echo Yii::t('backend', 'Название') ?></th>
                <th width="10%"><?php echo Yii::t('backend', 'IP') ?></th>
                <th width="5%"><?php echo Yii::t('backend', 'Порт') ?></th>
                <th width="15%"><?php echo Yii::t('backend', 'Версия') ?></th>
                <th width="10%"><?php echo Yii::t('backend', 'Статус') ?></th>
                <th width="12%"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?php echo $form->textField($model, 'id', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'name', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'ip', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->textField($model, 'port', array('class' => 'form-control input-sm')) ?></td>
                <td><?php echo $form->dropDownList($model, 'version', app()->params['server_versions'], array('class' => 'form-control input-sm', 'empty' => Yii::t('backend', 'Выбрать'))) ?></td>
                <td><?php echo $form->dropDownList($model, 'status', array('' => Yii::t('backend', 'Выбрать')) + $model->getStatusList(), array('class' => 'form-control input-sm')) ?></td>
                <td>
                    <button type="submit" class="btn btn-primary glyphicon glyphicon-search" title="<?php echo Yii::t('backend', 'Искать') ?>"></button>
                    <?php echo HTML::link('', array('/backend/' . $this->getId() . '/index'), array('class' => 'btn btn-default glyphicon glyphicon-ban-circle', 'title' => Yii::t('backend', 'Сбросить'))) ?>
                </td>
            </tr>
            <?php if($data = $dataProvider->getData()) { ?>
                <?php foreach($data as $row) { ?>
                    <tr>
                        <td><?php echo $row->id ?></td>
                        <td><?php echo e($row->name) ?></td>
                        <td><?php echo $row->ip ?></td>
                        <td><?php echo $row->port ?></td>
                        <td><?php echo $row->version ?></td>
                        <td><span class="label <?php echo ($row->isStatusOn() ? 'label-success' : 'label-default') ?>"><?php echo $row->getStatus() ?></span></td>
                        <td>
                            <ul class="actions list-unstyled">
                                <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/form', 'ls_id' => $row->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                                <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/allow', 'ls_id' => $row->id), array('class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? Yii::t('backend', 'Выключить') : Yii::t('backend', 'Включить')), 'rel' => 'tooltip')) ?></li>
                                <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/accounts', 'ls_id' => $row->id), array('class' => 'glyphicon glyphicon-th', 'title' => Yii::t('backend', 'Игровые аккаунты'), 'rel' => 'tooltip')) ?></li>
                                <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/del', 'ls_id' => $row->id), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
                            </ul>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="7"><?php echo Yii::t('backend', 'Нет данных.') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
)) ?>