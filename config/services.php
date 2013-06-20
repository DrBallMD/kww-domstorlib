<?php
$dir = realpath(dirname(__FILE__).'/..');

return array(
    'templating' => array(
        'class' => 'Ds_Templating',
        // Arguments will be passed to class constructor
        'arguments' => array(
            'twig_params' => array('value' => array(
                'cache' =>  $dir.'/cache/twig',
                'autoescape' => false,
                'charset' => 'utf-8',
                'debug' => true,
                'strict_variables' => true,
            )),
        ),
    ),
    'form.base' => array(
        'class' => 'Ds_Form_BaseForm',
        // If factory = new, container returns new object every time
        'factory' => 'new',
        'arguments' => array(
            'templating' => array('value' => '@templating'),
        ),
    ),
    'form.builder.flat' => array(
        'class' => 'Ds_Form_Builder_FlatFormBuilder',
    ),
    'form.builder.house' => array(
        'class' => 'Ds_Form_Builder_HouseFormBuilder',
    ),
    'form.builder.garage' => array(
        'class' => 'Ds_Form_Builder_GarageFormBuilder',
    ),
    'form.builder.land' => array(
        'class' => 'Ds_Form_Builder_LandFormBuilder',
    ),
    'form.hidden_fields' => array(
        'class' => 'Ds_Form_FormHiddenFields',
        'factory' => 'new',
        'arguments' => array(
            'templating' => array('value' => '@templating'),
        ),
    ),
    'data_loader.driver' => array(
        'class' => 'Ds_DataLoader_Driver_XmlDriver',
    ),
    'data_loader.reader' => array(
        'class' => 'Ds_DataLoader_Reader_CurlReader',
    ),
    'data_loader' => array(
        'class' => 'Ds_DataLoader_AggregateDataLoader',
        'arguments' => array(
            // Use @ in argument's value to pass another service
            'driver' => array('value' => '@data_loader.driver'),
            'reader' => array('value' => '@data_loader.reader'),
        ),
        // Use calls to call any object methods after creation
        'calls' => array(
            'key' => array('value'=>'e84403a3-66d6-47f7-83df-19017a558c52', 'method' => 'setKey'),
            'server'=>array('value'=>'http://domstor.test', 'method' => 'setServer'),
        ),
    ),
    'definer.page' => array(
        'class' => 'Ds_Definer_PageDefiner',
    ),
    'definer.onpage' => array(
        'class' => 'Ds_Definer_OnPageDefiner',
    ),
    'definer.sort' => array(
        'class' => 'Ds_Definer_SortDefiner',
    ),
    'url_generator' => array(
        'class' => 'Ds_UrlGenerator_SimpleUrlGenerator',
    ),
    'pagination' => array(
        'class' => 'Ds_Pagination_Pagination',
        'arguments' => array(
            'templating' => array('value' => '@templating'),
        ),
        'calls' => array(
            'url_generator' => array('value'=>'@url_generator', 'method' => 'setUrlGenerator'),
        ),
    ),
    'counter' => array(
        'class' => 'Ds_Counter_BaseCounter',
        'arguments' => array(
            'data_loader' => array('value' => '@data_loader'),
        ),
    ),
    'list.base' => array(
        'class' => 'Ds_List_TableList',
        // If factory = new, container returns new object every time
        'factory' => 'new',
        'arguments' => array(
            'templating' => array('value' => '@templating'),
        ),
    ),
    'list.exchange' => array(
        'class' => 'Ds_List_ExchangeTableList',
        // If factory = new, container returns new object every time
        'factory' => 'new',
        'arguments' => array(
            'templating' => array('value' => '@templating'),
        ),
    ),
    'list.builder.flat' => array(
        'class' => 'Ds_List_Builder_FlatListBuilder',
    ),
    'list.builder.house' => array(
        'class' => 'Ds_List_Builder_HouseListBuilder',
    ),
    'list.column' => array(
        'class' => 'Ds_List_Column_TableColumn',
        /*
         * By default factory = instance, it means that object creates once,
         * and in the next requests returns the same instance.
         * If factory = prototype object creates once and will be cloned every
         * time when service is needed.
         */
        'factory' => 'prototype',
        'arguments' => array(
            'templating' => array('value' => '@templating'),
        ),
    ),
    'detail.block.factory' => array(
        'class' => 'Ds_Detail_Block_BlockFactory',
    ),
    'detail.flat.sale' => array(
        'class' => 'Ds_Detail_Flat_FlatSale',
        'arguments' => array(
            'templating' => array('value' => '@templating'),
            'block_factory' => array('value' => '@detail.block.factory'),
        ),
    ),
    'detail.house.sale' => array(
        'class' => 'Ds_Detail_House_HouseSale',
        'arguments' => array(
            'templating' => array('value' => '@templating'),
            'block_factory' => array('value' => '@detail.block.factory'),
        ),
    ),
);