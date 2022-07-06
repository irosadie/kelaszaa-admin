<?php

namespace app\models\danabos\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\danabos\generals\Juknis as JuknisModel;

/**
 * Juknis represents the model behind the search form of `app\models\danabos\generals\Juknis`.
 */
class Juknis extends JuknisModel
{
    public $query;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code', 'name', 'desc', 'schools', 'year'], 'safe'],
            [['school_year_id'], 'number'],
            [['query'], 'safe']
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
        $query = JuknisModel::find();

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
            'id' => $this->id,
            'school_year_id' => $this->school_year_id,
            'year' => $this->year,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'schools', $this->schools]);

        $query->orFilterWhere(['like', 'code', $this->query])
            ->orFilterWhere(['like', 'name', $this->query])
            ->orFilterWhere(['like', 'desc', $this->query]);

        return $dataProvider;
    }
}