<?php

namespace src\lib;

/**
 * Customized Pagination Handler File
 *
 * 
 */
class Pagination {

    /**
     *
     * @var Object Model
     */
    private $model;

    /**
     *
     * @var INT
     */
    public $totRecords = 0;

    /**
     *
     * @var Array
     */
    public $filters = [];

    /**
     *
     * @var INT 
     */
    public $page = 1;

    /**
     *
     * @var INT 
     */
    public $start = 1;

    /**
     *
     * @var INT 
     */
    public $end = 0;

    /**
     *
     * @var INT 
     */
    public $limit = 25;

    /**
     *
     * @var String 
     */
    public $sort = '';
    public $lastPK = 0;

    public function __construct($model, $filters, $page = 1) {
        $this->model = $model;
        $this->filters = $filters;
        $this->page = $page;
        $this->setCount();
    }

    /**
     * Method to calculate the total records
     */
    public function setCount() {
        $where = $this->model->where($this->filters);
        $this->model->query("SELECT count(1) as totCnt FROM {$this->model->tableName} $where");
        $this->model->attrBind($this->filters);
        $this->totRecords = $this->model->single()['totCnt'];
    }

    /**
     * 
     * @return Array
     */
    public function getData() {
        $offset = (isset($this->page) && (int) $this->page >= 1) ? $this->limit * ($this->page - 1) : 0;
        return $this->model->findAll($this->filters, $this->sort, ' LIMIT ' . $offset . ', ' . $this->limit);
    }

    /**
     * Method to return the stripped alphanumeric
     * @param String $attr
     * @return String
     */
    private function stripAttr($attr) {
        return preg_replace("/[^a-zA-Z0-9]+/", "", $attr);
    }

    /**
     * Method to do pagination with speed query optimization
     * @return Array
     */
    public function getFastData() { //print_r($this->filters);
        $pk = $this->model->getPK(); //echo $pk;
        $where = $this->model->where($this->filters); //echo $where;
        $pkWhere = "`{$pk}` > :pk{$this->stripAttr($pk)}";
        if ((int) $this->lastPK > 0) {
            $where .= !empty($where) ? " AND {$pkWhere}" : " WHERE " . $pkWhere;
        }
        try { //echo "SELECT * FROM `{$this->model->tableName}` {$where} ORDER BY `{$pk}` ASC LIMIT {$this->limit}"; //exit;
            $this->model->query("SELECT * FROM `{$this->model->tableName}` {$where} ORDER BY `{$pk}` ASC LIMIT {$this->limit}");
            $this->model->attrBind($this->getWherePK($pk));
            $res = $this->model->resultset();
        } catch (\PDOException $pdoE) {
            echo '<pre>';
            print_r($pdoE);
            echo '</pre>';
            die;
        }
        $this->setLastPK($res, $pk);
        return $res;
    }

    /**
     * 
     * @param String $pk
     * @return Array
     */
    private function getWherePK($pk) {
        $filter = $this->filters;
        if ((int) $this->lastPK > 0) {
            $filter["pk{$this->stripAttr($pk)}"] = (string) $this->lastPK;
        }
        return $filter;
    }

    /**
     * 
     * @param Array $res
     * @param String $pk
     */
    public function setLastPK($res, $pk) { //echo $pk;
        $last = end($res);
        $this->lastPK =$pk; 
        //$this->lastPK =array_key_exists($pk, $last) ? $last[$pk] : 0;
    }

    public function getPaginationString($page, $totalitems, $limit, $adjacents, $targetpage = "/", $pagestring = "?page=") {
        //defaults
        //echo "$page, $totalitems, $limit, $adjacents,";
        if (!$adjacents)
            $adjacents = 1;
        if (!$limit)
            $limit = 15;
        if (!$page)
            $page = 1;
        if (!$targetpage)
            $targetpage = "/";
        $ogrtarget = $targetpage;

        $adjacents = 1;

        //other vars
        $prev = $page - 1;         //previous page is page - 1
        $next = $page + 1;         //next page is page + 1
        $lastpage = ceil($totalitems / $limit);    //lastpage is = total items / items per page, rounded up.
        $lpm1 = $lastpage - 1;        //last page minus 1

        if($page == 1)
            $fromcount = 1;
        else
            $fromcount = ($page -1) * $limit +1;

        if($page == $lastpage)
            $tocount = $totalitems;
        else
            $tocount = $page * $limit;

        /*
          Now we apply our rules and draw the pagination object.
          We're actually saving the code to a variable in case we want to draw it more than once.
         */
        $pagination = "";
        if ($lastpage > 1) {
            // $pagination .= '<div class=row">
            //             <div class="col-sm-6">
            //                     <div id="rechargehistory-table_info" class="dataTables_info" role="alert" aria-live="polite" aria-relevant="all">Showing '.$fromcount.' to '.$tocount.' of '.$totalitems.' entries</div>
            //             </div>
            //             <div class="col-sm-6">
            //                 <div id="rechargehistory-table_paginate" class="dataTables_paginate paging_simple_numbers">
            //                     <ul class="pagination">';
            //previous button
            if ($page > 1) {
                $targetpage = str_replace('***', $prev, $ogrtarget);
                $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>&laquo;</a></li>";
            } else
                $pagination .= "<li class='paginate_button page-item disabled' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>&laquo;</a></li>";

            //pages
            if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class='paginate_button page-item active' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;'  class='page-link'>$counter</a></li>";
                    else
                    {
                        $targetpage = str_replace('***', $counter, $ogrtarget);
                        $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>$counter</a></li>";
                    }
                }
            }
            elseif ($lastpage >= 7 + ($adjacents * 2)) { //enough pages to hide some
                //close to beginning; only hide later pages
                if ($page < 1 + ($adjacents * 3)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='paginate_button page-item active' aria-controls='rechargehistory-table' tabindex='0'><a class=\"page-link\">$counter</a></li>";
                        else
                        {
                            $targetpage = str_replace('***', $counter, $ogrtarget);
                            $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>$counter</a></li>";
                        }
                    }
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><span class=\"elipses\">...</span></li>";
                    //$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
                    $targetpage = str_replace('***', $lpm1, $ogrtarget);
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>$lpm1</a></li>";
                    //$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";
                    $targetpage = str_replace('***', $lastpage, $ogrtarget);
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>$lastpage</a></li>";
                }
                //in middle; hide some front and some back
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $targetpage = str_replace('***', 1, $ogrtarget);
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>1</a></li>";
                    $targetpage = str_replace('***', 2, $ogrtarget);
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>2</a></li>";
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><span class=\"elipses\">...</span></li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='paginate_button page-item active' aria-controls='rechargehistory-table' tabindex='0'><a class=\"page-link\">$counter</a></li>";
                        else
                        {
                            $targetpage = str_replace('***', $counter, $ogrtarget);
                            $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>$counter</a></li>";
                        }
                    }
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><span class=\"elipses\">...</span></li>";
                    //$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
                    $targetpage = str_replace('***', $lpm1, $ogrtarget);
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>$lpm1</a></li>";
                    //$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";
                    $targetpage = str_replace('***', $lastpage, $ogrtarget);
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>$lastpage</a></li>";
                }
                //close to end; only hide early pages
                else {
                    $targetpage = str_replace('***', 1, $ogrtarget);
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>1</a></li>";
                    //$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";
                    $targetpage = str_replace('***', 2, $ogrtarget);
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>2</a></li>";
                    $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><span class=\"elipses\">...</span></li>";
                    for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='paginate_button page-item active' aria-controls='rechargehistory-table' tabindex='0'><a class=\"page-link\">$counter</a></li>";
                        else
                        {
                            $targetpage = str_replace('***', $counter, $ogrtarget);
                            $pagination .= "<li class='paginate_button page-item' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;' class='page-link'>$counter</a></li>";
                        }
                    }
                }
            }

            //next button
            if ($page < $counter - 1)
            {
                //$pagination .= "<a href=\"" . $targetpage . $pagestring . $next . "\">next »</a>";
                $targetpage = str_replace('***', $next, $ogrtarget);
                $pagination .= "<li class='paginate_button' aria-controls='rechargehistory-table' tabindex='0'><a $targetpage style='cursor:pointer;'  class='page-link'>&raquo;</a></li>";
            }
            else
                $pagination .= "<li class='paginate_button disabled' aria-controls='rechargehistory-table' tabindex='0'><a style='cursor:default;'  class='page-link'>&raquo;</a></li>";

            $pagination .='             </ul>
                                    </div>
                                </div>
                            </div>';;

        }
        //echo $pagination;
        return $pagination;
    }

}
