<?php
/**
 * @author Pavol Eichler
 * 
 * Simple usage example:
 *
 * $ab1 = new ABTester ($req, $res);
 * $ab1->identifyUser();
 * switch($ab1->getUserGroup()){
 *      case ABTesting::GROUP_A:
 *          break;
 *      case ABTesting::GROUP_B
 *          break;
 * }
 *  
 * $ab2 = new ABTester ($req, $res);
 * $ab2->identifyUser();
 * $ab2->setGroupsCount(3); // divide users into 3 distinct audiences
 * if ($ab2->isA())
 *      doAnAThing();
 * if ($ab2->isB())
 *      doABThing();
 * if ($ab2->isC())
 *      doACThing();
 * 
 */

namespace Zaraguza;

class ABTester {
   
    const GROUP_A = 0;
    const GROUP_B = 1;
    const GROUP_C = 2;
    const GROUP_D = 3;
    const GROUP_E = 4;
    const GROUP_F = 5;
    const GROUP_G = 6;
    const GROUP_H = 7;
    const GROUP_I = 8;
    const GROUP_J = 9;
    
    protected $count = 2;
    
    protected $cookieName = '_zrgzabid';
    protected $cookieMax = 100;
    
    protected $userId = null;

    protected $request;
    protected $response;

    public function __construct(\Nette\Http\Request $request, \Nette\Http\Response $response) {
        
        $this->request = $request;
        $this->response = $response;
        
    }
    
    public function setGroupsCount($count) {
        
        if ($count < 0){
            throw new Exception('You must have at least one variation.');
        }
        
        if ($count > 10){
            throw new Exception('The maximum allowed number of variations is 10.');
        }
        
        $this->count = $count;
        
    }
    
    public function getGroupsCount() {
        
        return $this->count;
        
    }
    
    public function getUserGroup($groupsCount = null) {
        
        $id = $this->getUserId();
        
        $count = ($groupsCount === null) ? $this->count : $groupsCount;
        
        return $id % $count;
        
    }
    
    public function identifyUser() {
        
        $id = $this->getUserId();
        
        if (!$this->validateUserId($id)){
            $this->setUserId($this->getRandomId());
        }
        
    }
    
    protected function validateUserId($id) {
        
        if (!is_int($id)){
            return false;
        }
        
        if ($id < 1 OR $id > $this->cookieMax){
            return false;
        }
        
        return true;
        
    }
    
    protected function setUserId($id) {
        
        $this->userId = $id;
        $this->response->setCookie($this->cookieName, $id, '3 years');
        
        return $id;
        
    }
    
    protected function getUserId() {
        
        if($this->userId !== null){
            $id = $this->userId;
        }else{
            $id = $this->request->getCookie($this->cookieName);
        }
        
        return intval($id);
        
    }
    
    protected function getRandomId() {
        
        return mt_rand(1, $this->cookieMax);
        
    }
    
    public function isA() { return $this->getUserGroup() === self::GROUP_A; }
    public function isB() { return $this->getUserGroup() === self::GROUP_B; }
    public function isC() { return $this->getUserGroup() === self::GROUP_C; }
    public function isD() { return $this->getUserGroup() === self::GROUP_D; }
    public function isE() { return $this->getUserGroup() === self::GROUP_E; }
    public function isF() { return $this->getUserGroup() === self::GROUP_F; }
    public function isG() { return $this->getUserGroup() === self::GROUP_G; }
    public function isH() { return $this->getUserGroup() === self::GROUP_H; }
    public function isI() { return $this->getUserGroup() === self::GROUP_I; }
    public function isJ() { return $this->getUserGroup() === self::GROUP_J; }
    
}
