<?php

namespace App\Lib;

class MyFunction
{

public static function yearSelect(){
    $n = date("Y"); //現在の年
    $y = $n - 125; //125年前からスタート
    for($y; $y<$n; $y++){
        echo '<option value="'.$y.'">'.$y.'</option>';
    }
}

public static function yearSelect10(){
    $n = date("Y"); //現在の年
    $y = $n - 10; //125年前からスタート
    for($y; $y<=$n; $y++){
        echo '<option value="'.$y.'">'.$y.'</option>';
    }
}

public static function monthSelect(){
  for($i=1; $i<=12; $i++){
      $pad = str_pad($i, 2 ,0, STR_PAD_LEFT); //1~9までは前を0埋め
      echo '<option value="'.$pad.'">'.$i.'</option>';
  }
}

public static function daySelect(){
  for($i=1; $i<=31; $i++){
      $pad = str_pad($i, 2 ,0, STR_PAD_LEFT); //1~9までは前を0埋め
      echo '<option value="'.$pad.'">'.$i.'</option>';
  }
}

public static function numToGender($num){
    if($num == 1){
        return '男';
    }elseif($num == 2){
        return '女';
    }elseif($num == 3){
        return 'その他';
    }else{
        return '';
    }
}

public static function birthdayToAge($birthday){
    $now = date("Ymd");
    $birthday = str_replace("-", "", $birthday);//ハイフンを除去しています。
    return floor(($now - $birthday)/10000);
}

public static function postBefore($postcode){
    return mb_substr($postcode, 0, 3);
}

public static function postAfter($postcode){
    return mb_substr($postcode, 3, 4);
}

public static function postDisplay($postcode){
    return substr_replace($postcode, '-', 3, 0);
}

public static function phoneToArray($phone_number){
    return explode('-',$phone_number);
}

public static function dateYear($date){
    return date("Y", strtotime($date));
}

public static function dateMonth($date){
    return date("m", strtotime($date));
}

public static function dateDay($date){
    return date("d", strtotime($date));
}

public static function dateDisplay($date){
    return date("Y年n月j日", strtotime($date));
}

public static function menuDisplay($menu,$menu_price){
    $menu_arr = explode(',',$menu);
    $price_arr = explode(',',$menu_price);
    $count = count($menu_arr);
    echo '<div class="data-item-wrap">';
    for($i=0; $i<$count; $i++){
        echo '<p class="data-item"><span class="name">'.$menu_arr[$i].'</span><span class="price">¥'.number_format($price_arr[$i]).'</span></p>';
    }
    echo '</div>';
    echo '<p class="data-item sum"><span class="name">計</span><span class="price">¥'.number_format(array_sum($price_arr)).'</span></p>';
}

public static function menuChecked($old_menu, $menu){
    $menu_arr = explode(',',$old_menu);
    if(in_array($menu, $menu_arr)){
        return 'checked';
    }else{
        return '';
    }
}

}