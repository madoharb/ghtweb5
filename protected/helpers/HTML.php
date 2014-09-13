<?php 

class HTML extends CHtml
{
    public static function myErrorSummary($model, $class = 'danger')
    {
        return parent::errorSummary($model, '', '', array(
            'class' => 'alert alert-' . $class,
        ));
    }
}
