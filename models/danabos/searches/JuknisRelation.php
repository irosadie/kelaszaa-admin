<?php

namespace app\models\danabos\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\danabos\generals\JuknisRelation as JuknisRelationModel;

/**
 * JuknisRelation represents the model behind the search form of `app\models\danabos\generals\JuknisRelation`.
 */
class JuknisRelation extends JuknisRelationModel
{
    public $q2;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'juknis_id', 'juknis_item_id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['q2'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = JuknisRelationModel::find()->joinWith('juknisItem');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'juknis_relation.id' => $this->id,
            'juknis_relation.juknis_id' => $this->juknis_id,
            'juknis_relation.juknis_item_id' => $this->juknis_item_id,
            'juknis_relation.status' => $this->status,
            'juknis_relation.created_at' => $this->created_at,
            'juknis_relation.created_by' => $this->created_by,
            'juknis_relation.updated_at' => $this->updated_at,
            'juknis_relation.updated_by' => $this->updated_by,
            'juknis_relation.deleted_at' => $this->deleted_at,
            'juknis_relation.deleted_by' => $this->deleted_by,
        ]);

        $query->orFilterWhere(['like', 'juknis_item.value', $this->q2]);
        $query->orFilterWhere(['like', 'juknis_item.code', $this->q2]);

        return $dataProvider;
    }
}