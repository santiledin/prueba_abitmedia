<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm es el modelo que maneja el formulario de inicio de sesión.
 *
 * @property-read User|null $user
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    /**
     * Usuario autenticado en caché
     */
    private $_user = false;

    /**
     * Retorna las reglas de validación del modelo.
     *
     * @return array reglas de validación
     */
    public function rules()
    {
        return [
            // El usuario y la contraseña son obligatorios
            [['username', 'password'], 'required'],

            // rememberMe debe ser un valor booleano
            ['rememberMe', 'boolean'],

            // La contraseña se valida mediante el método validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Etiquetas personalizadas para los atributos
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Usuario',
            'password' => 'Contraseña',
            'rememberMe' => 'Recuérdame',
        ];
    }

    /**
     * Valida la contraseña ingresada.
     * Este método funciona como una validación inline.
     *
     * @param string $attribute atributo que se está validando
     * @param array $params parámetros adicionales de la regla
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user) {
                // Usuario no encontrado
                $this->addError($attribute, 'Usuario o contraseña incorrectos.');
            } elseif ($user->status === User::STATUS_INACTIVE) {
                // Usuario inactivo
                $this->addError($attribute, 'Este usuario está inactivo.');
            } elseif (!$user->validatePassword($this->password)) {
                // Contraseña incorrecta
                $this->addError($attribute, 'Usuario o contraseña incorrectos.');
            }
        }
    }

    /**
     * Inicia sesión con el usuario y contraseña proporcionados.
     *
     * @return bool indica si el inicio de sesión fue exitoso
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe ? 3600 * 24 * 30 : 0
            );
        }

        return false;
    }

    /**
     * Busca un usuario por su nombre de usuario.
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
