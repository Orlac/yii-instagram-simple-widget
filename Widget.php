<?php
namespace instagramWidget;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Widget
 *
 * @author antonio
 */
class Widget extends \CWidget{
    //put your code here
    
    public $userName;
    public $clientId;
    
    public $cachePath = 'cache';

    public $cacheTime=600; //5 min

    public $count;

    public function run(){
        
        $cr=new \CDbCriteria();
        $cr->addColumnCondition(array('username'=>  $this->userName));
        if(!$userModel = Images::model()->find($cr)){
            $userModel = new Images();
            $userModel->username=  $this->userName;
            if(!$userModel->save()){
                throw new \CException(\Chtml::errorSummary($userModel));
            }
        }
        
        $upadatedTime = strtotime($userModel->updatedOn);
        $images = $userModel->getPhotos();
        
        if($upadatedTime+$this->cacheTime < time() || count($images) !== (int)  $this->count){
            $user = $this->send('https://api.instagram.com/v1/users/search?q='.$this->userName.'&client_id='.$this->clientId);
            if(!$user = json_decode($user)){
                throw new \CException('User OR CLIENT_ID not found');
            }
            if( $user->meta->code === 200){
                $user = $user->data[0];
                $images = $this->send('https://api.instagram.com/v1/users/'.$user->id.'/media/recent/?client_id='.$this->clientId.'&count='.$this->count);
                $images = json_decode($images);
                $userModel->setParsedData($images);
                if(!$userModel->save()){
                    throw new \CException(\Chtml::errorSummary($userModel));
                }
                
            }else{
                throw new \CException('User OR CLIENT_ID not found');
            }
        }
        $images = $userModel->getPhotos();
        $this->render('inner', array('images'=>$images) );
    }
    
    protected function send($url){
		if(extension_loaded('curl')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_HEADER, false);
			curl_setopt($ch, CURLOPT_POST, false);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:26.0) Gecko/20100101 Firefox/26.0');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_URL, $url);
			$answer = curl_exec($ch);
			curl_close($ch);
			return $answer;
		}
		elseif(ini_get('allow_url_fopen') AND extension_loaded('openssl')){
			$answer = file_get_contents($url);
			return $answer;
		}
        else{
            throw new \CException('Can\'t send request. You need the cURL extension OR set allow_url_fopen to "true" in php.ini and openssl extension');
        }
	}
}
