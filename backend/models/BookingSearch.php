<?php

namespace backend\models;

use Yii;
use common\models\Event;
use common\models\Booking;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class BookingSearch extends Booking{

    var $name_search;
    var $confirmed = 1;

	public function rules(){
		return [
			[['email', 'name_search', 'id', 'total_price'], 'safe']
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
        $query = self::find()->where(['confirmed' => $this->confirmed]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
			'sort'		=> [				
				'defaultOrder'	=> ['id' => SORT_DESC,]
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
        if(!empty($this->total_price)){
            $query->andWhere(['=', 'total_price', $this->total_price]);
        }
        if(!empty($this->email)){
            $query->andWhere(['LIKE', 'email', $this->email]);
        }
        if(!empty($this->name_search)){
            $q = str_replace(' ', '%', strtolower($this->name_search));
            $query->andWhere(['OR',
                'LOWER(CONCAT(firstname," ", lastname)) LIKE "%'.$q.'%"',
                'LOWER(CONCAT(lastname," ", firstname)) LIKE "%'.$q.'%"'
            ]);
        }
        if(!empty($this->id)){
            $query->andWhere(['LIKE', 'LPAD(id, 5, "0")', $this->id]);
        }
        
      
        return $dataProvider;
    }
}