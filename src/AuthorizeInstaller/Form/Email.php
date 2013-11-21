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

class Email extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'from',
            'options' => array('label' => 'Sender Email:'),
            'attributes' => array('type' => 'text','placeholder' => 'e.g. noreply@mydomain.com'),
        ));

        $this->add(array(
            'name' => 'transport',
            'type' => 'select',
            'options' => array('label' => 'Default mail transport?:', 'value_options' => array('sendmail'=>'PHP Mailer (Sendmail)', 'gmail'=>'Gmail', 'smtp'=>'Smtp', 'pop3'=>'Pop3', 'custom'=>'Custom'),),
            'attributes' => array('value' => 'gmail',),
        ));

        $this->add(array(
            'name' => 'options_connection_class',
            'type' => 'select',
            'options' => array('label' => 'Connection Class?:',
                'value_options' => array(
                    'smtp'=>'Smtp',
                    'plain'=>'Plain Auth (default)',
                    'login'=>'Login Auth',
                    'crammd5'=>'Cram Md5 Auth',
                ),
            ),
            'attributes' => array('value' => 'plain'),
        ));

        $this->add(array(
            'name' => 'options_connection_config_host',
            'options' => array('label' => 'Mail Host:'),
            'attributes' => array('type' => 'text','placeholder' => 'e.g. mail.mydomain.com'),
        ));

        $this->add(array(
            'name' => 'options_connection_config_username',
            'options' => array('label' => 'Username:'),
            'attributes' => array('type' => 'text', 'placeholder' => 'e.g. me@here.com'),
        ));

        $this->add(array(
            'name' => 'options_connection_config_password',
            'options' => array('label' => 'Password:'),
            'attributes' => array('type' => 'password', 'value' => ''),
        ));

        $this->add(array(
            'name' => 'options_connection_config_ssl',
            'type' => 'select',
            'options' => array('label' => 'SSL?:',
                'value_options' => array(
                    '' => 'None',
                    'tls'=>'TLS',
                    'ssl'=>'SSL',
                ),
            ),
            'attributes' => array('value' => 'plain'),
        ));

        $this->add(array(
            'name' => 'options_connection_config_port',
            'options' => array('label' => 'Port:'),
            'attributes' => array('type' => 'text', 'value' => '25'),
        ));


    }
}
