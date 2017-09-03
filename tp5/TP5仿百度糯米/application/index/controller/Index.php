<?php
namespace app\index\controller;
use think\Controller;

class Index extends Base
{
    public function index()
    {
        //获取首页大图相关数据
        $topPic= model('Featured')->getToppicBytype(0,1);
        
        //获取广告位相关的数据
        $advPic=model('Featured')->getToppicBytype(1,1);
        //print_r($advPic);exit;
        
        //商品分类数据-美食 推荐的数据
        $datas = model('Deal')->getNormalDealByCategoryCityId(1,$this->city->id);
        //print_r($datas);exit;
        //获取4个子分类
        $meishicates = model('Category')->getNormalRecommendCategoryByParentId(1,4);
       //print_r($meishicates);exit;
        return $this->fetch('',[
            'topPic'=>$topPic,
            'advPic'=>$advPic,
            'datas'=>$datas,
            'meishicates'=>$meishicates,
        ]);   
    }
}
