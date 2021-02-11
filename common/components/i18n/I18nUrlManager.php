<?php

/**
 * copyright 2014, I-logs.  All rights reserved. 
 */
/**
 * LICENSE
 *
 * This source file is copyrighted i-Logs SPRL. All rights reserved.
 *
 * You may not copy, reproduce, republish, download, post, broadcast,
 * transmit, make available to the public, or otherwise use this source file.
 * You also agree not to adapt, alter or create a derivative work from
 * this source file content except otherwise explicitly granted by i-Logs SPRL.
 * This source file is subject to a commercial license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license, please send an email
 * to info@i-logs.com so we can send you a copy immediately.
 *
 * @category i-Logs
 * @link http://www.i-logs.com/
 * @author Clément Maniraguha, clement.maniraguha@i-logs.com
 * @package
 * @version
 */

/**
 * Description of I18nUrlManager
 *
 * @author Clément Maniraguha, clement.maniraguha@i-logs.com
 */

namespace common\components\i18n;

use yii\web\UrlManager;
use Yii;
class I18nUrlManager  extends UrlManager
{

    /**
* @var array Supported languages
*/
    public $languages;

    /**
* @var bool Whether to display the source app language in the URL
*/
    public $displaySourceLanguage = true;

    /**
* @var string Parameter used to set the language
*/
    public $languageParam = 'lang';

    /**
* @inheritdoc
*/
    public function init()
    {

        if (empty($this->languages)) {
            $this->languages = [Yii::$app->language];
        }
        parent::init();
    }

    /**
* Parses the URL and sets the language accordingly
* @param \yii\web\Request $request
* @return array|bool
*/
    public function parseRequest($request)
    {

        if ($this->enablePrettyUrl) {
            $pathInfo = $request->getPathInfo();
			
            $language = explode('/', $pathInfo)[0];
			if (in_array($language, $this->languages)) {
                $request->setPathInfo(substr_replace($pathInfo, '', 0, (strlen($language) + 1)));
                Yii::$app->language = $language;
			}else{
				//no language in url
				if(!Yii::$app->user->isGuest && isset(\Yii::$app->user->identity->language) && $language=\Yii::$app->user->identity->language){
					
					$referer = explode('/', Yii::$app->request->referrer);
					$result = array_intersect($referer, $this->languages);
					//keep the selected language on login as the default one after login
					if (!empty($result)){
						$initialLang = (array_slice($result, 0, 1));
						Yii::$app->language = $initialLang[0];
					}elseif (in_array($language, $this->languages)){
						//otherwise default language is user's language
						Yii::$app->language = $language;
					}
				}
			}
			
        } else {
            $params = $request->getQueryParams();
            $route = isset($params[$this->routeParam]) ? $params[$this->routeParam] : '';
            if (is_array($route)) {
                $route = '';
            }
            $language = explode('/', $route)[0];
            if (in_array($language, $this->languages)) {
                $route = substr_replace($route, '', 0, (strlen($language) + 1));
                $params[$this->routeParam] = $route;
                $request->setQueryParams($params);
                Yii::$app->language = $language;
            }
        }
        return parent::parseRequest($request);
    }

    /**
* Adds language functionality to URL creation
* @param array|string $params
* @return string
*/
    public function createUrl($params)
    {
        if (array_key_exists($this->languageParam, $params)) {
            $lang = $params[$this->languageParam];
            if (($lang !== Yii::$app->sourceLanguage || $this->displaySourceLanguage) && !empty($lang)) {
                $params[0] = $lang . '/' . ltrim($params[0], '/');
            }
            unset($params[$this->languageParam]);
        } else {
            if (Yii::$app->language !== Yii::$app->sourceLanguage || $this->displaySourceLanguage) {
                $params[0] = Yii::$app->language . '/' . ltrim($params[0], '/');
            }
        }
        return parent::createUrl($params);
    }
	 
}