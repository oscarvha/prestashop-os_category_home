<?php
/**
 * Created by PhpStorm.
 * User: Usuario
 * Date: 07/12/2017
 * Time: 19:22
 */

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;


include_once(_PS_MODULE_DIR_ . 'os_category_home/src/Model/CategoryHome.php');

class os_category_home extends Module implements WidgetInterface
{
    private $templateFile;

    public function __construct()
    {
        $this->name = 'os_category_home';
        $this->author = 'Oscar Sanchez';
        $this->version = '1.0';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Categorias destacadas Home', array());
        $this->description = $this->trans('Añade .' );

        $this->ps_versions_compliancy = array('min' => '1.7.1.0', 'max' => _PS_VERSION_);

        $this->templateFile = 'module:os_category_home/views/templates/front/home-list.tpl';

    }

    public function install()
    {
        if (parent::install() &&
            $this->registerHook('displayHome')
            && $this->registerHook('header')
            && $this->registerHook('actionCategoryDelete')
        ) {
            return $this->createTables();

        }
        return false;
    }

    public function createTables(){

        $db = Db::getInstance();

        $sql1 = "CREATE TABLE IF NOT EXISTS " . _DB_PREFIX_ . "os_category_home (
                id_category INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                position INT(10) unsigned NOT NULL DEFAULT 0
                )";



        return $db->execute($sql1) ;
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('home-list'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('home-list'));
    }
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {

        $categories = $this->getCategoriesForTemplate();

        return array(

            'categories' => $categories,
            'lang' =>$this->context->language->id,
            'link' => $this->context->link

        );
    }

    public function getCategoriesForTemplate(){

        $categoriesInHome = CategoryHome::getAllCategoriesHome();
        $categoriesHome = array();

        foreach($categoriesInHome as $categoryHome){

            $newCategory = new Category($categoryHome['id_category']);
            if(Validate::isLoadedObject($newCategory))
                array_push($categoriesHome, $newCategory);

        }

        return $categoriesHome;

    }

    public function getContent(){
        $output = null;

        $this->registerHook('actionCategoryDelete');

        $id_category = Tools::getValue('changePosition');

        if($id_category  && $positionDestiny =  Tools::getValue('positionDestininy')){


            $newCategoryHome = new CategoryHome($id_category);
            if($newCategoryHome->position != $positionDestiny){

                $positionChange = CategoryHome::changePosition($id_category, $newCategoryHome->position, $positionDestiny);


            }


        }


        if(Tools::getValue('addCategory')){

            $maxPosition = CategoryHome::getMaxPosition();

            if(isset( $maxPosition[0]['position'] )){

                $maxPosition = $maxPosition[0]['position'];

            } else{
                $maxPosition = 0;
            }

            $id_category = Tools::getValue('addCategory');

            if(!CategoryHome::categoryExist($id_category) &&  Product::existsInDatabase($id_category, 'category')){

              $newCategoryHome = new CategoryHome($id_category);
              $newCategoryHome->id_category = $id_category;
              $newCategoryHome->position = $maxPosition+1;

              if($newCategoryHome->save()){

                  $this->displayConfirmation($this->l('Producto añadido a la home correctamente'));

              }else{

                  $this->displayError($this->l('El producto no se ha podido añadir'));
              }
            }

        }

        if(Tools::getValue('deleteCategory')){

            $id_category = Tools::getValue('deleteCategory');


            if(CategoryHome::categoryExist($id_category) &&  Category::existsInDatabase($id_category, 'category')){

                $newCategoryHome = new CategoryHome($id_category);



                if($newCategoryHome->delete()){

                    $positionDelete = $newCategoryHome->position;
                    if($positionDelete>0){
                        $delete =  CategoryHome::orderPositionAfterDelete($positionDelete);
                    }
                    $this->displayConfirmation($this->l('Producto borrado de la home correctamente'));

                }else{


                    $this->displayError($this->l('El producto no se ha podido borrar de la home'));
                }


            }


        }


        $output .= $this->renderList();
        $this->context->controller->addJqueryUI('ui.sortable');

        return $output;

    }


    public function renderList(){


        $categories = CategoryCore::getAllCategoriesName();

        $categoriesInHome = CategoryHome::getAllCategoriesHome();


            $categoriesHome = array();
            $positions = array();

            foreach($categoriesInHome as $categoryHome)
            {

                $newCategory = new Category($categoryHome['id_category']);

                array_push($positions, $categoryHome['position']);
                array_push($categoriesHome, $newCategory);

                /**Eliminamos los products existentes en el slider del array de productos **/
                $key = array_search($categoryHome['id_category'], array_column($categories, 'id_category'));
                if($key)
                {
                    unset($categories[$key]);
                }
            }



        $this->context->smarty->assign(
            array(
                'link' => $this->context->link,
                'categories'=>$categories,
                'categoriesHome' =>$categoriesHome,
                'positions' =>$positions,
                'lang' => $this->context->language->id
            )
        );

        return $this->display(__FILE__, '/views/templates/admin/admin-render.tpl');
    }


    public function hookActionCategoryDelete($params){

        return CategoryHome::deleteByIdCategory(($params['category'])->id_category);

    }


}