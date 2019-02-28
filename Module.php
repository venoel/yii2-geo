<?php

namespace wdmg\geo;

use Yii;


/**
 * geo module definition class
 */
class Module extends \yii\base\Module
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'wdmg\geo\controllers';

    /**
     * @var string the prefix for routing of module
     */
    public $routePrefix = "admin";

    /**
     * @var string the vendor name of module
     */
    private $vendor = "wdmg";

    /**
     * @var string the module version
     */
    private $version = "1.0.5";

    /**
     * @var integer, priority of initialization
     */
    private $priority = 6;

    /**
     * @var array of strings missing translations
     */
    public $missingTranslation;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Set controller namespace for console commands
        if (Yii::$app instanceof \yii\console\Application)
            $this->controllerNamespace = 'wdmg\geo\commands';

        // Set current version of module
        $this->setVersion($this->version);

        // Register translations
        $this->registerTranslations();
    }

    /**
     * Return module vendor
     * @var string of current module vendor
     */
    public function getVendor() {
        return $this->vendor;
    }

    /**
     * {@inheritdoc}
     */
    public function afterAction($action, $result)
    {

        // Log to debuf console missing translations
        if (is_array($this->missingTranslation) && YII_ENV == 'dev')
            Yii::warning('Missing translations: ' . var_export($this->missingTranslation, true), 'i18n');

        $result = parent::afterAction($action, $result);
        return $result;

    }

    // Registers translations for the module
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['app/modules/geo'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@vendor/wdmg/yii2-geo/messages',
            'on missingTranslation' => function($event) {

                if (YII_ENV == 'dev')
                    $this->missingTranslation[] = $event->message;

            },
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('app/modules/geo' . $category, $message, $params, $language);
    }
}
