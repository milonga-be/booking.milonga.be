<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('booking', 'Scan');
$this->params['breadcrumbs'] = [
    [
        'label' => $event->title,
        'url' => ['event/view', 'uuid' => $event->uuid]
    ],
    [
        'label' => Yii::t('booking', 'Reservations'),
        'url' => ['booking/index', 'event_uuid' => $event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['booking/scan', 'event_uuid' => $event->uuid]
    ]
];

$activities = $event->getActivities()->orderBy('datetime')->all();

$this->registerJsFile('https://unpkg.com/html5-qrcode', ['position' => View::POS_HEAD]);

$checkUrl = Url::to(['booking/check-participation']);
$bookingViewUrl = Url::to(['booking/view', 'uuid' => 'UUID_PLACEHOLDER']);
$csrfToken = Yii::$app->request->getCsrfToken();
$successSoundUrl = Yii::getAlias('@web/sounds/success.mp3');
$errorSoundUrl = Yii::getAlias('@web/sounds/error.mp3');

$js = <<<JS
let html5QrcodeScanner;

const successSound = new Audio('{$successSoundUrl}');
const errorSound = new Audio('{$errorSoundUrl}');

function onScanSuccess(decodedText, decodedResult) {
    // Pause scanner
    var booking_uuid = decodedText;
    html5QrcodeScanner.pause();
    
    let selectedActivities = [];
    document.querySelectorAll('input[name="selected_activities[]"]:checked').forEach(function(checkbox) {
        selectedActivities.push(checkbox.value);
    });

    if (selectedActivities.length === 0) {
        errorSound.play();
        const resultDiv = document.getElementById('scan-result');
        const summaryDiv = document.getElementById('booking-summary');
        summaryDiv.innerHTML = '';

        


        resultDiv.innerHTML = '<div class="alert alert-warning">Please select at least one activity to scan for.</div>';
        setTimeout(function() {
            resultDiv.innerHTML = '';
            html5QrcodeScanner.resume();
        }, 2000);
        return;
    }

    fetch('{$checkUrl}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': '{$csrfToken}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: 'booking_uuid=' + decodedText + '&' + selectedActivities.map(uuid => 'activity_uuids[]=' + uuid).join('&')
    })
    .then(response => response.json())
    .then(data => {
        const resultDiv = document.getElementById('scan-result');
        const alertClass = data.status === 'success' ? 'alert-success' : 'alert-danger';
        loadBookingSummary(booking_uuid);
        if (data.status === 'success') {
            successSound.play();
        } else {
            errorSound.play();
        }
        resultDiv.innerHTML = '<div class="alert ' + alertClass + '">' + data.message + '</div>';

        // Resume scanning after a short delay to avoid re-scanning the same code
        setTimeout(function() {
            html5QrcodeScanner.resume();
        }, 500);

        // Clear the message after a few seconds
        setTimeout(function() {
            const resultDiv = document.getElementById('scan-result');

            resultDiv.innerHTML = '';
        }, 3500);
    })
    .catch(error => {
        errorSound.play();
        console.error('Error:', error);
		loadBookingSummary(booking_uuid);
        const resultDiv = document.getElementById('scan-result');
        const summaryDiv = document.getElementById('booking-summary');
        summaryDiv.innerHTML = '';

        resultDiv.innerHTML = '<div class="alert alert-danger">An unexpected error occurred. Please try again.</div>';
        html5QrcodeScanner.resume();
        setTimeout(function() { resultDiv.innerHTML = ''; }, 3500);
    });
}

function onScanFailure(error) {
    // handle scan failure, we can ignore it to keep scanning.
    // console.warn(`Code scan error = \${error}`);
}
if (document.getElementById("qr-reader")) {
    html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
}
JS;
$this->registerJs($js, View::POS_READY);

?>
<h1><?= Html::encode($this->title) ?></h1>
<?php
$this->registerJs(
    "
    function loadBookingSummary(booking_uuid) {
        var summaryDiv = document.getElementById('booking-summary');
        
        // Clear previous content
        summaryDiv.innerHTML = '';
        
        // Make AJAX request to fetch booking details
        $.ajax({
            url: '".Url::to(['booking/summary'])."',
            type: 'GET',
            data: { uuid: booking_uuid },
            success: function(response) {
                summaryDiv.innerHTML = response;
            },
            error: function(xhr, status, error) {
                summaryDiv.innerHTML = '<div class=\"alert alert-danger\">Error loading booking summary.</div>';
                console.error(error);
            }
        });
    }


    "
);
?>
<div class="row mt-2 mb-2">
    <div class="col-md-6">
        <div id="qr-reader" style="width: 500px; margin: 0 auto; min-height: 250px;"></div><div id="scan-result" style="width: 500px; margin: 15px auto; text-align: center; min-height: 50px;"></div>
    </div>
    <div class="col-md-6" id="booking-summary"></div>
	<div class="col-md-12">
        <h2><?= Yii::t('booking', 'Activities') ?></h2>
        <p><?= Yii::t('booking', 'Please select the activity/activities to scan for.') ?></p>
        <div id="activity-list">
            <?php foreach ($activities as $activity): ?>
                <div class="checkbox">
                    <label>
                        <?= Html::checkbox('selected_activities[]', false, ['value' => $activity->uuid]) ?>
                        <?= $activity->datetime ? Html::encode(Yii::$app->formatter->asDatetime($activity->datetime, 'php:D j M H:i')) . ' - ' : '' ?> <?= Html::encode($activity->getSummary(75)) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
	</div>

</div>
<style>
    #qr-reader {
        margin-right: 20px;
    }
</style>

        </div>
</div>

<?php
/*




























































*/




























































?>