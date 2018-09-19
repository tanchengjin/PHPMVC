<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/15
 * Time: 17:14
 */

namespace compass;


use compass\cores\App;

class Paginate
{
    //用于显示当前页码
    private $page=1;
    public function __construct()
    {
        if(isset($_GET['p'])){
            $this->page=$_GET['p'];
        }

    }

    public function show(){
        //每页显示条数
        $showPage=10;
        //获取文章条数
        $totalPage=App::get('db')->connect()->query("select count(*) from blog_article")[0]['count(*)'];
        //对文章进行分页
        $totalPage=ceil($totalPage/$showPage);
        $showPage=5;
        $page_banner="<div class='paginate'>";
        if($this->page > $totalPage){
            throw new \Exception('error');
        }
        //计算偏移量
        $pageOffset=($showPage-1)/2;
        if($this->page > 1){
            $page_banner.="<a href=".'index?p=1'.">首页</a>";
            $page_banner.="<a href=".'index?p='.($this->page-1).">上一页</a>";
        }
        //页数在1开始
        $start=1;
        //最后一页
        $end=$totalPage;
        //如果总页数大于所要显示的页数
        if($totalPage > $showPage){
            //当前页大于偏移量+1
            if($this->page > $pageOffset+1){
                $page_banner.="...";
            }
            //如果当前页大于偏移量
            if($this->page > $pageOffset){
                $start=$this->page-$pageOffset;
                //如果总页数大于当前页+偏移量那么结束位置就等于当前页+偏移量,如果不大于则显示最后一条
                $end=$totalPage>$this->page+$pageOffset?$this->page+$pageOffset:$totalPage;
            }else{
                $start=1;
                //最后一页是否大于要显示的页数就显示要显示的页数否则就显示总页数
                $end=$totalPage>$showPage?$showPage:$totalPage;
            }
            //如果当前页+偏移量大于最后一页
            if($this->page+$pageOffset > $totalPage){
                //起始位置变为本来应该的开始位置-(当前页+偏移量-结束位置)
                $start=$start-($this->page+$pageOffset-$end);
            }

        }

        for ($i=$start;$i<=$end;$i++){
            if($this->page == $i){
                $page_banner.="<a href=".'index?p='.$i." class='current_page'>{$i}</a>";
            }else{
                $page_banner.="<a href=".'index?p='.$i.">{$i}</a>";

            }
        }

        //如果我的最后一页大于了我要显示的条数,并且最后一页还要大于当前页+偏移量
        if($totalPage > $showPage && $totalPage>$this->page+$pageOffset){
            $page_banner.="...";
        }
        if($this->page < $totalPage){
            $page_banner.="<a href=".'index?p='.($this->page+1).">下一页</a>";
            $page_banner.="<a href=".'index?p='.$totalPage.">尾页</a>";

        }
        $page_banner.="</div>";
        return $page_banner;
    }
}