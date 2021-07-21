<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

/**
 * getParamsIndicators - параметры таблицы
 *
 * @return void
 */
function getParamsIndicators(){
    $arHLBl = array();
    $UFObject = '';

    $arHLBl["Lang"] = Array(
        'ru' => 'Рейтинг городов',
        'en' => 'Rating sity'
    );

    $arHLBl["Name"] =[
        'NAME' => 'Ratingsity',
        'TABLE_NAME' => 'ratingsity'
        ];

    $arHLBl["Filds"] = Array(
        'UF_NAME_SITY'=>Array(
            'ENTITY_ID'         => $UFObject,
            'FIELD_NAME'        => 'UF_NAME_SITY',
            'USER_TYPE_ID'      => 'string',
            'MANDATORY'         => 'N',
            "EDIT_FORM_LABEL"   => Array('ru'=>'Название города', 'en'=>'Name sity'), 
            "LIST_COLUMN_LABEL" => Array('ru'=>'Название города', 'en'=>'Name sity'),
            "LIST_FILTER_LABEL" => Array('ru'=>'Название города', 'en'=>'Name sity'), 
            "ERROR_MESSAGE"     => Array('ru'=>'', 'en'=>''), 
            "HELP_MESSAGE"      => Array('ru'=>'', 'en'=>''),
            "SHOW_IN_LIST"      => "N",
        ),

      'UF_INCOME'=>Array(
          'ENTITY_ID' => $UFObject,
          'FIELD_NAME' => 'UF_INCOME',
          'USER_TYPE_ID' => 'double',
          'MANDATORY' => 'N',
          "EDIT_FORM_LABEL" => Array('ru'=>'Общие доходы', 'en'=>''), 
          "LIST_COLUMN_LABEL" => Array('ru'=>'Общие доходы', 'en'=>''),
          "LIST_FILTER_LABEL" => Array('ru'=>'Общие доходы', 'en'=>''), 
          "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
          "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
          "SETTINGS" => array(
            "PRECISION" => 2
          )
      ),

      'UF_COSTS'=>Array(
        'ENTITY_ID' => $UFObject,
        'FIELD_NAME' => 'UF_COSTS',
        'USER_TYPE_ID' => 'double',
        'MANDATORY' => 'N',
        "EDIT_FORM_LABEL" => Array('ru'=>'Общие расходы', 'en'=>''), 
        "LIST_COLUMN_LABEL" => Array('ru'=>'Общие расходы', 'en'=>''),
        "LIST_FILTER_LABEL" => Array('ru'=>'Общие расходы', 'en'=>''), 
        "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
        "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
        "SETTINGS" => array(
          "PRECISION" => 2
        )
    ),
      
      'UF_COUNTS_PEOPLE'=>Array(
        'ENTITY_ID' => $UFObject,
        'FIELD_NAME' => 'UF_COUNTS_PEOPLE',
        'USER_TYPE_ID' => 'integer',
        'MANDATORY' => 'N',
        "EDIT_FORM_LABEL" => Array('ru'=>'Количество жителей', 'en'=>''), 
        "LIST_COLUMN_LABEL" => Array('ru'=>'Количество жителей', 'en'=>''),
        "LIST_FILTER_LABEL" => Array('ru'=>'Количество жителей', 'en'=>''), 
        "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''), 
        "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
      ),               
  );

  return $arHLBl;
}


/**
 * createTable - создаем таблицу 
 *
 * @return void
 */
function createTable(){
       
    $arProperty = getParamsIndicators();
    //создание HL-блока 
    $result = HL\HighloadBlockTable::add($arProperty["Name"]);      
    
    // создаем поля таблицы
    if ($result->isSuccess()) {
        $id = $result->getId();
        $UFObject = 'HLBLOCK_'.$id;
        
        // название таблицы
        foreach($arProperty["Lang"] as $lang_key => $lang_val){
            HL\HighloadBlockLangTable::add(array(
                'ID' => $id,
                'LID' => $lang_key,
                'NAME' => $lang_val
            ));
        }         
        // поля 
        foreach($arProperty["Filds"] as $arCartField){
          $obUserField  = new CUserTypeEntity;
          $arCartField["ENTITY_ID"] = $UFObject;
          $ID = $obUserField->Add($arCartField);

        }
    }

}


/**
 * checkTable  -  проверяем наличие таблицы
 *
 * @param  mixed $NameTable
 * @return void
 */
function checkTable($NameTable){
    $rsData = HL\HighloadBlockTable::getList(array('filter'=>array('TABLE_NAME'=>$NameTable)));
    if ( !($hldata = $rsData->fetch()) ){
        echo "Инфоблок не создан";
        return true;
    }else {
        echo "Инфоблок создан";
        return false;
    }

}

/**
 * addData - добавление записей в таблицу
 *
 * @return void
 */
function addData($arParams){
    
}

/**
 * getItem - Получаем записи HL блока 
 *
 * @return void
 */
function getItem(){
    
}

/**
 * getAllElements - получить все элементы
 *
 * @return void
 */
function getAllElements($NameTable){
    $result = HL\HighloadBlockTable::add($NameTable);
    $hlbl = $result->getId();
    $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch(); 

    $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
    $entity_data_class = $entity->getDataClass(); 

    $rsData = $entity_data_class::getList(array(
    "select" => array("*"),
    "order" => array("UF_COUNTS_PEOPLE" => "ASC"),
    "filter" => array()
    ));

    $arData = array();
    while($arData = $rsData->Fetch()){
        $arDataTable[] = $arData ;
    }

    return $arDataTable;
}

function sortArray( $array, $nameFild ){
    foreach ($array as $key => $row)
    {
        $array_sort[$key] = $row[$nameFild];
    }
    array_multisort($array_sort, SORT_DESC, $array);
    unset( $array_sort );
    
    return $array;
}

if($_GET["create_table"] == 'Y' && checkTable('Ratingsity')){
    $CreateTable = createTable();
    print_r($CreateTable);
}

if($_GET["view_table"] == 'Y'){
    $arrItems = getAllElements('Ratingsity');

    // сортировка по доходам и добавления рейтинга
    $arrItems = sortArray( $array, 'UF_INCOME' );   
    foreach($arrItems as $key => $value){
        $arrItems[$key]['RATING_INCOME'] = $key;
    }

    // сортировка по расходам и добавления рейтинга
    $arrItems = sortArray( $array, 'UF_COSTS' );   
    foreach($arrItems as $key => $value){
        $arrItems[$key]['RATING_COST'] = $key;
    }

    // сортировка по количеству жителей и добавления рейтинга
    $arrItems = sortArray( $array, 'UF_COUNTS_PEOPLE' );   
    foreach($arrItems as $key => $value){
        $arrItems[$key]['RATING_PEOPLE'] = $key;
    }

    // выводим таблицу 
    echo '<table><tr>
    <th>Название</th>
    <th>Доходы общие</th>
    <th>Расходы общие</th>
    <th>Количество жителей</th>
    <th>Место в рейтинге по количеству жителей</th>
    <th>Место в рейтинге по средним доходам населения</th>
    <th>Место по средним расходам населения</th>
    </tr>';

    foreach($arrItems as $key => $value){
        echo '<tr><td>'.$value['UF_NAME_SITY'].'</td>
        <td>'.$value['UF_INCOME'].'</td>
        <td>'.$value['UF_INCOME'].'</td>
        <td>'.$value['UF_COSTS'].'</td>
        <td>'.$value['UF_COUNTS_PEOPLE'].'</td>
        <td>'.$value['RATING_INCOME'].'</td>
        <td>'.$value['RATING_COST'].'</td>
        <td>'.$value['RATING_PEOPLE'].'</td>
        </tr>';
    }

    echo '</table>';


}


?>