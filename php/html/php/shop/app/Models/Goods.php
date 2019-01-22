<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    protected $table = 'tp_goods';
    protected $primaryKey = 'goods_id';
    public    $timestamps = false;
    /**
    * 关联图片
    *
    * @return AlbumPicture
    */
    public function albumPicture()
    {
        //                      关联表       关联表的主键    当前表的外键
        return $this->hasOne(\App\Models\AlbumPicture::class, 'pic_id',   'picture');
    }
}
