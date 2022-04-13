<?php

require BASE_PATH.'/vendor/autoload.php';
use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;

class IndexController extends Controller
{
    /**
     * Listed every city using API
     *
     * @return void
     */
    public function indexAction()
    {
        // $this->response->redirect('spotify');
    }
    public function signupAction()
    {
        $user = new Users();
        $data = $this->request->getPost();
        $userdata = array(
            "name" =>$this->request->getPost("name"),
            "email" => $this->request->getPost("email"),
            "username" => $this->request->getPost("username"),
            "password" => $this->request->getPost("password"),
            "accesstoken" => $this->session->response->access_token,
            "refreshtoken" => $this->session->response->refresh_token,

            

        );
        

        $user->assign(
            $userdata,
            [
                'name',
                'email',
                'username',
                'password',
                'accesstoken',
                'refreshtoken',
                
            ]
        );
        

        $success = $user->save();
        $this->response->redirect('index/login');
    }
    public function loginAction() {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = Users::find(
            [
                'email = ?1 AND password = ?2',
                'bind' => [
                    1 => $email,
                    2 => $password,
                ],
            ]
        );
        if(count($user)) {
            $this->response->redirect('index/connect');
        }
    }
    public function connectAction() {

    }
}