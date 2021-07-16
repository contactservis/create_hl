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

?>