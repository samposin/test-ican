<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">

       <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">

          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-title-navbar" aria-expanded="false">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.funnels') }} ({{ count($funnels) }})</a>
          </div>

          <div class="collapse navbar-collapse" id="bs-title-navbar">

            <div class="navbar-form navbar-right">
               <button type="button" class="btn btn-success" onclick="createFunnel(false);"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('global.create_funnel') }}</button>
            </div>

          </div>
        </div>
      </nav>

    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="card-box table-responsive">
        <table class="table table-striped table-hover" id="table-funnels" style="width:100%; border: 1px solid #ddd;">
          <thead>
            <tr>
              <th>{{ trans('global.name') }}</th>
              <th style="width:120px" class="text-center">{{ trans('global.created') }}</th>
              <th style="width:74px" class="text-center">{{ trans('global.actions') }}</th>
            </tr>
          </thead>
          <tbody>
<?php
foreach ($funnels as $funnel) {
  $sl_funnel = \Platform\Controllers\Core\Secure::array2string(['funnel_id' => $funnel->id]);

  $selected = ($funnel_id == $funnel->id) ? ' &nbsp; <strong>(' . trans('global.currently_selected') . ')</strong>' : '';
?>
            <tr>
              <td>
                <div class="row-actions" data-sl="{{ $sl_funnel }}">
                  <a href="javascript:void(0);" class="link row-btn-select">{{ $funnel->name }}</a> {!! $selected !!}
                </div>
              </td>
              <td class="text-center"><span data-moment="fromNowDateTime" data-toggle="tooltip" title="{{ $funnel->created_at }}">{{ $funnel->created_at }}</span></td>
              <td class="text-center">
                <div class="row-actions-wrap">
                  <div class="text-center row-actions" data-sl="{{ $sl_funnel }}" data-name="{{ $funnel->name }}">
<?php /*                    <a href="javascript:void(0);" class="btn btn-xs btn-primary row-btn-select" data-toggle="tooltip" title="{{ trans('global.select') }}"><i class="fa fa-sign-in"></i></a>*/ ?>
                    <a href="javascript:void(0);" class="btn btn-xs btn-inverse row-btn-edit" data-toggle="tooltip" title="{{ trans('global.edit') }}"><i class="fa fa-pencil"></i></a>
                    <a href="javascript:void(0);" class="btn btn-xs btn-danger row-btn-delete" data-toggle="tooltip" title="{{ trans('global.delete') }}"><i class="fa fa-trash"></i></a>
                  </div>
                </div>
              </td>
            </tr>
<?php
}
?>
          </tbody>
       </table>
      </div>
    </div>
  </div>

</div>

<script>

$('#table-funnels').on('click', '.row-btn-delete', function() {
  var sl = $(this).parent('.row-actions').attr('data-sl');

  swal({
    title: _lang['confirm'],
    text: "{{ trans('global.confirm_delete_funnel') }}",
    type: "warning",
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#DD6B55",
    confirmButtonText: _lang['yes_delete']
  }).then(function() {
    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('platform/funnels/delete') }}",
      data: {sl: sl, _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if(data.type == 'success') {
        document.location.reload();
      } else {
        swal(data.msg);
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });
  }, function (dismiss) {
    // Do nothing on cancel
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
  });
});

$('#table-funnels').on('click', '.row-btn-select', function() {
  var sl = $(this).parent('.row-actions').attr('data-sl');
  selectFunnel(sl);
});

$('#table-funnels').on('click', '.row-btn-edit', function() {
  var sl = $(this).parent('.row-actions').attr('data-sl');
  var name = $(this).parent('.row-actions').attr('data-name');

  swal({
    title: "{{ trans('global.update_funnel') }}",
    text: "{{ trans('global.update_funnel_text') }}",
    input: 'text',
    inputValue: name,
    allowOutsideClick: false,
    allowEscapeKey: false,
    allowEnterKey: true,
    showCancelButton: true,
    cancelButtonText: _lang['cancel'],
    confirmButtonColor: "#138dfa",
    confirmButtonText: _lang['ok'],
    inputValidator: function (value) {
      return new Promise(function (resolve, reject) {
        if (value) {
          resolve()
        } else {
          reject('{{ trans('global.please_enter_value') }}')
        }
      })
    }
  }).then(function (result) {

    blockUI();

    var jqxhr = $.ajax({
      url: "{{ url('platform/funnels/edit') }}",
      data: {name: result, sl: sl, _token: '<?= csrf_token() ?>'},
      method: 'POST'
    })
    .done(function(data) {
      if (typeof data.redir !== 'undefined') {
        document.location.reload();
      } else if (typeof data.msg !== 'undefined') {
        swal(
          "{{ trans('global.oops') }}",
          data.msg,
          'error'
        )
      }
    })
    .fail(function() {
      console.log('error');
    })
    .always(function() {
      unblockUI();
    });

  }, function (dismiss) {
    // dismiss can be 'cancel', 'overlay', 'close', and 'timer'
    if (dismiss == 'cancel') {
      $('#selected_funnel').val(selected_funnel).trigger('change.select2');
    }
  });

});
</script>