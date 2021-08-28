<?php

namespace src\lib;

//use src\lib\RRedis;
use src\lib\Database;
use src\lib\walletClass;
use inc\Raise;

class commonClass extends Database
{
    public function __construct($db = 'db')
    {
        parent::__construct(Raise::params()[$db]);
        //$this->rds = new RRedis();
        $this->wal = new walletClass();
        $this->skp = 0;
    }

    /**
     * Public Method to get All My Parring Upline
     * @param int $id
     * @param Array $res
     * @return Array $res
     */

    public function getPlacingUpline($id, $res = array())
    {
        $myUp = $this->callSql("SELECT paring_id FROM user WHERE id='$id'", 'value');

        if (!empty($myUp)) {
            $res[] = $myUp;
            $res = $this->getPlacingUpline($myUp, $res);
        }
        return $res;
    }

    /**
     * Public Method to get All My Parring Upline with My Position
     * @param int $id 
     * @param Array $res
     * @return Array $res
     */

    public function getMyUplineWithPosition($id, $res = array())
    {
        $myUp = $this->callSql("SELECT paring_id FROM user WHERE id='$id'", 'value');

        if (!empty($myUp)) {
            $res[] = $myUp;
            $res = $this->getMyUplineWithPosition($myUp, $res);
        }
        return $res;
    }

    /**
     * Public Method to get all my Direct Sponsor Upline
     * @param INT $id User Id
     * @param Array $res
     * @return Array $res
     */
    public function getDirectSponsorUpline($id, $res = array())
    {
        $dsId = $this->callSql("SELECT sponsor FROM user WHERE id='$id'", 'value');
        if (!empty($dsId)) {
            $res[] = $dsId;
            $res = $this->getDirectSponsorUpline($dsId, $res);
        }
        return $res;
    }


    /**
     * Public Function to allocate Parring Left & Right
     * @param Array $parringArray Parring Upline Array with Position
     * @param INT $amount Purchase Amount
     * @return NULL 
     */

    public function allocateLeftRight($parringArray, $amount = 0)
    {
        foreach ($parringArray as $li) {
            $id = $li['paring_id'];
            $pos = $li['position'];

            if ($pos == 0)
                $sql = 'pb_left = pb_left+' . $amount;
            else
                $sql = 'pb_right = pb_right+' . $amount;

            $update  = $this->callsql("UPDATE user_wallet SET $sql WHERE user_id='$id'");
        }
        return NULL;
    }

    /** Prorcess Parring & Compress Paring  Per Transaction
     * @param Array $parringArray Parring Upline Array
     * @param INT $buyerId Purchasing User Id
     * 
     */
    public function processParringBouns($buyerId, $pairAmount, $leftRightArray)
    {
        $maxCommission = $this->getSystemMaxParringCommission();

        $this->leftOver  = 0;
        $this->totalUsed = 0;
        $myCom = $this->getUserParrinCommissionValue($buyerId);
        $this->leftOver = $maxCommission - $myCom;
        $this->totalUsed = $myCom;

        $this->leftRightArray = $leftRightArray;
        $beforeLeft = $this->leftRightArray['bl'];
        $afterleft = $this->leftRightArray['al'];
        $beforeright = $this->leftRightArray['br'];
        $afterright = $this->leftRightArray['ar'];

        $date = date('Y-m-d');

        $time = time();

        $ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($myCom)) {
            $this->pairedAmount = $pairAmount;

            $myPb = bcmul(bcdiv($pairAmount, 100, 2), $myCom, 2);

            $capsLeft = $this->checkUserCaps($buyerId);

            if ($capsLeft > $myPb) {
                $deb = $myPb;
            } else {
                $deb = $capsLeft;
            }

            if ($deb > 0) {



                $this->callSql("INSERT INTO pb_history (`user_id`,`init_id`,`compressfrom_id`,`value`,`level`,`precentage`,`before_left`,`before_right`,`after_left`,`after_right`,`transactiondate`,`createtime`,`createip`,`display_status`) VALUES 
                            ($buyerId,0,0,$deb,0,$myCom,$beforeLeft,$beforeright,$afterleft,$afterright,'$date',$time,'$ip',0)");

                if ($myPb > $deb) {
                    $missed = $myPb - $deb;

                    $this->callSql("INSERT INTO pb_history (`user_id`,`init_id`,`compressfrom_id`,`value`,`level`,`precentage`,`before_left`,`before_right`,`after_left`,`after_right`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES 
                            ($buyerId,0,0,$missed,0,$myCom,$beforeLeft,$beforeright,$afterleft,$afterright,'$date',$time,'$ip',0,1)");
                }

                $newBal = $this->wal->updateCapsLeftWallet($buyerId, 1, 1, $deb);

                $key  = 'caps_left_' . $buyerId;

                $this->rds->set($key, $newBal, 60);

                $this->callSql("UPDATE user_cutoff_payout SET hold_pb_wallet=hold_pb_wallet+$deb WHERE user_id='$buyerId'");

                if (!empty($this->leftOver)) {
                    $this->compressedFrom = $buyerId;

                    $getMyTree  = $this->callSql("SELECT data FROM ds_upline WHERE user_id='$buyerId'", 'value');

                    $dsArray = json_decode($getMyTree, true);

                    $this->lastKey = 0;

                    $this->processCompressParing($dsArray, $buyerId);
                }
            } else {
                $this->callSql("INSERT INTO pb_history (`user_id`,`init_id`,`compressfrom_id`,`value`,`level`,`precentage`,`before_left`,`before_right`,`after_left`,`after_right`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES 
                            ($buyerId,0,0,$myPb,0,$myCom,$beforeLeft,$beforeright,$afterleft,$afterright,'$date',$time,'$ip',0,1)");
            }
        } else {
            $this->callSql("INSERT INTO pb_history (`user_id`,`init_id`,`compressfrom_id`,`value`,`level`,`precentage`,`before_left`,`before_right`,`after_left`,`after_right`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES 
                            ($buyerId,0,0,0,0,$myCom,$beforeLeft,$beforeright,$afterleft,$afterright,'$date',$time,'$ip',0,1)");
        }
    }

    /** Process Compress Parring Bonus  
     * @param Array $parringArray Directsponsor Upline Array
     * @param INT $buyerId Purchasing User Id
     * @return BOOLEAN TRUE
     */
    private function processCompressParing($parringArray, $buyerId)
    {
        if (!empty($this->leftOver)) {
            $this->lastKey = $this->lastKey;
            $newUserId = $parringArray[$this->lastKey];
            $myCom = $this->getUserParrinCommissionValue($newUserId);
            if ($myCom > $this->totalUsed) {
                $diff = $myCom - $this->totalUsed;
                $this->leftOver = $this->leftOver - $diff;
                if ($this->leftOver >= 0 && $diff > 0) {
                    $this->totalUsed = $this->totalUsed + $diff;

                    $myPb = bcmul(bcdiv($this->pairedAmount, 100, 2), $diff, 2);

                    $date = date('Y-m-d');

                    $time = time();

                    $ip = $_SERVER['REMOTE_ADDR'];



                    $capsLeft = $this->checkUserCaps($newUserId);

                    if ($capsLeft > $myPb) {
                        $deb = $myPb;
                    } else {
                        $deb = $capsLeft;
                    }

                    if ($deb > 0) {

                        $this->callSql("INSERT INTO pb_history_compress (`user_id`,`init_id`,`value`,`level`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`) VALUES ($newUserId,$buyerId,$deb,$this->lastKey,$diff,$this->pairedAmount,'$date',$time,'$ip',0)");

                        if ($myPb > $deb) {
                            $missed = $myPb - $deb;

                            $this->callSql("INSERT INTO pb_history_compress (`user_id`,`init_id`,`value`,`level`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES ($newUserId,$buyerId,$missed,$this->lastKey,$diff,$this->pairedAmount,'$date',$time,'$ip',0,1)");
                        }

                        $newBal = $this->wal->updateCapsLeftWallet($newUserId, 1, 1, $deb);

                        $key  = 'caps_left_' . $newUserId;

                        $this->rds->set($key, $newBal, 60);

                        $this->callSql("UPDATE user_cutoff_payout SET hold_pbc_wallet=hold_pbc_wallet+$deb WHERE user_id='$newUserId'");
                    } else {
                        $this->callSql("INSERT INTO pb_history_compress (`user_id`,`init_id`,`value`,`level`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES ($newUserId,$buyerId,$myPb,$this->lastKey,$diff,$this->pairedAmount,'$date',$time,'$ip',0,1)");
                    }
                }
            }

            if (!empty($this->leftOver)) {
                $this->lastKey = $this->lastKey + 1;
                $this->processCompressParing($parringArray, $buyerId);
            }
        }

        return true;
    }

    /**
     * Process and Update DSD Group Sales to all Upline
     * @param Array $dsArray List of My DS Upline
     * @param INT $salesAmount Sales Amount
     */
    public function updateDSSalesUpline($dsArray, $salesAmount)
    {
        foreach ($dsArray as $key => $data) {
            $this->callSql("UPDATE user_wallet SET ds_group_sales=ds_group_sales+$salesAmount WHERE user_id='$data'");
        }
        return 1;
    }

    /**
     * Process and Proceed ROI
     * @param INT $userId
     * @param INT $amount
     */
    public function processROI($userId, $amount)
    {
        $getMyPre = $this->callSql("SELECT roi FROM user_wallet WHERE user_id='$userId'", 'value');
        $this->userId = $userId;

        if (!empty($getMyPre)) {
            $this->myROI = bcmul(bcdiv($amount, 100, 2), $getMyPre, 2);

            $data = $this->callSql("SELECT data FROM ds_upline WHERE user_id='$this->userId'", 'value');
            $this->dsArray = json_decode($data, true);
        }
    }

    /**
     * Process Network Bonus Uplines
     * 
     */
    private function processNB()
    {
        $systemMax = $this->getSystemMaxNBLevel();

        foreach ($this->dsArray as $key => $id) {
            $myNB = 0;
            $currentLevel = $key + 1;
            if ($systemMax >= $currentLevel) {
                $levelDetails = $this->getNBDetails($id);
                $myMax = $levelDetails['max_nb_level'];
                $myNBPre  = $levelDetails['nb'];

                if (!empty($myNBPre)) {

                    if ($myMax >= $currentLevel) {
                        $myNB = bcmul(bcdiv($this->myROI, 100, 2), $myNBPre, 2);
                    }
                }
            }
        }
    }

    /** Prorcess DS & Compress DS  Per Transaction
     * @param INT $buyerId Purchasing User Id
     * 
     */
    public function processDS($buyerId, $salesAmount, $getMyTree)
    {
        $maxCommission = $this->getSystemMaxDSCommission();

        $myDirectUpline = $this->callSql("SELECT sponsor FROM user WHERE id='$buyerId'", 'value');

        $dsArray = json_decode($getMyTree, true);

        $date = date('Y-m-d');

        $time = time();

        $ip = $_SERVER['REMOTE_ADDR'];

        if (!empty($dsArray)) {

            //$getMyTree  = $this->callSql("SELECT data FROM ds_upline WHERE user_id='$buyerId'", 'value');

            //$dsArray = $this->getDirectSponsorUpline($buyerId);

            $myDirectUpline = $dsArray[0];

            $this->leftOver  = 0;
            $this->totalUsed = 0;
            $myCom = $this->getUserDSCommission($myDirectUpline);
            $this->leftOver = $maxCommission - $myCom;
            $this->totalUsed = $myCom;

            if (!empty($myCom)) {
                $this->dsAmount = $salesAmount;

                $myDs = bcmul(bcdiv($salesAmount, 100, 2), $myCom, 2);

                $capsLeft = $this->checkUserCaps($myDirectUpline);

                if ($capsLeft > $myDs) {
                    $deb = $myDs;
                } else {
                    $deb = $capsLeft;
                }

                if ($deb > 0) {
                    $this->callSql("INSERT INTO ds_history (`user_id`,`init_id`,`value`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`) VALUES ($myDirectUpline,$buyerId,$deb,$myCom,$salesAmount,'$date',$time,'$ip',0)");

                    if ($myDs > $deb) {
                        $diff = $$myDs - $deb;
                        $this->callSql("INSERT INTO ds_history (`user_id`,`init_id`,`value`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES ($myDirectUpline,$buyerId,$diff,$myCom,$salesAmount,'$date',$time,'$ip',0,1)");
                    }

                    $newBal = $this->wal->updateCapsLeftWallet($myDirectUpline, 1, 1, $deb);

                    $key  = 'caps_left_' . $myDirectUpline;

                    $this->rds->set($key, $newBal, 60);

                    $this->callSql("UPDATE user_cutoff_payout SET hold_ds_wallet=hold_ds_wallet+$deb WHERE user_id='$myDirectUpline'");

                    if (!empty($this->leftOver)) {
                        $this->compressedFrom = $myDirectUpline;
                        $this->lastKey = 0;

                        $this->processCompressedDS($dsArray, $buyerId);
                    }
                } else {
                    $this->callSql("INSERT INTO ds_history (`user_id`,`init_id`,`value`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES ($myDirectUpline,$buyerId,$myDs,$myCom,$salesAmount,'$date',$time,'$ip',0,1)");
                }
            } else {

                $this->callSql("INSERT INTO ds_history (`user_id`,`init_id`,`value`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES ($myDirectUpline,$buyerId,0,$myCom,$salesAmount,'$date',$time,'$ip',0,1)");
            }
        }
    }

    /** Process Compress Parring Bonus  
     * @param Array $dsArray DS Upline Array
     * @param INT $buyerId Purchasing User Id
     * @return BOOLEAN TRUE
     */
    private function processCompressedDS($dsArray, $buyerId)
    {

        if (!empty($this->leftOver)) {
            $this->lastKey = $this->lastKey + 1;
            $newUser = $dsArray[$this->lastKey];
            $myCom = $this->getUserDSCommission($newUser);
            if ($myCom > $this->totalUsed) {
                $diff = $myCom - $this->totalUsed;
                $this->leftOver = $this->leftOver - $diff;
                if ($this->leftOver >= 0 && $diff > 0) {
                    $this->totalUsed = $this->totalUsed + $diff;

                    $myDs = bcmul(bcdiv($this->dsAmount, 100, 2), $diff, 2);

                    $capsLeft = $this->checkUserCaps($newUser);

                    if ($capsLeft > $myDs) {
                        $deb = $myDs;
                    } else {
                        $deb = $capsLeft;
                    }

                    $date = date('Y-m-d');

                    $time = time();

                    $ip = $_SERVER['REMOTE_ADDR'];

                    if ($deb > 0) {



                        $this->callSql("INSERT INTO ds_history_compress (`user_id`,`init_id`,`value`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`) VALUES ($newUser,$buyerId,$deb,$diff,$this->dsAmount,'$date',$time,'$ip',0)");

                        if ($myDs > $deb) {
                            $missed = $myDs - $deb;
                            $this->callSql("INSERT INTO ds_history_compress (`user_id`,`init_id`,`value`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES ($newUser,$buyerId,$missed,$diff,$this->dsAmount,'$date',$time,'$ip',0,1)");
                        }

                        $newBal = $this->wal->updateCapsLeftWallet($newUser, 1, 1, $deb);

                        $key  = 'caps_left_' . $newUser;

                        $this->rds->set($key, $newBal, 60);

                        $this->callSql("UPDATE user_cutoff_payout SET hold_dsc_wallet=hold_dsc_wallet+$deb WHERE user_id='$newUser'");
                    } else {
                        $this->callSql("INSERT INTO ds_history_compress (`user_id`,`init_id`,`value`,`precentage`,`purchase_value`,`transactiondate`,`createtime`,`createip`,`display_status`,`expired`) VALUES ($newUser,$buyerId,$myDs,$diff,$this->dsAmount,'$date',$time,'$ip',0,1)");
                    }
                }
            }

            if (!empty($this->leftOver))
                $this->processCompressedDS($dsArray, $buyerId);
        }

        return true;
    }


    /** Get System Max Parring Commission
     * @return INT $commission
     */
    private function getSystemMaxParringCommission()
    {
        $commission = $this->rds->get('maxParringPre');
        if (empty($commission)) {
            $commission = $this->callSql("SELECT pb FROM  user_cutoff_payout ORDER BY pb DESC LIMIT 1", 'value');
            $this->rds->set('maxParringPre', $commission, 60);
        } else {
            $this->skp++;
        }
        return $commission;
    }

    /** Get Individual Parring Bonus Commission 
     * @param INT $userId Requested User Id
     * @return INT $commission User Paring Commission
     */
    public function getUserParrinCommissionValue($userId)
    {
        $keys = 'parring' . $userId;

        $commission = $this->rds->get($keys);

        if (empty($commission)) {
            $commission = $this->callSql("SELECT pb FROM  user_cutoff_payout WHERE user_id='$userId'", 'value');
            $this->rds->set($keys, $commission, 60);
        } else {
            $this->skp++;
        }
        if (empty($commission) || $commission == '-')
            return 0;
        else
            return $commission;
    }

    /** Get System Max Direct Sponsor Commission
     * @return INT $commission
     */
    private function getSystemMaxDSCommission()
    {
        $commission = $this->rds->get('maxDS');
        if (empty($commission)) {
            $commission = $this->callSql("SELECT ds FROM  user_cutoff_payout ORDER BY ds DESC LIMIT 1", 'value');
            $this->rds->set('maxDS', $commission, 60);
        } else {
            $this->skp++;
        }
        return $commission;
    }


    /**
     * Get System Max NB Level 
     * @return INT $level
     */

    private function getSystemMaxNBLevel()
    {
        $level = $this->rds->get('maxNB');
        if (empty($level)) {
            $level = $this->callSql("SELECT max_nb_level FROM user_wallet ORDER BY max_nb_level DESC LIMIT 1", 'value');
            $this->rds->set('maxNB', $level, 60);
        } else {
            $this->skp++;
        }

        if (empty($level))
            $level = 0;

        return $level;
    }


    /**
     * Private Function to Get My Network Bonus Level and %
     * @param INT $userId
     * @return JSON $details Return Max Level and NB %
     */
    public function getNBDetails($id)
    {
        $details = $this->rds->get('nbDetails_' . $id);
        if (empty($details)) {
            $details = $this->callSql("SELECT max_nb_level,nb FROM user_wallet WHERE user_id='$id'", 'row');
            $details = json_encode($details);
            $this->rds->set('nbDetails_' . $id, $details, 60);
            $details = json_decode($details, true);
        } else {
            $this->skp++;
        }
        return $details;
    }

    /** Get Individual Direct Sponsor Commission 
     * @param INT $userId Requested User Id
     * @return INT $commission User Paring Commission
     */
    public function getUserDSCommission($userId)
    {
        $keys = 'DS_' . $userId;

        $commission = $this->rds->get($keys);

        if (empty($commission)) {
            $commission = $this->callSql("SELECT ds FROM  user_cutoff_payout WHERE user_id='$userId'", 'value');
            $this->rds->set($keys, $commission, 60);
        } else {
            $this->skp++;
        }

        if (empty($commission) || $commission == '-')
            return 0;
        else
            return $commission;
    }

    public function checkUserCaps($userId)
    {
        $key  = 'caps_left_' . $userId;

        $caps = $this->rds->get($key);

        if (empty($caps)) {

            $caps = $this->wal->getBalance('caps_left', $userId);
            $this->rds->set($key, $caps, 60);
        } else {
            $this->skp++;
        }
        return $caps;
    }
}
