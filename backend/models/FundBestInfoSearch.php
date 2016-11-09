<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\FundBestInfo;

/**
 * FundBestInfoSearch represents the model behind the search form about `backend\models\FundBestInfo`.
 */
class FundBestInfoSearch extends FundBestInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['fund_id', 'name', 'name_en', 'date', 'poundage', 'create_time'], 'safe'],
            [['unit_net_value', 'total_net_value', 'day_gr', 'week_gr', 'month_gr', 'three_month_gr', 'six_month_gr', 'year_gr', 'two_year_gr', 'three_year_gr', 'this_year_gr', 'establish_gr', 'self_define'], 'number'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = FundBestInfo::find();

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
            'unit_net_value' => $this->unit_net_value,
            'total_net_value' => $this->total_net_value,
            'day_gr' => $this->day_gr,
            'week_gr' => $this->week_gr,
            'month_gr' => $this->month_gr,
            'three_month_gr' => $this->three_month_gr,
            'six_month_gr' => $this->six_month_gr,
            'year_gr' => $this->year_gr,
            'two_year_gr' => $this->two_year_gr,
            'three_year_gr' => $this->three_year_gr,
            'this_year_gr' => $this->this_year_gr,
            'establish_gr' => $this->establish_gr,
            'self_define' => $this->self_define,
        ]);

        $query->andFilterWhere(['like', 'fund_id', $this->fund_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_en', $this->name_en])
            ->andFilterWhere(['like', 'date', $this->date])
            ->andFilterWhere(['like', 'poundage', $this->poundage])
            ->andFilterWhere(['like', 'create_time', $this->create_time]);

        return $dataProvider;
    }
}
