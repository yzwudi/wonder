<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\FundForecastInfo;

/**
 * FundForecastSearch represents the model behind the search form about `backend\models\FundForecastInfo`.
 */
class FundForecastSearch extends FundForecastInfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fund_id'], 'integer'],
            [['name', 'month'], 'safe'],
            [['max_forecast', 'min_forecast', 'avg_forecast'], 'number'],
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
        $query = FundForecastInfo::find();

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
            'fund_id' => $this->fund_id,
            'max_forecast' => $this->max_forecast,
            'min_forecast' => $this->min_forecast,
            'avg_forecast' => $this->avg_forecast,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'month', $this->month]);

        return $dataProvider;
    }
}
