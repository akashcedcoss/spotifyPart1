<?php

require BASE_PATH.'/vendor/autoload.php';
use Phalcon\Mvc\Controller;
use GuzzleHttp\Client;

class SpotifyController extends Controller
{
    /**
     * Generated token to access spotify API.
     *
     * @return void
     */
    public function indexAction()
    {   
        $user = '31wvkutnkqrj4z5dem2dnyuqi6jm';
        $client_id = 'c4e8a7adef2a49118f7cdf746eca525a';
        $client_secret = 'e5cc1a64c27d43ba89cb85cba7c3c26b';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.spotify.com/api/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Basic '.base64_encode($client_id.':'.$client_secret)));
        $result=json_decode(curl_exec($ch), 1);
        print_r($result);
        $this->view->result = $result;
/**
 * Listing recommendations
 */
        $token = $this->session->token;
        $ch2 = curl_init();
        curl_setopt($ch2, CURLOPT_URL, 'https://api.spotify.com/v1/recommendations?seed_artists=4NHQUGzhtTLFvgF5SZesLK&seed_genres=classical,country&seed_tracks=0c6xIDDpzE81m2q797ordA');
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
        $result=curl_exec($ch2);
        // echo $result;
        $result = json_decode($result);
        $this->view->recomendation = $result;


        
    }
    /**
     * Listed search items using some filters provided in checkbox.
     *
     * @return void
     */
    public function searchAction()
    {   
        echo"<pre>";
        $checks = $this->request->getPost('filter');
        $checks = implode(",", $checks);
        $token = $this->request->getPost('token');
        $search = $this->request->getPost('name');
        $search = str_replace(" ", "+", $search);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/search?query='.$search.'&type='.$checks.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));

        $result = curl_exec($ch);
        $this->view->result = json_decode($result);
        $this->view->token = $token;
    }
    public function userAction() {

    }
    public function addtoplaylistAction()
    {

    }
    /**
     * Listed the playlists of user.
     *
     * @return void
     */
    public function getplaylistAction()
    {

        $user = '31wvkutnkqrj4z5dem2dnyuqi6jm';
        $token = $this->session->token;
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/users/'.$user.'/playlists');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
        $result = curl_exec($ch);
        $result = json_decode($result);
        $this->view->result = $result;
    }


    /**
     * Create new playlist function is added.
     *
     * @return void
     */
    public function createplaylistAction() {
        $token = $this->session->token;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/users/31wvkutnkqrj4z5dem2dnyuqi6jm/playlists');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
        $data = [
            'name' =>  $this->request->getPost('p_name'),
            'description' =>  $this->request->getPost('p_desc'),
            'public' =>  $this->request->getPost('visibility'),
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $result=curl_exec($ch);
        $result = json_decode($result);
    }
    public function addplaylistAction() {
            
         }
         /**
          * Added songs in the playlist.
          *
          * @return void
          */
         public function playlistaddAction() {
            print_r($this->request->getPost());
            $playlistid = $this->request->getPost('playlist');
            $trackid = $this->request->getPost('trackid');        
                $data =  [
                        "uris" => [
                        $trackid
                        ],
                        "position" => 0
                ];
                $token = $this->session->token;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/playlists/'.$playlistid.'/tracks');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                $result=curl_exec($ch);
                $result = json_decode($result);
                $this->response->redirect('/spotify/getplaylist');
            
         }
         /**
          * Listed every songs/tracks present in playlist.
          *
          * @return void
          */
         public function playlistitemAction() {

            print_r($this->request->getQuery());
            $name = $this->request->getQuery('name');
            $playlistid = $this->request->getQuery('id');
            $token = $this->session->token;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/playlists/'.$playlistid.'/tracks');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
            $result = curl_exec($ch);
            $result = json_decode($result);
            $this->view->result = $result;

         }
         /**
          * Delete songs/tracks function is added.
          *
          * @return void
          */
         public function deleteAction() {
            $track_id = $this->request->getQuery('id');
            $playlist_id = $this->request->getQuery('playlist');
            $data =  [
                "uris" => [
                $track_id
                ],
                "position" => 0
            ];
            $token = $this->session->token;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.spotify.com/v1/playlists/'.$playlist_id.'/tracks');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$token));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result=curl_exec($ch);
            $result = json_decode($result);
            $this->response->redirect('/spotify/getplaylist');
         }
         /**
          * Fetching token details as response.
          *
          * @return void
          */
        public function tokenAction()
        {
            $code = $this->request->getQuery('code');
            $id = 'c4e8a7adef2a49118f7cdf746eca525a';
            $secret = 'e5cc1a64c27d43ba89cb85cba7c3c26b';
            $redirect_uri = "http://localhost:8080/spotify/token";
            $client = new Client([
                'headers' => [
                    'Authorization' => 'Basic '.base64_encode($id.':'.$secret),
                    'Content-Type' => 'application/x-www-form-urlencoded'
                    ]
                ]);
    
            
            $body = array(
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirect_uri,
            );
    
            $response = $client->request('POST', 'https://accounts.spotify.com/api/token', [
                'form_params' => $body
            ]);
            $response = json_decode($response->getBody());
            $this->session->response = $response;
            $this->response->redirect('spotify');
        }

}
