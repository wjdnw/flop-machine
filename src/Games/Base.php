<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/22
 * Time: 15:14
 */

namespace Wjdnw\FlopMachine\Games;

use Illuminate\Config\Repository;
use App\Exceptions\CustomException;
use Illuminate\Http\Request;

class Base
{
    protected $poker = [
        1 ,2 ,3 ,4 ,5 ,6 ,7 ,8 ,9 ,10,11,12,13,
        14,15,16,17,18,19,20,21,22,23,24,25,26,
        27,28,29,30,31,32,33,34,35,36,37,38,39,
        40,41,42,43,44,45,46,47,48,49,50,51,52,100,200
    ];  // 所有牌。

    protected $push_poker_num = 5;  // fa pai shu   fa ji zhang pai

    protected $big_king_num;

    protected $small_king_num;

    protected $poker_content = [ 1=>'A',2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10,11=>'J',12=>'Q',13=>'K',100=>'Queen',200=>'KING'];  // pai 的内容

    protected $poker_huase   = [ 0=>'heitao', 1=>'hongtao', 2=>'meihua', 3=>'fangkuai', 4=>'wang']; //0 黑，1红，2梅花，3方，4w   的花色

    protected $king_hua_val;

    protected $poker_icon      = [ 0=>'♠', 1=>'♥', 2=>'♣', 3=>'♦', 4=> 'Ψ' ]; // 花色的图标

    protected $pair_min_poker = 8; // 一对的时候，牌必须是大于或者等于8

    private $config;

    protected $king_num     = 0; // 初始化 w的个数

    protected $poker_shuffle;    // 洗牌后的牌

    protected $poker_start;   // 开始时，发牌得到的牌
    public $param;
    public function __construct( Repository $config, array $param )
    {
        $this->param = $param;
        $this->config           = $config;
        $this->poker            = $this->config->get('flopMachineConfig.poker.poker');
        $this->big_king_num     = $this->config->get('flopMachineConfig.poker.big_king_num');
        $this->small_king_num   = $this->config->get('flopMachineConfig.poker.small_king_num');
        $this->push_poker_num   = $this->config->get('flopMachineConfig.poker.push_poker_num');
        $this->poker_content    = $this->config->get('flopMachineConfig.poker.poker_content');
        $this->poker_huase      = $this->config->get('flopMachineConfig.poker.poker_huase');
        $this->king_hua_val     = $this->config->get('flopMachineConfig.poker.king_hua_val');
        $this->poker_icon       = $this->config->get('flopMachineConfig.poker.poker_icon');
        $this->pair_min_poker   = $this->config->get('flopMachineConfig.poker.pair_min_poker');

        $this->initPoker();
    }


    public function check( $str )
    {
        // .   '  ,  :  ;  *  ?  ~  `   !  @  #  $  %  ^  &  +  =  )  (  <  >  {  }  [  ]  \  /  "  |
        $pattern = "/[\'.,:;*?~`!@#$%^&+=)(<>{}]|\]|\[|\/|\\\|\"|\|/";
        $a = preg_match_all( $pattern , $str, $match );
        dd( $a,$match );
    }


    public function initPoker()
    {
        $pai_shuffle = $this->poker;
        shuffle( $pai_shuffle );  // 打乱所有固定顺序
        $this->poker_shuffle = $pai_shuffle;
        $push_pokers = array_rand( $pai_shuffle, $this->push_poker_num ); //  抽取 数组中 N 个key

        $paiArr = [];
        // 转换成 原有数组的 具体的数值
        foreach ( $push_pokers as $paikey ) {
            $paiArr[] = $this->jisuan( $this->poker_shuffle[$paikey] );
        }

        dd($paiArr);
        return $this->poker_start = $paiArr;
    }

    /**
     * 计算牌数  牌的花色，牌的大小
     * @param int $num  // 抽到的牌
     * @return array
     */
    private function jisuan( int $num )
    {
        if ( !in_array( $num, $this->poker ) ) {
            throw new CustomException('G10001');
        }
        if ( $num != $this->big_king_num && $num != $this->small_king_num ) {
            $hua_num = (int)( $num / 13 ); // 花色 0黑桃，1红桃，2梅花，3方块，5大小王
            $pai_num = $num % 13; // 牌数
            $hua_num = $pai_num ? $hua_num : ( $hua_num - 1 );
            $pai_num = $pai_num ? $pai_num : 13;
        } else {
            $hua_num = $this->king_hua_val;
            $pai_num = $num;
            $this->king_num = $this->king_num + 1;  // 计算王牌的个数
        }
        $hua_val = $this->poker_huase[$hua_num];
        $pai_val = $this->poker_content[$pai_num];
        $pai_ico = $this->poker_icon[$hua_num];
        $pai_num_as = $pai_num;
        if ( $pai_num == 1 || $pai_num >= 10 ) {  // 只有牌值大于等于10的才会有pai_num_as的值
            $pai_num_as = ( $pai_num == 1 ) ? '14' : $pai_num;
        }
        return $arr = [
            'hua_num'      => $hua_num, // 花色的值 //0 黑，1红，2梅花，3方，4w   的花色
            'hua_char'     => $hua_val, // 花色字符 // heitao, hongtao, meihua, fangkuai
            'hua_icon'     => $pai_ico, // 花色图标
            'poker_num'    => $pai_num, // 牌值
            'poker_char'   => $pai_val, // 扑克牌字符  A,2,3,4,5,6,7,8,9,10,J,Q,K
            'poker_num_as' => $pai_num_as, // 牌值别名  用途：
            'yuan'         => $num      // 扑克牌的原数值
        ];
    }


    public function start()
    {

    }
}