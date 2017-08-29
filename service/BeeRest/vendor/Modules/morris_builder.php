<?php
/**
 * Class morris_builder | vendor/Modules/morris_builder.php
 *
 * @package RESTful WebService Model
 * @author Juan Matias de la Camara Beovide <juanmatias@gmail.com>
 *
 */

 namespace Modules;

 /**
  * Class morris_builder - Generates response to build morris widgets
  *
  *
  *
  */

class morris_builder extends dbservice
{
  protected $labelColor = '#416192';
  protected $backgroundColor = '#aaa';
  protected $color = array(4,3,6,3,3,3);
  protected $coloridx = 0;
  protected $colorpallete = array('#ffcc00','#cc0033','#004b80','#00788a','#556775','#d7361c','#990033','#eff5f9','#e3ecf1');
  /**
  *
  */
  function __construct()
  {
    parent::__construct();
  }



  protected function build_text($mypane)
  {
    //'action' => [int 1=draw (or redraw) (sent); 0=delete (sent); 2=stay (when data has no changed this option keep draw in same state, generated here if data is the same, TODO: bind request to client to allow this function)]
    if(!isset($mypane['action']))
    {
      $mypane['action'] = 1;
    }
    if((isset($mypane['action']) && $mypane['action'] == 1))
    {
      if(!$this->exec_query($mypane['sql'],(isset($mypane['sql_values'])?$mypane['sql_values']:array())))
      {
        return null;
      }
      else
      {

        $data = array();
        while($row = $this->fetch_assoc())
        {
          $data[] = array('x' => $row[$mypane['x'][0]]);
        }

        $htmlcolors = array();
        foreach ($data as $key => $value) {
          $htmlcolors[] = $this->_get_color();
        }

        $options = array_merge(array('colors' => $htmlcolors,
              'labelColor' => $this->labelColor,
              'backgroundColor' => $this->backgroundColor), $mypane['options']);

        $pentaboard = array($mypane['name'],$mypane['id'],$mypane['cols'],$mypane['type'],
        $data,
        $options,
        $mypane['action']);
      }
    }else if(isset($mypane['action']) && $mypane['action'] == 0)
    {
      $pentaboard = array($mypane['name'],$mypane['id'],'','',
      null,
      null,
      $mypane['action']);
    }

    //add unadded properties to options just in case
    // $pentaboard[sizeof($pentaboard) - 1][5] = array_merge($pentaboard[sizeof($pentaboard) - 1][5], $mypane['options']);

    return $pentaboard;

  }

  protected function build_donut($mypane)
  {
    //'action' => [int 1=draw (or redraw) (sent); 0=delete (sent); 2=stay (when data has no changed this option keep draw in same state, generated here if data is the same, TODO: bind request to client to allow this function)]
    if(!isset($mypane['action']))
    {
      $mypane['action'] = 1;
    }
    if((isset($mypane['action']) && $mypane['action'] == 1))
    {
      if(!$this->exec_query($mypane['sql'],(isset($mypane['sql_values'])?$mypane['sql_values']:array())))
      {
        return null;
      }
      else
      {
        // $r = $this->db->getResults();

        $data = array();
        $qty = 0;
        while($row = $this->fetch_assoc())
        {
          $data[] = array('value' => $row[$mypane['values'][0]],'label'=>$row[$mypane['labels'][0]]);
          $qty += floatval($row[$mypane['values'][0]]);
        }
        if($mypane['values2percentage'])
        {
          foreach ($data as $key => $value) {
            $per = floatval($value['value']) * 100 / $qty;
            $data[$key]['value'] = round($per,2);
          }
        }
        $htmlcolors = array();
        foreach ($data as $key => $value) {
          $htmlcolors[] = $this->_get_color();
        }

        $options = array_merge(array('colors' => $htmlcolors,
              'labelColor' => $this->labelColor,
              'backgroundColor' => $this->backgroundColor,
              'labels' => $mypane['labels']), $mypane['options']);

        $pentaboard = array($mypane['name'],$mypane['id'],$mypane['cols'],$mypane['type'],
        $data,
        $options,
      $mypane['action']);
      }

    }else if(isset($mypane['action']) && $mypane['action'] == 0)
    {
      $pentaboard = array($mypane['name'],$mypane['id'],'','',
      null,
      null,
      $mypane['action']);
    }

    return $pentaboard;

  }

  protected function build_line($mypane)
  {
    return $this->build_bars($mypane);
  }
  protected function build_area($mypane)
  {
    return $this->build_bars($mypane);
  }

  protected function build_bars($mypane)
  {
    //'action' => [int 1=draw (or redraw) (sent); 0=delete (sent); 2=stay (when data has no changed this option keep draw in same state, generated here if data is the same, TODO: bind request to client to allow this function)]
    if(!isset($mypane['action']))
    {
      $mypane['action'] = 1;
    }
    if((isset($mypane['action']) && $mypane['action'] == 1))
    {
      if(!$this->exec_query($mypane['sql'],(isset($mypane['sql_values'])?$mypane['sql_values']:array())))
      {
        return null;
      }
      else
      {
        // $r = $this->db->getResults();

        $data = array();
        $qty = 0;
        while($row = $this->fetch_assoc())
        {
          $aux = array('x' => $row[$mypane['x'][0]]);
          foreach ($mypane['values'] as $key => $value)
          {

            if($row[$value] == '' || $row[$value] == null)
            {
              $aux[$value] = 0;
            }else
            {
              $aux[$value] = $row[$value];
            }

            $qty += floatval($row[$value]);
          }
          $data[] = $aux;
        }
        // if($mypane['values2percentage'])
        // {
        //   foreach ($data as $key => $value)
        //   {
        //     $per = floatval($value['value']) * 100 / $qty;
        //     $data[$key]['value'] = round($per,2);
        //   }
        // }
        $ykeys = array();
        $barColors = array();
        foreach ($mypane['values'] as $key => $value)
        {
          $ykeys[] = $value;
          $barColors[] = $this->_get_color();
          $this->_get_color();
        }

        $options = array_merge(array('labelColor' => $this->labelColor,
          'backgroundColor' => $this->backgroundColor,
          'ykeys' => $ykeys,
          'barColors' => $barColors,
          'labels' => $mypane['labels']), $mypane['options']);

        $pentaboard = array($mypane['name'],$mypane['id'],$mypane['cols'],$mypane['type'],
        $data,
        $options,
        $mypane['action']);
      }

    }else if(isset($mypane['action']) && $mypane['action'] == 0)
    {
      $pentaboard = array($mypane['name'],$mypane['id'],'','',
      null,
      null,
      $mypane['action']);
    }

    return $pentaboard;

  }

  /**
  * Handles the cache
  */
  protected function cached($data)
  {
    return false;
  }

  protected function _get_color()
  {

    if(isset($this->coloridx))
    {
      $this->coloridx++;
      if($this->coloridx >= sizeof($this->colorpallete))
      {
        $this->coloridx = 0;
      }
      return $this->colorpallete[$this->coloridx];
    }else
    {
      $idx = 4;
      if($this->_get_color_sum($idx) == 0)
      {
        $idx = 2;
        if($this->_get_color_sum($idx) == 0)
        {
          $idx = 0;
          $this->_get_color_sum($idx);
        }
      }

      return '#'.join('',$this->color);
    }
  }

  protected function _get_color_sum($idx)
  {

    $val = $this->color[$idx];
    switch ($val) {
      case 'A':
        $val = 10;
        break;
      case 'B':
        $val = 11;
        break;
      case 'C':
        $val = 12;
        break;
      case 'D':
        $val = 13;
        break;
      case 'E':
        $val = 14;
        break;
      case 'F':
        $val = 15;
        break;
    }
    $val += 1;
    if($val > 15)
    {
      $val = 0;
    }
    switch ($val) {
      case '10':
        $val = 'A';
        break;
      case '11':
        $val = 'B';
        break;
      case '12':
        $val = 'C';
        break;
      case '13':
        $val = 'D';
        break;
      case '14':
        $val = 'E';
        break;
      case '15':
        $val = 'F';
        break;
    }
    $this->color[$idx] = $val;
    return $val;
  }

}
?>
