<?php
    namespace Contr;
    use Model\SAP;
    class SAPContr extends SAP {

        public function __construct() {
            
        }
        public function createPurGroup($rqst) {
            $this->setPurGroup($rqst);

        }
        public function createSupplier($rqst) {
            $this->setSupplier($rqst);

        }
        public function createPoItem($rqst) {
            $this->setPoItem($rqst);

        }
        public function createPoSline($rqst) {
            $this->setPoSline($rqst);

        }
        public function createShipment($rqst) {
            $this->setShipment($rqst);

        }
        public function createDelivery($rqst) {
            $this->setDelivery($rqst);
        }
        public function createGoodsMvt($rqst) {
            $this->setGoodsMvt($rqst);

        }
        public function createInvoice($rqst) {
            $this->setInvoice($rqst);
        }
        public function createSubstock($rqst) {
            $this->setSubstock($rqst);
        }
        public function createPlant($rqst) {
            $this->setPlant($rqst);
        }
        public function createStrLoc($rqst) {
            $this->setStrLoc($rqst);
        }
        public function updtPassSGP($rqst) {
            $this->modVehPass($rqst);
            $rqst->user_id = 'SAP';
            $rqst->pstatus = 'GP';
            $this->modVPStatus($rqst);
        }
        public function updtPassIBD($rqst) {
            $this->modVehPass($rqst);
            $rqst->user_id = 'SAP';
            $rqst->pstatus = 'ID';
            $this->modVPStatus($rqst);
        }
        public function updtPassTKN($rqst) {
            $this->modVehPass($rqst);
            $rqst->user_id = 'SAP';
            $rqst->pstatus = 'TK';
            $this->modVPStatus($rqst);
        }
        public function updtPass103($rqst) {
            $this->modVehPass($rqst);
            $rqst->user_id = 'SAP';
            $rqst->pstatus = 'BG';
            $this->modVPStatus($rqst);
        }
        public function updtPass105($rqst) {
            $this->modVehPass($rqst);
            $rqst->user_id = 'SAP';
            $rqst->pstatus = 'GR';
            $this->modVPStatus($rqst);
        }
        public function updtPassGWT($rqst) {
            $this->modVehPass($rqst);
            $rqst->user_id = 'SAP';
            $rqst->pstatus = 'GW';
            $this->modVPStatus($rqst);
        }
        public function updtPassTWT($rqst) {
            $this->modVehPass($rqst);
            $rqst->user_id = 'SAP';
            $rqst->pstatus = 'TW';
            $this->modVPStatus($rqst);
        }
        public function updtPassSHC($rqst) {
            $this->modVehPass($rqst);
            $rqst->user_id = 'SAP';
            $rqst->pstatus = 'SC';
            $this->modVPStatus($rqst);
        }
    }