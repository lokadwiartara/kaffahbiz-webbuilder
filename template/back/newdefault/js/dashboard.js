$(document).ready(function() {
    var blogid = $('#hideblogid').val();
    var web_address = 'http://' + window.location.hostname;
    var ajaxDataRenderer = function(url, plot, options) {
        var ret = null;

        $.ajax({
            async: false,
            url: url,
            dataType: "json",

            success: function(data) {
                $('div.load').fadeOut('fast');
                $('div.chart1').fadeIn('slow');
                ret = data;
            }
        });

        return ret;

    };

    var jsonurl = web_address + '/req/getstatistic/' + blogid;

    var plot1 = $.jqplot('chart1', jsonurl, {
        title: 'Statistik Jumlah Pengunjung PerHari',
        dataRenderer: ajaxDataRenderer,
        dataRendererOptions: {
            unusedOptionalUrl: jsonurl
        },


        grid: {
            shadow: false,
            borderWidth: 0,
            borderColor: '#bbb',
            background: '#fff',
            gridLineColor: '#dfdfdf'
        },


        fontSize: "14px",
        series: [{
            color: '#fc2f03',
            shadow: true
        }],

        axes: {
            xaxis: {

                renderer: $.jqplot.DateAxisRenderer,
                labelOptions: {
                    fontFamily: 'Georgia, Serif',
                    fontSize: '12pt'
                },
                tickOptions: {
                    showGridline: false,
                    formatString: '%b %#d'
                },


            },
            yaxis: {
                tickOptions: {

                    formatString: '%.0d '
                }
            }
        },
        highlighter: {
            show: true,
            sizeAdjust: 3.5
        },
        cursor: {
            show: false
        },

    });


    getsortinfo(blogid)
});



function getsortinfo(blogid) {
    /* kirim via ajax semuanya untuk di delete */
    $.ajax('http://www.kaffah.biz/req/getsortinfo', {
      dataType : 'JSON',
      type : 'POST',
      data: {},
      beforeSend: function(){
        $('div.popup').hide();
        $('div.blackbg').show();  
        $('div.loadupload').show();         
      },
       
      success: function(data){
        $('div.loadupload').hide();       
        $('#pendingcomment').html(data.comment);
        $('#pendingorder').html(data.order);
        $('#pendingconfirmation').html(data.confirmation);
      },
    });
}