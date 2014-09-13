<table class="table">
    <tr class="divider">
        <td colspan="2"><?php echo Yii::t('main', 'Разное') ?></td>
    </tr>
    <tr>
        <td width="30%"><?php echo Yii::t('main', 'В игре') ?></td>
        <td width="70%"><?php echo $countOnline ?></td>
    </tr>
    <tr class="even">
        <td><?php echo Yii::t('main', 'Аккаунтов') ?></td>
        <td><?php echo $countAccounts ?></td>
    </tr>
    <tr>
        <td><?php echo Yii::t('main', 'Персонажей') ?></td>
        <td><?php echo $countCharacters ?></td>
    </tr>
    <tr class="even">
        <td><?php echo Yii::t('main', 'Кланов') ?></td>
        <td><?php echo $countClans ?></td>
    </tr>
    <tr>
        <td><?php echo Yii::t('main', 'Мужчин') ?></td>
        <td><?php echo $countMen ?></td>
    </tr>
    <tr class="even">
        <td><?php echo Yii::t('main', 'Женщин') ?></td>
        <td><?php echo $countFemale ?></td>
    </tr>
    <tr class="divider">
        <td colspan="2"><?php echo Yii::t('main', 'Расы') ?></td>
    </tr>
    <tr>
        <td><?php echo Yii::t('main', 'Люди') ?></td>
        <td><?php echo $races_percentage['human'] ?>% (<?php echo $races['human'] ?>)</td>
    </tr>
    <tr class="even">
        <td><?php echo Yii::t('main', 'Эльфы') ?></td>
        <td><?php echo $races_percentage['elf'] ?>% (<?php echo $races['elf'] ?>)</td>
    </tr>
    <tr>
        <td><?php echo Yii::t('main', 'Темные Эльфы') ?></td>
        <td><?php echo $races_percentage['dark_elf'] ?>% (<?php echo $races['dark_elf'] ?>)</td>
    </tr>
    <tr class="even">
        <td><?php echo Yii::t('main', 'Орки') ?></td>
        <td><?php echo $races_percentage['ork'] ?>% (<?php echo $races['ork'] ?>)</td>
    </tr>
    <tr>
        <td><?php echo Yii::t('main', 'Гномы') ?></td>
        <td><?php echo $races_percentage['dwarf'] ?>% (<?php echo $races['dwarf'] ?>)</td>
    </tr>
    <?php if(isset($races_percentage['kamael'])) { ?>
        <tr class="even">
            <td><?php echo Yii::t('main', 'Камаэли') ?></td>
            <td><?php echo $races_percentage['kamael'] ?>% (<?php echo $races['kamael'] ?>)</td>
        </tr>
    <?php } ?>
    <tr class="divider">
        <td colspan="2"><?php echo Yii::t('main', 'Рейты') ?></td>
    </tr>
    <tr>
        <td>Exp</td>
        <td><?php echo $this->_gs->exp ?></td>
    </tr>
    <tr class="even">
        <td>Sp</td>
        <td><?php echo $this->_gs->sp ?></td>
    </tr>
    <tr>
        <td>Adena</td>
        <td><?php echo $this->_gs->adena ?></td>
    </tr>
    <tr class="even">
        <td>Drop</td>
        <td><?php echo $this->_gs->drop ?></td>
    </tr>
    <tr>
        <td>Items</td>
        <td><?php echo $this->_gs->items ?></td>
    </tr>
    <tr class="even">
        <td>Spoil</td>
        <td><?php echo $this->_gs->spoil ?></td>
    </tr>
    <tr>
        <td>Quest drop</td>
        <td><?php echo $this->_gs->q_drop ?></td>
    </tr>
    <tr class="even">
        <td>Quest reward</td>
        <td><?php echo $this->_gs->q_reward ?></td>
    </tr>
    <tr>
        <td>Raid boss</td>
        <td><?php echo $this->_gs->rb ?></td>
    </tr>
    <tr class="even">
        <td>Epic Raid boss</td>
        <td><?php echo $this->_gs->erb ?></td>
    </tr>
</table>