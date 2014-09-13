<?php
// Fancybox
css('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.css');
js('//cdnjs.cloudflare.com/ajax/libs/fancybox/2.1.5/jquery.fancybox.min.js', CClientScript::POS_END);

$title_ = Yii::t('backend', 'Галерея');
$this->pageTitle = $title_;
$this->breadcrumbs = array($title_);
?>

<p><?php echo HTML::link(Yii::t('backend', 'Создать'), array('/backend/' . $this->getId() . '/form'), array('class' => 'btn btn-primary')) ?></p>

<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php if($data = $dataProvider->getData()) { ?>
    
    <style>
    .gallery-list .options,
    .gallery-list
    {
        word-spacing: -4px;
    }
        .gallery-list > li > a
        {
            margin-bottom: 7px;
        }
        .gallery-list .options > li,
        .gallery-list > li
        {
            margin: 0 10px 10px 0;
            display: inline-block;
            word-spacing: 0;
        }
    </style>

    <ul class="list-unstyled gallery-list">
        <?php foreach($data as $row) { ?>
            <li>
                <a href="<?php echo $row->getImgUrl() ?>" class="img-thumbnail fancybox" rel="gallery"><?php echo CHtml::image($row->getThumbUrl()) ?></a>
                <ul class="list-unstyled options">
                    <li><?php echo CHtml::link('', array('/backend/' . $this->getId() . '/form', 'id' => $row->id), array('class' => 'glyphicon glyphicon-pencil', 'title' => Yii::t('backend', 'Редактировать'), 'rel' => 'tooltip')) ?></li>
                    <li><?php echo HTML::link('', array('/backend/' . $this->getId() . '/allow', 'id' => $row->id), array('class' => ($row->isStatusOn() ? 'glyphicon glyphicon-eye-close' : 'glyphicon glyphicon-eye-open'), 'title' => ($row->isStatusOn() ? Yii::t('backend', 'Выключить') : Yii::t('backend', 'Включить')), 'rel' => 'tooltip')) ?></li>
                    <li><?php echo CHtml::link('', array('/backend/' . $this->getId() . '/del', 'id' => $row->id), array('class' => 'glyphicon glyphicon-remove', 'title' => Yii::t('backend', 'Удалить'), 'rel' => 'tooltip')) ?></li>
                </ul>
            </li>
        <?php } ?>
    </ul>

    <?php $this->widget('CLinkPager', array(
        'pages' => $dataProvider->getPagination(),
    )) ?>

<?php } else { ?>
    <?php echo Yii::t('main', 'Нет данных.') ?>
<?php } ?>
