<?php

namespace app\models\mains\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\mains\generals\ClassMembers as ClassMembersModel;

/**
 * ClassMembers represents the model behind the search form of `app\models\mains\generals\ClassMembers`.
 */
class ClassMembers extends ClassMembersModel
{
    public $query;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'booking_id', 'class_id', 'member_id', 'is_blocked', 'is_alumni', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['begin_at', 'end_at', 'certificate_file'], 'safe'],
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
        $query = ClassMembersModel::find();

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
            'booking_id' => $this->booking_id,
            'class_id' => $this->class_id,
            'member_id' => $this->member_id,
            'begin_at' => $this->begin_at,
            'end_at' => $this->end_at,
            'is_blocked' => $this->is_blocked,
            'is_alumni' => $this->is_alumni,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
        ]);

        $query->andFilterWhere(['like', 'certificate_file', $this->certificate_file]);

        return $dataProvider;
    }
}