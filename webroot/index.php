<?php
// � ��������� �� ��� E_NOTICE ���������� :(
error_reporting(E_ALL & !E_NOTICE);

// ���������� �������������
require_once(__DIR__.'/../domstorlib/autoload.php');

// ������� ������ Domstor
$domstor = new Domstor();

// ������������� ������������� �����������
$domstor->setMyId(0);

// ������������� ������������� �������������� �� ���������
$domstor->setHomeLocation(2004);

// ������������� ���-�������� - ������������� �������� ��� ���������� ��������������
//$driver = extension_loaded('apc')? new Doctrine_Cache_Apc() : new Doctrine_Cache_Array();
//$domstor->setCacheDriver($driver);
//$domstor->setCacheTime(600);

// �������� ��������� object, action ����������� ��� �����������
/**
 * ��������� �������� object:
 * flat - ��������
 * house - ���� � ��������
 * land - ����� � ����
 * garage - ������ � ��������
 * commerce - ��� ������������
 * ������������ ����� ��������� ��
 *  trade - ��������
 *  office - �������
 *  product - ����������������
 *  storehouse - ���������
 *  landcom - �����
 *  complex - ��������
 *  other - ������
 *
 * ��������� �������� ��� action:
 * sale - �������
 * rent - �����
 * purchase - �����
 * rentuse - ������
 * exchange - ����� (�������� ������ ��� flat, house)
 * new - ����������� (������ ��� flat)
 */
$object = isset($_GET['object'])? $_GET['object'] : null;
$action = isset($_GET['action'])? $_GET['action'] : null;

// �������� ������� ��������
$page = isset($_GET['page'])? $_GET['page'] : 1;

// �������� ������������� �������
$id = isset($_GET['id'])? $_GET['id'] : null;

// ��� ���������� ������� �� view
$html = '';
$title = '';
$filter = '';
$count = '';

// ��������� $object $action
if( Domstor_Helper::checkEstateAction($object, $action) )
{
    // ���� ��������� id - ������� �������� � ��������� �����������
    if( $id )
    {
        // ����������� ������ ���������� ��������
        $detail = $domstor->getDetail($object, $action, $id);

        // ��������� ��� �������
        if( $detail )
        {
            // �������� html-��� ���������� ��������
            $html = $detail->render();
            // �������� �����
            $title = $detail->getPageTitle();
        }
        else
        {
            // �������� 404
        }

    }
    else // ����� ������
    {
        // ������� ������ ������
        $list = $domstor->getList($object, $action, $page);
        // �������� html-��� ������
        $html = $list->getHtml();
        // �������� ������ ������
        $filter = $list->getFilter();
        // �������� ����� ���������� �������� �������
        $count = $domstor->getCount($object, $action);
    }
}
else
{
    // ���������� ������
}
?>

<!-- ���-�� �� view... -->
<?php echo $title ?><br/>
<?php echo $filter ?> <!-- � ������� filter ���� ��������� ����� __toString -->
<?php if($count): ?>�����: <?php echo $count ?><?php endif ?>
<?php echo $html ?>

