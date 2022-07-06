<?php
namespace app\utils\gdrive;
use Yii;
class Gdrive{

    public function getClient()
    {
        $client = new \Google_Client();
        $client->setApplicationName('Quickstart APP');
        $client->setScopes(Yii::$app->params['drive']['scopes']);
        $client->setAuthConfig(Yii::$app->params['drive']['credentials']);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->setApprovalPrompt('force');
        $tokenPath = Yii::$app->params['drive']['token'];
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }
    
        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                if(isset($_GET['code'])){
                    $code=$_GET['code'];
                    if (preg_match("@^[a-zA-Z0-9%+-_]*$@", $code)) {
                        $code= urldecode($code);
                    }
                    // Exchange authorization code for an access token.
                    $accessToken = $client->fetchAccessTokenWithAuthCode($code);
                    $client->setAccessToken($accessToken);
                    // Check to see if there was an error.
                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                    // Save the token to a file.
                    if (!file_exists(dirname($tokenPath))) {
                        mkdir(dirname($tokenPath), 0700, true);
                    }
                    file_put_contents($tokenPath, json_encode($client->getAccessToken()));
                    die("token berhasil di buat.., silahkan refresh");
                }
                else{
                    // Request authorization from the user.
                    $authUrl = $client->createAuthUrl();
                    header("Location: $authUrl");
                    exit;
                }
            }
        }
        return $client;
    }

    public function getList()
    {
        // Get the API client and construct the service object.
        $client = $this->getClient();
        $service = new \Google_Service_Drive($client);
        $optParams = array(
            'pageSize' => 10,
            'fields' => 'nextPageToken, files(id, name)'
        );
        $results = $service->files->listFiles($optParams);

        if (count($results->getFiles()) == 0) {
            return NULL;
        } else {
            $data=[];
            foreach ($results->getFiles() as $file) {
                $data[]=[$file->getName()=>$file->getId()];
            }
        }
    } 

    public function uploadFile($name, $tmpPath, $mimeType){
        $client = $this->getClient();
        $service = new \Google_Service_Drive($client);
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
            'name' => $name, 'parents' => array(Yii::$app->params['drive']['folder'])));
        $file = $service->files->create($fileMetadata, array(
            'data' => file_get_contents($tmpPath),
            'mimeType' => $mimeType,
            'uploadType' => 'multipart',
            'fields' => '*'));
        $permission = $this->getFilePermissions(Yii::$app->params['drive']['permission']);
        if(!empty($file->id)):
            $service->permissions->create($file->id, $permission);
        endif;
        return $file->id;
    }

    public function readIdToUrl($id){
        return 'https://docs.google.com/uc?id='.$id;
    }

    private function getFilePermissions($allow="private") {
        $permission = new \Google_Service_Drive_Permission();
        switch($allow):
            case "private":
                $permission->setType('user');
                $permission->setRole('owner');
                break;
            case "public":
                $permission->setAllowFileDiscovery(true);
                $permission->setType('anyone');
                $permission->setRole('reader');
                break;
            default:
                $permission->setType('anyone');
                $permission->setRole('reader');
                break;
        endswitch;
        return $permission;
    }

}

?>