<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Modelo de Producto
 *
 * @property integer $id
 * @property string $sku
 * @property string $name
 * @property string $description
 * @property float $price
 * @property integer $stock
 * @property integer $created_at
 * @property integer $updated_at
 */
class Product extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sku', 'name', 'price', 'stock'], 'required'],
            [['description'], 'string'],
            [['price'], 'number', 'min' => 0],
            [['stock'], 'integer', 'min' => 0], // RF-06: Stock no negativo
            [['sku'], 'unique'],
            [['sku', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sku' => 'SKU',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'price' => 'Precio',
            'stock' => 'Existencias',
            'created_at' => 'Creado El',
            'updated_at' => 'Actualizado El',
        ];
    }
}
