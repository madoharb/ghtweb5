<?php
/**
 * @var ShopController $this
 * @var ShopCategories[] $categories
 */

$title_ = Yii::t('main', 'Магазин');
$this->pageTitle = $title_;
?>
<?php
$this->breadcrumbs=array($title_);
?>

<?php $this->renderPartial('//cabinet/shop/menu', array('categories' => $categories)) ?>


<script>
(function(){

    'use strict';

    if((window.location.hash).indexOf('success') + 1){
        alert("Спасибо!\n" + 'Ваш баланс успешно пополнен');
    }else if((window.location.hash).indexOf('fail') + 1){
        alert("Извините, что-то пошло не так.\n" + 'Действие временно недоступно. Повторите попытку позже.');
    }

})();
</script>