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
 * Description of MissingTranslationHandler
 *
 * @author Clément Maniraguha, clement.maniraguha@i-logs.com
 */
namespace common\components\i18n;
use yii\i18n\MissingTranslationEvent;
use ilogs\TranslationBackend\models\SourceMessage;
use yii\helpers\Html;
class MissingTranslationHandler {
	
	 public  static function load(MissingTranslationEvent $event) {
		
		 $exist = SourceMessage::find()
                                ->where(['category' => $event->category, 'message' => Html::encode($event->message)])->count();
			
		 if(!($exist)){
			$model = new SourceMessage;
			$model->category = $event->category;
			$model->message =  Html::encode($event->message);
			$model->save();
		}

    }
	 
}