<?php

namespace backend\models;

use Yii;
use common\models\Event;
use common\models\Booking;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class BookingSearch extends Booking{

	public function rules(){
		return [
			[['title'], 'safe']
		];
	}

	/**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search( $params )
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'		=> [				
				'defaultOrder'	=> ['created_at' => SORT_DESC,]
			],
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        // Sort handling
        $dataProvider->sort->attributes['created_at'] = [
            'asc' => ['created_at' => SORT_ASC],
            'desc' => ['created_at' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->event_id)){
            $query->andWhere(['=', 'event_id', $this->event_id]);
        }
        
      
        return $dataProvider;
    }
}