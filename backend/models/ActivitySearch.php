<?php

namespace backend\models;

use Yii;
use common\models\Activity;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class ActivitySearch extends Activity{

    var $activityGroup_title;

	public function rules(){
		return [
			[['title', 'activityGroup_title'], 'safe']
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
        $query->joinWith('activityGroup');

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
        $dataProvider->sort->attributes['activityGroup_title'] = [
            'asc' => ['activity_group.title' => SORT_ASC],
            'desc' => ['activity_group.title' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if(!empty($this->event_id)){
            $query->andWhere(['=', 'activity.event_id', $this->event_id]);
        }
        if(!empty($this->title)){
            $query->andWhere(['LIKE', 'activity.title', $this->title]);
        }
        if(!empty($this->activityGroup_title)){
            $query->andWhere(['LIKE', 'activity_group.title', $this->activityGroup_title]);
        }
        
      
        return $dataProvider;
    }
}