<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DeveloperBlock
 *
 * @author Dmitry Anikeev <da@kww.su>
 */
class Ds_Detail_Block_NewFlat_DeveloperBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function name()
    {
        $data = $this->getData()->get('Developer');
        return $data['name'];
    }
}
