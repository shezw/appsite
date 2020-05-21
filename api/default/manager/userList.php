<?php
/**
 * Description
 * itemList.php
 */

namespace manager;

use APS\ASResult;
use APS\Category;
use APS\Time;
use APS\User;

/**
 * 通用列表查询
 * itemList
 * @package manager
 */
class userList extends \APS\ASAPI{

    private $filters   = ['status'=>'enabled'];
    private $page = 1;
    private $size = 20;
    private $order   = 'createtime DESC';

    protected static $groupCharacterRequirement = ['super','manager','editor'];
    protected static $groupLevelRequirement = 40000;
    public  $mode = 'JSON';

    public function run(): ASResult
    {
        $this->filters   = $this->params['filters'] ?? ['status'=>'enabled'] ;
        $this->page      = $this->params['page'] ?? 1;
        $this->size      = $this->params['size'] ?? 10;
        $this->order      = $this->params['order'] ?? 'createtime DESC';

        $getCount =  User::common()->count( $this->filters ) ?? ASResult::shared();
        $getList  =  User::common()->list( $this->filters, $this->page, $this->size, $this->order ) ?? ASResult::shared();

        $list = [];
        $maxPage = (int)(($getCount->getContent() - 1 )/ $this->size + 1);
        $list['nav'] = $list['navigation'] = [
            'total'=> $getCount->getContent(),
            'count'=> $maxPage,'max'=> $maxPage,
            'current'=>$this->page,'page'=>$this->page,
            'size'=>$this->size
        ];

        if( !$getList->isSucceed() ){
            return $this->take($list)->error(400,$getList->getMessage());
        }

        $list['list'] = $getList->getContent();

        for ( $i = 0; $i < count($list['list']); $i ++ ){

            if( isset($list['list'][$i]['status']) ){
                $list['list'][$i]['status_'] = i18n($list['list'][$i]['status']);
            }
            if( isset($list['list'][$i]['type']) ){
                $list['list'][$i]['type_'] = i18n($list['list'][$i]['type']);
            }

            $list['list'][$i]['createtime_'] = Time::common($list['list'][$i]['createtime'])->humanityOutput();
            $list['list'][$i]['lasttime_'] = Time::common($list['list'][$i]['lasttime'])->humanityOutput();

        }

        return $this->take($list)->success();
    }


}