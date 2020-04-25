<?php

namespace APS;

/**
 * 文章模块
 * Article
 * @package APS\service
 */
class Article extends ASModel{


    public static $table     = "item_article";  // 表
    public static $primaryid = "articleid";     // 主字段

    public static $addFields = [
        'articleid','categoryid','areaid','authorid','regionid',
        'type','mode','title','cover','gallery','video','link','tags','description','introduce',
        'viewtimes','sort','featured','status',
        'createtime','lasttime',
    ];

    public static $updateFields = [
        'categoryid','areaid','regionid',
        'type','mode','title','cover','gallery','video','link','tags','description','introduce',
        'viewtimes','sort','featured','status','createtime','lasttime'
    ];
    public static $detailFields  = ["*"];
    public static $overviewFields  = [
        'articleid','categoryid','areaid','authorid','regionid',
        'type','mode','title','cover','gallery','video','link','tags','description','introduce',
        'viewtimes','sort','featured','status','createtime','lasttime'
    ];
    public static $listFields  = [
        'articleid','categoryid','areaid','authorid','regionid',
        'type','mode','title','cover','description','link','tags',
        'viewtimes','sort','featured','status','createtime','lasttime'
    ];
    public static $countFilters  = [
        'articleid','categoryid','areaid','authorid','regionid',
        'type','mode','title','keyword',
        'viewtimes','sort','featured','status','createtime','lasttime'
    ];
    public static $depthStruct  = [
        'featured'=>'int',
        'createtime'=>'int',
        'lasttime'=>'int',
        'sort'=>'int'
    ];
    public static $searchFilters = ['title','description'];


}