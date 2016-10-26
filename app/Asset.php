<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model {

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /*一、农业文明史展厅A（单据号为1xxxxxxx）
    二、传统农具展厅B（单据号为2xxxxxxx）
    三、土壤与岩石展厅C（单据号为3xxxxxxx）
    四、植物世界展厅D（单据号为4xxxxxxx）
    五、动物世界展厅E（单据号为5xxxxxxx）
    六、昆虫世界展厅F（以盒为单位编流水号）（单据号为6xxxxxxx）
    七、林业资源与生产展厅G（单据号为7xxxxxxx）
    八、南海海洋生物展厅H（单据号为8xxxxxxx）
    九、可转让科技成果专题展厅I（单据号为9xxxxxxx）*/
    const TYPE_A = 1;
    const TYPE_B = 2;
    const TYPE_C = 3;
    const TYPE_D = 4;
    const TYPE_E = 5;
    const TYPE_F = 6;
    const TYPE_G = 7;
    const TYPE_H = 8;
    const TYPE_I = 9;
    const TYPE = [
        self::TYPE_A => '农业文明史展厅A（单据号为1xxxxxxx）',
        self::TYPE_B => '传统农具展厅B（单据号为2xxxxxxx）',
        self::TYPE_C => '土壤与岩石展厅C（单据号为3xxxxxxx）',
        self::TYPE_D => '植物世界展厅D（单据号为4xxxxxxx）',
        self::TYPE_E => '动物世界展厅E（单据号为5xxxxxxx）',
        self::TYPE_F => '昆虫世界展厅F（以盒为单位编流水号）（单据号为6xxxxxxx）',
        self::TYPE_G => '林业资源与生产展厅G（单据号为7xxxxxxx）',
        self::TYPE_H => '南海海洋生物展厅H（单据号为8xxxxxxx）',
        self::TYPE_I => '可转让科技成果专题展厅I（单据号为9xxxxxxx）',
    ];

    public function consumer()
    {
        return $this->belongsTo('App\User', 'consumer_id', 'id');
    }

    public function handler()
    {
        return $this->belongsTo('App\User', 'handler_id', 'id');
    }
}
