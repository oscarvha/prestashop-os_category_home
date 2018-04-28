<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 10/12/2017
 * Time: 19:22
 */

class CategoryHome extends ObjectModel
{
    public $position;
    public $id_category;

    public static $definition = array(
        'table' => 'os_category_home',
        'primary' => 'id_category',
        'multilang' => false,
        'fields' => array(
            'id_category' =>  array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => false),
        )
    );


    /**
     * @param $idProduct
     * @return array|bool|null|object
     */
    public static function categoryExist ($idCategory)
    {

        $req = 'SELECT ams.`id_category` as id_category
                FROM `' . _DB_PREFIX_ . 'os_category_home` ams
                WHERE ams.`id_category` = ' . (int)$idCategory;

        $row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($req);
        return ($row);
    }



    /**
     * @return array|bool|null|object
     */
    public static function getAllCategoriesHome()
    {

        $req = 'SELECT *
                FROM `' . _DB_PREFIX_ . 'os_category_home` ams
                 ORDER BY position ASC';

        $row = Db::getInstance()->executeS($req);
        return ($row);
    }


    public static function getMaxPosition(){


        $req = 'SELECT MAX(ams.`position`) as position
                FROM `' . _DB_PREFIX_ . 'os_category_home` ams';

        $row = Db::getInstance()->executeS($req);
        return ($row);

    }

    public static function orderPositionAfterDelete($positionDelete){


        $req = 'UPDATE '._DB_PREFIX_.'os_category_home SET position = position-1
                WHERE position > '.(int) $positionDelete;

        $row = Db::getInstance()->execute($req);
        return ($row);

    }


    public static function changePosition($idCategory,$currentPosition, $positionDestiny){

        $db = Db::getInstance();


        if($currentPosition < $positionDestiny){

            $req = 'UPDATE '._DB_PREFIX_.'os_category_home SET position = position-1
                WHERE position >  '.(int) $currentPosition. ' AND position <=  '.(int)$positionDestiny;


            $rowTwo = $db->execute($req);
        }else{

            $req = 'UPDATE '._DB_PREFIX_.'os_category_home SET position = position+1
                WHERE  position >='.(int)$positionDestiny.' AND position <  '.(int) $currentPosition;
            $rowTwo = $db->execute($req);
        }



        $req = 'UPDATE '._DB_PREFIX_.'os_category_home SET position ='.(int)$positionDestiny.'
                WHERE id_category = '.(int) $idCategory;

        $rowOne = $db->execute($req);


        return ($rowOne) && ($rowTwo);

    }

    /**
     * @param $idCategory
     * @return bool
     */
    public static function deleteByIdCategory($idCategory)
    {
        $req = 'DELETE    FROM `' . _DB_PREFIX_ . 'os_category_home` WHERE `id_category` = ' . (int)$idCategory;
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($req);
    }

}