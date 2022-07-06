<?php

namespace app\models\mains\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mains\generals\Classes as ClassesModel;

/**
 * Classes represents the model behind the search form of `app\models\mains\generals\Classes`.
 */
class Classes extends ClassesModel
{
    public $query;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'mentor_id', 'days_in_class', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code', 'thumbnail', 'title', 'short_desc', 'desc', 'main_video', 'main_file', 'class_begin', 'class_end', 'certificate_file'], 'safe'],
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
        $query = ClassesModel::find();

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
            'mentor_id' => $this->mentor_id,
            'class_begin' => $this->class_begin,
            'class_end' => $this->class_end,
            'days_in_class' => $this->days_in_class,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'thumbnail', $this->thumbnail])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'short_desc', $this->short_desc])
            ->andFilterWhere(['like', 'desc', $this->desc])
            ->andFilterWhere(['like', 'main_video', $this->main_video])
            ->andFilterWhere(['like', 'main_file', $this->main_file])
            ->andFilterWhere(['like', 'certificate_file', $this->certificate_file]);

        return $dataProvider;
    }
}