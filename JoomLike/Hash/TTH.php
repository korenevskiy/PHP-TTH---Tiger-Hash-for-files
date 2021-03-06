<?php
/**
 * Get file TTH and Get files(folders) TTH
 * @version 0.97
 * @author Sergei Korenevskiy <korenevskiy.sergei@gmail.com> 
 */
namespace JoomLike\Hash;
class TTH {
  private static $BASE32_ALPHABET = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
  private static $tiger_hash = null;
  private static $tiger_mhash = null;
  private static $php54 = null;

  /**
   * Generates TTH of a folder.
   *
   * @param string $path folder 
   * @param bool $subdir If TRUE then scan and get hash subdirectories else do not scan subdirectories
   * @param bool $array_hash If TRUE then method return array HashStrings files else return once HashString all files
   * @return mix string or array TTH folder
   */
    public static function getTTHfolder($path,$subdir=FALSE, $array_hash=FALSE)
    {
        $path = rtrim ($path,DIRECTORY_SEPARATOR); 
        if($path=='')   return;
        $items = scandir ($path, SCANDIR_SORT_NONE);
        
        if(!$items ) return '';
        
        sort($items); 
        
        $hashes = array();
        
        foreach ($items as $item){
            if($item=='.' || $item=='..') 
                continue;
            else 
                $item=$path.DIRECTORY_SEPARATOR.$item;
                
            //$item = $path.'/'.$item;
            $isdir  = is_dir ($item);
            $isfile = is_file($item);
            
            if($isdir && $subdir){
                $hf = self::getTTHfolder($item,TRUE, TRUE);
                if(is_array($hf) && count($hf)){
                    $hashes[] = self::tiger(array_pop(explode(DIRECTORY_SEPARATOR,$item)));
                    $hashes[] = array_merge ($hashes, $hf);
                    continue;
                }
                    
                if($hf)$hashes[] = $hf.self::tiger(dirname($item));
            }
            
            if($isfile)
                $hashes[] = self::getTTH($item); 
        }
        
        if($array_hash) return $hashes;
        
        if(count($hashes) == 0) return '';
        
        //sort($hashes); 
         
        return self::tiger(join('', $hashes));
    }
  /**
   * Generates DC-compatible TTH of a file.
   *
   * @param string $filename
   * @return string return HashString from file
   */
  public static function getTTH(string $filename) 
  {
    $fp = fopen($filename, "rb");
    if($fp) {
      $i = 1;
      $hashes = array();
      while(!feof($fp)) {
        $buf = fread($fp, 1024);
        if ($buf || ($i == 1)) {
          $hashes[$i] = self::tiger("\0".$buf);
          $j = 1;
          while($i % ($j * 2) == 0) {
            $hashes[$i] = self::tiger("\1".$hashes[$i - $j].$hashes[$i]);
            unset($hashes[$i - $j]);
            $j = round($j * 2);
          }
          $i++;
        }
      }
      $k = 1;
      while($i > $k) {
        $k = round($k * 2);
      }
      for(; $i <= $k; $i++) {
          $j = 1;
          while($i % ($j * 2) == 0) {
            if(isset($hashes[$i])) {
              $hashes[$i] = self::tiger("\1".$hashes[$i - $j].$hashes[$i]);
            } elseif(isset($hashes[$i - $j])) {
              $hashes[$i] = $hashes[$i - $j];
            }
            unset($hashes[$i - $j]);
            $j = round($j * 2);
          }
      }
      fclose($fp);
 
      return self::base32encode($hashes[$i-1]);
    }
  }

  /**
   * Generates a DC-compatible tiger hash (not TTH).
   * Automatically chooses between hash() and mhash().
   *
   * @param string $string
   * @return string return HashString from string
   */
  private static function tiger($string) 
  {
    if (is_null(self::$tiger_hash)) {
       self::$tiger_hash = function_exists("hash_algos") && in_array("tiger192,3", hash_algos());
    }
    if (self::$tiger_hash) {
        $x = self::tigerfix(hash("tiger192,3", $string, 1));
        //print_r($x);
      return $x;
    }

    if (is_null(self::$tiger_mhash)) {
      self::$tiger_mhash = function_exists("mhash");
    }
    if(self::$tiger_mhash) {
      return self::tigerfix(mhash(MHASH_TIGER, $string));
    }

    trigger_error(E_USER_ERROR, "Neither tiger hash function is available.");
  }

  /**
   * Repairs tiger hash for compatibility with DC.
   *
   * @url http://www.php.net/manual/en/ref.mhash.php#55737
   * @param string $binary_hash
   * @return string 
   */ 
  private static function tigerfix($binary_hash) 
  {
      $my_split = str_split($binary_hash,8);
      $my_tiger ="";
      foreach($my_split as $key => $value) {
         if(is_null(self::$php54))
            self::$php54 = version_compare(PHP_VERSION, '5.4', '>=');
         if(!self::$php54)
            $my_split[$key] = strrev($value);
         $my_tiger .= $my_split[$key];
      }
     return $my_tiger;
  }

  /**
   * Just a base32encode function :)
   *
   * @url http://www.php.net/manual/en/function.sha1-file.php#61741
   * @param string $input
   * @return string
   */
  private static function base32encode($input)
  {
    $output = '';
    $position = 0;
    $storedData = 0;
    $storedBitCount = 0;
    $index = 0;
    while ($index < strlen($input)) {
      $storedData <<= 8;
      $storedData += ord($input[$index]);
      $storedBitCount += 8;
      $index += 1;
      //take as much data as possible out of storedData
      while ($storedBitCount >= 5) {
        $storedBitCount -= 5;
        $output .= self::$BASE32_ALPHABET[$storedData >> $storedBitCount];
        $storedData &= ((1 << $storedBitCount) - 1);
      }
    } //while
    //deal with leftover data
    if ($storedBitCount > 0) {
      $storedData <<= (5-$storedBitCount);
      $output .= self::$BASE32_ALPHABET[$storedData];
    }
    return $output;
  }
}

?>
