<?php

namespace app\models\danabos\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\danabos\generals\DanabosSettingDetail as DanabosSettingDetailModel;

/**
 * DanabosSettingDetail represents the model behind the search form of `app\models\danabos\generals\DanabosSettingDetail`.
 */
class DanabosSettingDetail extends DanabosSettingDetailModel
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'student_total_estimate', 'funds_per_person_estimate', 'total_amount_estimate', 'disbursement_date_estimate', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['code', 'name', 'desc', 'school_id'], 'safe'],
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
        $query = DanabosSettingDetailModel::find();

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
            'student_total_estimate' => $this->student_total_estimate,
            'funds_per_person_estimate' => $this->funds_per_person_estimate,
            'total_amount_estimate' => $this->total_amount_estimate,
            'disbursement_date_estimate' => $this->disbursement_date_estimate,
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
            ->andFilterWhere(['like', 'school_id', $this->school_id]);

        return $dataProvider;
    }
}
