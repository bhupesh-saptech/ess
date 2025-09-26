<?php
    namespace Contr;
    use Model\User;
    class UserContr extends User {
        public function __construct() {
        }
        public function loginUser($rqst) {
            $this->logUser($rqst);
        }
        public function readUser($query,$param=[],$rows=0) {
            return $this->getUser($query,$param,$rows);
        }
        public function createUser($rqst) {
            $this->setUser($rqst);
            $query = "select * from usr_data where user_id = ?";
            $param = array($rqst->user_id);
            return $this->getUser($query,$param,1);
        }
        public function modifyUser($rqst) {
            $this->modUser($rqst);
            $query = "select * from usr_data where user_id = ?";
            $param = array($rqst->user_id);
            return $this->getUser($query,$param,1);
        }
    }