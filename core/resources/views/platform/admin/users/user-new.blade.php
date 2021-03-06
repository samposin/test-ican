<div class="container">

  <div class="row m-t">
    <div class="col-sm-12">
      <nav class="navbar navbar-default card-box sub-navbar">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.admin') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand link" href="#/admin/users">{{ trans('global.users') }}</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">\</a>
            <a class="navbar-brand no-link" href="javascript:void(0);">{{ trans('global.create_user') }}</a>
          </div>
        </div>
      </nav>
    </div>
  </div>

  <div class="row">
    <form class="ajax" id="frm" method="post" action="{{ url('platform/admin/user/new') }}">
      {!! csrf_field() !!}
      <div class="col-md-6">
<?php if (\Gate::allows('owner-management') || \Gate::allows('admin-management')) { ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.reseller') }}</h3>
          </div>
          <fieldset class="panel-body">
<?php if (\Gate::allows('owner-management')) { ?>
            <div class="form-group">
<?php
                  $resellers_list = Former::select('reseller_id')
                    ->class('select2-required form-control')
                    ->name('reseller_id')
                    ->forceValue(\Platform\Controllers\Core\Reseller::get()->id)
                    ->fromQuery($resellers, 'name', 'id')
                    ->label('');
 
                  echo $resellers_list;
?>
            </div>
<?php } ?>
<?php if (\Gate::allows('admin-management')) { ?>
            <div class="form-group">
<?php
                  $plans_list = Former::select('plan_id')
                    ->addOption(trans('global.free'), null)
                    ->class('select2-required form-control')
                    ->name('plan_id')
                    ->options($plans_list)
                    ->forceValue($default_plan->id)
                    ->label(trans('global.plan'));
 
                  echo $plans_list;
?>
            </div>
<?php } ?>

<?php
    //if (\Gate::allows('owner-management')) {
    if (\Gate::allows('reseller-management')) {
    ?>
<?php
$field_name = 'trial_ends_at';
$datetime_value = \Carbon\Carbon::now()->addDays(14)->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');

$date_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('D M jS Y');
$date_data_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('Y-m-d');

$time_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('H:i');
$time_data_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('H:i');
?>
              <div class="form-group">
                <input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $datetime_value }}">
                <label>{{ trans('global.trial_expires') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.trial_expires_help') }}">&#xE887;</i></label>
                <div class="input-group" style="width: 100%">
                  <input type="text" class="form-control" id="{{ $field_name }}_date" value="{{ $date_value }}" data-value="{{ $date_data_value }}">
                  <span class="input-group-addon b-0">@</span>
                  <input type="text" class="form-control" id="{{ $field_name }}_time" value="{{ $time_value }}" data-value="{{ $time_data_value }}">
                </div>
              </div>

<script>
  /* Date picker */
  $('#{{ $field_name }}_date').datepicker({
    autoclose: true,
    todayHighlight: true,
    orientation: 'top',
    format: {
      toDisplay: function (date, format, language) {
        $('#{{ $field_name }}_date').attr('data-value', moment(date).format('YYYY-MM-DD'));
        return moment(date).format('ddd MMM Do YYYY');
      },
      toValue: function (date, format, language) {
        var d = new Date($(this).attr('data-value'));
        return new Date(d);
      }
    }
  }).on('show', function(e) {
    $('.datepicker-orient-top, .datepicker-orient-bottom').css({'margin-top':'54px'});
  }).on('changeDate', function(e) {
    setDate{{ $field_name }}();
  });

  /* Time picker */
  $('#{{ $field_name }}_time').timepicker({
    minuteStep: 5,
    appendWidgetTo: 'body',
    showSeconds: false,
    showMeridian: true,
    showInputs: false,
    defaultTime: null
  }).on('changeTime.timepicker', function(e) {
    var hours = (e.time.meridian == 'PM') ? parseInt(e.time.hours) + 12: e.time.hours;
    hours = (parseInt(hours) < 10) ? '0' + hours : hours;
    var minutes = (parseInt(e.time.minutes) < 10) ? '0' + e.time.minutes : e.time.minutes;
    var time = hours + ':' + minutes;

    $('#{{ $field_name }}_time').attr('data-value', time);

    setDate{{ $field_name }}();
  });

  $('#{{ $field_name }}_date,#{{ $field_name }}_time').on('change', function() {
    if ($('#{{ $field_name }}_date').val() == '' && $('#{{ $field_name }}_time').val() == '') $('#{{ $field_name }}').val('');
  });

  function setDate{{ $field_name }}() {
    if ($('#{{ $field_name }}_date').attr('data-value') != '' && $('#{{ $field_name }}_time').attr('data-value') != '') {
      $('#{{ $field_name }}').val($('#{{ $field_name }}_date').attr('data-value') + ' ' + $('#{{ $field_name }}_time').attr('data-value') + ':00');
    }
  }
</script>



<?php
$field_name = 'expires';
$datetime_value = '';

$date_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('D M jS Y');
$date_data_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('Y-m-d');

$time_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('H:i');
$time_data_value = ($datetime_value == null) ? '' : \Carbon\Carbon::parse($datetime_value)->timezone(\Auth::user()->timezone)->format('H:i');
?>
              <div class="form-group">
                <input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $datetime_value }}">
                <label>{{ trans('global.subscription_expires') }} <i class="material-icons help-icon" data-container="body" data-trigger="hover" data-toggle="popover" data-placement="top" data-content="{{ trans('global.subscription_expires_help') }}">&#xE887;</i></label>
                <div class="input-group" style="width: 100%">
                  <input type="text" class="form-control" id="{{ $field_name }}_date" value="{{ $date_value }}" data-value="{{ $date_data_value }}">
                  <span class="input-group-addon b-0">@</span>
                  <input type="text" class="form-control" id="{{ $field_name }}_time" value="{{ $time_value }}" data-value="{{ $time_data_value }}">
                </div>
              </div>

<script>
  /* Date picker */
  $('#{{ $field_name }}_date').datepicker({
    autoclose: true,
    todayHighlight: true,
    orientation: 'top',
    format: {
      toDisplay: function (date, format, language) {
        $('#{{ $field_name }}_date').attr('data-value', moment(date).format('YYYY-MM-DD'));
        return moment(date).format('ddd MMM Do YYYY');
      },
      toValue: function (date, format, language) {
        var d = new Date($(this).attr('data-value'));
        return new Date(d);
      }
    }
  }).on('show', function(e) {
    $('.datepicker-orient-top, .datepicker-orient-bottom').css({'margin-top':'54px'});
  }).on('changeDate', function(e) {
    setDate{{ $field_name }}();
  });

  /* Time picker */
  $('#{{ $field_name }}_time').timepicker({
    minuteStep: 5,
    appendWidgetTo: 'body',
    showSeconds: false,
    showMeridian: true,
    showInputs: false,
    defaultTime: null
  }).on('changeTime.timepicker', function(e) {
    var hours = (e.time.meridian == 'PM') ? parseInt(e.time.hours) + 12: e.time.hours;
    hours = (parseInt(hours) < 10) ? '0' + hours : hours;
    var minutes = (parseInt(e.time.minutes) < 10) ? '0' + e.time.minutes : e.time.minutes;
    var time = hours + ':' + minutes;

    $('#{{ $field_name }}_time').attr('data-value', time);

    setDate{{ $field_name }}();
  });

  $('#{{ $field_name }}_date,#{{ $field_name }}_time').on('change', function() {
    if ($('#{{ $field_name }}_date').val() == '' && $('#{{ $field_name }}_time').val() == '') $('#{{ $field_name }}').val('');
  });

  function setDate{{ $field_name }}() {
    if ($('#{{ $field_name }}_date').attr('data-value') != '' && $('#{{ $field_name }}_time').attr('data-value') != '') {
      $('#{{ $field_name }}').val($('#{{ $field_name }}_date').attr('data-value') + ' ' + $('#{{ $field_name }}_time').attr('data-value') + ':00');
    }
  }
</script>

<?php } ?>
          </fieldset>
        </div>
<?php } ?>
<?php if (\Gate::allows('reseller-management')) { ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.reseller') }}</h3>
          </div>
          <fieldset class="panel-body">

<?php if (\Gate::allows('reseller-management')) { ?>
            <div class="form-group">
<?php
                  $admins_list = Former::select('admin_id')
                    ->class('select2-required form-control')
                    //->placeholder('Select one option...')
                    ->addOption('Please Select Admin')
                    ->name('admin_id')
                    ->fromQuery($admins, 'name', 'id')
                    ->label(trans('global.admin'));

                  echo $admins_list;
?>
            </div>
<?php } ?>
          </fieldset>
        </div>
<?php } ?>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.general') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
              <label for="name">{{ trans('global.name') }} <sup>*</sup></label>
              <input type="text" class="form-control" name="name" id="name" value="" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="email">{{ trans('global.email_address') }} <sup>*</sup></label>
              <input type="email" class="form-control" name="email" id="email" value="" required autocomplete="off">
            </div>
            <div class="form-group">
              <label for="password">{{ trans('global.password') }} <sup>*</sup></label>
              <div class="input-group">
                <input type="password" class="form-control" id="password" name="password" required autocomplete="off">
                <div class="input-group-btn add-on">
                  <button class="btn btn-inverse" type="button" id="show_password" data-toggle="tooltip" title="{{ trans('global.show_hide_password') }}"><i class="fa fa-eye" aria-hidden="true"></i></button>
                  <button class="btn btn-inverse" type="button" id="generate_password" data-toggle="tooltip" title="{{ trans('global.generate_password') }}"><i class="fa fa-random" aria-hidden="true"></i></button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="mail_login" id="mail_login" type="checkbox" value="1" checked>
                <label for="mail_login"> {{ trans('global.mail_login') }}</label>
              </div>
            </div>
            <?php
                if (\Gate::allows('owner-management')) {
            ?>

            <div class="form-group">
              <div class="checkbox checkbox-primary">
                <input name="active" id="active" type="checkbox" value="1" checked>
                <label for="active"> {{ trans('global.active') }}</label>
              </div>
              <p class="text-muted">{{ trans('global.active_user_desc') }}</p>
            </div>
            <?php
                }
            ?>
            <div class="form-group">
              <label for="metatag">{{ trans('global.metatag') }}</label>
                <input type="text" class="form-control" id="metatag" name="metatag">
            </div>
            <div class="form-group">
              <label for="business_name">{{ trans('global.business_name') }}</label>
                <input type="text" class="form-control" id="business_name" name="business_name">
            </div>
            <div class="form-group">
              <label for="manager_name">{{ trans('global.manager_name') }}</label>
                <input type="text" class="form-control" id="manager_name" name="manager_name">
            </div>
            <div class="form-group">
              <label for="store_number">{{ trans('global.store_number') }}</label>
                <input type="text" class="form-control" id="store_number" name="store_number">
            </div>
            <div class="form-group">
              <label for="address">{{ trans('global.address') }}</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="form-group">
              <label for="business_type">{{ trans('global.business_type') }}</label>
                <input type="text" class="form-control" id="business_type" name="business_type">
            </div>
            <div class="form-group">
              <label for="phone">{{ trans('global.main_phone_number_1') }}</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <div class="form-group">
              <label for="phone2">{{ trans('global.phone_number_2') }}</label>
                <input type="text" class="form-control" id="phone2" name="phone2">
            </div>
            <div class="form-group">
              <label for="phone3">{{ trans('global.phone_number_3') }}</label>
                <input type="text" class="form-control" id="phone3" name="phone3">
            </div>
            <div class="form-group">
              <label for="email_2">{{ trans('global.email_2') }}</label>
                <input type="email" class="form-control" id="email_2" name="email_2">
            </div>
            <div class="form-group">
              <label for="email_3">{{ trans('global.email_3') }}</label>
                <input type="email" class="form-control" id="email_3" name="email_3">
            </div>
            <div class="form-group">
              <label for="website_1">{{ trans('global.website_1') }}</label>
                <input type="text" class="form-control" id="website_1" name="website_1">
            </div>
            <div class="form-group">
              <label for="website_2">{{ trans('global.website_2') }}</label>
                <input type="text" class="form-control" id="website_2" name="website_2">
            </div>
            <div class="form-group">
              <label for="facebook_url">{{ trans('global.facebook_page') }}</label>
                <input type="text" class="form-control" id="facebook_url" name="facebook_url">
            </div>
            <div class="form-group">
              <label for="instagram_url">{{ trans('global.instagram_page') }}</label>
                <input type="text" class="form-control" id="instagram_url" name="instagram_url">
            </div>
            <div class="form-group">
              <label for="linked_in_url">{{ trans('global.linked_in_page') }}</label>
                <input type="text" class="form-control" id="linked_in_url" name="linked_in_url">
            </div>
            <div class="form-group">
              <label for="youtube_url">{{ trans('global.youtube_page') }}</label>
                <input type="text" class="form-control" id="youtube_url" name="youtube_url">
            </div>
            <div class="form-group">
              <label for="notes">{{ trans('global.notes') }}</label>
                 <textarea class="form-control" rows="3" id="notes" name="notes"></textarea>
            </div>

          </fieldset>
        </div>

        <div class="panel panel-inverse panel-border">
        <div class="panel-heading"></div>
          <div class="panel-body">
            <a href="#/admin/users" class="btn btn-lg btn-default waves-effect waves-light w-md">{{ trans('global.back') }}</a>
            <button class="btn btn-lg btn-success waves-effect waves-light w-md ladda-button" type="submit" data-style="expand-right"><span class="ladda-label">{{ trans('global.create') }}</span></button>
          </div>
        </div>
      </div>
      <!-- end col -->
      
      <div class="col-md-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.role') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
<?php
                  $roles = Former::select('role')
                    ->class('select2-required form-control')
                    ->name('role')
                    ->forceValue('user')
                    ->options(trans('global.user_roles'))
                    ->label('');
 
                  echo $roles;
?>
            </div>
          </fieldset>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">{{ trans('global.localization') }}</h3>
          </div>
          <fieldset class="panel-body">
            <div class="form-group">
              <?php
                  echo Former::select('language')
                    ->class('select2-required form-control')
                    ->name('language')
                    ->forceValue($reseller->default_language)
                    ->options(\Platform\Controllers\Core\Localization::getLanguagesArray())
                    ->label(trans('global.language'));
                  ?>
            </div>
            <div class="form-group">
              <?php
                  echo Former::select('timezone')
                    ->class('select2-required form-control')
                    ->name('timezone')
                    ->forceValue($reseller->default_timezone)
                    ->options(trans('timezones.timezones'))
                    ->label(trans('global.timezone'));
                  ?>
            </div>
          </fieldset>
        </div>
      </div>
      <!-- end col -->
      
    </form>
  </div>
  <!-- end row --> 
  
</div>
<script>
    <?php if (\Gate::allows('owner-management')) { ?>
    $(document).ready(function(){

        $('#reseller_id').on('change', function() {

            var reseller_id=this.value;

            var jqxhr = $.ajax({
                url: "{{ url('platform/admin/users/admin-role/reseller/') }}/"+reseller_id,
                data: {user_id: 0},
                method: 'GET',
                dataType:'json'
            })
            .done(function(data) {

                $("#admin_id").empty();
                $("#admin_id").append('<option value="">Please Select Admin</option>');

                if(data.success)
                {
                    for(var i=0;i<data.info.data.length;i++)
                    {
                        $("#admin_id").append('<option value="'+data.info.data[i].id+'">'+data.info.data[i].name+'</option>')
                    }
                }

                $("#admin_id").trigger('change');
                //$("#admin_id").empty().append('<option value="id">text</option>').val('id').trigger('change');
            })
            .fail(function() {
                console.log('error');
            })
            .always(function() {
                //unblockUI();
            });
        });
    });
    <?php } ?>

  $('#show_password').on('click', function()
  {
    if(! $(this).hasClass('active'))
    {
      $(this).addClass('active');
      togglePassword('password', 'form-control', true);
    }
    else
    {
      $(this).removeClass('active');
      togglePassword('password', 'form-control', false);
    }
  });
  
  $('#generate_password').on('click', function()
  {
    $('#password').val(randomString(8));
  });    
</script>