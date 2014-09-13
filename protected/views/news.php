<?php
/**
 * @var CActiveDataProvider $dataProvider
 * @var News[] $data
 */

$this->pageTitle = Yii::t('main', 'Новости');
?>

<?php if($data = $dataProvider->getData()) { ?>

    <div class="entry">
        <div class="scroll-pane">
            <?php foreach($data as $row) { ?>
                <div style="margin-bottom: 30px;">
                    <header>
                        <h1><?php echo CHtml::link($row->title, array('/news/default/detail', 'news_id' => $row->id)) ?></h1>
                    </header>
                    <div class="desc"><?php echo $row->description ?></div>
                    <footer>
                        <ul>
                            <li><?php echo Yii::t('main', 'Дата') ?>: <span class="label label-info"><?php echo $row->getDate() ?></span></li>
                        </ul>
                    </footer>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="news">
        <?php $this->widget('CLinkPager', array(
            'pages' => $dataProvider->getPagination(),
        )) ?>
    </div>
<?php } else { ?>
    <?php echo Yii::t('main', 'Нет данных') ?>
<?php } ?>