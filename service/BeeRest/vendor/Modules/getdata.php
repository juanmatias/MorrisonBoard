<?php
/**
 * Class getdata | vendor/Modules/destinos.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */

 namespace Modules;

 /**
  * Class getdata - Service to query destinos statistics
  *
  * Queries the database and build statistics about "destinos"
  *
  */

class getdata extends morris_builder
{
  /**
  * Fills the valid_actions with this object's actions
  */
  function __construct()
  {
    parent::__construct();
    $this->add_actions(array('statisticsboard',));
  }

  /**
  * Returns the json object to build the pentamorrison board
  */
  public function statisticsboard($params,$request)
  {
    $pentaboard = array();

    /*
    * Returned json must be like this javascript array:
    *  [panels_name,id,cols,type, data, options ]
    *
    * panels_name string panel name
    * id string unique id
    * cols int number of columns (should be created classes such col1 col2 etc)
    * type string donut, stackedbars, bars, area, line
    * data object like {x: '2011 Q1', y: 30, z: 30, o: 39, w: 1}
    * options object depending on the widget type
    */

/*
array(
'type' => ['area'|'line'|'donut'|'stackedbars'|'bars'],
'name' => [graphic name],
'id' => [unique string id],
'cols' => [1 | 2 ], //binded to classess col1, col2 and so on
'sql' => [query to run on DBServer],
'sql_values' => [array with values, if query has ? marks they will be replaced with the parameters here, each value is array like array('type','value') where type is  s = string, i = integer, d = double, b = blob]
'values' => [array of y values, must be the name of fields in query],
'x' => [array of x values, must be the name of fields in query], //so far can be specified only one
'values2percentage' => [false|true], // wheter to convert value to %
'ttl' => [time to live in ms, for cache],
'labels' => [array of y value labels, they are binded to the same position of values],
'options' => [array with other options to send to frontend inside the "options" key],
'action' => [int 1=draw (or redraw); 0=delete]
),

*/
    $mypanes = array(
      array(
      'type' => 'text',
      'name' => 'Último código utilizado',
      'id' => 'last_code',
      'cols' => 1,
      'sql' => "SELECT  selected_option FROM `pm_options` limit 1;",
      // 'sql' => "SELECT concat(selected_option,' este es el colorete') AS selected_option FROM `pm_options` limit 1;",
      'values' => array(),
      'x' => array('selected_option'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array(),
      'options' => array('unit' => '', 'labelColor' => '#BD9626', 'auto_text_size' => true)
      ),
      array(
      'type' => 'area',
      'name' => 'por genero4',
      'id' => 'area1',
      'cols' => 1,
      'sql' => "SELECT CASE WHEN y.qty IS NULL THEN 0 ELSE y.qty END as qtym, CASE WHEN z.qty IS NULL THEN 0 ELSE z.qty END as qtyh, DATE_FORMAT( NOW() - INTERVAL c.number MINUTE, '%d.%m.%Y %H:%i') AS date FROM (SELECT singles + tens + hundreds number FROM  ( SELECT 0 singles UNION ALL SELECT   1 UNION ALL SELECT   2 UNION ALL SELECT   3 UNION ALL SELECT   4 UNION ALL SELECT   5 UNION ALL SELECT   6 UNION ALL SELECT   7 UNION ALL SELECT   8 UNION ALL SELECT   9 ) singles JOIN  (SELECT 0 tens UNION ALL SELECT  10 UNION ALL SELECT  20 UNION ALL SELECT  30 UNION ALL SELECT  40 UNION ALL SELECT  50 UNION ALL SELECT  60 UNION ALL SELECT  70 UNION ALL SELECT  80 UNION ALL SELECT  90 ) tens  JOIN  (SELECT 0 hundreds UNION ALL SELECT  100 UNION ALL SELECT  200 UNION ALL SELECT  300 UNION ALL SELECT  400 UNION ALL SELECT  500 UNION ALL SELECT  600 UNION ALL SELECT  700 UNION ALL SELECT  800 UNION ALL SELECT  900 ) hundreds ORDER BY number DESC)  AS c LEFT OUTER JOIN (select count(*) as qty, DATE_FORMAT( time, '%d.%m.%Y %H:%i') as time2 from pm_options where gender = 0 group by time2) AS y ON DATE_FORMAT( NOW() - INTERVAL c.number MINUTE, '%d.%m.%Y %H:%i') = y.time2 LEFT OUTER JOIN (select count(*) as qty, DATE_FORMAT( time, '%d.%m.%Y %H:%i') as time3 from pm_options where gender = 1 group by time3) AS z ON DATE_FORMAT( NOW() - INTERVAL c.number MINUTE, '%d.%m.%Y %H:%i') = z.time3 WHERE c.number BETWEEN 0 and 10 GROUP BY date;",
      'values' => array('qtym','qtyh'),
      'x' => array('date'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('Mujeres','Hombres'),
      'options' => array('unit' => 'clicks','parseTime' => false,),
      'action' => 1,
      ),
      array(
      'type' => 'area',
      'name' => 'por genero4',
      'id' => 'area2',
      'cols' => 1,
      'sql' => "SELECT CASE WHEN y.qty IS NULL THEN 0 ELSE y.qty END as qtym, CASE WHEN z.qty IS NULL THEN 0 ELSE z.qty END as qtyh, DATE_FORMAT( NOW() - INTERVAL c.number MINUTE, '%d.%m.%Y %H:%i') AS date FROM (SELECT singles + tens + hundreds number FROM  ( SELECT 0 singles UNION ALL SELECT   1 UNION ALL SELECT   2 UNION ALL SELECT   3 UNION ALL SELECT   4 UNION ALL SELECT   5 UNION ALL SELECT   6 UNION ALL SELECT   7 UNION ALL SELECT   8 UNION ALL SELECT   9 ) singles JOIN  (SELECT 0 tens UNION ALL SELECT  10 UNION ALL SELECT  20 UNION ALL SELECT  30 UNION ALL SELECT  40 UNION ALL SELECT  50 UNION ALL SELECT  60 UNION ALL SELECT  70 UNION ALL SELECT  80 UNION ALL SELECT  90 ) tens  JOIN  (SELECT 0 hundreds UNION ALL SELECT  100 UNION ALL SELECT  200 UNION ALL SELECT  300 UNION ALL SELECT  400 UNION ALL SELECT  500 UNION ALL SELECT  600 UNION ALL SELECT  700 UNION ALL SELECT  800 UNION ALL SELECT  900 ) hundreds ORDER BY number DESC)  AS c LEFT OUTER JOIN (select count(*) as qty, DATE_FORMAT( time, '%d.%m.%Y %H:%i') as time2 from pm_options where gender = 0 group by time2) AS y ON DATE_FORMAT( NOW() - INTERVAL c.number MINUTE, '%d.%m.%Y %H:%i') = y.time2 LEFT OUTER JOIN (select count(*) as qty, DATE_FORMAT( time, '%d.%m.%Y %H:%i') as time3 from pm_options where gender = 1 group by time3) AS z ON DATE_FORMAT( NOW() - INTERVAL c.number MINUTE, '%d.%m.%Y %H:%i') = z.time3 WHERE c.number BETWEEN 0 and 10 GROUP BY date;",
      'values' => array('qtym','qtyh'),
      'x' => array('date'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('Mujeres','Hombres'),
      'options' => array('unit' => 'clicks','parseTime' => false,'behaveLikeLine' => false,)
      ),
      array(
      'type' => 'donut',
      'name' => 'My test',
      'id' => 'my_test',
      'cols' => 1,
      'sql' => 'select count(*) as qty, selected_option as color from pm_options  where categories_id = 1 group by selected_option order by selected_option;',
      'values' => array('qty'),
      'values2percentage' => true,
      'ttl' => 1000,
      'labels' => array('color'),
      'options' => array('unit' => 'clicks', )
      ),
      array(
      'type' => 'pie',
      'name' => 'My test pie',
      'id' => 'my_test_pie',
      'cols' => 1,
      'sql' => 'select count(*) as qty, selected_option as color from pm_options  where categories_id = 1 group by selected_option order by selected_option;',
      'values' => array('qty'),
      'values2percentage' => true,
      'ttl' => 1000,
      'labels' => array('color'),
      'options' => array('unit' => 'clicks', )
      ),
      array(
      'type' => 'pie',
      'name' => 'My test pie No Labels',
      'id' => 'my_test_pie_nolabels',
      'cols' => 1,
      'sql' => 'select count(*) as qty, selected_option as color from pm_options  where categories_id = 1 group by selected_option order by qty, selected_option limit 4;',
      'values' => array('qty'),
      'values2percentage' => true,
      'ttl' => 1000,
      'labels' => array('color'),
      'options' => array('unit' => 'clicks', 'showLabel' => false)
      ),
      array(
      'type' => 'donut',
      'name' => 'My test2',
      'id' => 'my_test2',
      'cols' => 1,
      'sql' => 'select count(*) as qty, selected_option as color from pm_options  where categories_id = 1 group by selected_option order by selected_option limit ?; ',
      'sql_values' => array(array('i','4'),),
      'values' => array('qty'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('color'),
      'options' => array('unit' => 'clicks', )
      ),
      array(
      'type' => 'line',
      'name' => 'últimos 10 minutos',
      'id' => 'my_test3',
      'cols' => 2,
      'sql' => "SELECT CASE WHEN y.qty IS NULL THEN 0 ELSE y.qty END as qty, DATE_FORMAT( NOW() - INTERVAL c.number MINUTE, '%d.%m.%Y %H:%i') AS date FROM (SELECT singles + tens + hundreds number FROM  ( SELECT 0 singles UNION ALL SELECT   1 UNION ALL SELECT   2 UNION ALL SELECT   3 UNION ALL SELECT   4 UNION ALL SELECT   5 UNION ALL SELECT   6 UNION ALL SELECT   7 UNION ALL SELECT   8 UNION ALL SELECT   9 ) singles JOIN  (SELECT 0 tens UNION ALL SELECT  10 UNION ALL SELECT  20 UNION ALL SELECT  30 UNION ALL SELECT  40 UNION ALL SELECT  50 UNION ALL SELECT  60 UNION ALL SELECT  70 UNION ALL SELECT  80 UNION ALL SELECT  90 ) tens  JOIN  (SELECT 0 hundreds UNION ALL SELECT  100 UNION ALL SELECT  200 UNION ALL SELECT  300 UNION ALL SELECT  400 UNION ALL SELECT  500 UNION ALL SELECT  600 UNION ALL SELECT  700 UNION ALL SELECT  800 UNION ALL SELECT  900 ) hundreds ORDER BY number DESC)  AS c LEFT OUTER JOIN (select count(*) as qty, DATE_FORMAT( time, '%d.%m.%Y %H:%i') as time2 from pm_options group by time2) AS y ON DATE_FORMAT( NOW() - INTERVAL c.number MINUTE, '%d.%m.%Y %H:%i') = y.time2 WHERE c.number BETWEEN 0 and 10 GROUP BY date;",
      'values' => array('qty'),
      'x' => array('date'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('Mujeres','Hombres'),
      'options' => array('unit' => 'clicks',)
      ),
      array(
      'type' => 'stackedbars',
      'name' => 'por genero',
      'id' => 'genero',
      'cols' => 1,
      'sql' => "select x.selected_option, y.yqty, z.zqty from pm_options as x LEFT OUTER JOIN (select count(*) as yqty, gender, selected_option from pm_options where gender = 1 group by selected_option, gender) as y ON x.selected_option = y.selected_option  LEFT OUTER JOIN (select count(*) as zqty, gender, selected_option from pm_options where gender = 0 group by selected_option, gender) as z ON x.selected_option = z.selected_option group by x.selected_option;",
      'values' => array('yqty','zqty'),
      'x' => array('selected_option'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('Mujeres','Hombres'),
      'options' => array('unit' => 'clicks' )
      ),
      array(
      'type' => 'stackedbars',
      'name' => 'por genero horizontal',
      'id' => 'generoh',
      'cols' => 1,
      'sql' => "select x.selected_option, y.yqty, z.zqty from pm_options as x LEFT OUTER JOIN (select count(*) as yqty, gender, selected_option from pm_options where gender = 1 group by selected_option, gender) as y ON x.selected_option = y.selected_option  LEFT OUTER JOIN (select count(*) as zqty, gender, selected_option from pm_options where gender = 0 group by selected_option, gender) as z ON x.selected_option = z.selected_option group by x.selected_option;",
      'values' => array('yqty','zqty'),
      'x' => array('selected_option'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('Mujeres','Hombres'),
      'options' => array('unit' => 'clicks', 'horizontal' => true )
      ),
      array(
      'type' => 'bars',
      'name' => 'por genero radius',
      'id' => 'generor',
      'cols' => 2,
      'sql' => "select x.selected_option, y.yqty, z.zqty from pm_options as x LEFT OUTER JOIN (select count(*) as yqty, gender, selected_option from pm_options where gender = 1 group by selected_option, gender) as y ON x.selected_option = y.selected_option  LEFT OUTER JOIN (select count(*) as zqty, gender, selected_option from pm_options where gender = 0 group by selected_option, gender) as z ON x.selected_option = z.selected_option group by x.selected_option;",
      'values' => array('yqty','zqty'),
      'x' => array('selected_option'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('Mujeres','Hombres'),
      'options' => array('unit' => 'clicks', 'barRadius' => array(10,10,0,0) )
      ),
      array(
      'type' => 'bars',
      'name' => 'por genero barOpacity',
      'id' => 'generobo',
      'cols' => 1,
      'sql' => "select x.selected_option, y.yqty, z.zqty from pm_options as x LEFT OUTER JOIN (select count(*) as yqty, gender, selected_option from pm_options where gender = 1 group by selected_option, gender) as y ON x.selected_option = y.selected_option  LEFT OUTER JOIN (select count(*) as zqty, gender, selected_option from pm_options where gender = 0 group by selected_option, gender) as z ON x.selected_option = z.selected_option group by x.selected_option limit 2;",
      'values' => array('yqty','zqty'),
      'x' => array('selected_option'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('Mujeres','Hombres'),
      'options' => array('unit' => 'clicks', 'barOpacity' => 0.5 )
      ),

      array(
      'type' => 'bars',
      'name' => 'por genero2',
      'id' => 'genero2',
      'cols' => 2,
      'sql' => "select x.selected_option, y.yqty, z.zqty from pm_options as x LEFT OUTER JOIN (select count(*) as yqty, gender, selected_option from pm_options where gender = 1 group by selected_option, gender) as y ON x.selected_option = y.selected_option  LEFT OUTER JOIN (select count(*) as zqty, gender, selected_option from pm_options where gender = 0 group by selected_option, gender) as z ON x.selected_option = z.selected_option group by x.selected_option;",
      'values' => array('yqty','zqty'),
      'x' => array('selected_option'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('Mujeres','Hombres'),
      'options' => array('unit' => 'clicks', 'xLabelAngle' => '60',)
      ),
      array(
      'type' => 'line',
      'name' => 'por genero3',
      'id' => 'genero3',
      'cols' => 1,
      'sql' => "select x.selected_option, y.yqty, z.zqty from pm_options as x LEFT OUTER JOIN (select count(*) as yqty, gender, selected_option from pm_options where gender = 1 group by selected_option, gender) as y ON x.selected_option = y.selected_option  LEFT OUTER JOIN (select count(*) as zqty, gender, selected_option from pm_options where gender = 0 group by selected_option, gender) as z ON x.selected_option = z.selected_option group by x.selected_option;",
      'values' => array('yqty','zqty'),
      'x' => array('selected_option'),
      'values2percentage' => false,
      'ttl' => 1000,
      'labels' => array('Mujeres','Hombres'),
      'options' => array('unit' => 'clicks', )
      ),
    );



    // first item is a key that allows us to reload the page if changed
    $pentaboard = array('343rf34r4ewe');

    foreach ($mypanes as $key => $mypane) {
      switch ($mypane['type']) {
        case 'donut':
        case 'pie':
          $pentaboard[] = $this->build_donut($mypane);
          break;
        case 'line':
          $pentaboard[] = $this->build_line($mypane);
          break;
        case 'stackedbars':
        case 'bars':
          $pentaboard[] = $this->build_bars($mypane);
          break;
        case 'area':
          $pentaboard[] = $this->build_area($mypane);
          break;
          case 'text':
            $pentaboard[] = $this->build_text($mypane);
            break;
        default:
          # code...
          break;
      }
    }
    return $pentaboard;
  }



}

 ?>
