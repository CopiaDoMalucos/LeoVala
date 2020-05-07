<?php
  

  
  $GLOBALS['Imdb'] = new IMDB;
  
  class IMDB
  {
      public $param = array('plot' => 'full', 'tomatoes' => 'true');
      
      public function get($uri = null)
      {
          if ((preg_match('#tt([0-9]+)#i', $uri, $m)) && ($_data = $this->_request($m[0])))
          {
               return $_data;
          }
          
          return ( bool ) false;
      }
      
      public function getImage($image = null, $path = null, $id = 0)
      {
          $ext = pathinfo($image, PATHINFO_EXTENSION);
         
          $iid = sprintf('%d.%s', $id, $ext);
              
          if (($data = file_get_contents($image)) && (file_put_contents($path . $iid, $data)))
          {
               return $iid;
          }
          
          return ( bool ) false;
      }

      private function _url($id)
      {                  
          return "http://www.imdbapi.com/?i=$id&" . http_build_query($this->param);
      }
      
      private function _parse($_data)
      {
          if (($info = json_decode($_data)) && ($info->Response == 'True'))
          {
               return $info;
          }
          
          return ( bool ) false;
      }
      
      private function _request($id)
      {                  
          if (function_exists('curl_exec'))
          {
              $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL, $this->_url($id));
              curl_setopt($ch, CURLOPT_HEADER, false);
              curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
              curl_setopt($ch, CURLOPT_USERAGENT, 'TorrentTrader v2.xx');
              curl_setopt($ch, CURLOPT_TIMEOUT, 10);
              $data = curl_exec($ch);
              curl_close($ch);
          }
          else
          {
              $data = file_get_contents($this->_url($id));
          }
          
          return $this->_parse($data);
      }
  }