var widgets = {};
var initialized = false;
/* panels name,id,cols,type, data, options */
var paneidpreffix = 'pane_';
var contentidpreffix = 'content_';
var titleidpreffix = 'title_';
var commentidpreffix = 'comment_';


$(document).ready(function()
{

  $("#connecting").css("display","none");
  // add_panes(panels);

  $(document).ajaxStart(function(){
    $("#connecting").css("display","inline");
  });
  $(document).ajaxComplete(function(){
    $("#connecting").css("display","none");
  });

  // setTimeout(update_widgets,500);
  poll_server();

}); // document Ready


/* **************************************************************************** UPDATE ********************** */
function update_widgets(panels)
{
  for (var x = 0; x < panels.length; x ++)
  {
    widgets[panels[x][1]].setData(panels[x][4]);
  }
}

function add_panes(panels)
{
  /* Add panes */
  //<div id="pane1" class="wcontainer col1"><div id='title_x'></div><div id='content_x'></div><div id='comment_x'></div></div>

  for (var x = 0; x < panels.length; x ++)
  {
    var mydiv = $('<div/>', {
        id: paneidpreffix+panels[x][1],
        title: panels[x][0],
        class: 'wcontainer col'+panels[x][2],
      });
    $('<div/>', {
        id: titleidpreffix+panels[x][1],
        class: 'title',
      }).html(panels[x][0])
      .appendTo(mydiv);
    $('<div/>', {
        id: contentidpreffix+panels[x][1],
        class: 'content',
      }).appendTo(mydiv);
    $('<div/>', {
        id: commentidpreffix+panels[x][1],
        class: 'comment',
      }).appendTo(mydiv);
    $(mydiv).appendTo('#board');

    add_widget(panels[x]);
  }

}

function add_widget(panel)
{
    switch (panel[3]) {
      case 'donut':
        widgets[panel[1]]=add_widget_donut(panel[1],panel[4],panel[5]);
      break;
      case 'pie':
          widgets[panel[1]]=add_widget_pie(panel[1],panel[4],panel[5]);
        break;
      case 'stackedbars':
          widgets[panel[1]]=add_widget_bar(panel[1],panel[4],panel[5],true);
        break;
      case 'bars':
          widgets[panel[1]]=add_widget_bar(panel[1],panel[4],panel[5],false);
        break;
      case 'area':
          widgets[panel[1]]=add_widget_area(panel[1],panel[4],panel[5]);
        break;
      case 'line':
          widgets[panel[1]]=add_widget_line(panel[1],panel[4],panel[5]);
        break;
      default:

    }

}

function poll_server()
{
  $.ajax(
  {
     data:{apikey:'destinos'},
     url:  morris_url,
     type:  'get',
     dataType: "json",
     async:false
  }).done(function(data){
    if(data.code != 0)
    {
      console.log('error '+data.error);
    }else
    {
      if(! initialized)
      {
        add_panes(data.response);
        initialized = true;
      }else
      {
        update_widgets(data.response);
      }

    }

    setTimeout(poll_server,morris_poll_timeout);

  });

}
/* ********************************************************************************************************* */
function add_widget_donut(idx,data,usroptions, ...args)
{
  // falta generar los colores automaticamente para la cantidad de objetos
  var unit = '%';
  if(usroptions['unit'])
  {
    unit = usroptions['unit'];
  }
  var options = {
    element: contentidpreffix+idx,
    data: data,
    hideHover: true,
    backgroundColor: '#ccc',
    labelColor: '#060',
    formatter: function (x) { return x + ' ' + unit}
  };
  options = Object.assign({}, options, usroptions);

  var m = Morris.Donut(options);

  var s = '';
  for(data_idx in data)
  {
    s += '<div style="color: '+options['colors'][data_idx]+'">'+data[data_idx].label+' '+data[data_idx].value+'</div>';
  }
  $('#'+commentidpreffix+idx).html(s);
  $('#'+commentidpreffix+idx).addClass('donut');

  return m;

}

function add_widget_pie(idx,data,usroptions, ...args)
{
  // falta generar los colores automaticamente para la cantidad de objetos
  var unit = '%';
  if(usroptions['unit'])
  {
    unit = usroptions['unit'];
  }
  var options = {
    element: contentidpreffix+idx,
    data: data,
    hideHover: true,
    backgroundColor: '#ccc',
    labelColor: '#060',
    formatter: function (x) { return x + ' ' + unit}
  };
  options = Object.assign({}, options, usroptions);

  var m = Morris.Pie(options);

  var s = '';
  for(data_idx in data)
  {
    s += '<div style="color: '+options['colors'][data_idx]+'">'+data[data_idx].label+' '+data[data_idx].value+'</div>';
  }
  $('#'+commentidpreffix+idx).html(s);
  $('#'+commentidpreffix+idx).addClass('pie');

  return m;

}

function add_widget_bar(idx,data,usroptions, ...args)
{
  // falta generar los colores automaticamente para la cantidad de objetos
  var stacked = false;
  if(args[0]==true)
  {
    stacked = true;
  }
  var options = {
    element: contentidpreffix+idx,
    data:data,
    xkey: 'x',
    ykeys: [],
    labels: [],
    stacked: stacked,
    xLabelAngle: 0,
    hideHover: true,
  };
  options = Object.assign({}, options, usroptions);

  var m = Morris.Bar(options);

  var s = '';
  for(data_idx in options['ykeys'])
  {
    s += '<div style="color: '+options['barColors'][data_idx]+'">'+options['labels'][data_idx]+'</div>';
  }
  $('#'+commentidpreffix+idx).html(s);
  $('#'+commentidpreffix+idx).addClass('bar');

  return m;
}

function add_widget_area(idx,data,usroptions, ...args)
{
  var options = {
    element: contentidpreffix+idx,
    behaveLikeLine: true,
    hideHover: true,
    data: data,
    xkey: 'x',
    ykeys: [],
    labels: []
  };

  options = Object.assign({}, options, usroptions);

  var m = Morris.Area(options);

  var s = '';
  for(data_idx in options['ykeys'])
  {
    s += '<div style="color: '+options['barColors'][data_idx]+'">'+options['labels'][data_idx]+'</div>';
  }
  $('#'+commentidpreffix+idx).html(s);
  $('#'+commentidpreffix+idx).addClass('area');

  return m;

}

function add_widget_line(idx,data,usroptions, ...args)
{
  var options = {
    element: contentidpreffix+idx,
    data: data,
    xkey: 'x',
    ykeys: ['y'],
    hideHover: true,
    labels: [''],
    parseTime: false,
//    goals: [-1, 0, 1]
  };


  options = Object.assign({}, options, usroptions);

  var m = Morris.Line(options);

  return m;

}
