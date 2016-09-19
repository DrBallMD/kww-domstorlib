<?php
$dir = dirname(__FILE__);

require_once $dir.'/vendor/Spv/ClassLoader.php';
$loader = new Spv_ClassLoader();
$loader->registerPrefix('Spv', $dir.'/vendor');
$loader->registerPrefix('Twig', $dir.'/vendor');
$loader->registerPrefix('Ds', $dir.'/vendor');
$loader->registerPrefix('Doctrine', $dir.'/vendor');
$loader->registerPrefix('Custom', $dir.'/src');
$loader->register();

$container = Ds_IoC_Container::instance();
$services = include($dir.'/config/services.php');
$ioc_config_loader = new Ds_IoC_ConfigLoader_PhpConfigLoader($services);
$container->setConfigLoader($ioc_config_loader);

$block_factory = $container->get('detail.block.factory');
$block_factory->setConfig(include($dir.'/config/detail_blocks.php'));

// Register Spv template engine for form with key "spv_form"
Spv_Form_TemplatingDispatcher::getInstance()->register('spv_form', new Spv_Form_Templating());
Spv_Form_TemplatingDispatcher::getInstance()->register('ds_twig', Ds_IoC_Container::instance()->get('templating'));