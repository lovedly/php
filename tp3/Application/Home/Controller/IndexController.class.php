<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index($type=""){
        //获取首页大图数据
        $topPicNews = D("PositionContent")->select(array('status'=>1,'position_id'=>2),1);
         /*print_r($topPicNews);exit;
        [id] => 47
        [position_id] => 2
        [title] => 文章31测试
        [thumb] => /upload/2017/01/18/587f71472e7c1.jpg
        [url] =>
        [news_id] => 30
        [listorder] => 6
        [status] => 1
        [create_time] => 1484723682
        [update_time] => 0 */
        $topSmallNews = D("PositionContent")->select(array('status'=>1,'position_id'=>3),3);
        $advNews = D("PositionContent")->select(array('status'=>1,'position_id'=>5),2);
        $listNews = D("News")->select(array('status'=>1,'thumb'=>array('neq','')),5);
        $rankNews = $this->getRank();
        $this->assign('result',array(
            'topPicNews'=>$topPicNews,
            'topSmallNews'=>$topSmallNews,
            'advNews'=>$advNews,
            'listNews'=>$listNews,
            'rankNews'=>$rankNews,
            'catid'=>0,
        ));
        /**
         * 生成页面静态化
         */
        if ($type == 'buildHtml'){
            $this->buildHtml('index',HTML_PATH,'index/index');
        }else{
            $this->display();
        }
    }
    
    public function build_html(){
        $this->index('buildHtml');
        return show(1,'首页缓存成功');
    }
    
    public function crontab_build_html(){
            if(APP_CRONTAN != 1){
            die("the_file_must_exec_crontab");
        } 
        $result = D("Basic")->select();
        /* print_r($result);exit;
        [title] => 新闻资讯网
        [keywords] => 资讯
        [description] => lol资讯信息
        [dumpmysql] => 0
        [cacheindex] => 1 */
        if (!$result['cacheindex']){
            die('系统没有设置开启自动生成首页缓存的内容');
        }
        $this->index('buildhtml');
    }
    
    public function getCount(){
        if(!$_POST){
            return show(0,'没有任何内容');   
        }
       /*  print_r($_POST); 获取了页面对应的id集合
        Array ( [0] => 21 [1] => 45 [2] => 44 [3] => 43 [4] => 42 [5] => 41 ) */
        $newsIds = array_unique($_POST);  
        try{
            $list = D("News")->getNewsByNewsIdIn($newsIds);
        }catch (Exception $e){
            return show(0,$e->getMessage());
        }
        if(!$list){
            return show(0,'没有内容');
        }
        /* print_r($list);exit;
        [news_id] => 41
        [catid] => 3
        [title] => 巴西电竞迷狂热
        [small_title] => 季中赛门票
        [title_font_color] => #5674ed
        [thumb] => /upload/2017/02/23/58aececa45860.jpg
        [keywords] => 巴西   季中赛   LOL   门票   售空
        [description] => 季中赛门票一小时售空
        [posids] =>
        [listorder] => 6
        [status] => 1
        [copyfrom] => 0
        [username] => admin
        [create_time] => 1487851254
        [update_time] => 0
        [count] => 12 */
        $data =array();
        foreach($list as $k=>$v){
            $data[$v['news_id']] =$v['count'];
        } 
        return show(1,'success',$data);
    
    }
}