<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $booking common\models\Booking */
/* @var $priceManager common\components\PriceManager */
/* @var $qrCodeDataUri string */
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Booking Summary</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            
        }
        .container{
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px;
        }

        h1 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .qr-code-table {
            width: 100%;
            margin-bottom: 10px; /* Add some spacing below the table */
            
        }
        p.text-muted{
            margin: 5px 0;
            color:grey;
        }
        .qrcode{
            border: 1px solid #ccc;
            padding: 5px;
            background-color: #fff;
        }
    </style>
</head>
<body>
<div class="container">
    <table class="qr-code-table">
        <tr>
            <td style="text-align: left; vertical-align: top;">
                <h1>Booking Summary</h1>
                <div class="booking-details">
                    <p class="text-muted"><strong>Reference:</strong> <?= Html::encode($booking->reference) ?></p>
                    <p class="text-muted"><strong>Name:</strong> <?= Html::encode($booking->name) ?></p>
                    <p class="text-muted"><strong>Email:</strong> <?= Html::encode($booking->email) ?></p>
                </div>
            </td>
            <td style="text-align: right; vertical-align: top;">
                <img class="qrcode"  src="<?= $qrCodeDataUri ?>" alt="QR Code">
            </td>
        </tr>
    </table>

    

    <h2>Activities</h2>    
    <table class="table" style="width:100%; border-collapse: collapse;">
       


        <thead>
            <tr>
                <th style="text-align: left;">Date</th>
                <th style="text-align: left;">Title</th>
                <th style="text-align: right;">Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($booking->participations as $participation): ?>
            <tr style="border-bottom: 1px solid #ccc;">
                <td style="text-align: left;color:grey;">
                    <?=  isset($participation->activity->datetime)?Yii::$app->formatter->asDatetime($participation->activity->datetime):'-' ?>
                </td>
                <td style="text-align: left;">
                    <?= $participation->activity->getSummary(75) ?>
                </td>
                <td style="text-align: right;">
                    <strong><?= $participation->getPriceSummary() ?></strong>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>


    <h2>Total Price</h2>
    <p><?= Yii::$app->formatter->asCurrency($booking->total_price) ?></p>

    <p>
       <?= nl2br($booking->event->payment_instructions) ?>
    </p>

    <p>
        For any questions or modifications, please contact us at:
        <a href="mailto:<?= $booking->event->email ?>"><?= $booking->event->email ?></a>
    </p>
</div>


</body>

</html>