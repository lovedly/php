<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 20:47
 */
namespace app\common\model;
use think\Model;

class News extends Base {

    /**
     * 自动化分页
     * @param array $data
     * @return \think\Paginator
     */
    public function getNews($data=[]){
        if (!isset($data['status'])){
            $data['status'] =[
                'neq' , config('code.status_delete'),
            ];
        }
        $order = [
            'id' => 'desc',
        ];

        $result = $this->where($data)
            ->order($order)
            ->paginate();
        //echo $this->getLastSql();
        return $result;
    }

    public function getNewsByCondition($data=[]){
        if (!isset($data['status'])){
            $data['status'] = [
                'neq', config('code.status_delete'),
            ];
        }
        $order = [
            'id' => 'desc',
        ];

        $result = $this->where($data)
            ->field($this->_getListfield())
            ->order($order)
            ->paginate();
        //echo $this->getLastSql();
        return $result;
    }

    /**
     * 获取首页头图数据
     * @param int $num
     * @return array
     */
    public function getIndexHeadNormalNews($num = 4){
        $data =[
            'status' => config('code.status_normal'),
            'is_head_figure' =>1,
        ];
        $order = [
            'id' => 'desc',
        ];

        $result = $this->where($data)
            ->field($this->_getListfield())
            ->order($order)
            ->limit($num)
            ->select();
        return $result;
    }

    /**
     * 获取推荐的数据
     */
    public function getPositionNormalNews($num = 20){
        $data =[
            'status' => config('code.status_normal'),
            'is_position' =>1,
        ];
        $order = [
            'id' => 'desc',
        ];

        $result = $this->where($data)
            ->field($this->_getListfield())
            ->order($order)
            ->limit($num)
            ->select();
        return $result;
    }

    /**
     * 获取排行榜数据
     * @param int $num
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getRankNormalNews($num = 5){
        $data =[
            'status' => config('code.status_normal'),
        ];
        $order = [
            'read_count' => 'desc',
        ];

        $result = $this->where($data)
            ->field($this->_getListfield())
            ->order($order)
            ->limit($num)
            ->select();
        return $result;
    }

    /**
     * 通用化获取参数的数据字段
     */
    private function _getListfield(){
        return [
            'id','title','catid','image','read_count' ,'status','is_position','update_time', 'create_time'
        ];
    }


}