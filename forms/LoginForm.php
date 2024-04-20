<?php
namespace MVC\Forms;


use MVC\Core\Application;
use MVC\Core\Model;
use MVC\Models\User;


class LoginForm extends Model
{
    public string $emailAddress = '';
    public string $password = '';

    public function rules()
    {
        return [
            'emailAddress' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'password' => [self::RULE_REQUIRED],
        ];
    }

    public function labels()
    {
        return [
            'emailAddress' => 'Your Email address',
            'password' => 'Password'
        ];
    }

    public function login()
    {
        $user = User::findOne(['emailAddress' => $this->emailAddress]);
        if (!$user) {
            $this->addError('emailAddress', 'User does not exist with this email address');
            return false;
        }
        if (!$user->isActive) {
            $this->addError('emailAddress', 'Your account was inactivated.');
            return false;
        }
        if (!password_verify($this->password, $user->password)) {
            $this->addError('password', 'Password is incorrect');
            return false;
        }
        $user->setLoginedAt("now");
        $updated_user = User::update($data=['loginedAt' => $user->getLoginedAt()], $where=['emailAddress' => $this->emailAddress]);
        return Application::$app->login($user);
    }
}