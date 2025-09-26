<?php
    namespace Model;
    use Model\Util;
    class User extends Util {
        public $user_id = "";
        public $pass_wd = "";
        public $user_nm = "";
        public $mail_id = "";
        public $user_ph = "";
        public $role_id = "";
        public $user_ty = "user";
        public $user_st = "1";
        public $objty = "PLNT";
        public $objky = "";
        protected function logUser($rqst) {
            $util = new Util();
            $query = "select * from usr_data where user_id = ? and pass_wd = ?";
            $param = array($rqst->user_id,$rqst->pass_wd);
            $user  = $util->execQuery($query,$param,1);
            $_SESSION['user_id']    = $user->user_id;
            $_SESSION['role_nm']    = $user->role_nm;
            $_SESSION['home_pg']    = $user->home_pg;
            $_SESSION['user_nm']    = $user->user_nm;
            $_SESSION['user_ty']    = $user->user_ty;
            if(isset($_SESSION['page_id'])) {
                $page_id = $_SESSION['page_id'];
            } else {
                $page_id = $user->home_pg;
            } 
            header("Location: ".$page_id);  
            exit(0);
            
        }
        protected function getUser($query,$param=[],$rows=0) {
            $stmt = $this->connect()->prepare($query);
            $rset = $stmt->execute($param);
            if($rows == 1) {
                return $stmt->fetch();
            } else {
                return $stmt->fetchAll();
            }
        }
        protected function setUser($rqst) {
            $sqls = "insert ignore 
                        into users( user_id,
                                    pass_wd,      
                                    user_nm,
                                    mail_id,
                                    user_ph,
                                    role_id,
                                    user_ty,
                                    user_st,
                                    objty,
                                    objky )
                             values(?,?,?,?,?,?,?,?,?,?)"; 
            $stmt = $this->connect()->prepare($sqls);
            $rset = $stmt->execute(array($rqst->user_id,
                                         $rqst->pass_wd,
                                         $rqst->user_nm,
                                         $rqst->mail_id,
                                         $rqst->user_ph,
                                         $rqst->role_id,
                                         $rqst->user_ty,
                                         $rqst->user_st,
                                         $rqst->objty,
                                         $rqst->objky));            
        }
        protected function modUser($rqst) {
            $sqls = "update users set   pass_wd = ?,      
                                        user_nm = ?,
                                        mail_id = ?,
                                        user_ph = ?,
                                        role_id = ?,
                                        user_ty = ?,
                                        user_st = ?,
                                        objty   = ?,
                                        objky   = ?
                                  where user_id = ? "; 
            $stmt = $this->connect()->prepare($sqls);
            $rset = $stmt->execute(array($rqst->pass_wd,
                                         $rqst->user_nm,
                                         $rqst->mail_id,
                                         $rqst->user_ph,
                                         $rqst->role_id,
                                         $rqst->user_ty,
                                         $rqst->user_st,
                                         $rqst->objty,
                                         $rqst->objky,
                                         $rqst->user_id));            
        }
    }
?>