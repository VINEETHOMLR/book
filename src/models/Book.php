<?php


namespace src\models;

use inc\Raise;
use src\lib\Database;
use src\lib\Router;
use src\traits\DataTableTrait;
use src\traits\FilterTrait;
use src\traits\ModelTrait;
use src\lib\Helper as H;


class Book extends Database
{
    use ModelTrait, FilterTrait, DataTableTrait;
    protected $pk = 'id';
    /**
     * Constructor of the model
     */
    public function __construct($db = "db")
    {
        
        parent::__construct(Raise::db()[$db]);

        $this->tableName = "book";

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


    public function getList($params){


        $where = " WHERE status=1 ";
        if(!empty($params['category_id'])) {
            
            $where.= " AND category_id=$params[category_id]";
        }
        $response = $this->callSql("SELECT * FROM $this->tableName $where  ORDER BY id DESC ","rows");


        $rows = array();

        if (!empty($response)) {
                foreach ($response as $key => $info) {
                    
                    $author_id  = $info['user_id'];
                    $author = $this->callSql("SELECT fullname FROM user WHERE id=$author_id","value");
                   
                    $rows[$key]['id']    = !empty($info['id'])?strval($info['id']):'-';
                    $rows[$key]['title']  = !empty($info['title'])?$info['title']:'-';
                    $rows[$key]['author']  = !empty($author)?$author:'-';
                    $rows[$key]['cover_photo'] = !empty($info['cover_photo'])?BASEURL.'web/uploads/cover/'.$info['cover_photo']:'';
                    $rows[$key]['synopsis']  = !empty($info['synopsis'])?$info['synopsis']:'-';
                    
                }
        }

       

        

        return !empty($rows) ? $rows :$rows;
    }


    public function getDetails($id){
        
        $bookDetails = $this->callSql("SELECT * FROM $this->tableName WHERE id=$id","row");
        $result = [];
        if(!empty($bookDetails)) {
            
            $author_id  = $bookDetails['user_id'];
            $author = $this->callSql("SELECT fullname FROM user WHERE id=$author_id","value");
            $result['title']       = !empty($bookDetails['title']) ? $bookDetails['title'] : '';
            $result['cover_photo'] = !empty($bookDetails['cover_photo']) ? BASEURL.'web/uploads/cover/'.$bookDetails['cover_photo'] : '';
            $result['author']      = !empty($author) ? $author : '';
            $result['synopsis']    = !empty($bookDetails['synopsis'])?$bookDetails['synopsis']:'-';
            $result['pdf_file']    = !empty($bookDetails['pdf_file']) ? BASEURL.'web/uploads/pdf/'.$bookDetails['pdf_file'] : '';
            $result['user_id']       = !empty($bookDetails['user_id']) ? $bookDetails['user_id'] : '';
        }

        return $result;

    }
    
    

    

    
   
    
}
