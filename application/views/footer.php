    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="http://cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    <script src="../bootstrap/js/bootstrap-datetimepicker.min.js"></script>
    <script src="../bootstrap/js/bootstrap-datetimepicker.zh-CN.js"></script>
    <script>
        $(document).ready(function(){
            $('.date').datetimepicker({format: 'mm-dd', autoclose: true, startDate: '2014-8-1', startView: 'month', minView : 'month',
            todayHighlight: true, language: 'zh-CN'});

            $('.time').datetimepicker({format: 'hh:ii', autoclose: true, startDate: '2014-8-1', startView: 'day', minView : 'day',
            todayHighlight: true, language: 'zh-CN'});
        });
    </script>
  </body>
</html>
