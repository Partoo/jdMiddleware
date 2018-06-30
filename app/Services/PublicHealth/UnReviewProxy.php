<?php

namespace App\Services\PublicHealth;

use App\Mail\PublicHealthPostFeedback;
use App\Services\GetPostToken;
use Goutte\Client as Goutte;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\BrowserKit\Cookie;

/**
 * 抓取公卫系统的蜘蛛抽象类
 */
class UnReviewProxy
{
    protected $client;
    protected $cookies;
    protected $params;
    protected $pages;
    protected $currentPage = 1;
    protected $crawler;

    public function __construct()
    {
        $this->client = new Goutte();

    }

    /**
     * 由子类指定不同的url参数
     * @return $this->params
     */

    /**
     * 获取某时间段的数据
     * @param  string $start 开始时间
     * @param  string $end 结束时间
     */
//    public function period($start)
//    {
//        return $this->scrape($start);
//    }
//
//    public function show()
//    {
//        $this->login();
//        $crawler = Goutte::request('POST', $this->url('2017-01-01'));
//        dd($crawler->html());
//        return $this->fetchArchive(request('fn'), request('id'));
//    }

    /**
     * 依次抓取患者病例
     * 第一步：根据传入参数获取到index(第一页)页面
     * 第二步：根据index页面依次进入病例页面获取到病例
     * 第三步：将病例入库
     * 第四步：重复第二步，直至本页结束
     * @param  [type] $start
     * @param  string $end [description]
     * @return [type]        [description]
     */
    public function scrape()
    {
        $this->login();
//        dd($this->cookies);
        $crawler = $this->client->request('POST', 'http://35.1.175.236:9080/sdcsm/grjbxxsearch.action?val888=1&sign=false&null=%E8%83%B6%E5%B7%9E%E5%B8%82%E8%83%B6%E5%8C%97%E9%95%87%E5%8D%AB%E7%94%9F%E9%99%A2&pZcrgid=370281B10011&dXm=&dXb=&dSfzh=&dGrdabh=&startdate=&enddate=&dDalb=&dDazt=&dasfhg=&dSspq=&startlr=&endlr=&dJd=&dJwh=&dXxdz=&pxbz=0&startdc=&enddc=&qxcx=&hjzt=&fhzt=1&dZy=&dJzzk=&dMz=&dHyzk=&dWhcd=&dYlbxh=&dXnhh=&dXx=&dSfrhyx=&startgx=&endgx=&age1=&age2=&updateuser=&createuser=&_dYhzgx=on&_cxgxy=on&_cxtnb=on&_cxgxb=on&_cxnzz=on&_cxzl=on&_cxmzf=on&_cxjsb=on&_cxtlc=on&_cxyyc=on&_cxztc=on&_cxzlc=on&_cxslc=on&_ljgxy=on&_xzbysg=on&_kfxtss=on&_tnlyc=on&_fp=on&_zdxy=on&_czqzxxfp=on');
        $pages = $crawler->filter('#all')->attr('value');
        $count = 0; // 记录条数
//        $uri = 'http://35.1.175.236:9080/sdcsm/grjbxxsearch1.action?&status=ajax&val888=1&sign=false&null=%E8%83%B6%E5%B7%9E%E5%B8%82%E8%83%B6%E5%8C%97%E9%95%87%E5%8D%AB%E7%94%9F%E9%99%A2&pZcrgid=370281B10011&dXm=&dXb=&dSfzh=&dGrdabh=&startdate=&enddate=&dDalb=&dDazt=&dasfhg=&dSspq=&startlr=&endlr=&dJd=&dJwh=&dXxdz=&pxbz=0&startdc=&enddc=&qxcx=&hjzt=&fhzt=1&dZy=&dJzzk=&dMz=&dHyzk=&dWhcd=&dYlbxh=&dXnhh=&dXx=&dSfrhyx=&startgx=&endgx=&age1=&age2=&updateuser=&createuser=&_dYhzgx=on&_cxgxy=on&_cxtnb=on&_cxgxb=on&_cxnzz=on&_cxzl=on&_cxmzf=on&_cxjsb=on&_cxtlc=on&_cxyyc=on&_cxztc=on&_cxzlc=on&_cxslc=on&_ljgxy=on&_xzbysg=on&_kfxtss=on&_tnlyc=on&_fp=on&_zdxy=on&_czqzxxfp=on&_=1529043927678';
        $uri = 'http://35.1.175.236:9080/sdcsm/grjbxxsearch1.action?status=ajax&val888=1&sign=false&null=%E8%83%B6%E5%B7%9E%E5%B8%82%E8%83%B6%E5%8C%97%E9%95%87%E5%8D%AB%E7%94%9F%E9%99%A2&pZcrgid=370281B10011&dXm=&dXb=&dSfzh=&dGrdabh=&startdate=&enddate=&dDalb=&dDazt=&dasfhg=&dSspq=&startlr=&endlr=&dJd=&dJwh=&dXxdz=&pxbz=0&startdc=&enddc=&qxcx=&hjzt=&fhzt=1&dZy=&dJzzk=&dMz=&dHyzk=&dWhcd=&dYlbxh=&dXnhh=&dXx=&dSfrhyx=&startgx=&endgx=&age1=&age2=&updateuser=&createuser=&_dYhzgx=on&_cxgxy=on&_cxtnb=on&_cxgxb=on&_cxnzz=on&_cxzl=on&_cxmzf=on&_cxjsb=on&_cxtlc=on&_cxyyc=on&_cxztc=on&_cxzlc=on&_cxslc=on&_ljgxy=on&_xzbysg=on&_kfxtss=on&_tnlyc=on&_fp=on&_zdxy=on&_czqzxxfp=on&_=1529044318388';
        for ($i = 1; $i <= $pages; $i++) {
            $crawler = $this->client->request('GET', $uri . '&page.currentPage=' . $i);
            echo '共有' . $pages . '页,正在抓取第' . $i . "页\n";
            $crawler->filter('.QueryTable tr')->siblings()->each(function ($tr) use (&$count, $crawler) {
                $link = $crawler->selectLink($tr->filter('td')->eq(1)->text())->link()->getUri();
                preg_match('/\?dGrdabh=(\d+)/', $link, $matches);
                // 正则匹配出的后两组，一个是患者的公卫档案号，一个是该患者在当前页面中存在的病例记录号
                $final = $this->autoPost($matches[1]);
                if ($final->success) {
                    echo $final->data . "\n";
                    $count++;
                } else {
                    echo $final->data . "\n";
//                    Mail::to(env('ADMIN_EMAIL'))->send(new PublicHealthPostFeedback(['time' => date('Y-m-d H:i:s'), 'content' => $final->data]));
                    exit;
                }
            });
        }
//        Mail::to(env('ADMIN_EMAIL'))->send(new PublicHealthPostFeedback(['time' => date('Y-m-d H:i:s'), 'content' => '成功写入' . $count . '条记录']));
        unset($count);
    }

    /**
     * 抓取病例
     * @param  string $id 病人档案号
     * @return mixed [type]     [description]
     */
    public function autoPost($id)
    {

        $urlPost = 'http://35.1.175.236:9080/sdcsm/healthArchives/saveUpdate.action?dGrdabh=370281110091028003&dJtdabh=3702811100910280&dGrdabhshow=370281110091028003&dGrdabh17=37028101100900149&dPyjm=&dWzd=94&qdqxz=1&dYhzgx=99&dDazt=1&dDaztyy=&dXm=%E7%8E%8B%E4%BD%B3%E4%BE%9D&dXb=2&dZjlx=1&dSfzh=&dZjhqt=&dCsrq=2018-05-05&fhzt=2&dLxdh=15866826700&dGzdw=&dLxrdh=15866826700&dLxrxm=&dHj=2&dJzzk=3&dMz=1&jkzk.dXx=4&jkzk.dSfrhyx=3&dZy=11&dWhcd=90&shxg.dLdqd=2&dHyzk=10&dYlfzflx=4&dYlfzflxqt=&dYlbxh=&dXnhh=&dSheng=37&dShi=3702&dQu=370281&dJd=37028111&dJwh=37028111009&dXxdz=%E9%BB%91%E9%BE%99%E6%B1%9F~%E5%8C%97%E9%83%BD&dZzbh=&jkzk.dGms=2&jkzk.dYwjws=1&jbgrhidden=jb0&isjb=2&exzl=&zybqt=&jbqt=&isshsh=1&iswsh=1&isshx=1&grhidden=jzsjbmc0&rowNumber=&Jkzk.dYwjb=2&jkzkjzsqt=&jkzk.dBls=2&jkzk.dYcbs=2&jkzk.dYwcj=2&hzxm=&jtrks=&jtjg=&jtys=&jtysdh=&jths=&jthsdh=&ggwsry=&ggwsrydh=&happentime=2018-05-12&createtime=2018-06-14+14%3A15%3A39&createuser=370281B100110043&updatetime=2018-06-14+14%3A16%3A42&updateuser=370281B100110043&pRgid=370281B10011&creatregion=370281B10011';
//        $url = 'http://35.1.175.236:9080/sdcsm/healthArchives/saveUpdate.action';
        $url = 'http://35.1.175.236:9080/sdcsm/healthArchives/updateArchives.action?dah=370281110231030401&tz=1&zdgllx=&date=Sat%20Jun%2016%202018%2014:54:42%20GMT+0800%20(%E4%B8%AD%E5%9B%BD%E6%A0%87%E5%87%86%E6%97%B6%E9%97%B4)';
        // 基本资料抓取
        $attempt = 0;
        $base = null;
        $param = [
            'dGrdabh' => '370281110231030401',
            'dJtdabh' => '3702811102310304',
            'dGrdabhshow' => '370281110231030401',
            'dGrdabh17' => '37028101102300018',
            'dPyjm' => '',
            'dWzd' => '94',
            'qdqxz' => '1',
            'dYhzgx' => '3',
            'dDazt' => '1',
            'dDaztyy' => '',
            'dXm' => 'wangjia',
            'dXb' => '2',
            'dZjlx' => '1',
            'dSfzh' => '',
            'dZjhqt' => '',
            'dCsrq' => '2018-05-05',
            'dLxdh' => '15866826700',
            'dGzdw' => '',
            'dLxrdh' => '15866826700',
            'dLxrxm' => '',
            'dHj' => '2',
            'dJzzk' => '3',
            'dMz' => '1',
            'jkzk.dXx' => '4',
            'jkzk.dSfrhyx' => '3',
            'dZy' => '11',
            'dWhcd' => '90',
            'shxg.dLdqd' => '2',
            'dHyzk' => '10',
            'dYlfzflx' => '4',
            'dYlfzflxqt' => '',
            'dYlbxh' => '',
            'dXnhh' => '',
            'dSheng' => '37',
            'dShi' => '3702',
            'dQu' => '370281',
            'dJd' => '37028111',
            'dJwh' => '37028111009',
            'dXxdz' => '',
            'dZzgn' => '',
            'jkzk.dGms' => '2',
            'jkzk.dYwjws' => '1',
            'jbgrhidden' => 'jb0',
            'isjb' => '2',
            'fhzt' => '2',
            'exzl' => '',
            'zybqt' => '',
            'jbqt' => '',
            'isshsh' => '1',
            'iswsh' => '1',
            'isshx' => '1',
            'grhidden' => 'jzsjbmc0',
            'rowNumber' => '',
            'Jkzk.dYwjb' => '2',
            'jkzjjzsqt' => '',
            'jkzk.dBls' => '2',
            'jkzk.dYcbs' => '2',
            'jkzk.dYwcj' => '2',
            'hzxm' => '',
            'jtrks' => '',
            'jtjg' => '',
            'jtys' => '',
            'jtysdh' => '',
            'jths' => '',
            'jthsdh' => '',
            'ggwsry' => '',
            'ggwsrydh' => '',
            'happentime' => '2018-05-12',
            'createtime' => '2018-06-14 14:15:39',
            'createruser' => '370281B100110043',
            'updatetime' => '1900-12-31 22:22:22',
            'updateuser' => '370281B100110043',
            'pRgid' => '370281B10011',
            'creatregion' => '370281B10011'
        ];
//        $crawler = $this->client->request('GET', $url);
//        $form = $crawler->filter('#tdaJkdaRkxzl')->form();
//        $this->client->submit($form, array('fhzt' => '2', 'dXm' => '丽丽'));
        $crawler = $this->client->request('POST', $urlPost, ['form_params' => $param, 'cookies' => $this->cookies]);
        dd($this->cookies);
        dd($crawler->html());
        do {
            try {
                $crawler->filter('#table2')->text();
            } catch (\InvalidArgumentException $e) {
                $attempt++;
                print_r("抓取失败，进行第" . $attempt . "次重试\n");
                print_r($crawler->html());
                sleep(2);
                $this->login();
                continue;
            }

            break;

        } while ($attempt < 10);

        $base = $crawler->filter('#table2');
        $baseInfo['name'] = $this->clean($base->filter('tr td')->eq(5)->text());
        $baseInfo['gender'] = $this->clean($base->filter('tr td')->eq(7)->text());
        $baseInfo['identify'] = $this->clean($base->filter('tr td')->eq(9)->text());
        $baseInfo['birthday'] = $this->clean($base->filter('tr td')->eq(11)->text());
        $baseInfo['phone'] = $this->clean($base->filter('tr td')->eq(13)->text());
        $baseInfo['village'] = preg_split('/\s+/', $this->clean($base->filter('tr td')->eq(15)->text()))[4];

        // $baseInfo['createtime'] = '2011-01-01';
        // dd($baseInfo);
        /**
         * 弃用部分
         */
        // $base = $crawler->filter('#table2')->text();
        // $baseMapper = [
        //     'name' => '姓名',
        //     'gender' => '性别',
        //     'identify' => '身份证号',
        //     'birthday' => '出生日期',
        //     'phone' => '联系电话',
        //     'village' => '居住地址',
        // ];
        // $baseInfo = [];
        // foreach ($baseMapper as $key => $mapper) {
        //     if ($key === 'village') {
        //         dd($base);
        //         preg_match_all('/(办事处|马店镇).*?\\r\\n(\s+.*)/', $base, $match);
        //         $match = array_last(explode(' ', array_flatten($match)[0]));
        //         $baseInfo['village'] = preg_replace('/胶东街道办事处|马店镇/', '', trim($match));
        //         // 去除有村的字符，但如果只有两个字则忽略（防止"大村"变成"大"）
        //         if (mb_strlen($baseInfo['village'], 'UTF-8') > 2) {
        //             $baseInfo['village'] = preg_replace('/村/', '', $baseInfo['village']);
        //         }
        //     } else {
        //         preg_match_all('/' . $mapper . '(.*)?\\r\\n(\s+.*)/', $base, $match);
        //         $baseInfo[$key] = preg_replace('/\\r|\\t/', '', array_last($match))[0];
        //     }
        // }
        // 当前病例
        $archiveInfo = $crawler->filter('#tableAllAreaWord')->text();
        // dd($archiveInfo);
        // 其中一些项目为老年人特有
        $archiveMapper = [
            'doctor' => '责任医生',
            'temperature' => '体温',
            'pulse' => '脉率',
            'breathe' => '呼吸频率',
            'blood_pressure_left' => '左侧',
            'blood_pressure_right' => '右侧',
            'height' => '身高',
            'weight' => '体重',
            'waistline' => '腰围',
            'bmi' => '体重指数',
            'beat' => '心率',
            'hgb' => '血红蛋白',
            'wbc' => '白细胞',
            'plt' => '血小板',
            'hcy' => '同型半胱氨酸',
            'afp' => 'AFP甲胎蛋白',
            'cea' => 'CEA癌胚抗原',
            'fbg' => '空腹血糖',
            'ecg' => '心电图',
            'alt' => '血清谷丙转氨酶',
            'ast' => '血清谷草转氨酶',
            'stb' => '总胆红素',
            'scr' => '血清肌酐',
            'bun' => '血尿素氮',
            'rut' => '尿常规',
            'ua' => '尿酸',
            'tcho' => '总胆固醇',
            'trig' => '甘油三酯',
            'ldl' => '血清低密度脂蛋白胆固醇',
            'hdl' => '血清高密度脂蛋白胆固醇',
            'bray' => 'B 超',
            'brain_sickness' => '脑血管疾病',
            'kidney_sickness' => '肾脏疾病',
            'heart_sickness' => '心血管疾病',
            'eye_sickness' => '眼部疾病',
            'neural_sickness' => '神经系统疾病',
            'other_sickness' => '其他系统疾病',
            'comment' => '健康评价',
            'control' => '健康指导',
        ];
        $archive = [];
        foreach ($archiveMapper as $key => $keyword) {
            if ($keyword == '健康评价') {
                preg_match_all('/异常1(.*)?(\s+.*){3}/', $archiveInfo, $match);
                $archive[$key] = trim(preg_replace('/\s+/', '', array_first($match[0])));
            } else if ($keyword == 'AFP甲胎蛋白' || $keyword == 'CEA癌胚抗原') {
                $archive[$key] = $this->pickCancerData($keyword, $archiveInfo);
            }
            // else if ($keyword == '空腹血糖') {
            //     $archive[$key] = $this->pickFbgData($keyword, $archiveInfo);
            // }
            //  else if ($keyword == '其他系统疾病') {
            //     if ($this->pickData('其他系统疾病', $archiveInfo) == '未发现') {
            //         $archive[$key] = '未发现';
            //     } else {
            //         preg_match_all('/(其他系统疾病).*?\\r\\n(\s+.*){2}/', $archiveInfo, $match);
            //         preg_match_all('/[^\\t]+\\r/', array_last($match[0]), $result);
            //         $archive[$key] = trim(preg_replace('/\\r/', '', array_last($result[0])));
            //     }

            // }
            else {
                $archive[$key] = $this->pickData($keyword, $archiveInfo);
            }
        }
        $archive['cn_medicine'] = '基本是';
        // 获取录入时间,它们命名为'createTime0'
        $tempCreateTime = $crawler->filter('#createTime0')->text();
        preg_match('/\d{4}-\d{2}-\d{2}/', $tempCreateTime, $createtime);
        $archive['createtime'] = $createtime[0];

        $final['baseInfo'] = $baseInfo;
        $final['baseInfo']['fn'] = $fn;
        $final['archive'] = $archive;
        $final['archive']['pub_id'] = $id;
        $final['abnormal'] = DetectSickness::handle($final['archive']);
        return $this->persistData($final);
    }

    private function persistData($result)
    {
        $http = new Client;
        $response = $http->post(env('PUBLIC_UPLOAD_URL'), [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . GetPostToken::get(),
            ],
            'json' => $result,
        ]);
        // sleep(1); //休息1秒，API 限制1秒2条
        return json_decode($response->getBody());
    }

    private function url($startDate, $endDate = '', $page = 1)
    {
        // 从Cookie中的当前用户登录名截取前12位作为机构编码
        $unitCode = substr($this->cookies[1]->getValue('zljyLoginname'), 0, 12);
        return config('publicHealth.rootUrl') . 'health/healthQuery.action?page.currentPage=' . $page . '&dqjg=' . $unitCode . '&crestarttime=' . $startDate . '&creendtime=' . $endDate . '&' . $this->params;
    }

    private function pickData($keyword, $string)
    {
        preg_match_all('/' . $keyword . '(.*)?\\r\\n(\s+.*)/', $string, $match);
        return trim(preg_replace('/\s+/', '', array_last($match))[0]);
    }

    // 肿瘤标志物的特殊情况
    private function pickCancerData($keyword, $string)
    {
        preg_match_all('/' . $keyword . ':(.*)?\\r\\n(\s+.*)/', $string, $match);
        return $match[1][0] . 'ng/ml';
    }

    // 空腹血糖的特殊情况
    // private function pickFbgData($keyword, $string) {
    //     preg_match_all('/' . $keyword . '(.*)?\\r\\n(\s+.*)/', $string, $match);
    //     $str = trim(preg_replace('/\s+/', '', array_last($match))[0]);
    //     preg_match('/\d+\.\d+/', $str, $match);
    //     return match[0] . 'mmol/L';
    // }

    /**
     * 模拟登陆
     * @return $this->client
     */
    private function login()
    {
        $url = 'http://35.1.175.236:9080/sdcsm/login.action';
        $this->client->request('GET', $url);
//        $form = $crawler->filter('#loginForm')->form();
        $crawler = $this->client->request('POST', $url . '?loginname=B2nRgFO8l4Ep681e7AxVmyqVDz29GD7jKMlnZeX09XI%3D&password=T98BsHOEYVr4h%2F54GhTgFA%3D%3D');
        $this->cookies = $this->client->getCookieJar()->all();
        $domain = '35.1.175.236';
        $cookie = new Cookie('DWRSESSIONID', 'IcjB*h2FkUINpYi5xZnGNoeY7gm', strtotime('+1 day'), '/sdcsm', $domain);
        array_push($this->cookies, $cookie);
        $this->client->getCookieJar()->updateFromSetCookie($this->cookies);

//        $values = [
//            'DWRSESSIONID' => 'IcjB*h2FkUINpYi5xZnGNoeY7gm',
//            'JSESSIONID' => '0000iwWQjYq2umjeOjxQm2XKDoN:-1',
//            'zljyLoginname' => '370281B100110005'
//        ];

//        $jar = new \GuzzleHttp\Cookie\CookieJar();
//        $cookieJar = $jar->fromArray($values, $domain);
//        dd($this->client->getCookieJar()->updateFromSetCookie($values, $domain));
//        $domain = '35.1.175.236';
//        $cookieJar = CookieJar::fromArray([
//            'DWRSESSIONID' => 'IcjB*h2FkUINpYi5xZnGNoeY7gm',
//            'JSESSIONID' => '0000iwWQjYq2umjeOjxQm2XKDoN:-1',
//            'zljyLoginname' => '370281B100110005'
//        ], $domain);
//        $guzzleClient = new Client(array(
//            'cookies' => $cookieJar
//        ));
//
//        $this->client->setClient($guzzleClient);

//        $client->request('GET', '/get', ['cookies' => $cookieJar]);

//        $this->client = new Goutte();
//        $guzzleClient = new Client(array(
//            'cookies' => $cookieJar
//        ));
//        $this->client->setClient($guzzleClient);
    }

    private function clean($string)
    {
        return trim(preg_replace('/\s\s+/', ' ', $string));
    }

    private function getCnMedicine($pid)
    {
        $url = config('publicHealth.rootUrl') . 'lnr/zyytzgl/viewAll.action?dah=' . $pid;
        $crawler = Goutte::request('GET', $url);
        $base = $crawler->filter('#table2');
        $str = $this->clean($base->filter('tr')->eq(3)->text());
        preg_match('/\d{4}/', $str, $date);
        if ($date[0] == date('Y')) {
            preg_match('/辨识: (.*)/', $str, $result);
            return $result[1];
        } else {
            return '基本是';
        }
        return false;
    }
}
