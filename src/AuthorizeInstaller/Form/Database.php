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

class Database extends Form
{
    public function __construct($name = null)
    {
        parent::__construct($name);

        $this->add(array(
            'name' => 'database',
            'options' => array('label' => 'Database:'),
            'attributes' => array('type' => 'text','placeholder' => 'e.g. zfhAuthorize'),
        ));

        $this->add(array(
            'name' => 'host',
            'options' => array('label' => 'Host:'),
            'attributes' => array('type' => 'text','value' => 'localhost',),
        ));

        $this->add(array(
            'name' => 'username',
            'options' => array('label' => 'Username:'),
            'attributes' => array('type' => 'text','placeholder' => 'e.g. root'),
        ));

        $this->add(array(
            'name' => 'password',
            'options' => array('label' => 'Password:'),
            'attributes' => array('type' => 'text'),
        ));

        $filter = new InputFilter();
        $filter->add(array('name' => 'database','required' => true,'validators' => array(array('name' => 'NotEmpty'))));
        $filter->add(array('name' => 'host','required' => true,'validators' => array(array('name' => 'NotEmpty'))));
        $filter->add(array('name' => 'username','required' => true,'validators' => array(array('name' => 'NotEmpty'))));

        $this->setInputFilter($filter);

    }
}
