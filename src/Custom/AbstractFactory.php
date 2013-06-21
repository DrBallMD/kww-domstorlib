<?php

/**
 * Description of AbstractFactory
 *
 * @author pahhan
 */
abstract class Custom_AbstractFactory extends Ds_Factory_AbstractFactory
{
    protected $action;

    abstract public function createForm();
    abstract public function createList($detail_url, array $params);
    abstract public function createDetail(array $params);
    abstract public function getDefaultSort();
    abstract public function createCounter();

    function __construct($action) {
        $this->action = $action;
    }

        /**
     * @return Ds_DataLoader_DataLoaderInterface
     */
    public function getDataLoader()
    {
        return $this->getContainer()->get('data_loader');
    }
    /**
     *
     * @return Ds_Pagination_Pagination
     */
    public function createPagination()
    {
        return $this->getContainer()->get('pagination');
    }

    /**
     *
     * @return Ds_Definer_SortDefiner
     */
    public function getSortDefiner()
    {
        return $this->getContainer()->get('definer.sort');
    }

    /**
     *
     * @return Ds_Definer_PageDefiner
     */
    public function getPageDefiner()
    {
        return $this->getContainer()->get('definer.page');
    }

    /**
     *
     * @return Ds_Definer_OnPageDefiner
     */
    public function getOnPageDefiner()
    {
        return $this->getContainer()->get('definer.onpage');
    }

    /**
     * @return Ds_UrlGenerator_UrlGeneratorInterface
     */
    public function getUrlGenerator()
    {
        return $this->getContainer()->get('url_generator');
    }
}

