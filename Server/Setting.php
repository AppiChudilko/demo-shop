<?php

namespace Server;

use Server\Core\EnumConst;
use Server\Core\QueryBuilder;
use Server\Core\Server;

if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

/**
 * User
 */
class Setting
{
    protected $qb;
    protected $server;
    private $footer;

    function __construct(QueryBuilder $qb, $check = null, $param = null)
    {
        $this->qb = $qb;
        $this->server = new Server($qb);
        $this->footer = $this->qb->createQueryBuilder('setting_footer')->selectSql()->executeQuery()->getSingleResult();
    }

    /**
     * @return array
     */
    public function getDesign() {
        return $this->qb->createQueryBuilder('setting_design')->selectSql()->executeQuery()->getSingleResult();
    }

    /**
     * @return array
     */
    public function getMainSlider() {
        return $this->qb->createQueryBuilder('setting_main_slider')->selectSql()->executeQuery()->getSingleResult();
    }

    /**
     * @return array
     */
    public function getFooter() {
        return $this->footer;
    }

    /**
     * @return string
     */
    public function getAboutUs() {
        return $this->footer['about_us'];
    }

    /**
     * @return string
     */
    public function getTerms() {
        return $this->footer['terms'];
    }
}