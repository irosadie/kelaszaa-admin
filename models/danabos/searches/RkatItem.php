<?php

namespace app\models\danabos\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\danabos\generals\RkatItem as RkatItemModel;

/**
 * RkatItem represents the model behind the search form of `app\models\danabos\generals\RkatItem`.
 */
class RkatItem extends RkatItemModel
{
    public $q1;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rkat_id', 'juknis_relation_id', 'amount_estimate', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['validations', 'validation_level', 'note'], 'safe'],
            [['q1'], 'safe']
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
        $query = RkatItemModel::find()->joinWith('juknisRelation.juknisItem');

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
            'rkat_item.id' => $this->id,
            'rkat_item.rkat_id' => $this->rkat_id,
            'rkat_item.juknis_relation_id' => $this->juknis_relation_id,
            'rkat_item.amount_estimate' => $this->amount_estimate,
            'rkat_item.status' => $this->status,
            'rkat_item.created_at' => $this->created_at,
            'rkat_item.created_by' => $this->created_by,
            'rkat_item.updated_at' => $this->updated_at,
            'rkat_item.updated_by' => $this->updated_by,
            'rkat_item.deleted_at' => $this->deleted_at,
            'rkat_item.deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'rkat_item.validations', $this->validations])
            ->andFilterWhere(['like', 'rkat_item.validation_level', $this->validation_level])
            ->andFilterWhere(['like', 'rkat_item.note', $this->note]);

        $query->orFilterWhere(['like', 'juknis_item.value', $this->q1]);
        $query->orFilterWhere(['like', 'juknis_item.code', $this->q1]);

        return $dataProvider;
    }
}