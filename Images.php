<?php
namespace instagramWidget;
/**
 * This is the model class for table "instagram_images".
 *
 * The followings are the available columns in table 'instagram_images':
 * @property integer $id
 * @property string $username
 * @property string $data
 * @property string $updatedOn
 */
class Images extends _base\ImagesBase{
	
    public function behaviors() {
        return array(
			'CTimestampBehavior' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => null,
				'updateAttribute' => 'updatedOn',
				'setUpdateOnCreate' => true,
			),
        );
    }
    
    public function getParsedData(){
        return json_decode($this->data, true);
    }
    
    public function getPhotos(){
        $data = $this->getParsedData();
        $images = ($data['data'])? $data['data'] : array();
        return $images;
    }
    
    public function setParsedData($data){
        $this->data=  json_encode($data);
    }
    
    public static function model($className=__CLASS__){
		return parent::model($className);
	}
}
