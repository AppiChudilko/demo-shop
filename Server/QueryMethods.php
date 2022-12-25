<?php

namespace Server;

use Server\Core\QueryBuilder;

if (!defined('AppiEngine')) {
    header( "refresh:0; url=/");
}

/**
 * User
 */
class QueryMethods
{
    protected $qb;

    function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }

    public function addProduct(
        $name,
        $price,
        $discount,
        $brand,
        $count,
        $text,
        $subDesc1,
        $subDesc2,
        $subDesc3,
        $categoryId,
        $imagePrev,
        $image1,
        $image2,
        $image3,
        $widthS,
        $widthM,
        $widthL
    ) {

        $hasProd = $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
            ->where('name = \'' . $name . '\'')
            ->andWhere('text = \'' . $text . '\'')
            ->executeQuery()
            ->getSingleResult()
        ;

        if (empty($hasProd))
            return $this->qb
                ->createQueryBuilder('products')
                ->insertSql(array(
                    'name',
                    'price',
                    'discount',
                    'brand',
                    'count',
                    'text',
                    'sub_desc_1',
                    'sub_desc_2',
                    'sub_desc_3',
                    'category_id',
                    'image_prev',
                    'image_1',
                    'image_2',
                    'image_3',
                    'width_s',
                    'width_m',
                    'width_l'
                ), array(
                    $name,
                    $price,
                    $discount,
                    $brand,
                    $count,
                    $text,
                    $subDesc1,
                    $subDesc2,
                    $subDesc3,
                    $categoryId,
                    $imagePrev,
                    $image1,
                    $image2,
                    $image3,
                    $widthS,
                    $widthM,
                    $widthL
                ))
                ->executeQuery()
                ->getResult()
            ;

        return false;
    }

    public function editProduct(
        $id,
        $name,
        $price,
        $discount,
        $brand,
        $count,
        $text,
        $subDesc1,
        $subDesc2,
        $subDesc3,
        $categoryId,
        $imagePrev,
        $image1,
        $image2,
        $image3,
        $widthS,
        $widthM,
        $widthL
    ) {

        return $this->qb
            ->createQueryBuilder('products')
            ->updateSql(array(
                'name',
                'price',
                'discount',
                'brand',
                'count',
                'text',
                'sub_desc_1',
                'sub_desc_2',
                'sub_desc_3',
                'category_id',
                'image_prev',
                'image_1',
                'image_2',
                'image_3',
                'width_s',
                'width_m',
                'width_l'
            ), array(
                $name,
                $price,
                $discount,
                $brand,
                $count,
                $text,
                $subDesc1,
                $subDesc2,
                $subDesc3,
                $categoryId,
                $imagePrev,
                $image1,
                $image2,
                $image3,
                $widthS,
                $widthM,
                $widthL
            ))
            ->where('id = \'' . $id . '\'')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function deleteProduct($id) {
        return $this->qb
            ->createQueryBuilder('products')
            ->deleteSql()
            ->where('id = \'' . $id . '\'')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getProductById($id) {
        return $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
            ->where('id = \'' . intval($id) . '\'')
            ->executeQuery()
            ->getSingleResult()
        ;
    }

    public function getProductByName($name) {
        global $server;
        return $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
            ->where('name = \'' . $server->charsString($name) . '\'')
            ->executeQuery()
            ->getSingleResult()
        ;
    }

    public function getAllProductByCategory($catId) {
        return $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
            ->where('category_id = \'' . intval($catId) . '\'')
            ->orderBy('id DESC')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getAllProduct($search = '', $limit = 50, $count = null) {

        $query = $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
        ;

        if (!empty($search)) {
            $search = addcslashes(htmlspecialchars(stripslashes($search)), '\'"\\');
            $query->andWhere(
                '(name LIKE \'%' . $search . '%\' 
                OR tags LIKE \'%' . $search . '%\' 
                OR brand LIKE \'%' . $search . '%\' 
                OR text LIKE \'%' . $search . '%\' 
                OR sub_desc_1 LIKE \'%' . $search . '%\' 
                OR sub_desc_2 LIKE \'%' . $search . '%\' 
                OR sub_desc_3 LIKE \'%' . $search . '%\')'
            );
        }
        if ($count != null)
            $query->andWhere('(count = \'' . intval($count) . '\')');

        return $query
            ->limit($limit)
            ->where('id > 0') //костыль
            ->orderBy('id DESC')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getTopProduct($search = '', $limit = 4) {

        $query = $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
        ;

        if (!empty($search)) {
            $search = addcslashes(htmlspecialchars(stripslashes($search)), '\'"\\');
            $query->where(
                '(name LIKE \'%' . $search . '%\' 
                OR tags LIKE \'%' . $search . '%\' 
                OR brand LIKE \'%' . $search . '%\' 
                OR text LIKE \'%' . $search . '%\' 
                OR sub_desc_1 LIKE \'%' . $search . '%\' 
                OR sub_desc_2 LIKE \'%' . $search . '%\' 
                OR sub_desc_3 LIKE \'%' . $search . '%\')'
            );
        }

        return $query
            ->orderBy('views DESC')
            ->limit($limit)
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getHotProduct($search = '', $limit = 6) {

        $query = $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
        ;

        if (!empty($search)) {
            $search = addcslashes(htmlspecialchars(stripslashes($search)), '\'"\\');
            $query->where(
                '(name LIKE \'%' . $search . '%\' 
                OR tags LIKE \'%' . $search . '%\' 
                OR brand LIKE \'%' . $search . '%\' 
                OR text LIKE \'%' . $search . '%\' 
                OR sub_desc_1 LIKE \'%' . $search . '%\' 
                OR sub_desc_2 LIKE \'%' . $search . '%\' 
                OR sub_desc_3 LIKE \'%' . $search . '%\')'
            );
        }

        return $query
            ->orderBy('discount DESC, views DESC')
            ->where('discount > 0')
            ->limit($limit)
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getRecommendedProduct($catArr, $limit = 3) {
        $sqlCat = '';
        foreach ($catArr as $catId)
            $sqlCat .= ' OR category_id = \'' . intval($catId) . '\'';

        return $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
            ->where(substr($sqlCat, 4))
            ->orderBy('RAND()')
            ->limit($limit)
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getAllFilterProduct($search = '', $offset = 0, $limit = 50) {

        $query = $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
        ;

        if (!empty($search)) {
            $search = addcslashes(htmlspecialchars(stripslashes($search)), '\'"\\');
            $query->where(
                '(name LIKE \'%' . $search . '%\' 
                OR tags LIKE \'%' . $search . '%\' 
                OR brand LIKE \'%' . $search . '%\' 
                OR text LIKE \'%' . $search . '%\' 
                OR sub_desc_1 LIKE \'%' . $search . '%\' 
                OR sub_desc_2 LIKE \'%' . $search . '%\' 
                OR sub_desc_3 LIKE \'%' . $search . '%\')'
            );
        }

        return $query
            ->orderBy('id DESC')
            ->limit($offset . ',' . $limit)
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getAllFilterProductByCatId($catArr = array(), $search = '', $maxPrice = 0, $orderBy = 'id DESC', $brand = array(), $isDiscount = 0, $offset = 0, $limit = 50) {

        global $server;

        $search = $server->charsString($search);

        $query = $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
        ;

        $maxPrice = intval($maxPrice);
        if ($maxPrice < 0)
            $maxPrice = $maxPrice * -1;

        if (!empty($search))
            $query->andWhere(
                '(name LIKE \'%' . $search . '%\' 
                OR tags LIKE \'%' . $search . '%\' 
                OR brand LIKE \'%' . $search . '%\' 
                OR text LIKE \'%' . $search . '%\' 
                OR sub_desc_1 LIKE \'%' . $search . '%\' 
                OR sub_desc_2 LIKE \'%' . $search . '%\' 
                OR sub_desc_3 LIKE \'%' . $search . '%\')'
            );

        if ($maxPrice > 0)
            $query->andWhere('(price < ' . (++$maxPrice) . ')');

        $sqlCat = '';
        foreach ($catArr as $catId)
            $sqlCat .= ' OR category_id = \'' . intval($catId) . '\'';

        $sqlBrand = '';
        foreach ($brand as $brandItem)
            $sqlBrand .= ' OR brand = \'' . $server->charsString($brandItem) . '\'';

        if (!empty($sqlBrand))
            $query->andWhere('(' . substr($sqlBrand, 4) . ')');

        if ($isDiscount != 0)
            $query->andWhere('discount > \'0\'');

        return $query
            ->orderBy($orderBy)
            ->where('(category_id = \'' . intval($catArr[0]) . '\'' . $sqlCat . ')')
            ->limit(intval($offset) . ',' . $limit)
            ->executeQuery()
            ->getResult()
        ;
    }

    public function addCategory($name, $parentId) {

        global $server;
        $hasCat = $this->qb
            ->createQueryBuilder('categories')
            ->selectSql()
            ->where('name = \'' . $name . '\'')
            ->executeQuery()
            ->getSingleResult()
        ;

        if (empty($hasCat))
            return $this->qb
                ->createQueryBuilder('categories')
                ->insertSql(array(
                    'name',
                    'link',
                    'parent_id'
                ), array(
                    $name,
                    $server->toLink($name),
                    $parentId
                ))
                ->executeQuery()
                ->getResult()
            ;

        return false;
    }

    public function editCategory($name, $id) {
        global $server;
        return $this->qb
            ->createQueryBuilder('categories')
            ->updateSql(array(
                'name',
                'link'
            ), array(
                $name,
                $server->toLink($name)
            ))
            ->where('id = \'' . $id . '\'')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function deleteCategory($id) {

        foreach ($this->getCategories($id) as $item) {
            $this->qb
                ->createQueryBuilder('products')
                ->deleteSql()
                ->where('category_id = \'' . $item['id'] . '\'')
                ->executeQuery()
                ->getResult()
            ;
        }

        $resultCatDel = $this->qb
            ->createQueryBuilder('categories')
            ->deleteSql()
            ->where('id = \'' . $id . '\'')
            ->orWhere('parent_id = \'' . $id . '\'')
            ->executeQuery()
            ->getResult()
        ;

        return $resultCatDel && $this->qb
            ->createQueryBuilder('products')
            ->deleteSql()
            ->where('category_id = \'' . $id . '\'')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getChildCategories($categoryId) {
        $getChildCat = $this->getCategories($categoryId);
        $getChildCatArr = array($categoryId);
        foreach ($getChildCat as $item)
            $getChildCatArr = array_merge($getChildCatArr, array($item['id']));

        $cat = $this->getCategoryById($categoryId);
        if (empty($getChildCat))
            $getChildCat = array_merge($getChildCat, $this->getCategories($cat['parent_id']));
        return $getChildCat;
    }

    public function getChildArrayCategories($categoryId) {
        $getChildCat = $this->getCategories($categoryId);
        $getChildCatArr = array($categoryId);
        foreach ($getChildCat as $item)
            $getChildCatArr = array_merge($getChildCatArr, array($item['id']));
        return $getChildCatArr;
    }

    public function getCategories($parentId) {
        return $this->qb
            ->createQueryBuilder('categories')
            ->selectSql()
            ->where('parent_id = \'' . intval($parentId) . '\'')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getAllCategories() {
        return $this->qb
            ->createQueryBuilder('categories')
            ->selectSql()
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getAllCategoriesNotChild() {
        return $this->qb
            ->createQueryBuilder('categories')
            ->selectSql()
            ->where('parent_id = 0')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getCategory($name) {
        return $this->qb
            ->createQueryBuilder('categories')
            ->selectSql()
            ->where('name = \'' . $name . '\'')
            ->executeQuery()
            ->getSingleResult()
        ;
    }

    public function getCategoryById($id) {
        return $this->qb
            ->createQueryBuilder('categories')
            ->selectSql()
            ->where('id = \'' . intval($id) . '\'')
            ->executeQuery()
            ->getSingleResult()
        ;
    }

    public function getParentCategory($parentId) {
        return $this->getCategoryById($parentId);
    }

    public function getAllBrand() {
        $this->qb
            ->createQueryBuilder('')
            ->otherSql("set session sql_mode=''", false)
            ->executeQuery()
            ->getResult()
        ;

        return $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
            ->orderBy('id DESC')
            ->groupBy('brand')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getBrandByCategory($catId) {
        $this->qb
            ->createQueryBuilder('')
            ->otherSql("set session sql_mode=''", false)
            ->executeQuery()
            ->getResult()
        ;

        return $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
            ->orderBy('id DESC')
            ->groupBy('brand')
            ->where('category_id = \'' . intval($catId) . '\'')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getBrandByCategories($catArr) {
        $this->qb
            ->createQueryBuilder('')
            ->otherSql("set session sql_mode=''", false)
            ->executeQuery()
            ->getResult()
        ;


        $sqlCat = '';
        foreach ($catArr as $catId)
            $sqlCat .= ' OR category_id = \'' . intval($catId) . '\'';

        return $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
            ->orderBy('brand ASC')
            ->groupBy('brand')
            ->where('category_id = \'' . intval($catArr[0]) . '\'' . $sqlCat)
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getMaxPriceByCategories($catArr) {
        $sqlCat = '';
        foreach ($catArr as $catId)
            $sqlCat .= ' OR category_id = \'' . intval($catId) . '\'';

        return $this->qb
            ->createQueryBuilder('products')
            ->selectSql()
            ->orderBy('price DESC')
            ->limit(1)
            ->where('category_id = \'' . intval($catArr[0]) . '\'' . $sqlCat)
            ->executeQuery()
            ->getSingleResult()
        ;
    }

    public function addOrder(
        $name,
        $email,
        $phone,
        $desc,
        $prods,
        $sum,
        $token
    ) {
        global $server;
        return $this->qb
            ->createQueryBuilder('orders')
            ->insertSql(array(
                'name',
                'email',
                'phone',
                'text',
                'prods',
                'sum',
                'timestamp',
                'token',
                'ip'
            ), array(
                $name,
                $email,
                $phone,
                $desc,
                $prods,
                round($sum),
                time(),
                $token,
                $server->getClientIp(),
            ))
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getAllOrder($offset = 0) {
        return $this->qb
            ->createQueryBuilder('orders')
            ->selectSql()
            ->limit($offset . ', 100')
            ->orderBy('id DESC')
            ->executeQuery()
            ->getResult()
        ;
    }

    public function getOrderByToken($token) {
        global $server;
        return $this->qb
            ->createQueryBuilder('orders')
            ->selectSql('id, token')
            ->where('token = \'' . $server->charsString($token) . '\'')
            ->executeQuery()
            ->getSingleResult()
        ;
    }
}