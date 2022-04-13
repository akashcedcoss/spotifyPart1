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
        $this->response->redirect('spotify');
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
            

        );
        

        $user->assign(
            $userdata,
            [
                'name',
                'email',
                'username',
                'password',
                
            ]
        );
        

        $success = $user->save();
    }
}