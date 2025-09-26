<?php
    namespace Contr;
    use Model\Staff;
    class StaffContr extends Staff {
        public function __construct() {
        }
        public function loginTime($rqst) {
            $this->logTime($rqst);
        }

    }