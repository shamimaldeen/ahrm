<div class="row">
<div class="col-md-12">
@include('error.msg')
<!-- BEGIN EXAMPLE TABLE PORTLET-->
<div class="portlet light bordered">

      <div class="portlet-title">
        <div class="col-md-10">
          <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase" id="hidden_table_title"> {!!$info['page_title']!!}</span>
          </div>
        </div>

        <div class="col-md-2">
            <div class="actions pull-right">
              <div class="btn-group">
                  <a class="btn red btn-outline btn-circle" href="javascript:;" data-toggle="dropdown">
                      <i class="fa fa-gears"></i>
                      <span> Tools </span>
                      <i class="fa fa-angle-down"></i>
                  </a>
                  <ul class="dropdown-menu pull-right" id="sample_3_tools">
                      <li>
                          <a href="javascript:;" data-action="0" class="tool-action">
                              <i class="icon-printer"></i> Print</a>
                      </li>

                      <li>
                          <a href="javascript:;" data-action="2" class="tool-action">
                              <i class="icon-doc"></i> PDF</a>
                      </li>
                      <li>
                          <a href="javascript:;" data-action="3" class="tool-action">
                              <i class="icon-paper-clip"></i> Excel</a>
                      </li>

                      </li>
                  </ul>
              </div>
            </div>
          </div>
          <div class="tools"> </div>
      </div>

      <div class="portlet-body">
        <form class="form-horizontal" method="post" enctype="multipart/form-data" action="{{URL::to('')}}" id="data_form">
        {{ csrf_field() }}
         <table class="table table-striped table-bordered table-hover" id="sample_3">
            <thead>
              {!! $thead !!}
            </thead>

            <tbody>
              {!! $data !!}
            </tbody>
         </table>
       </form>
      </div>


</div>

<script type="text/javascript">
 $(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

</script>

@if($info['chart_show'])

@if(isset($info['chart_title']) &&
isset($info['chart_short_title']) && isset($info['chart_num_datasets'])&& isset($info['chart_datasets'])&& isset($info['chart_levels']) && isset($info['chart_data']) && isset($info['chart_backgroundColor']) && isset($info['chart_borderColor']))

<div class="portlet light bordered">
  <!-- @include('Admin.report.chart') -->
</div>

@endif

@endif

</div>
</div>

<script>
  function getAttendanceSummary(att_empid,total_working_hour) {
    $.dialog({
        title: 'Entry & Exit Summary',
        content: "url:{{URL::to('getAttendanceSummary')}}/"+att_empid+"/"+total_working_hour,
        animation: 'scale',
        columnClass: 'medium',
        closeAnimation: 'scale',
        backgroundDismiss: true,
    });
}

function getAttendanceSummarySearch(att_empid,total_working_hour,date) {
    $.dialog({
        title: 'Entry & Exit Summary',
        content: "url:{{URL::to('getAttendanceSummarySearch')}}/"+att_empid+"/"+total_working_hour+"/"+date,
        animation: 'scale',
        columnClass: 'medium',
        closeAnimation: 'scale',
        backgroundDismiss: true,
    });
}
</script>