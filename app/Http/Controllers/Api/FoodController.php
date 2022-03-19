<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FoodController extends Controller
{

    
    /**
     * 获取推荐的菜品
     */
    public function getFoods()
    {
        $currenHour = date('H');
        
        if (in_array($currenHour, [5,6,7,8,9])) {
            $breakfastLenght = 0;
            $dinnerLenght = 10;
            $afternoonTeaLength = 5;
            $midnightSnachLength = 5;
        } else if (in_array($currenHour, [10,11,12,13,17,18,19,20,21])) {
            // 正餐时刻(午餐、晚餐)
            $breakfastLenght = 10;
            $dinnerLenght = 0;
            $afternoonTeaLength = 10;
            $midnightSnachLength = 10;
        } else if (in_array($currenHour, [14,15,16])) {
            // 下午茶 
            $breakfastLenght = -1;
            $dinnerLenght = -1;
            $afternoonTeaLength = 0;
            $midnightSnachLength = -1;
        } elseif (in_array($currenHour, [22,23,0,1,2,3,4])) {
            // 宵夜
            $breakfastLenght = 10;
            $dinnerLenght = 15;
            $afternoonTeaLength = 15;
            $midnightSnachLength = 0;
        }
        $breakfast = $this->getBreakfast($breakfastLenght);
        $dinner = $this->getDinner($dinnerLenght);
        $afternoonTea = $this->getAfternoonTea($afternoonTeaLength);
        $midnightSnack = $this->getMidnightSnack($midnightSnachLength);

        $foods = array_merge($breakfast, $dinner, $afternoonTea, $midnightSnack);
        shuffle($foods);

        return $this->responseSuccess(['foods' => $foods]);
    }

    /**
     * 获取早餐
     */
    private function getBreakfast($length = 0)
    {
        if ($length == -1) {
            return [];
        }
        $foods = [
            '三明治 + 牛奶',
            '两个鸡蛋',
            '豆浆配油条',
            '肠粉',
            '煎饼果子',
            '福鼎肉片',
            '糯米鸡',
            '糊汤粉',
            '热干面',
            '重庆小面',
            '生煎包',
            '沙茶面',
            '灌汤包',
            '疙瘩汤',
            '胡辣汤',
            '鸡蛋汉堡',
            '沙县小吃',
            '肯德基早餐',
            '麦当劳早餐',
            '来杯咖啡',
            '煎饺',
            '拌粉',
            '热狗',
            '老北京豆汁儿',
            '饭团',
            '粥',
            '牛杂汤',
            '簸箕板',
            '油茶麻花',
            '永安粿条',
            '锅边糊',
            '面线糊',
            '馒头',
            '花生汤',
            '炸酱面',
            '牛奶',
            '烧麦'
        ];
        if (!empty($length)) {
            shuffle($foods);
            return array_slice($foods, 0, $length);
        }

        return $foods;
    }

    /**
     * 获取午餐、晚餐
     */
    private function getDinner($length)
    {
        if ($length == -1) {
            return [];
        }
        $foods = [
            '闽南猪脚饭',
            '肯德基',
            '麦当劳',
            '干锅田鸡',
            '养生粥',
            '麻辣烫',
            '泡椒田鸡',
            '烤鱼',
            '酸菜鱼',
            '羊肉手抓饭',
            '螺蛳粉',
            '家常菜',
            '火锅',
            '麻辣香锅',
            '轻食沙拉',
            '卤煮',
            '猪脚干饭',
            '莆田卤面',
            '韭菜饺子',
            '玉米馅饺子',
            '饺子拼盘',
            '韩式炸鸡',
            '北京烤鸭',
            '盖浇饭',
            '沙县小吃',
            '汉堡王',
            '华莱士',
            '炸鸡汉堡',
            '刀削面',
            '凉皮',
            '肉夹馍',
            '黄闷鸡米饭',
            '皮蛋瘦肉粥',
            '面食',
            '快餐',
            '咸饭',
            '红油抄手',
            '油泼面',
            '炒面',
            '炒饭',
            '酸汤肥牛',
            '兰州拉面',
            '河南烩面',
            '客家小吃',
            '酸笋面'
        ];
        if (!empty($length)) {
            shuffle($foods);
            return array_slice($foods, 0, $length);
        }

        return $foods;
    }

    /**
     * 获取下午茶
     *
     * @param int $length
     */
    private function getAfternoonTea($length)
    {
        if ($length == -1) {
            return [];
        }
        $foods = [
            '奶茶',
            '咖啡',
            '柠檬茶',
            '水果',
            '蛋糕甜品',
            '星巴克',
            '瑞星咖啡',
            '快乐番薯',
            '老塞咖啡',
            '喜茶',
            '85度C',
            '奈雪的茶',
            '茶颜悦色',
            'koi',
            '贡茶',
            '1點點',
            '肯德基',
            '麦当劳',
            'SEVENBUS',
            '满记甜品',
            'CoCo都可',
            '烧仙草',
            '冰淇淋',
            '牛奶布丁',
            '蛋挞',
            '鹿角巷',
            '益禾堂',
            '快乐柠檬',
            '古茗',
            'LELECHA',
            '蜜雪冰城',
        ];
        if (!empty($length)) {
            shuffle($foods);
            return array_slice($foods, 0, $length);
        }

        return $foods;
    }

    /**
     * 获取宵夜
     *
     * @param int $length
     */
    private function getMidnightSnack($length)
    {
        if ($length == -1) {
            return [];
        }
        $foods = [
            '小龙虾',
            '烧烤',
            '羊肉串',
            '炸鸡',
            '啤酒',
            '烤面筋',
            '牛肉丸汤',
            '永安粿条',
            '胡辣汤',
            '蒜香烤生蚝',
            '清蒸生蚝',
            '烤羊排',
            '绝味鸭脖',
            '麻辣烫',
            '烤冷面',
            '洪濑鸡爪',
            '沙茶面',
            '卤味',
            '烤鱼',
            '烤兔',
            '锡纸花蛤',
            '炒田螺',
            '皮皮虾',
            '铁板鱿鱼',
            '长沙臭豆腐',
        ];
        if (!empty($length)) {
            shuffle($foods);
            return array_slice($foods, 0, $length);
        }

        return $foods;
    }





}
