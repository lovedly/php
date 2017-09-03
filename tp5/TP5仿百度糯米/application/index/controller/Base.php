<?php
namespace app\index\controller;
use think\Controller;

class Base extends Controller
{
    public $city = '';
    public $account='';
    
    public function _initialize(){
        //城市数据
        $citys = model('City')->getNormalCitys();
        //用户数据
        $this->getCity($citys);
        //获取首页分类数据
        $cats = $this->getRecommendsCats();
        //print_r($cats);exit;
        $this->assign('citys',$citys);
        $this->assign('city',$this->city);
        $this->assign('cats',$cats);
        $this->assign('controller',strtolower(request()->controller()));
        $this->assign('user',$this->getLoginUser());
        $this->assign('title','o2o团购网');
    }
   
    public function getCity($citys){
        foreach($citys as $city){
            $city = $city->toArray();
          
            if($city['is_default'] == 1){
               $defaultuname = $city['uname'];
               break;//终止foreach循环               
           }
       }
       
            $defaultuname =$defaultuname ? $defaultuname: 'wuhan';
       if(session('cityuname','','o2o') && !input('get.city')){
           $cityuname = session('cityuname','','o2o');
       }else{
           $cityuname = input('get.city',$defaultuname,'trim');
           session('cityuname',$cityuname,'o2o');
       } 
       
       $this->city = model('city')->where(['uname'=>$cityuname])->find();
       //print_r($this->city);exit;
   }
   
   //获取session值
   public function getLoginUser(){
       if(!$this->account) {
           $this->account = session('o2o_user', '', 'o2o');
       }
       return  $this->account;
      
       /* [id] => 1
       [username] => admin
       [password] => 665b5b35243c7f78774bd2bcf0ae6770
       [code] => 7132
       [last_login_ip] =>
       [last_login_time] => 1502846236
       [email] => yiifrm@163.com
       [mobile] =>
       [listorder] => 0
       [status] => 1
       [create_time] => 1497759159
       [update_time] => 1502846236 */
   }
   
    /**
    * 获取首页推荐中的商品分类数据
    */
    public function getRecommendsCats(){                   
        $parentIds = $sedCatArr = $recomCats = [];
        $cats = model('Category')->getNormalRecommendCategoryByParentId(0,5);
        
        foreach($cats as $cat){
           $parentIds[] =$cat->id;
        }
       /*  print_r($parentIds);exit;
        [0] => 1
        [1] => 6
        [2] => 10
        [3] => 4
        [4] => 2 */
        //获取二级分类数据
        $sedCats = model('Category')->getNormalCategoryParentId($parentIds);
        //组装 注意是数组所以要加个[]
        foreach($sedCats as $sedcat){
            $sedCatArr[$sedcat->parent_id][] = [
                    'id'=>$sedcat->id,
                    'name'=>$sedcat->name,
            ];
        }
        //print_r($sedCatArr);exit;
        //Array ( [10] => Array ( [id] => 19 [name] => 温泉 ) [4] => Array ( [id] => 17 [name] => 豪华店 ) [2] => Array ( [id] => 3 [name] => ktv ) [6] => Array ( [id] => 14 [name] => 美容 ) [1] => Array ( [id] => 8 [name] => 披萨 ) )
        foreach($cats as $cat){
            //recomCats代表一级和二级的数据,[]第一个参数是一级分类的name,第二个参数是此一级分类下面的所有二级分类数据
            $recomCats[$cat->id] =[$cat->name,empty($sedCatArr[$cat->id])?[]:$sedCatArr[$cat->id]];
            
        }
        
        return $recomCats;
    }
}
