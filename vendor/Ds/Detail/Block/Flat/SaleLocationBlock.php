<?php

/**
 * Description of SaleLocationBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_Flat_SaleLocationBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

	public function location()
	{
		$data = $this->getData();

		$out = Ds_Detail_Helper::getAddress($data);

        if( $data->isSetAnd('District') and $data->District->id > 0 ) $out.= ', '.$data->District->name.', ';

        if( isset($data['City']) ) $out.= Ds_Detail_Helper::getCity($data['City']).', ';

        if( $data['address_note'] ) $out.= $data['address_note'].', ';

		$out = substr($out, 0, -2);

		return $out;
	}

	public function metro()
	{
		$metro = $this->getData('Metro');
		if( $metro and isset($metro['name']) ) return $metro['name'];

		return '';
	}

	// available... доступность до отсановки, метро и т.д.
	public function availableBus()
	{
		return (int) $this->getData()->get('available_bus');
	}

	public function availableMetro()
	{
		return (int) $this->getData()->get('available_metro');
	}

	public function availableBusToMetro()
	{
		return (int) $this->getData()->get('available_bus_to_metro');
	}

	public function availableSea()
	{
		return (int) $this->getData()->get('available_sea');
	}

	public function availableShop()
	{
		return (int) $this->getData()->get('available_shop');
	}

	public function availableCafe()
	{
		return (int) $this->getData()->get('available_cafe');
	}

	public function availableCarSea()
	{
		return (int) $this->getData()->get('available_car_sea');
	}

	public function availableCarAirport()
	{
		return (int) $this->getData()->get('available_car_airport');
	}

	public function availableCarShop()
	{
		return (int) $this->getData()->get('available_car_shop');
	}

	public function availableCarCafe()
	{
		return (int) $this->getData()->get('available_car_cafe');
	}

	public function mapWebLink()
	{
		return $this->getData()->get('map_weblink');
	}

	public function yandexMapLink()
	{
		$data = $this->getData();
		$out = '';
		$city = $data->isSetAndArray('City')? $data['City']['name'] : FALSE;
		if( $city )
		{
			$addr = sprintf('%s, %s',
				Ds_Detail_Helper::getLocation($data),
				str_replace('/', 'К', Ds_Detail_Helper::getBuilding($data))
			);
            $addr = iconv('UTF-8', 'WINDOWS-1251', $addr);
			$addr = urlencode($addr);
			$out = sprintf('http://domstor.ru/gateway/maps/yandex?address=%s', $addr);
		}
		return $out;
	}

	public function fourGeoMapLink()
	{
		$data = $this->getData();
		$out = '';
		$city = $data->isSetAndArray('City')? $data['City']['name'] : FALSE;
		$street = $data->isSetAndArray('Street')? $data['Street']['name'] : FALSE;

		if( $city and $street )
		{
			$addr = sprintf('%s, %s, %s',
				$city,
				$street,
				Ds_Detail_Helper::getBuilding($data)
			);
            $addr = iconv('UTF-8', 'WINDOWS-1251', $addr);
			$addr = urlencode($addr);
			$out = sprintf('http://domstor.ru/gateway/maps/4geo?address=%s', $addr);
		}
		return $out;
	}

	public function mapImages()
	{
		return $this->getData()->get('img_map', array());
	}
}

