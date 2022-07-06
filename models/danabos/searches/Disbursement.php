<?php

namespace app\models\danabos\searches;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\danabos\generals\Disbursement as DisbursementModel;

/**
 * Disbursement represents the model behind the search form of `app\models\danabos\generals\Disbursement`.
 */
class Disbursement extends DisbursementModel
{
    public $query, $date_begin, $date_end, $school_id, $period_id, $approve_status, $operator, $amount;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'rkat_item_id', 'disbursement_plan_id', 'percentage', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by', 'updated_by'], 'integer'],
            [['desc', 'validations', 'validation_level', 'amount_request', 'status', 'query', 'date_begin', 'date_end', 'school_id', 'period_id', 'approve_status', 'operator', 'amount'], 'safe'],
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
        $query = DisbursementModel::find()
            ->joinWith('rkatItem.rkat');

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
            'disbursement.id' => $this->id,
            'disbursement.rkat_item_id' => $this->rkat_item_id,
            'disbursement.disbursement_plan_id' => $this->period_id,
            'disbursement.percentage' => $this->percentage,
            'disbursement.amount_request' => $this->amount_request,
            'disbursement.status' => $this->status,
            'disbursement.created_at' => $this->created_at,
            'disbursement.created_by' => $this->created_by,
            'disbursement.updated_at' => $this->updated_at,
            'disbursement.deleted_at' => $this->deleted_at,
            'disbursement.deleted_by' => $this->deleted_by,
            'disbursement.updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'disbursement.desc', $this->desc])
            ->andFilterWhere(['like', 'disbursement.validations', $this->validations])
            ->andFilterWhere(['like', 'disbursement.validation_level', $this->validation_level]);

        $query->andFilterWhere(['like', 'disbursement.desc', $this->query]);

        if ($this->school_id) :
            $query->andFilterWhere(['rkat.school_id' => $this->school_id]);
        endif;

        if ($this->date_begin && $this->date_end) :
            $query->andWhere(['between', 'disbursement.created_at', strtotime($this->date_begin), strtotime(date('Y-m-d', strtotime($this->date_end . ' +1 day')))]);
        endif;

        switch ($this->approve_status ?? 0) {
            case 1:
                $query
                    ->andWhere(['is not', 'disbursement.validations', new \yii\db\Expression('null')])
                    ->andWhere(['disbursement.validation_level' => 'treasurer']);
                break;
            case 2:
                $query
                    ->andWhere(['is', 'disbursement.validations', new \yii\db\Expression('null')])
                    ->andWhere(['is', 'disbursement.validation_level', new \yii\db\Expression('null')]);
                break;
        }

        if ($this->operator && $this->amount) :
            $amount = str_replace('.', '', $this->amount);
            $query->andWhere("(CASE WHEN disbursement.amount_approved IS NULL THEN disbursement.amount_request {$this->operator}{$amount} ELSE disbursement.amount_approved {$this->operator}{$amount} END)");
        endif;

        return $dataProvider;
    }
}