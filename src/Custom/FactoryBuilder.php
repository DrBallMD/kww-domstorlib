<?php

/**
 * Description of FactoryBuilder
 *
 * @author pahhan
 */
class Custom_FactoryBuilder
{
    /**
     *
     * @param string $key
     * @return \Custom_AbstractFactory
     * @throws Exception
     */
    public function build($key)
    {
        $key = explode('_', $key);
        $estate = $key[0];
        $action = $key[1];

        switch ($estate) {
            case 'flat':
                return new Custom_FlatFactory($action);
            case 'house':
                return new Custom_HouseFactory($action);
            case 'newflat':
                return new Custom_NewFlatFactory($action);
            case 'commerce':
                return new Custom_CommerceFactory($action);

            default:
                throw new Exception(sprintf('Undefined estate type "%s"', $estate));
        }
    }
}

