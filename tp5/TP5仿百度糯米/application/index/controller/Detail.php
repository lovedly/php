<?php
namespace app\index\controller;
use think\Controller;

class Detail extends Base
{
    public function index($id)
    {
        if(!intval($id)){
            $this->error('ID不合法');
        }
        //根据id查询商品数据
        $deal = model('Deal')->get($id);
        if(!$deal || $deal->status !=1){
            $this->error('该商品不存在');
        }
        //获取商户介绍
        $bisId =$deal->bis_id;
        $bisdescripiton =model('Bis')->get($bisId);
        //print_r($deal);exit;
        //获取分类信息
        $category = model('Category')->get($deal->category_id);
        //获取分店信息
        $locations = model("BisLocation")->getNormalLocationsInId($deal->location_ids);
        //print_r($locations);exit;
        
        $flag = 0;
        if($deal->start_time >time()){
            $flag = 1;
            $dtime = $deal->start_time-time();
            $d = floor($dtime/(3600*24)); //  0.6 1  1.2 1
            $timedata ='';
            if($d){
                $timedata .= $d. "天";                
            }
            $h = floor($dtime%(3600*24)/3600);
            if($h){
                $timedata .= $h. "小时";
            }
            $m = floor($dtime%(3600*24)%3600/60);
            if($m){
                $timedata .= $m. "分";
            }
            $this->assign('timedata',$timedata);
            
        }
        return $this->fetch('',[
            'deal' =>$deal,
            'title'=>$deal->name,
            'category'=>$category,
            'locations'=>$locations,
            'overplus'=>$deal->total_count-$deal->buy_count,
            'flag'=>$flag,
            'notes'=>$deal->notes,
            'mapstr'=>$locations[0]['xpoint'].','.$locations[0]['ypoint'],
            'bisdescription'=>$bisdescripiton->description,
        ]);   
    }
}
