<?php

/**
 * Description of SaleLocationBlock
 *
 * @author pahhan
 */
class Ds_Detail_Block_CityLocationBlock extends Ds_Detail_Block_AbstractBlock
{
    public function render(array $params = array())
    {
        $vars = array('block' => $this);

        return $this->getTemplating()->render($this->getTemplate(), $vars);
    }

	public function location()
	{
		$data = $this->getData();

		$out = Ds_Detail_Helper::getAddress($data).', ';

        $distr = (!empty($data['location_id']) and isset($data['location_name']))?
                $data->location_name :
                (isset($data['District']['name'])?
                        $data['District']['name'].', '.$data['location_name'] :
                        '');

        if( $distr ) $out.= $distr.', ';

        if( $data['address_note'] ) $out.= $data['address_note'].', ';

        if( !empty($data['Subregion']['name']) ) $out.= $data['Subregion']['name'].' '.$data['Subregion']['socr'].', ';

		return trim($out, ', ');
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
		$location = Ds_Detail_Helper::getLocation($data);
		if( $location )
		{
			$addr = sprintf('%s, %s',
				$location,
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
		//var_dump($data);
		$out = '';
		$city = isset($data['location_name'])? $data['location_name'] : '';
		$street = !empty($data['street_name'])? $data['street_name'] : $data['address_note'];
        $city_id = $data['master_city_id'];
        $cities4geo = array(
            2466, // vologda
            2471, // voronezh
            2653, // izhevsk
            2004, // kemerovo
            2707, // krasnoyarsk
            2049, // kotlas
            2784, // nizhnevartovsk
            2006, // novokuznetsk
            2253, // orenburg
            2254, // orsk
            2279, // rostov-na-donu
            2783, // surgut
            2411, // tula
            2782, // hanty-mansiysk
            2467, // cherepovets
        );

		if( in_array($city_id, $cities4geo) )
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

