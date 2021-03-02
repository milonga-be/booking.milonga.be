<?php

namespace backend\models;

use Yii;
use common\models\Teacher;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class TeacherSearch extends Teacher{


	public function rules(){
		return [
			[['name'], 'safe']
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
            $query->andWhere(['=', 'teacher.event_id', $this->event_id]);
        }
        if(!empty($this->name)){
            $query->andWhere(['LIKE', 'teacher.name', $this->name]);
        }
        
      
        return $dataProvider;
    }
}