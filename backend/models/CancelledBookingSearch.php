<?php

namespace backend\models;

use Yii;
use common\models\Event;
use common\models\Booking;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class CancelledBookingSearch extends BookingSearch{

    var $confirmed = 0;
}