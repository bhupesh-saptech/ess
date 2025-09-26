<?php
    namespace Model;
    use Model\Util;
    class Staff extends Util {
        protected function logTime($rqst) {
            $sqls = "insert ignore 
                        into time_log(  staff_id,
                                        log_date,
                                        log_time )
                             values(?,curdate(),curtime())"; 
            $stmt = $this->connect()->prepare($sqls);
            $rset = $stmt->execute(array($rqst->staff_id));            
        }
       
    }
?>