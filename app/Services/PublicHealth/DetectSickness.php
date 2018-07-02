<?php
namespace App\Services\PublicHealth;

/**
 * 用来筛选有异常的参数
 */
class DetectSickness
{
    protected static $range = [
        'wbc' => [4, 10], // 白细胞数目
        'hgb' => [110, 160], // 血红蛋白浓度
        'plt' => [100, 300], // 血小板数目
        'fbg' => [4.5, 7], // 空腹血糖
        'alt' => [0, 40], // 谷丙转氨酶
        'ast' => [0, 40], // 谷草转氨酶
        'stb' => [5, 19], // 总胆红素
        'scr' => [59, 104], // 血清肌酐
        'bun' => [2.86, 7.14], // 尿素氮
        // 'ua' => [125, 440], // 尿酸
        'tcho' => [3.9, 5.72], // 总胆固醇
        'trig' => [0.7, 2.28], // 甘油三酯
        'ldl' => [0, 4.11], //血清低密度脂蛋白
        'hdl' => [0, 1.15], //血清高密度脂蛋白
    ];

    protected static $mapper = [
        'wbc' => '白细胞数目',
        'hgb' => '血红蛋白浓度',
        'plt' => '血小板数目',
        'fbg' => '空腹血糖',
        'alt' => '谷丙转氨酶',
        'ast' => '谷草转氨酶',
        'stb' => '总胆红素',
        'scr' => '血清肌酐',
        'bun' => '尿素氮',
        // 'ua' => '尿酸',
        'tcho' => '总胆固醇',
        'trig' => '甘油三酯',
        'ldl' => '血清低密度脂蛋白胆固醇',
        'hdl' => '血清高密度脂蛋白胆固醇',
    ];

    public static function handle(array $result)
    {
        $abnormal = [];
        // 诊断心电图
        if ($result['ecg'] !== '正常') {
            $abnormal['ecg'] = $result['ecg'];
        }
        // 诊断B超
        if (strpos($result['bray'], '正常') !== true) {
            $abnormal['bray'] = $result['bray'];
        }
        // 诊断尿样，调用私有方法
        if (self::checkRut($result['rut'])) {
            $abnormal['rut'] = '异常';
        }
        // 诊断中医辨识
        if ($result['cn_medicine'] !== '平和质' && $result['cn_medicine'] !== '基本是') {
            $abnormal['cn_medicine'] = $result['cn_medicine'];
        }

        // 检查是否血压高
        if (self::checkBloodPressure($result['blood_pressure_left'])) {
            $abnormal['blood_pressure'] = '偏高';
        }
        // 检查体质指数
        if ($result['bmi'] >= 28) {
            $abnormal['bmi'] = '偏高';
        }
        // 遍历诊断其它基本
        foreach ($result as $key => $value) {
            if (strpos($key, '_sickness')) {
                if ($value !== '未发现') {
                    $abnormal[$key] = $value;
                }
            }
        }
        // 遍历对比化验参数
        foreach ($result as $key => $value) {
            if (array_key_exists($key, self::$range)) {
                if (self::pickValue($value) > self::$range[$key][1]) {
                    $abnormal[$key] = '偏高';
                } elseif (self::pickValue($value) < self::$range[$key][0]) {
                    $abnormal[$key] = '偏低';
                }
            }
        }
        return $abnormal;
    }
    /**
     * 从公卫系统抓取结果中只提取出数字
     */
    private static function pickValue($string)
    {
        preg_match('/^(\d+(\.\d+)?)/', $string, $matches);

        if (!empty($matches)) {
            return $matches[0];
        } else {
            return 0;
        }

    }

    /**
     * 检测尿样化验里是否含有'+'
     */
    private static function checkRut($string)
    {
        return strpos($string, '+');
    }
    /**
     * 检测中医辨识是否为平和质
     */
    private static function checkChineseMedicine($string)
    {
        return $string !== '平和质';
    }

    private static function checkBloodPressure($string)
    {
        $data = preg_match('/\d+\/\d+/', $string, $matches);
        if (isset($matches[0])) {
            return max(array_flatten(explode('/', $matches[0]))) > 150;
        } else {
            echo $string . '不符合要求';
        }
    }
}
