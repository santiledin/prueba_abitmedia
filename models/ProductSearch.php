<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

class ProductSearch extends Product
{
    public $min_price;
    public $max_price;

    public function rules()
    {
        return [
            [['id', 'stock', 'created_at', 'updated_at'], 'integer'],
            [['sku', 'name', 'description'], 'safe'],
            [['price', 'min_price', 'max_price'], 'number'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20, // RF-07: Default 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'stock' => $this->stock,
        ]);

        $query->andFilterWhere(['like', 'sku', $this->sku])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        // RF-07: Price Range Filter
        if ($this->min_price) {
            $query->andFilterWhere(['>=', 'price', $this->min_price]);
        }
        if ($this->max_price) {
            $query->andFilterWhere(['<=', 'price', $this->max_price]);
        }

        return $dataProvider;
    }
}
