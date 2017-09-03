<?php
namespace app\admin\controller;
use think\Controller;

class Featured extends Base
{
    private $obj;
    public function _initialize(){
        $this->obj = model("Featured");
    }
    public function index()
    {
        //获取推荐位类别
        $types = config('featured.featured_type');
        //获取列表
        $type =input('get.type',0,'intval'); 
        $results = $this->obj->getFeaturedsByType($type);
        
        return $this->fetch('',[
    		    'types'=>$types,
    		    'results'=>$results,
                'type'=>$type,
    		]);    
    }
	
	public function add(){
	    if (request()->isPost()){
	        //入库的逻辑
	        $data = input('post.');
	        //数据严格校验
	        $validate = validate('Featured');
	        if (!$validate->scene('add')->check($data)){
	            $this->error($validate->getError());
	        }
	        $id = model('Featured')->add($data);
	        if($id){
	            $this->success('添加成功');
	        }else{
	            $this->error('添加失败');
	        }
	    }else{ 
    	    //获取推荐位类别
    	    $types = config('featured.featured_type');
    		return $this->fetch('',[
    		    'types'=>$types,
    		    
    		]); 
	    }
	}
	
	
}
