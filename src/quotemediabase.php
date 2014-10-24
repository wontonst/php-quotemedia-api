<?php
class QuoteMediaBase{
public function __construct(){
        $this->error = 0;
}
    public function getErrorID() {
        return $this->error;
    }
    public function getError(){
      return QuoteMediaError::IDtoError($this->error);
    }

}
?>