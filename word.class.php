<?php

class word extends basic_curl {
  //tdk dictionary url path list
  private $dict_url_list = array("gts" => "http://tdk.gov.tr/index.php?option=com_gts&arama=gts");
  
  //mysqli db object
  /** @var mysqli $dbh*/
  public $dbh;
  
  public function __construct(mysqli &$dbh) {
    $this->dbh = $dbh;
  }
  
  public function get_meaning($word, $dict="gts"){
    //check db for meaning is exist
    $meaning = $this->check_db($word, $dict);
    //if not exist, get from tdk
    if($meaning == false){
      $meaning = $this->get_from_tdk($word, $dict);
      //write result to db for future search
      $this->push_db($word,$dict,$meaning);
    }
    return $meaning;
  }
  
  public function check_db($word, $dict){
    $query = $this->dbh->prepare("SELECT `sozcuk_anlami` FROM `sozcuk` WHERE `sozcuk` = ? AND `sozluk` = ?");
    $query->bind_param('ss', $word,$dict);
    $query->execute();
    $query->bind_result($meaning);
    if($query->fetch()){
      $result = $meaning;
    } else {
      $result = false;
    }
    $query->free_result();
    $query->close();
    return $result;
  }
  
  public function push_db($word, $dict, $meaning){
    if(is_null($meaning) || $meaning == "")
      return false;
    
    $query = $this->dbh->prepare("INSERT INTO `sozcuk` (sozcuk,sozluk,sozcuk_anlami,ip) VALUES (?,?,?,?)");
    $query->bind_param('ssss', $word,$dict,$meaning,$_SERVER['REMOTE_ADDR']);
    $query->execute();
    $query->close();
  }
  
  public function get_from_tdk($word, $dict){
    //prepare post data
    $post       = 'kelime=' . urlencode($word);
    
    //get page with post
    $raw_result = $this->curl($this->dict_url_list[$dict], $post, "POST");
    
    //get html dom object from raw html page result
    $html_obj   = str_get_html($raw_result);
    
    //find description parts of page
    $meanings   = $html_obj->find('table[id=hor-minimalist-a]');
    $result     = "";
    foreach ($meanings as $meaning){
      $meaning->width = "100%";
      $result        .= $meaning->outertext;
    }
    return $result;
  }
}