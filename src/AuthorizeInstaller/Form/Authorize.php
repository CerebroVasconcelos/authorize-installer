<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace AuthorizeInstaller\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Crypt\Password\Bcrypt;

class Authorize extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'user_table',
            'options' => array('label' => 'User Table:'),
            'attributes' => array('type' => 'text','value' => 'users'),
        ));

        $this->add(array(
            'name' => 'permissions_allowRegister',
            'type' => 'radio',
            'options' => array('label' => 'Allow User Registration:', 'value_options' => array(0=>'No', 1=>'Yes'),),
            'attributes' => array('value' => 1,),
        ));

        $this->add(array(
            'name' => 'permissions_requireActivation',
            'type' => 'radio',
            'options' => array('label' => 'Require email activation?:', 'value_options' => array(0=>'No', 1=>'Yes'),),
            'attributes' => array('value' => 1,),
        ));

        $this->add(array(
            'name' => 'permissions_allowProfileUpdate',
            'type' => 'radio',
            'options' => array('label' => 'Allow profile update?:', 'value_options' => array(0=>'No', 1=>'Yes'),),
            'attributes' => array('value' => 1,),
        ));

        $this->add(array(
            'name' => 'permissions_allowForgotPassword',
            'type' => 'radio',
            'options' => array('label' => 'Allow forgot password?:', 'value_options' => array(0=>'No', 1=>'Yes'),),
            'attributes' => array('value' => 1,),
        ));

        $this->add(array(
            'name' => 'permissions_allowRememberMe',
            'type' => 'radio',
            'options' => array('label' => 'Allow remember me (stores cookie)?:', 'value_options' => array(0=>'No', 1=>'Yes'),),
            'attributes' => array('value' => 1,),
        ));

        $this->add(array(
            'name' => 'permissions_enableAcl',
            'type' => 'radio',
            'options' => array('label' => 'Enable ACL/RBAC (Access Control Lists)?:', 'value_options' => array(0=>'No', 1=>'Yes'),),
            'attributes' => array('value' => 1,),
        ));

        $this->add(array(
            'name' => 'permissions_redirectOn403',
            'type' => 'radio',
            'options' => array('label' => 'Redirect to login on 403 error?:', 'value_options' => array(0=>'No', 1=>'Yes'),),
            'attributes' => array('value' => 1,),
        ));

        $this->add(array(
            'name' => 'redirects_login_success',
            'options' => array('label' => 'Redirect route on login success?:'),
            'attributes' => array('type' => 'text','placeholder' => 'e.g. home', 'value'=>'home'),
        ));

        $this->add(array(
            'name' => 'redirects_logout',
            'options' => array('label' => 'Redirect route on logout?:'),
            'attributes' => array('type' => 'text','placeholder' => 'e.g. home', 'value'=>'authorize/login'),
        ));

        $bcrypt = new Bcrypt;
        $hash = md5($bcrypt->setCost(14)->create(uniqid()));

        $this->add(array(
            'name' => 'salt',
            'options' => array('label' => 'Secure Salt (this one was just generated):'),
            'attributes' => array('type' => 'text','placeholder' => 'e.g. home', 'value'=>$hash),
        ));

        $filter = new InputFilter();
        $filter->add(array('name' => 'user_table','required' => true,'validators' => array(array('name' => 'NotEmpty'))));
        $filter->add(array('name' => 'redirects_login_success','required' => true,'validators' => array(array('name' => 'NotEmpty'))));
        $filter->add(array('name' => 'redirects_logout','required' => true,'validators' => array(array('name' => 'NotEmpty'))));
        $filter->add(array('name' => 'salt','required' => true,'validators' => array(array('name' => 'NotEmpty'))));

        $this->setInputFilter($filter);

    }
}

