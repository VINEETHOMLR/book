<?php


namespace src\models;

use inc\Raise;
use src\lib\Database;
use src\lib\Router;
use src\traits\DataTableTrait;
use src\traits\FilterTrait;
use src\traits\ModelTrait;
use src\lib\Helper as H;


class Bookmark extends Database
{
    use ModelTrait, FilterTrait, DataTableTrait;
    protected $pk = 'id';
    /**
     * Constructor of the model
     */
    public function __construct($db = "db")
    {
        
        parent::__construct(Raise::db()[$db]);

        $this->tableName = "bookmark";

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
            $result['cover_photo'] = !empty($bookDetails['cover_photo']) ? BASEURL.'web/upload/cover/'.$bookDetails['cover_photo'] : '';
            $result['author']      = !empty($author) ? $author : '';
            $result['synopsis']    = !empty($bookDetails['synopsis'])?$bookDetails['synopsis']:'-';
            $result['pdf_file']    = !empty($bookDetails['pdf_file']) ? BASEURL.'web/upload/pdf/'.$bookDetails['pdf_file'] : '';
            $result['author_id']       = !empty($bookDetails['user_id']) ? $bookDetails['user_id'] : '';
        }

        return $result;

    }


    public function insertBookmark($params){

        $user_id         = !empty($params['user_id']) ? $params['user_id'] : '';
        $book_id         = !empty($params['book_id']) ? $params['book_id'] : '';
        $status          = '1';
        $created_at      = time();
        $query = "INSERT INTO $this->tableName (`user_id`,`book_id`,`status`,`created_at`) VALUES(:user_id,:book_id,:status,:created_at)";
        $this->query($query);
        $this->bind(':user_id', $user_id);
        $this->bind(':book_id', $book_id);
        $this->bind(':created_at', $created_at);
        $this->bind(':status', $status);
        if($this->execute()){
            return true;
        }
        return false;

    }

    public function updateBookmark($params){

        $user_id         = !empty($params['user_id']) ? $params['user_id'] : '';
        $book_id         = !empty($params['book_id']) ? $params['book_id'] : '';
        $status          = '2';
        $updated_at      = time();
        $query = "UPDATE $this->tableName SET status='2',updated_at=$updated_at WHERE user_id=$user_id AND book_id=$book_id";
        $this->query($query);
        if($this->execute()){
            return true;
        }
        return false;

    }

    public function checkAlreadyAdded($book_id,$user_id){
        
        return  $this->callSql("SELECT * FROM $this->tableName WHERE user_id=$user_id AND book_id=$book_id AND status=1","rows");
    }

    public function getBookMarkList($params){

        $user_id         = !empty($params['user_id']) ? $params['user_id'] : '';

        $where = " WHERE status=1 ";
        if(!empty($params['user_id'])) {
            
            $where.= " AND user_id=$params[user_id]";
        }
        $response = $this->callSql("SELECT * FROM $this->tableName $where  ORDER BY id DESC ","rows");


        $rows = array();

        if (!empty($response)) {
                foreach ($response as $key => $info) {


                    $bookDetails = $this->callSql("SELECT *  FROM book WHERE id=$info[book_id] AND status=1","row");

                    if(!empty($bookDetails)) {
                        
                        $author_id  = $info['user_id'];
                        $author     = $this->callSql("SELECT fullname FROM user WHERE id=$author_id","value");
                   
                        $rows[$key]['book_id']    = !empty($bookDetails['id'])?strval($bookDetails['id']):'-';
                        $rows[$key]['title']  = !empty($bookDetails['title'])?$bookDetails['title']:'-';
                        $rows[$key]['author']  = !empty($author)?$author:'-';
                        $rows[$key]['cover_photo'] = !empty($bookDetails['cover_photo'])?BASEURL.'web/upload/cover/'.$bookDetails['cover_photo']:'';
                        $rows[$key]['synopsis']  = !empty($info['synopsis'])?$info['synopsis']:'-';
                    }
                    
                    
                    
                }
        }

       

        

        return !empty($rows) ? $rows :$rows;

    }
    
    

    

    
   
    
}
