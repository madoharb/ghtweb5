<?php
/**
 * @var ShopController $this
 * @var ShopCategories[] $categories
 */
?>

<?php if($categories) { ?>
    <?php
    $items = array();

    foreach($categories as $category)
    {
        $items[] = array(
            'label' => $category->name,
            'url'   => array('/cabinet/shop/category', 'category_link' => $category->link),
        );
    }

    $this->widget('zii.widgets.CMenu',array(
        'htmlOptions' => array(
            'class' => 'tabs',
        ),
        'items' => $items,
    )) ?>
<?php } ?>