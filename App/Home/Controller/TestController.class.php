<?php
namespace Home\Controller;
use Think\Controller;
class TestController extends Controller {

    public function index()
    {
        $region_id = '421087100';
        $regModel = M('Region');
        $map['region_id'] = $region_id;
        $level = $regModel->where($map)->getField('level');

        switch ($level) {
            case 1:
                $res1 = $this->getEnterprisesByRegionID($region_id);

                foreach ($res1 as $key => $value) {
                    $res2 = $this->getEnterprisesByRegionID($value['region_id']);
                    $items1[] = $res2;

                    foreach ($res2 as $k => $v) {
                        $res3 = $this->getEnterprisesByRegionID($v['region_id']);
                        $items2[] = $res3;
                        foreach ($res3 as $k2 => $v2) {
                            $item4[] = $v2['region_id'];
                        }
                    }
                }

                return array_unique($item4);
                break;

            case 2 :
                 $res1 = $this->getEnterprisesByRegionID($region_id);

                foreach ($res1 as $key => $value) {
                    $res2 = $this->getEnterprisesByRegionID($value['region_id']);
                    $items1[] = $res2;

                    foreach ($res2 as $k2 => $v2) {
                            $item4[] = $v2['region_id'];
                        }
                }  
                return array_unique($item4);
            
            default:
                $res1 = $this->getEnterprisesByRegionID($region_id);
                foreach ($res1 as $k2 => $v2) {
                            $item4[] = $v2['region_id'];
                        }
                    return array_unique($item4);
                break;
        }
    }

    public function getEnterprisesByRegionID($region_id)
    {
        $regModel = M('Region');

        $map['region_id']       = $region_id;
        $map['parentId']        = $region_id;
        $map['_logic']          = 'OR';

        $res = $regModel->where($map)->select();
        return $res;

    }

    public function test($codeID = '4201')
    {
        $regModel = M('Region');

        $map['region_id']    = $codeID;
        $map['parentId']    = $codeID;
        $map['_logic']      = 'OR';
        $res = $regModel->where($map)->select();
            //var_dump($res);exit;
        var_dump($this->getSubs($res,$codeID));
    }


    public function getSubs($categorys,$catId=0,$level=1){  
        $subs=array();  
        foreach($categorys as $item){  
            if($item['parentId']==$catId){  
                $item['level']=$level;  
                $subs[]=$item;  
                $subs=array_merge($subs,$this->getSubs($categorys,$item['region_id'],$level+1));  
                  
            }  
                  
        }  
        return $subs;  
    }

    public function start()
    {
        var_dump($this->getNext('420102'));
    }

    public function getNext($region_id = '420102')
    {
        $regModel = M('Region');

        $map['region_id']        = $region_id;
        $map['parentId']    = $region_id;
        $map['_logic']      = 'OR';


        $res = $regModel->where($map)->select();
           // var_dump($res);exit;
        if ($res) {
            //$item = array();
            foreach ($res as $key => $value) {
                //var_dump($value);exit;
                // echo $value['name'].'<br>';
                // $this->getNext($value['region_id']);
                $item[] = $value;
                if ($this->getNext($value['region_id'])) {
                    $item = array_merge($item, $this->getNext($value['region_id']));
                }
                
            }
            //var_dump($item);exit;

            
        }
        return $item;
        
    }

    // function get_category($parent_id=0){
    //     $arr=array();
    //     $sql = "select * from category where parent_id=$parent_id";//查询子级数据
    //     $result = array(a_object,b_object,,,)=sql_query($sql);//查询结果一个数组或列表格式，自己完善。
    //     if($result){
    //         foreach($result as $re){//循环数组
    //         if(get_category($re.id))//如果子级不为空
    //         $re['child'] = get_category($re.id);
    //         $arr[] = $re;
    //     }
    //     return $arr;
    // }

    public function getPrev($region_id = '420102002')
    {
        //echo $region_id.'<br>';
        $regModel = M('Region');
        $str = '123';

        $res = $regModel->where('region_id ='.$region_id)->find();
        if ($res) {
            $str .= $res['name'].'/';
            $map['region_id']        = $res['parentid'];
            if ($regModel->where($map)->find()) {
                $item = $regModel->where($map)->find();
                $str .= $item['name'].'/';
                $str .= $this->getPrev($item['parentid']);
                
            } else {
                //$str .= $res['name'];
            }
            return $str;
        }
        
        
    }

    public function alert() {
        //var_dump($this->getPrev());
       $s =  trim($this->getPrev(),'/');
       $arr = explode('/', $s);
       echo join('/',array_reverse($arr));

    }



}