<?php


namespace src\models;

use inc\Raise;
use src\lib\Database;
use src\lib\Router;
use src\traits\DataTableTrait;
use src\traits\FilterTrait;
use src\traits\ModelTrait;
use src\lib\Helper as H;


class Announcement extends Database
{
    use ModelTrait, FilterTrait, DataTableTrait;
    protected $pk = 'id';
    /**
     * Constructor of the model
     */
    public function __construct($db = "db")
    {
        
        parent::__construct(Raise::db()[$db]);

        $this->tableName = "announcement";

        $this->columns = [
            'id', 
            'title', 
            'message',
            'createtime',  
        ];
    }

    /**
     *
     * @return Array
     */
    public static function attrs()
    {
        return  [
            'id', 
            'title', 
            'message', 
            'createtime', 
        ];
    }

    /**
     *
     * @return $this
     */
    public function assignAttrs($attrs = [])
    {   
        $isExternal = !empty($attrs);
        foreach (($isExternal ? $attrs : self::attrs()) as $eAttr => $attr) {
            $aAttr = $isExternal ? $eAttr : $attr;
           $this->{$aAttr} = $isExternal ? $attr : "";
        }
        
        return $this;
    }


    /**
     *
     * @param INT $pk
     */
    public function findByPK($pk)
    {
        $dtAry = parent::findByPK($pk);
        foreach ($dtAry as $attr => $val) {
            $this->{$attr} = $val;
        }
        return $this;
    }

   
    /**
     *
     * @return attrs data array
     */
    public function convertArray()
    {
        $temp = array();
        $attrs = $this->attrs();
        foreach ($attrs as $key) {
            $temp[$key] = isset($this->{$key})?$this->{$key}:'';
        }
        return $temp;
    }

    public function createRecord($data)
    {
        $this->assignAttrs($data);
        return $this->save();
    }    


    public function getList($param){


        $userId = $param['player_id']; 
		$language = $param['language'];
		
		$lang_id = $this->callSql("SELECT id FROM `language` WHERE  lang_code='$language' ","value");

        $response = $this->callSql("SELECT id,title,message,createtime FROM $this->tableName WHERE  status=0 and lang_id='$lang_id' ORDER BY id DESC ","rows");

        $rows = array();

        if (!empty($response)) {
                foreach ($response as $key => $info) {
                   
                    $rows[$key]['id'] = !empty($info['id'])?strval($info['id']):'-';
                    $rows[$key]['date'] = !empty($info['createtime'])?date('Y-m-d H:i:s',$info['createtime']):'-';
                    $rows[$key]['title'] = !empty($info['title'])?$info['title']:'-';
                    $rows[$key]['description'] = !empty($info['message'])?$info['message']:'-';
                }
        }/*else{

                    $rows[0]['id'] = '101';
                    $rows[0]['date'] = '2020-07-20 12:25:00';
                    $rows[0]['title'] = 'Extension of BTC Note upgrade,test1';
                    $rows[0]['description'] = 'Extension of BTC Note upgrade...,test2...';
                    $rows[1]['id'] = '102';
                    $rows[1]['date'] = '2020-07-19 10:25:00';
                    $rows[1]['title'] = 'Extension of BTC Note upgrade,test2';
                    $rows[1]['description'] = 'Extension of BTC Note upgrade...,test2...';

        }*/

        

        return !empty($rows) ? $rows :$rows;
    }
    public function getSingle($param){


        $userId     = $param['player_id'];
        $annou_id   = $param['annoucement_id'];

        $response = $this->callSql("SELECT id,title,message,createtime FROM $this->tableName WHERE  id= '$annou_id' ORDER BY id DESC ","rows");

        $rows = array();
        
        if (!empty($response)) {
                foreach ($response as $key => $info) {
                   
                    $rows[$key]['id'] = !empty($info['id'])?strval($info['id']):'-';
                    $rows[$key]['date'] = !empty($info['createtime'])?date('Y-m-d H:i:s',$info['createtime']):'-';
                    $rows[$key]['title'] = !empty($info['title'])?$info['title']:'-';
                    $rows[$key]['description'] = !empty($info['message'])?$info['message']:'-';
                }
        }/*else{

                    $rows[0]['id'] = '101';
                    $rows[0]['date'] = '2020-07-20 12:25:00';
                    $rows[0]['title'] = 'Extension of BTC Note upgrade,test';
                    $rows[0]['description'] = 'Extention of Btc note upgrade is about to ..test';

        }*/

        //return $rows;
        //return !empty($rows) ? $rows :(object)$rows;
        return !empty($rows) ? $rows :$rows;
    }
    

    

    
   
    
}
