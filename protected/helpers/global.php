<?php

function config($key)
{
    return Yii::app()->config->get($key);
}

/**
 * @return CWebApplication
 */
function app()
{
    return Yii::app();
}

/**
 * @return CClientScript
 */
function clientScript()
{
    return Yii::app()->getClientScript();
}

function css($url, $media = '')
{
    Yii::app()->clientScript->registerCssFile($url, $media);
}

function js($url, $position = null)
{
    Yii::app()->clientScript->registerScriptFile($url, $position);
}

function prt($var, $depth = 10, $highlight = TRUE)
{
    echo CVarDumper::dumpAsString($var, $depth, $highlight);
}

function e($text)
{
    return CHtml::encode($text);
}

function img($src, $alt = '', $htmlOptions = array())
{
    return CHtml::image($src, $alt, $htmlOptions);
}

function url($route, $params = array(), $ampersand = '&')
{
    return Yii::app()->urlManager->createUrl($route, $params, $ampersand);
}

/**
 * @return WebUser
 */
function user()
{
    return Yii::app()->user;
}

/**
 * @return WebAdmin
 */
function admin()
{
    return Yii::app()->admin;
}

/*function t($category, $message, $params = array(), $source = null, $language = null)
{
    return Yii::t($category, $message, $params, $source, $language);
}*/

/**
 * @return CHttpSession
 */
function session()
{
    return Yii::app()->getSession();
}

/**
 * @return CHttpRequest
 */
function request()
{
    return Yii::app()->getRequest();
}

/**
 * @return CDbConnection
 */
function db()
{
    return Yii::app()->getDb();
}

function v($model, $attribute, $defaultValue = null)
{
    return CHtml::encode(CHtml::value($model, $attribute, $defaultValue));
}

function sqlDateTime($timestamp = NULL)
{
    if ($timestamp === NULL)
    {
        $timestamp = time();
    }

    return formatDate($timestamp);
}

function themeName()
{
    return isset(Yii::app()->theme->name) ? Yii::app()->theme->name : '';
}

function theme()
{
    return 'themes/' . themeName();
}

function randomString($length = 10)
{
    $chars = array_merge(range(0,9), range('a','z'), range('A','Z'));
    shuffle($chars);
    return implode(array_slice($chars, 0, $length));
}

/**
 * @return CCache
 */
function cache()
{
    return Yii::app()->cache;
}

/**
 * @param string $type gs|ls
 * @param int $id
 *
 * @return Lineage
 */
function l2($type, $id)
{
    return Lineage::getInstance($type, $id);
}

function tinymce(array $fields)
{
    $fields    = implode(',#', $fields);
    $assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('webroot.themes.' . app()->theme->name . '.assets'), FALSE, -1, YII_DEBUG);

    echo '
    <script src="//tinymce.cachefly.net/4.0/tinymce.min.js"></script>
    <script type="text/javascript">
        tinymce.init({
            selector: "#' . $fields . '",
            language_url: "' . $assetsUrl . '/js/tinymce/langs/' . app()->getLanguage() . '.js",
            plugins: [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
                "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste textcolor"
            ],

            toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
            toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

            toolbar_items_size: "small"
        });
    </script>';
}

/**
 * @return CSecurityManager
 */
function security()
{
    return Yii::app()->getSecurityManager();
}

function userIp()
{
    return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
}

/**
 * Возвращает ссылку на сервис который даёт данные об IP
 *
 * @param $ip
 *
 * @return string
 */
function getLocationLinkByIp($ip)
{
    return 'http://speed-tester.info/ip_location.php?ip=' . $ip;
}

// Deprecated
function getNumberForPagination($number, $perPage)
{
    $page = request()->getQuery('page', 1);
    return $perPage * $page - $perPage + $number;
}

function us($segment)
{
    $segment--;
    $url = explode('/', trim(request()->getRequestUri(), '/'));
    return isset($url[$segment]) ? $url[$segment] : FALSE;
}

function formatCurrency($value, $returnCurrencyName = TRUE)
{
    $format = Yii::app()->numberFormatter->format(Yii::app()->getLocale()->getCurrencyFormat(), $value);

    if($returnCurrencyName)
    {
        return str_replace('¤', '', $format) . (isset(app()->controller->gs->currency_name) ? ' ' . CHtml::encode(app()->controller->gs->currency_name) : '');
    }

    return str_replace(' ¤', '', $format);
}

function formatDate($date)
{
    if(!is_numeric($date))
    {
        $date = strtotime($date);
    }

    return app()->getDateFormatter()->formatDateTime($date);
}

/*function myLog($title, $text, $file)
{
    $text = CVarDumper::dumpAsString($text);

    $text = '[' . date('Y-m-d H:i:s') . '] ' . $title . "\r\n\r\n" . $text . "\r\n\r\n------------------------------------------------\r\n\r\n";

    $path = Yii::getPathOfAlias('app.runtime.myLog') . '/' . $file . '_' . date('Y_m_d') . '.log';

    file_put_contents($path, $text, FILE_APPEND);
}*/

/**
 * Возвращает массив тем сайта
 *
 * @return array
 */
function getTemplates()
{
    $themes = array();
    $path   = Yii::getPathOfAlias('webrot') . 'themes' . DIRECTORY_SEPARATOR;

    foreach(glob($path . '*') as $dir)
    {
        $theme = str_replace($path, '', $dir);

        if($theme == 'backend' || !is_dir($dir) || !preg_match('/^[a-z0-9\_\-]+$/si', $theme))
        {
            continue;
        }

        $themes[] = $theme;
    }

    if($themes)
    {
        return array_combine($themes, $themes);
    }

    return array();
}

/**
 * Word Limiter (https://github.com/EllisLab/CodeIgniter/blob/develop/system/helpers/text_helper.php#L41)
 *
 * Limits a string to X number of words.
 *
 * @param string
 * @param int
 * @param string the end character. Usually an ellipsis
 *
 * @return string
 */
function wordLimiter($str, $limit = 100, $end_char = '&#8230;')
{
    if (trim($str) === '')
    {
        return $str;
    }

    preg_match('/^\s*+(?:\S++\s*+){1,'.(int) $limit.'}/', $str, $matches);

    if (strlen($str) === strlen($matches[0]))
    {
        $end_char = '';
    }

    return rtrim($matches[0]).$end_char;
}

/**
 * Character Limiter (https://github.com/EllisLab/CodeIgniter/blob/develop/system/helpers/text_helper.php#L73)
 *
 * Limits the string based on the character count. Preserves complete words
 * so the character count may not be exactly as specified.
 *
 * @param string
 * @param int
 * @param string the end character. Usually an ellipsis
 * @return string
 */
function characterLimiter($str, $n = 500, $end_char = '&#8230;')
{
    if (mb_strlen($str) < $n)
    {
        return $str;
    }

    // a bit complicated, but faster than preg_replace with \s+
    $str = preg_replace('/ {2,}/', ' ', str_replace(array("\r", "\n", "\t", "\x0B", "\x0C"), ' ', $str));

    if (mb_strlen($str) <= $n)
    {
        return $str;
    }

    $out = '';
    foreach (explode(' ', trim($str)) as $val)
    {
        $out .= $val.' ';

        if (mb_strlen($out) >= $n)
        {
            $out = trim($out);
            return (mb_strlen($out) === mb_strlen($str)) ? $out : $out.$end_char;
        }
    }
}

/**
 * @return Notify
 */
function notify()
{
    return app()->notify;
}

/**
 * @param string $path
 *
 * @return string
 */
function assetsUrl($path = NULL)
{
    static $paths = array();

    if($path === NULL)
    {
        $path = 'application.views.assets';

        if(app()->getTheme()->getName() != '')
        {
            $path = 'webroot.themes.' . app()->getTheme()->getName();
        }
    }

    if(isset($paths[$path]))
    {
        return $paths[$path];
    }

    $path_ = app()->getAssetManager()->publish(Yii::getPathOfAlias($path), FALSE, -1, YII_DEBUG);
    $paths[$path] = $path_;

    return $path_;
}