;(function ($) {

    "use strict";

    $(document).ready(function () {
        $('.rmt-form-date input').datetimepicker({
            timepicker: false,
            format:'D, F j, Y',
            scrollInput : false,
            onClose: function(obj) {
              var t = new Date(obj);
              var m =  t.getMonth() + 1;
              var m_zeroe = '';
              var d_zeroe = '';
              if(t.getMonth() < 10) {
                var m_zeroe = '0';
              }
              if(t.getDate() < 10) {
                var d_zeroe = '0';
              }
              var str = t.getFullYear() + '-' + m_zeroe + m + '-' + d_zeroe + t.getDate();
              var time = $('#rmt-open-table-time').val();
              $('[name="dateTime"]').val(str + 'T' + time);
            }
        });
        
        var rmt_day_name = ["Sun","Mon","Tue","Wed","Thu","Fri","Sat"];
        var rmt_month_name = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        function rmt_date_today(d) {
          var t = new Date(d);
          return rmt_day_name[t.getDay()] + ', ' + rmt_month_name[t.getMonth()] + ' ' + t.getDate() + ', ' + t.getFullYear();
        }

        function rmt_opentable_date_today(d) {
          var t = new Date(d);
          var m =  t.getMonth() + 1;
          var m_zeroe = '';
          var d_zeroe = '';
          if(t.getMonth() < 10) {
            var m_zeroe = '0';
          }
          if(t.getDate() < 10) {
            var d_zeroe = '0';
          }
          return t.getFullYear() + '-' + m_zeroe + m + '-' + d_zeroe + t.getDate();
        }

        var rmt_date_today_result = rmt_date_today(new Date());
        $('.rmt-form-date input').val(rmt_date_today_result);

        var rmt_opentable_date_today_result = rmt_opentable_date_today(new Date());
        var time = $('#rmt-open-table-time').val();
        $('[name="dateTime"]').val(rmt_opentable_date_today_result + 'T' + time);


        $('#rmt-open-table-time').on('change', function(e) {
            e.preventDefault();
            var time_up = $(this).val();
            $('[name="dateTime"]').val(rmt_opentable_date_today_result + 'T' + time_up);
        });

    });

})(jQuery);