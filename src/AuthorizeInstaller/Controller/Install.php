<?php
/**
 * ZF-Hipsters Authorize (https://github.com/zf-hipsters)
 *
 * @link      https://github.com/zf-hipsters/authorize for the canonical source repository
 * @copyright Copyright (c) 2013 ZF-Hipsters
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0
 */
namespace AuthorizeInstaller\Controller;

use AuthorizeInstaller\Controller\ControllerAbstract;

use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\FileGenerator;
use Zend\View\Model\ViewModel;

/**
 * Class Authentication
 * @package Authorize\Controller
 */
class Install extends ControllerAbstract
{
    public function indexAction()
    {
        $ds = DIRECTORY_SEPARATOR;
        $targetPath = getcwd() . $ds . 'config' . $ds . 'autoload' . $ds;

        $paths = array(
            'autoload' => array(
                'path'=>$targetPath,
                'writeable' => is_writeable($targetPath),
            ),
        );

        $install = true;
        foreach ($paths as $path) {
            if ($path['writeable'] == false) {
                $install = false;
            }
        }

        return new ViewModel(array(
            'paths' => $paths,
            'install' => $install
        ));
    }

    public function dbAction()
    {
        $form = $this->getServiceLocator()->get('AuthorizeInstaller\Form\Database');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();
            $form->setData($postData);

            if ($form->isValid()) {
                $values = $form->getData();

                $globalConfig = array(
                    'db' => array(
                        'driver' => 'Mysqli',
                        'database' => '',
                        'username' => '',
                        'password' => '',
                        'hostname' => '',
                        'options' => array(
                            'buffer_results' => true
                        ),
                    ),
                    'service_manager' => array(
                        'factories' => array(
                            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
                        ),
                    ),
                );

                $localConfig = array(
                    'db' => array(
                        'database' => $values['database'],
                        'username' => $values['username'],
                        'password' => $values['password'],
                        'hostname' => $values['host'],
                    ),
                );

                $ds = DIRECTORY_SEPARATOR;
                $targetPath = getcwd() . $ds . 'config' . $ds . 'autoload' . $ds;

                $globalFile = new FileGenerator(array(
                    'filename' => $targetPath . 'database.global.php',
                    'docblock' => $this->getDocBlock(),
                    'body' => 'return ' . $this->var_export($globalConfig, true) . ';',
                ));

                $localFile = new FileGenerator(array(
                    'filename' => $targetPath . 'database.local.php',
                    'docblock' => $this->getDocBlock(),
                    'body' => 'return ' . $this->var_export($localConfig, true) . ';',
                ));

                $globalFile->write();
                $localFile->write();

                $this->redirect()->toRoute('authorize_install', array('action'=>'authorize'));

            }
        }

        return new ViewModel(array(
            'form' => $form
        ));

    }

    public function authorizeAction()
    {
        $form = $this->getServiceLocator()->get('AuthorizeInstaller\Form\Authorize');

        $permissions = array(
            'allowRegister', 'requireActivation', 'allowProfileUpdate', 'allowForgotPassword', 'allowRememberMe', 'enableAcl', 'redirectOn403'
        );

        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();

            $form->setData($postData);

            if ($form->isValid()) {
                $values = $form->getData();

                foreach ($permissions as $permission) {
                    $permissionsArray[$permission] = ($values['permissions_' . $permission] == 1)?true:false;
                }

                $globalConfig = array(
                    'zf-hipsters' => array(
                        'authorize' => array(
                            'user_table' => $values['user_table'],
                            'permissions' => $permissionsArray,
                            'redirects' => array(
                                'login_success' => 'home',
                                'logout' => 'authorize/login'
                            ),
                            'salt' => 'scDsejlSMYqqpXltYIvKtVHFMhASJutxecLmpolI',
                        ),
                    ),
                );

                $ds = DIRECTORY_SEPARATOR;
                $targetPath = getcwd() . $ds . 'config' . $ds . 'autoload' . $ds;

                $globalFile = new FileGenerator(array(
                    'filename' => $targetPath . 'authorize.global.php',
                    'docblock' => $this->getDocBlock(),
                    'body' => 'return ' . $this->var_export($globalConfig, true) . ';',
                ));

                $globalFile->write();

                $this->redirect()->toRoute('authorize_install', array('action'=>'email'));

            }

            var_dump($form->getMessages());
        }

        return new ViewModel(array(
            'form' => $form,
            'permissions' => $permissions,
        ));
    }

    public function emailAction()
    {
        $form = $this->getServiceLocator()->get('AuthorizeInstaller\Form\Email');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $postData = $request->getPost();

            $form->setData($postData);

            if ($form->isValid()) {
                $values = $form->getData();

                $globalConfig = array(
                    'mail' => array(
                        'from' => $values['from'],
                        'transport' => array(
                            'default' => $values['transport'], // Change to sendmail for basic php mail
                            'options' => array(
                                'host' => $values['options_connection_config_host'], // Eg smtp.gmail.com
                                'connection_class' => $values['options_connection_class'],
                                'connection_config' => array(
                                    'username' => $values['options_connection_config_username'],
                                    'password' => $values['options_connection_config_password'],
                                    'ssl' => $values['options_connection_config_ssl'],
                                    'port' => $values['options_connection_config_port'],
                                ),
                            ),
                        ),
                    ),
                );

                $ds = DIRECTORY_SEPARATOR;
                $targetPath = getcwd() . $ds . 'config' . $ds . 'autoload' . $ds;

                $globalFile = new FileGenerator(array(
                    'filename' => $targetPath . 'email.local.php',
                    'docblock' => $this->getDocBlock(),
                    'body' => 'return ' . $this->var_export($globalConfig, true) . ';',
                ));

                $globalFile->write();

                $this->redirect()->toRoute('authorize_install', array('action'=>'complete'));
            }
        }

        return new ViewModel(array(
            'form' => $form,

        ));
    }

    public function completeAction()
    {

    }

    public function var_export($array)
    {
        $pretty = preg_replace('/=>\s*array/', '=> array', var_export($array, 1));
        $pretty = preg_replace('/[ ]{2}/', '    ', $pretty);

        return $pretty;
    }

    public function getDocBlock()
    {
        return new DocBlockGenerator(
            'ZF-Hipsters Authorize (https://github.com/zf-hipsters)',
            null,
            array(
                array(
                    'name'        => 'link',
                    'description' => 'https://github.com/zf-hipsters/authorize for the canonical repository',
                ),
                array(
                    'name'        => 'copyright',
                    'description' => 'Copyright (c) 2013 ZF-Hipsters',
                ),
                array(
                    'name'        => 'license',
                    'description' => 'http://www.apache.org/licenses/LICENSE-2.0 Apache Licence, Version 2.0',
                ),
            )
        );
    }
}