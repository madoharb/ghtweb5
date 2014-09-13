<?php
/**
 * @var News $model
 */

$this->pageTitle = $model->title
?>

<h2 class="title"><?php echo e($model->title) ?></h2>

<div class="entry">
    <div class="scroll-pane">
        <div class="desc"><?php echo $model->text ?></div>

        <?php if(config('news.detail.socials') == 1) { ?>
            <?php $this->widget('app.widgets.NewsSocials.NewsSocials', array(
                'url'   => $this->createAbsoluteUrl('/news/default/detail', array('news_id' => $model->id)),
                'title' => $model->title,
            )) ?>
        <?php } ?>

        <?php echo CHtml::link(Yii::t('main', 'Назад'), array('/news/default/index')) ?>
    </div>
</div>