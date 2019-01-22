<?php
namespace App\Utils;
/**
 * Jaspersong 无限极分类
 */
class TreeUtil
{
    /**
     * 根据数组中的字段分组
     *
     * @param  array  $list  数据
     * @param  string $grade 分组的字段
     * @return array         分组后的数据
     */
    public function grading($list, $grade)
    {
        $gradArr = [];
        foreach ($list as $key => $value) {
            $gradArr[$value[$grade]][] = $value;
        }
        return $gradArr;
    }
    /**
     * 数组集 列转行
     * @param  array $list 结果集
     * @param  string $row  字段
     */
    public function pivot($list, $row)
    {
        $pivotArr = [];
        // 对于阶段分组
        $gradArr = $this->grading($list, $row);

        // 求出不同阶段的数量
        $gradCount = [];
        foreach ($gradArr as $key => $value) {
            $gradCount[$key] = count($value);
        }
        // 求出最大循环次数
        $gradMax = max($gradCount);
        for ($i= 0; $i < $gradMax; $i++) {
            foreach ($gradCount as $key => $value) {
                // 判断阶段有没有数据，如果有就存放没有就为空
                // $pivotArr[$i][] = ($i < $value) ? $gradArr[$key][$i] : [];
                $pivotArr[$i][] = ($i < $value) ? $gradArr[$key][$i] : '';
            }
        }
        return [
          'pivotCount' => $gradCount,
          'pivotArr'   => $pivotArr
        ];
    }
   /**
    * 递归生成树
    *
    * 递归方式实现无限分类
    * 思路：
    *  1、使用循环，分别获取所有的根节点。
    *  2、在获取每个节点的时候，将该节点从原数据中移除，并递归方式获取其所有的子节点，一直原数据为空。
    *
    * @param  array   $list  要转换的数据集
    * @param  string  $pk    自增字段（栏目id）
    * @param  string  $pid   parent标记字段
    * @param  string  $child 孩子节点key
    * @param  integer $root  根节点标识
    * @return array
    */
    public function recursive_make_tree($list, $pk = 'Fid', $pid = 'pid', $child = '_child', $root = 0)
    {
        $tree = [];
        foreach ($list as $key => $val) {
           if ($val[$pid] == $root) {
               //获取当前$pid所有子类
               unset($list[$key]);
               if (!empty($list)) {
                 $child = self::recursive_make_tree($list, $pk, $pid, $child, $val[$pk]);
                 if (!empty($child)) {
                   $val['_child'] = $child;
                 }
               }
               $tree[] = $val;
           }
        }
        return $tree;
    }

    /**
     * 把返回的数据集转换成Tree
     * 支持二级
     *
     * 引用方式实现无限极分类
     * 思路：
     *  1、即所有待处理的数据进行包装成下标为主键Fid（pk）的数组，便于由pid获取对应的父栏目。
     *  2、对包装的数据进行循环，如果为根节点，则将其引用添加到tree中，否则，将其引用添加到其父类的子元素中。
     *  这样虽然tree中，只是添加了根节点，但是每个根节点如果有子元素，其中包含了子元素的引用。故能形成树型。
     *
     * @param array    $list  要转换的数据集
     * @param string   $pk    自增字段id
     * @param string   $pid   父级id
     * @param string   $child 子类标记
     * @param integer  $root  根节点标识
     * @Deprecated  　不推荐使用
     * @return array
     */
     public function quote_make_tree($list, $pk = 'Fid', $pid = 'pid',$child = '_child', $root = 0)
     {
         $tree = $packData = [];
         foreach ($list as $data) {
             $packData[$data[$pk]] = $data;
         }
         foreach ($packData as $key =>$val) {
             if ($val[$pid] == $root) {//代表跟节点
               $tree[] = & $packData[$key];
             } else {
               // 找到其父类
               $packData[$val[$pid]][$child][] = $packData[$key];
             }
         }
         return $tree;
     }
}
