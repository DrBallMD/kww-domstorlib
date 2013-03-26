<?php

/**
 * Forming link to agency website and Contact information
 * $row['Agency']['www']
 * $row['Contact']['phone'], $row['Contact']['email'],
 * $row['Contact']['webcam_telecom_id'], $row['Contact']['name']
 *
 * @author pahhan
 */
class Ds_Transformer_OwnerTransformer implements Spv_Transformer_TransformerInterface
{
    public function transform($row)
    {
        if( !isset($row['Agency']) ) return $row;

        $agency = $row['Agency'];
        $filial = isset($row['Filial'])? $row['Filial'] : array();
        $agent = isset($row['Agent'])? $row['Agent'] : array();

        $www = isset( $agency['www'] )? $agency['www'] : false;
        $sibwww = isset( $agency['sibwww'] )? $agency['sibwww'] : false;
        $flag = isset( $agency['site_link_flag'] )? $agency['site_link_flag'] : false;

        $www = $flag? $www : $sibwww;
        if( !(strstr($www, 'http://') or strstr($www, 'https://')) )
                $www = 'http://'.$www;

        $agency['www'] = $www;
        unset($agency['site_link_flag'], $agency['sibwww'], $www, $sibwww, $flag);

        $contact = array(
            'phone' => '',
            'email' => '',
            'webcam_telecom_id' => '',
            'name' => ''
        );

        if( isset($agent['webcam_telecom_id']) and $agent['webcam_telecom_id'] )
            $contact['webcam_telecom_id'] = $agent['webcam_telecom_id'];
        else
            if( isset($filial['webcam_telecom_id']) and $filial['webcam_telecom_id'] )
                $contact['webcam_telecom_id'] = $filial['webcam_telecom_id'];
            else
                if( isset($agency['webcam_telecom_id']) and $agency['webcam_telecom_id'] )
                    $contact['webcam_telecom_id'] = $agency['webcam_telecom_id'];

        $type = isset($agency['tipcont'])? (int) $agency['tipcont'] : 0;

        if( $type == 1 )
        {
            if( isset($agency['tel_cont']) and $agency['tel_cont'] )
                $contact['phone'] = $agency['tel_cont'];

            if( isset($agency['mail_cont']) and $agency['mail_cont'] )
                $contact['email'] = $agency['mail_cont'];
        }
        elseif( $type == 2 )
        {
            if( isset($filial['phone']) and $filial['phone'] )
                $contact['phone'] = $filial['phone'];

            if( isset($filial['mail']) and $filial['mail'] )
                $contact['email'] = $filial['mail'];
        }
        elseif( $type == 3 )
        {
            if( isset($agent['tel_work']) and $agent['tel_work'] )
                $contact['phone'] = $agent['tel_work'];

            if( isset($agent['mail']) and $agent['mail'] )
                $contact['email'] = $agent['mail'];

            if( isset($agent['name_as']) and $agent['name_as'] )
                $contact['name'] = $agent['name_as'];
        }

        $row['Contact'] = $contact;
        $row['Agency'] = $agency;

        return $row;
    }
}

