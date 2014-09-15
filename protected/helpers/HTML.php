<?php 

class HTML extends CHtml
{
    public static function myErrorSummary($model, $class = 'danger')
    {
        return parent::errorSummary($model, '', '', array(
            'class' => 'alert alert-' . $class,
        ));
    }

    /**
     * Проверяет доступность папки/файла на запись
     *
     * @param string $path
     *
     * @return bool
     */
    public static function isWritable($path)
    {
        return is_writable($path) || @chmod($path, 0777) && is_writable($path);
    }
}
