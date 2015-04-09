<?php
$this->breadcrumbs = array();
?>

<h4>Информация о лицензии</h4>
Домен: <code><?php echo ($this->license_info['domain'] ? implode(', ', $this->license_info['domain']) : '') ?></code><br>
Дата окончания лицензии: <code><?php echo date('Y-m-d H:i:s', $this->license_info['timeEnd']) ?></code>, <a href="http://cp.ghtweb.ru/cabinet/licenses/" target="_blank">продлить</a>
<br/><br/>
<h4>Версия CMS</h4>
Установленная версия: <code class="js-old-version"><?php echo $this->getVersion() ?></code><br><br>
<?php echo CHtml::link(Yii::t('main', 'Удалить кэш'), array('/backend/default/clearCache'), array('class' => 'btn btn-warning btn-sm js-clear-cache')) ?>