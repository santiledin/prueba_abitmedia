<?php

declare(strict_types=1);

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rbac_audit".
 *
 * @property int $id
 * @property int $user_id
 * @property string $action
 * @property string $item_name
 * @property int $changed_by
 * @property int $created_at
 *
 * @property User $user
 * @property User $changer
 */
class RbacAudit extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rbac_audit';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'action', 'item_name', 'changed_by'], 'required'],
            [['user_id', 'changed_by', 'created_at'], 'integer'],
            [['action'], 'string', 'max' => 50],
            [['item_name'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Usuario Afectado',
            'action' => 'Acción',
            'item_name' => 'Rol/Permiso',
            'changed_by' => 'Cambiado Por',
            'created_at' => 'Fecha',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getChanger()
    {
        return $this->hasOne(User::class, ['id' => 'changed_by']);
    }
}
