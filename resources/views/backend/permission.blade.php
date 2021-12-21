@extends('master')

@section('title','Permission Setting')

@section('page_specific_css')
  <!-- page specific script will be here -->
@endsection


@section('content')

 <section class="content-header" style="margin-right: 1%;height: 50px">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Permission Setting</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Permission Setting</li>
          </ol>
        </div>
      </div>
    </div>
  </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">
  <form method="post" action="{{route('save')}}">
         {{csrf_field()}}

   <div class="card-header">
      <div class="row">
        <div class="col-6">
          <select class="form-control" name="user_type" id="user_type" required="" oninvalid="this.setCustomValidity('Select a User Type')" oninput="setCustomValidity('')">
              <option value="">Select User Type</option>
              @foreach($user_types as $user)
                <option value="{{$user->id}}">{{$user->name}}</option>
              @endforeach
          </select>
        </div>
    </div>
  </div>

   <div class="card-body table-responsive p-0" style="height: 460px;background-color: #fff">

       <table class="table table-hover table-condensed table-striped table-head-fixed text-nowrap">
        <thead style="background-color: red ">
          <tr>
            <th style="border-left:1px solid #e9dddd;background-color: #f1eeee">Master Menu</th>
            <th style="border-left:1px solid #e9dddd;background-color: #f1eeee" >Sub Menu</th>
            <th style="text-align: center;background-color: #f1eeee">Read</th>
            <th style="text-align: center;background-color: #f1eeee">Edit</th>
            <th style="text-align: center;background-color: #f1eeee">Delete</th>
            <th style="text-align: center;background-color: #f1eeee">Restore</th>
          </tr>
        </thead>

        <tbody>

        <!-- get the number of rows parrent menu wise -->
          @foreach($master_menu as $m_menu)
           @php $i=1;@endphp

            @foreach($sub_menu as $c_menu)
            @if($c_menu->sub_id == $m_menu->id)
              @php $i++; @endphp
           @endif
           @endforeach
          <!-- get the number of rows parrent menu wise end-->


           <tr>
            <td rowspan="{{$i}}" style="border:1px solid #e9dddd"> <input type="checkbox" id="assign_parent_{{$m_menu->id}}"
              value="{{$m_menu->id}}"  name="parent_menu[]" class="SelectAllSub"> <span style="margin-left:5px">{{$m_menu->menu_name}}</span></td>

              @foreach($sub_menu as $c_menu)

              @if($c_menu->sub_id == $m_menu->id )

               <!--  <td><input type="checkbox"  class="SelectedSub_{{$m_menu->id}} SelectParent" data-id="{{$m_menu->id}}" value="{{$c_menu->id}}" name="sub_menu_{{$m_menu->id}}[]" id="submenu_{{$m_menu->id}}"> {{$c_menu->menu_name}} </td> -->

                <td><input type="checkbox"  class="SelectedSub_{{$m_menu->id}} SelectParent" data-id="{{$m_menu->id}}" value="{{$c_menu->id}}" name="sub_menu_{{$m_menu->id}}[]" id="submenu_{{$c_menu->id}}"> {{$c_menu->menu_name}} </td>


                <td style="text-align: center">
                  <input type="checkbox"  id="read_{{$c_menu->id}}" class="IsRead_{{$m_menu->id}} read_{{$c_menu->id}}" value="1" name="in_read_{{$c_menu->id}}">

                </td>
                <td style="text-align: center">
                  <input type="checkbox"  id="edit__{{$c_menu->id}}" value="1" class="is_edit_{{$m_menu->id}} edit_{{$c_menu->id}}" name="in_edit_{{$c_menu->id}}">
                </td>
                <td style="text-align: center">
                  <input type="checkbox"  id="delete__{{$c_menu->id}}" value="1" class="is_del_{{$m_menu->id}} del_{{$c_menu->id}}" name="in_del_{{$c_menu->id}}">
                </td>
                <td style="text-align: center">
                  <input type="checkbox"  id="restore__{{$c_menu->id}}" value="1" class="is_res_{{$m_menu->id}} res_{{$c_menu->id}}" name="in_rest_{{$c_menu->id}}">
                </td>
              @endif
          </tr>
            @endforeach
          @endforeach
         </tbody>
       </table>

      </div>
        <div align="center" style="margin-top:5px">
        <button class="btn btn-primary" type="submit" style="background-color: red;border-color: red">Update Permission</button>
      </div>

    </form>
</div>

<aside class="control-sidebar control-sidebar-dark"></aside>
@endsection



@section('page_specific_script')

<script type="text/javascript">

    //Global variable
    var protocol = window.location.protocol;
    var hostname = window.location.hostname;
    var port = window.location.port;
    var pathname = window.location.pathname;
    pathname = pathname.split("/");
    var domainName = pathname[1];

    if(port){
      var globalURL = protocol + "//" + hostname + ":" + port + "/";
    }else{
      var globalURL = protocol + "//" + hostname + "/";
    }

 //Select all sub menu
  $(".SelectAllSub").on('click', function(){
    var menu_id=$(this).val();
    var is_check=$(this).is(':checked');
    if(is_check===true)
    {
      $(".SelectedSub_"+menu_id).prop( "checked", true );
      $(".IsRead_"+menu_id).prop( "checked", true );
      $(".is_edit_"+menu_id).prop( "checked", true );
      $(".is_del_"+menu_id).prop( "checked", true );
      $(".is_res_"+menu_id).prop( "checked", true );
    }
    else
    {
      $( ".SelectedSub_"+menu_id).prop( "checked", false );
      $( ".IsRead_"+menu_id).prop( "checked", false );
      $(".is_edit_"+menu_id).prop( "checked", false );
      $(".is_del_"+menu_id).prop( "checked", false );
      $(".is_res_"+menu_id).prop( "checked", false );
    }

  });


  $(".SelectParent").on('click', function(){
    var menu_id=$(this).val();

    var is_check=$(this).is(':checked');
    if(is_check===true)
    {
      $(".read_"+menu_id).prop( "checked", true );
      $(".edit_"+menu_id).prop( "checked", true );
      $(".del_"+menu_id).prop( "checked", true );
      $(".res_"+menu_id).prop( "checked", true );
    }
    else
    {
      $( ".read_"+menu_id).prop( "checked", false );
      $(".edit_"+menu_id).prop( "checked", false );
      $(".del_"+menu_id).prop( "checked", false );
      $(".res_"+menu_id).prop( "checked", false );
    }
  });




   $("#user_type").change(function(){

        for (var i = 1; i <=9; i++) {
         var idd ="#assign_parent_"+i;
         var present_val= $(idd).val();
          $("#assign_parent_"+i).prop( "checked", false );
          $(".SelectedSub_"+i).prop( "checked", false);
          $(".IsRead_"+i).prop( "checked", false);
          $(".is_edit_"+i).prop( "checked", false);
          $(".is_del_"+i).prop( "checked", false);
          $(".is_res_"+i).prop( "checked", false);
        }

         var user_type_id = $(this).val();

          if (user_type_id !=="") {
            var url = globalURL + "check-user-permissions/"+user_type_id;

            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response){
                  console.log(response);
                  //console.log(response.read_ids);
                  
                  

                 
                  

                /*If the user has assigned menu and sub menus*/
                if(response.hasValue==1){
                    /*Sub Menu*/
                    var sub_menu_id = response.store_submenu_id;
                    for (var i = 0; i < sub_menu_id.length; i++) {
                      var submenu_ids ="#submenu_"+sub_menu_id[i];
                      var present_val= $(submenu_ids).val();
                      if (present_val==sub_menu_id[i]) {
                        $(submenu_ids).prop( "checked", true);
                      }
                    }

                    //document.getElementById("read_10").checked = true;
                     //$("#").prop("checked",false);

                   
                   
                    //document.getElementsByClassName("read_10").style.color = "blue";
                    // var read_ids = response.read_ids;
                    // for (var j = 0; j <read_ids.length; j++) {
                    //   if(read_ids[j]==0){
                    //     console.log("Not Checked");
                    //     var iddd = read_ids[j];
                    //     $(".read_"+iddd).prop( "checked",false);
                        
                    //   }

                    //   if(read_ids[j]!=0){
                    //     var iddd = read_ids[j];
                    //     console.log(iddd);
                    //     $(".read_"+iddd).prop( "checked",true);
                    //   }


                      
                    // }


                    /*Main Menu*/
                     response.data.forEach(row =>{
                        var idd ="#assign_parent_"+row.id;
                        var present_val= $(idd).val();
                        if (present_val == row.id) {
                            $("#assign_parent_"+row.id).prop( "checked", true );
                            $(".IsRead_"+row.id).prop( "checked", true);
                            $(".is_edit_"+row.id).prop( "checked", true);
                            $(".is_del_"+row.id).prop( "checked", true);
                            $(".is_res_"+row.id).prop( "checked", true);
                        }

                     });



                  }
                  
                  /*If the user was not assigned values*/
                  if(response.hasValue==0){
                    for (var i = 1; i <=9; i++) {
                       var idd ="#assign_parent_"+i;
                       var present_val= $(idd).val();
                        $("#assign_parent_"+i).prop( "checked", false );
                        $(".SelectedSub_"+i).prop( "checked", false);
                        $(".IsRead_"+i).prop( "checked", false);
                        $(".is_edit_"+i).prop( "checked", false);
                        $(".is_del_"+i).prop( "checked", false);
                        $(".is_res_"+i).prop( "checked", false);
                     }
                  }

                },


            });

          }

    });

</script>

@endsection
