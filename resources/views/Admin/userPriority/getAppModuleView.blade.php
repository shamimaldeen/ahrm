@php use App\Http\Controllers\BackEndCon\Controller; @endphp
@if(isset($mainmenu) && count($mainmenu)>0)
@foreach ($mainmenu as $mn)
@if($mn->routeName!="user-priority-level")
	<div class="col-md-12">
	
	<label class="col-md-12"  style="background: #ccc">
		@if(Controller::checkMenuChecked($mn->id,$pr_id)=="1")
		<input type="checkbox" name="prrole_menuid[]" value="{{$mn->id}}" id="mainmenu{{$mn->id}}" onclick="return toggoleDisabled('{{$mn->id}}');" checked="checked">&nbsp;&nbsp;{{$mn->Link_Name}}
		@else
		<input type="checkbox" name="prrole_menuid[]" value="{{$mn->id}}" id="mainmenu{{$mn->id}}" onclick="return toggoleDisabled('{{$mn->id}}');">&nbsp;&nbsp;{{$mn->Link_Name}}
		@endif
	</label>
	@if(Controller::checkSubmenuExist($mn->id)=="1")
		@if(isset($submenu) && count($submenu)>0)
			@foreach ($submenu as $sb)
			@if($sb->mainmenuId==$mn->id)
				
			@if($sb->routeName=="#")
				@if(Controller::previousLabelExist($sb)=="1")
	              </div>
	              <div class="col-md-4">
	            @else
	              <div class="col-md-4">
	            @endif
				@if(Controller::checkSubmenuChecked($sb->id,$pr_id)=="1")
				<label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="prrole_submenuid[]" value="{{$mn->id}}and{{$sb->id}}" checked="checked" id="submenu{{$mn->id}}">&nbsp;&nbsp;<strong>{{$sb->submenuname}}</strong></label><br>
				@elseif(Controller::checkMenuChecked($mn->id,$pr_id)=="1")
				<label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="prrole_submenuid[]" value="{{$mn->id}}and{{$sb->id}}"  id="submenu{{$mn->id}}">&nbsp;&nbsp;<strong>{{$sb->submenuname}}</strong></label><br>
				@else
				<label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="prrole_submenuid[]" value="{{$mn->id}}and{{$sb->id}}" disabled="disabled" id="submenu{{$mn->id}}">&nbsp;&nbsp;<strong>{{$sb->submenuname}}</strong></label><br>
				@endif
				<hr style="margin: 5px">
			@else
				@if(Controller::checkSubmenuChecked($sb->id,$pr_id)=="1")
				<label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="prrole_submenuid[]" value="{{$mn->id}}and{{$sb->id}}" checked="checked" id="submenu{{$mn->id}}">&nbsp;&nbsp;{{$sb->submenuname}}</label><br>
				@elseif(Controller::checkMenuChecked($mn->id,$pr_id)=="1")
				<label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="prrole_submenuid[]" value="{{$mn->id}}and{{$sb->id}}"  id="submenu{{$mn->id}}">&nbsp;&nbsp;{{$sb->submenuname}}</label><br>
				@else
				<label>&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="prrole_submenuid[]" value="{{$mn->id}}and{{$sb->id}}" disabled="disabled" id="submenu{{$mn->id}}">&nbsp;&nbsp;{{$sb->submenuname}}</label><br>
				@endif

			@if(isset($appmodule) && count($appmodule)>0)
			@foreach ($appmodule as $appm)
			@if($sb->id==$appm->appm_submenuid)
				@if(Controller::checkAppModuleChecked($appm->appm_id,$pr_id)=="1")
				<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="apm_appmid[]" value="{{$appm->appm_id}}" checked="checked"  id="appmodule{{$mn->id}}">&nbsp;&nbsp;{{$appm->appm_name}}</label> <br>
				@elseif(Controller::checkMenuChecked($mn->id,$pr_id)=="1")
				<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="apm_appmid[]" value="{{$appm->appm_id}}" id="appmodule{{$mn->id}}">&nbsp;&nbsp;{{$appm->appm_name}}</label> <br>
				@else
				<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="apm_appmid[]" value="{{$appm->appm_id}}" disabled="disabled"  id="appmodule{{$mn->id}}">&nbsp;&nbsp;{{$appm->appm_name}}</label> <br>
				@endif
			@endif
			@endforeach
			@endif
			@if(Controller::nextLabelExist($sb)=="0")
            </div>
           	@endif
			@endif
			</label>

			@endif
			@endforeach
		@endif
	@else
		<label class="col-md-4">
			<input type="checkbox" name="prrole_submenuid[]" id="submenu{{$mn->id}}" value="{{$mn->id}}and0" style="display: none;">
			@if(isset($appmodule1) && count($appmodule1)>0)
			@foreach ($appmodule1 as $appm)
			@if($mn->id==$appm->appm_menuid)
				@if(Controller::checkAppModuleChecked($appm->appm_id,$pr_id)=="1")
				<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="apm_appmid[]" value="{{$appm->appm_id}}" checked="checked"  id="appmodule{{$mn->id}}">&nbsp;&nbsp;{{$appm->appm_name}}</label> <br>
				@elseif(Controller::checkMenuChecked($mn->id,$pr_id)=="1")
				<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="apm_appmid[]" value="{{$appm->appm_id}}"  id="appmodule{{$mn->id}}">&nbsp;&nbsp;{{$appm->appm_name}}</label> <br>
				@else
				<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="apm_appmid[]" value="{{$appm->appm_id}}" disabled="disabled"  id="appmodule{{$mn->id}}">&nbsp;&nbsp;{{$appm->appm_name}}</label> <br>
				@endif
			@endif
			@endforeach
			@endif
		</label>
	@endif
	
	</div>
	<br><br><br>
@endif
@endforeach
@endif

<script type="text/javascript">
	function toggoleDisabled(id) {
	  if($('#mainmenu'+id).is(':checked')){
            $("input#submenu"+id).attr('disabled', false);
            $("input#submenu"+id).attr('checked', true);
            $("input#appmodule"+id).attr('disabled', false);
	  }else{
	  	$("input#submenu"+id).attr('disabled', true);
	  	$("input#submenu"+id).attr('checked', false);
        $("input#appmodule"+id).attr('disabled', true);
	  }
	}
</script>