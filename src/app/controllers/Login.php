<?php

/*
 * The Login Controller
 * Link http://github.com/typefo/pbx-mon
 * By typefo <typefo@qq.com>
 */

use Tool\Filter;

class LoginController extends Yaf\Controller_Abstract {
    public function indexAction() {
        $request = $this->getRequest();
        $response = $this->getResponse();

        /* Check login action */
        if ($request->isPost()) {
            $data = $request->getPost();
            $login = new LoginModel($data);

            /* Verify username and password */
            if (!$login->verify()) {
                goto output;
            }

            /* Check ip whitelist */
            $config = new ConfigModel();
            if ($config->get('security') === '1') {
                $acl = new AclModel();
                if (!$acl->check($_SERVER['REMOTE_ADDR'])) {
                    goto output;
                }
            }

            $session = Yaf\Session::getInstance();
            $session->set('login', true);
            $response->setRedirect('/cdr');
            $response->response();
            return false;
        }

        output:
        $response->setRedirect('/');
        $response->response();
        return false;
    }
}


