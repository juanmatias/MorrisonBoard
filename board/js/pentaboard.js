var widgets = {};
var resetkey = null;
/* panels name,id,cols,type, data, options */
var paneidpreffix = 'pane_';
var contentidpreffix = 'content_';
var titleidpreffix = 'title_';
var commentidpreffix = 'comment_';


$(document).ready(function()
{

  $("#connecting").removeClass('show').addClass('hide');
  // add_panes(panels);

  $(document).ajaxStart(function(){
    $("#connecting").removeClass('hide').addClass('show');
  });
  $(document).ajaxComplete(function(){
    $("#connecting").removeClass('show').addClass('hide');
  });

  // setTimeout(update_widgets,500);
  poll_server();

}); // document Ready


/* ************************************************************************************************** */
function poll_server()
{
  $('#message').html('').removeClass('show').addClass('hide');
  $.ajax(
  {
     data:{apiKey:morris_apiKey},
     url:  morris_url,
     type:  'get',
     dataType: "json",
     async:false
  }).done(function(data){
    if(data.code != 0)
    {
      $('#message').html('Error: '+data.error).removeClass('hide').addClass('show');
      console.log('error '+data.error);
    }else
    {
      if(resetkey==null)
      {
        resetkey = data.response.shift();
      }else
      {
        if(resetkey == data.response[0])
        {
          data.response.shift();
        }else
        {
          $('#message').html('Reloading page on server request...').removeClass('hide').addClass('show');
          window.location.reload(true);
        }
      }
      add_panes(data.response);
    }

    setTimeout(poll_server,morris_poll_timeout);

  }).fail(function(){
    $('#message').html('Cannot connect to server, polling in '+(morris_poll_timeout * 2 / 1000)+' seconds.').removeClass('hide').addClass('show');
    setTimeout(poll_server,morris_poll_timeout * 2);
  });

}

function add_panes(panels)
{
  /* Add panes */
  //<div id="pane1" class="wcontainer col1"><div id='title_x'></div><div id='content_x'></div><div id='comment_x'></div></div>

  for (var x = 0; x < panels.length; x ++)
  {

    //panels[x][6] is "action" 1:draw, 2:delete
    if(panels[x][6] == 1)
    {
      //if pane does not exist then create it
      if($('#'+paneidpreffix+panels[x][1]).length <= 0)
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
      }
      add_widget(panels[x]);
    }else if($('#'+paneidpreffix+panels[x][1]).length > 0 && panels[x][6] == 0) //delete
    {
      //delete morrison object from array
        if(widgets[panels[x][1]] != undefined)
        {
          delete widgets[panels[x][1]];
        }
      //delete pane
      $('#'+paneidpreffix+panels[x][1]).remove();
    }
  }

}

function add_widget(panel)
{
    switch (panel[3]) {
      case 'donut':
        add_widget_donut(panel[1],panel[4],panel[5]);
      break;
      case 'pie':
          add_widget_pie(panel[1],panel[4],panel[5]);
        break;
      case 'stackedbars':
          add_widget_bar(panel[1],panel[4],panel[5],true);
        break;
      case 'bars':
          add_widget_bar(panel[1],panel[4],panel[5],false);
        break;
      case 'area':
          add_widget_area(panel[1],panel[4],panel[5]);
        break;
      case 'line':
          add_widget_line(panel[1],panel[4],panel[5]);
        break;
      case 'text':
          add_widget_text(panel[1],panel[4],panel[5]);
        break;
      default:

    }

}



/* ********************************************************************************************************* */
function add_widget_donut(idx,data,usroptions, ...args)
{
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
  if(widgets[idx]==undefined)
  {

    var m = Morris.Donut(options);

    widgets[idx] = m;
  }else
  {
    widgets[idx].setData(data);
  }
  var s = '';
  $('#'+commentidpreffix+idx).html(s);
  for(data_idx in data)
  {
    s += '<div style="color: '+options['colors'][data_idx]+'"><strong>'+data[data_idx].label+'</strong> '+data[data_idx].value+unit+'</div>';
  }
  $('#'+commentidpreffix+idx).html(s);
  $('#'+commentidpreffix+idx).addClass('donut');

}

function add_widget_pie(idx,data,usroptions, ...args)
{
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
  if(widgets[idx]==undefined)
  {

    var m = Morris.Pie(options);

    widgets[idx] = m;
  }else
  {
    widgets[idx].setData(data);

  }

  var s = '';
  $('#'+commentidpreffix+idx).html(s);
  for(data_idx in data)
  {
    s += '<div style="color: '+options['colors'][data_idx]+'"><strong>'+data[data_idx].label+'</strong> '+data[data_idx].value+unit+'</div>';
  }
  $('#'+commentidpreffix+idx).html(s);
  $('#'+commentidpreffix+idx).addClass('pie');

}

function add_widget_bar(idx,data,usroptions, ...args)
{
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
  if(widgets[idx]==undefined)
  {

    var m = Morris.Bar(options);

    widgets[idx] = m;
  }else
  {
    widgets[idx].setData(data);

  }

  var s = '';
  $('#'+commentidpreffix+idx).html(s);
  for(data_idx in options['ykeys'])
  {
    s += '<div style="color: '+options['barColors'][data_idx]+'">'+options['labels'][data_idx]+'</div>';
  }
  $('#'+commentidpreffix+idx).html(s);
  $('#'+commentidpreffix+idx).addClass('bar');

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
  if(widgets[idx]==undefined)
  {
    var m = Morris.Area(options);

    widgets[idx] = m;
  }else
  {
    widgets[idx].setData(data);

  }

  var s = '';
  $('#'+commentidpreffix+idx).html(s);
  for(data_idx in options['ykeys'])
  {
    s += '<div style="color: '+options['barColors'][data_idx]+'">'+options['labels'][data_idx]+'</div>';
  }
  $('#'+commentidpreffix+idx).html(s);
  $('#'+commentidpreffix+idx).addClass('area');


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
  if(widgets[idx]==undefined)
  {

    var m = Morris.Line(options);

    widgets[idx] = m;
  }else
  {
    widgets[idx].setData(data);

  }


}

function add_widget_text(idx,data,usroptions, ...args)
{
  var options = {
    element: contentidpreffix+idx,
    data: data,
    backgroundColor: '#ccc',
    labelColor: '#060',
    auto_text_size: false,
    auto_text_size_max: 50,
    auto_text_size_min: 5,
    auto_text_size_margin: 10,
  };
  options = Object.assign({}, options, usroptions);
  if(widgets[idx]==undefined)
  {
    var m = {
      options: options,
      container: null,
      mytextdiv: null,
      text: function ()
      {
        if(this.container == null)
        {
          this.container = $('#'+this.options.element);
          if($(this.container).length <= 0)
          {
            return false;
          }
        }
        var style = 'color: '+this.options.labelColor+';padding: 0.1em 0.3em;font-size: 1.5em;line-height: 150%;font-weight: 700;text-align: center;';

        if(this.options.auto_text_size)
        {
          style += ' font-size:'+this.options.auto_text_size_min+'px';
        }
        for(x in this.options.data)
        {
          this.mytextdiv = $('<div/>', {
              class: 'morrison_text',
              style: style,
            }).html((this.options.data[x]['x']!=null?this.options.data[x]['x']:'')).appendTo(this.container);
        }
        if(this.options.auto_text_size)
        {
          this.autoSizeText();
        }


      },
      setData: function (data)
      {
        $(this.container).html('');
        this.options.data = data;
        this.text();
      },
      autoSizeText: function()
      {
        $(this.mytextdiv).css({'float':'left'});
        var cwidth = $(this.container).width();
        var mwidth = $(this.mytextdiv).width();
        var font_size = this.options.auto_text_size_min;
        while(mwidth < (cwidth - (this.options.auto_text_size_margin * 2)) && font_size <= this.options.auto_text_size_max)
        {
          font_size++;
          $(this.mytextdiv).css({'font-size':font_size+'px'});
          mwidth = $(this.mytextdiv).width();
        }
        $(this.mytextdiv).css({'float':'none'});
      }
    };
    m.text();
    widgets[idx] = m;
  }else
  {
    widgets[idx].setData(data);

  }
}
