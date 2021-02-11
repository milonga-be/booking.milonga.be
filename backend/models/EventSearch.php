<?php

namespace backend\models;

use Yii;
use common\models\Event;
use common\models\File;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class EventSearch extends Event{

	var $title;

	public function rules(){
		return [
			[['title', ], 'safe']
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
        $dataProvider->sort->attributes['title'] = [
            'asc' => ['title' => SORT_ASC],
            'desc' => ['title' => SORT_DESC],
        ];
        $dataProvider->sort->attributes['created_at'] = [
            'asc' => ['created_at' => SORT_ASC],
            'desc' => ['created_at' => SORT_DESC],
        ];

        $user = Yii::$app->user->identity;
        $query->andFilterWhere([
            'user_id' => $user->id,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->title)){
            $query->andWhere(['LIKE', 'title', $this->title]);
        }
        
      
        return $dataProvider;
    }
}