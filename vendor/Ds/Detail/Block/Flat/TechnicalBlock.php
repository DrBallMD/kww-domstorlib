<?php

/**
 * Description of SaleTechnical
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_TechnicalBlock extends Ds_Detail_Block_AbstractBlock
{

    protected $communication;
    protected $doors_front;

    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

    public function communication()
    {
        if ($this->communication)
            return $this->communication;
        $data = $this->getData();
        $out = '';
        if ($data->get('phone'))
            $out.= 'Телефон, ';
        if ($data->get('gas'))
            $out.= 'Газопровод, ';
        if ($data->get('cable_tv'))
            $out.= 'Кабельное ТВ, ';
        if ($data->get('door_phone'))
            $out.= 'Домофон, ';
        if ($data->get('satellite_tv'))
            $out.= 'Спутниковое ТВ, ';
        if ($data->get('signalizing'))
            $out.= 'Охранная сигнализация, ';
        if ($data->get('internet'))
            $out.= 'Интернет, ';
        if ($data->get('fire_prevention'))
            $out.= 'Противопожарная сигнализация, ';
        $out = substr($out, 0, -2);
        $this->communication = $out;
        return $out;
    }

    public function active()
    {
        return $this->communication() ||
            $this->santechActive() ||
            $this->balconyActive() ||
            $this->materialActive() ||
            $this->windowsActive() ||
            $this->doorsActive() ||
            $this->finishActive() ||
            $this->stateActive();
    }

    public function santechActive()
    {
        return $this->santechToilet() ||
            $this->santechToiletCount() ||
            $this->santechYear() ||
            $this->santechMaterial() ||
            $this->santechSewerageMaterial() ||
            $this->santechHeatBattery();
    }

    // Санузел
    public function santechToilet()
    {
        return $this->getData()->get('toilet');
    }

    // Количество санузлов
    public function santechToiletCount()
    {
        return (int) $this->getData()->get('toilet_count');
    }

    // Год замены/установки сантехники
    public function santechYear()
    {
        return $this->getData()->get('santech_year');
    }

    // Материал труб
    public function santechMaterial()
    {
        return $this->getData()->get('santech_material');
    }

    // Материал канализационных труб
    public function santechSewerageMaterial()
    {
        return $this->getData()->get('sewerage_material');
    }

    // Батареи отопления
    public function santechHeatBattery()
    {
        return $this->getData()->get('heat_battery');
    }

    public function materialActive()
    {
        return $this->materialCarrying() ||
            $this->materialCeiling() ||
            $this->materialWall();
    }

    // Материал несущих конструкций
    public function materialCarrying()
    {
        return $this->getData()->get('material_carrying');
    }

    // Материал наружних стен
    public function materialWall()
    {
        return $this->getData()->get('material_wall');
    }

    // Материал перекрытий
    public function materialCeiling()
    {
        return $this->getData()->get('material_ceiling');
    }

    public function balconyActive()
    {
        return $this->balconyLoggia() ||
            $this->balconyBalcony() ||
            $this->balconyArrangement();
    }

    // Количество лоджий
    public function balconyLoggia()
    {
        return (int) $this->getData()->get('loggia_count');
    }

    // Количество балконов
    public function balconyBalcony()
    {
        return (int) $this->getData()->get('balcony_count');
    }

    // Обустройство
    public function balconyArrangement()
    {
        return $this->getData()->get('balcony_arrangement');
    }

    public function windowsActive()
    {
        return $this->windowsMaterial() ||
            $this->windowsGlasing() ||
            $this->windowsOpening();
    }

    // Материал рам
    public function windowsMaterial()
    {
        return $this->getData()->get('window_material');
    }

    // Тип остекления
    public function windowsGlasing()
    {
        return $this->getData()->get('window_glasing');
    }

    // Тип открывания окон
    public function windowsOpening()
    {
        return $this->getData()->get('window_opening');
    }

    public function doorsActive()
    {
        return $this->doorsRoom() ||
            $this->doorsFront() ||
            $this->doorsPocket();
    }

    // Межкомнатные двери
    public function doorsRoom()
    {
        return $this->getData()->get('door_room');
    }

    // Входная дверь
    public function doorsFront()
    {
        if ($this->doors_front)
            return $this->doors_front;
        $out = '';
        $space = '';
        $door_front = $this->getData()->get('door_front');
        if ($door_front)
        {
            $space = ', ';
            $out = $door_front;
        }

        $door_front_material = $this->getData()->get('door_front_material');
        if ($door_front_material)
        {
            if ($door_front)
                $out = $door_front . $space . $door_front_material;
            else
                $out = $door_front_material;
        }
        $this->doors_front = $out;
        return $out;
    }

    // Дверь в карман
    public function doorsPocket()
    {
        return $this->getData()->get('door_pocket_material');
    }

    public function finishActive()
    {
        return $this->finishCeiling() ||
            $this->finishFloor() ||
            $this->finishPartition();
    }

    // Потолки
    public function finishCeiling()
    {
        return $this->getData()->get('finish_ceiling');
    }

    // Полы
    public function finishFloor()
    {
        return $this->getData()->get('finish_paul');
    }

    // Перегородки
    public function finishPartition()
    {
        return $this->getData()->get('finish_partition');
    }

    public function stateActive()
    {
        return $this->stateBuildYear() ||
            $this->stateWearout() ||
            $this->stateState();
    }

    // Год постройки
    public function stateBuildYear()
    {
        return $this->getData()->get('build_year');
    }

    // Процент износа
    public function stateWearout()
    {
        if ($v = $this->getData()->get('wearout'))
            return $v . '%';
    }

    // Состояние
    public function stateState()
    {
        return $this->getData()->get('state');
    }

}
