<?php
namespace app\bis\controller;
use think\Controller;
class Deal extends Base
{
    private $obj;
    public function _initialize(){
        $this->obj = model("Deal");
    }
    public function index()
    {
        $bisId = $this->getLoginUser()->bis_id;
        $deals = $this->obj->getApplyDealsByBisId($bisId);
        //print_r($deals);exit;
        //print_r($bisId);exit; 22
        return $this->fetch('',[
            'deals' =>$deals,
            
        ]);
    }
        
    public function add()
    {
        $bisId = $this->getLoginUser()->bis_id;
        $bisAccountId = $this->getLoginUser()->id;
        if (request()->isPost()){
            //走插入逻辑
            $data =input('post.');
            //严格校验
            $validate = validate('Deal');
            if (!$validate->scene('add')->check($data)){
                $this->error($validate->getError());
            }
            
            $deals = [
                'bis_id' =>$bisId,
                'name'  =>$data['name'],
                'image'=>$data['image'],
                'category_id'=>$data['category_id'],
                'se_category_id'=>empty($data['se_category_id'])? '' : implode(',', $data['se_category_id']),
                'city_id'=>$data['city_id'],
                'location_ids'=>empty($data['location_ids'])? '' : implode(',', $data['location_ids']),
                'start_time'=>strtotime($data['start_time']),
                'end_time'=>strtotime($data['end_time']),
                'total_count'=>$data['total_count'],
                'origin_price'=>$data['origin_price'],
                'current_price'=>$data['current_price'],
                'coupons_begin_time'=>strtotime($data['coupons_begin_time']),
                'coupons_end_time'=>strtotime($data['coupons_end_time']),
                'description'=>empty($data['description'])? '' : $data['description'],
                'notes'=>$data['notes'],
                'bis_account_id'=>$bisAccountId,  
            ];
            
            $id = model('Deal')->add($deals);
            if($id){
                $this->success('添加成功',url('deal/index'));
            }else{
                $this->error('添加失败');
            }
        }else{
            $bislocations = model('BisLocation')->getNormalLocationByBisId($bisId);
            //print_r($bisId);exit; 22
            //获取一级城市的数据
            $citys =model('City')->getNormalCityByParentId();
            //获取一级栏目的数据
            $categorys =model('Category')->getNormalCategoryByParentId();
            return $this->fetch('',[
                    'citys' =>$citys,
                    'categorys'=>$categorys,
                    'bislocations'=>$bislocations,
                
                ]);
        }
    }
}
