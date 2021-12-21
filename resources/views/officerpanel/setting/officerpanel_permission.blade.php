<!DOCTYPE html>
<html lang="en">
  @include('common.page-header')

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper" style="background-color: #f4f6f9">
  @include('common.preloader')

  <!-- Top navigation bar start-->
    @include('common.top-navbar')
  <!-- Top navigation bar end-->

  <!-- left navigationbar start -->
    @include('common.left-navbar')
  <!-- left navigationbar end -->

  <div class="content-wrapper">
  
  <div id="page-content" style="margin-top: 20px;margin-left: 20px">

       <!-- Alert part -->
      <div id="page-content" style="margin-top: 20px;margin-left: 20px">
          @if(Session::has('success'))
          <div class="row">
              <div class="col-sm-12">
                  <div id="alertMessage" class="alert alert-success collapse">
                       <i class="nav-icon fas fa-info-circle"></i> {{ Session::get('success') }}
                       <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-times"></i></a>
                  </div>
              </div>
          </div>
          @endif
         


          @if(Session::has('error'))
          <div class="row">
              <div class="col-sm-12">
                  <div id="alertMessage" class="alert alert-danger collapse">
                        <i class="nav-icon fas fa-exclamation-triangle"></i>  {{ Session::get('error') }}
                       <a href="#" class="close closeAlert" data-dismiss="alert"><i class="fas fa-trash-alt"></i></a>
                  </div>
              </div>
          </div>
          @endif  
      </div>   

      <!-- ./ alert part -->   
   
   <div id="page-content" style="margin-top: 20px;margin-left: 20px">
<form method="post" action="{{route('save')}}">
         {{csrf_field()}}
    <div class="row">
     <div class="col-md-3">             
        <div class="form-group">
            <label class="col-md-4 control-label" for="user_type">User Type</label>  
            <div class="col-md-12">
              <select class="form-control" name="user_type" id="user_type" required="">
                  <option value="">Select User Type</option>
                  @foreach($user_types as $user)
                    <option value="{{$user->id}}">{{$user->name}}</option>
                  @endforeach
              </select>
             </div>
        </div>
     </div>
    </div>
                
  
           
<div style="clear:both; height:10px;"></div>
 
    <div class="card-body table-responsive p-0" style="height: 480px;background-color: #fff">
      
       <table class="table table-head-fixed text-nowrap">
        <thead> 
          <tr style="background-color:#f3f0f0">
            <th style="border-left:1px solid #e9dddd">Master Menu</th>
            <th style="border-left:1px solid #e9dddd" >Sub Menu</th>
            <th style="text-align: center">Read</th>
            <th style="text-align: center">Edit</th>
            <th style="text-align: center">Delete</th>
            <th style="text-align: center">Restore</th>
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

                <td><input type="checkbox"  class="SelectedSub_{{$m_menu->id}} SelectParent" data-id="{{$m_menu->id}}" value="{{$c_menu->id}}" name="sub_menu_{{$m_menu->id}}[]" id="submenu_{{$m_menu->id}}"> {{$c_menu->menu_name}} </td>

                <td style="text-align: center">
                  <input type="checkbox"  id="read" class="IsRead_{{$m_menu->id}} read_{{$c_menu->id}}" value="1" name="in_read_{{$c_menu->id}}">

                </td>
                <td style="text-align: center">
                  <input type="checkbox"  id="edit" value="1" class="is_edit_{{$m_menu->id}} edit_{{$c_menu->id}}" name="in_edit_{{$c_menu->id}}">
                </td>
                <td style="text-align: center">
                  <input type="checkbox"  id="delete" value="1" class="is_del_{{$m_menu->id}} del_{{$c_menu->id}}" name="in_del_{{$c_menu->id}}">
                </td>
                <td style="text-align: center">
                  <input type="checkbox"  id="restore" value="1" class="is_res_{{$m_menu->id}} res_{{$c_menu->id}}" name="in_rest_{{$c_menu->id}}">
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

  </div>
  
        
  <aside class="control-sidebar control-sidebar-dark">
    
  </aside>
  
</div>
   @include('common.page-script')
   @yield('custom-script')
</body>
<script>
 
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

        //$(assign_parent_1).prop( "checked", false);
         var user_type_id = $(this).val();
          // var user_type_id = document.getElementById("user_type").value;

          if (user_type_id !=="") {
            var url = globalURL + "check-user-permissions/"+user_type_id;
            console.log(url);
            $.ajax({
                url: url,
                type: "GET",
                dataType: 'json',
                success: function(response){
                   
                   
                    var submenu_length =response.store_submenu_id.length

                 response.data.forEach(row =>{

                    // var idd ="#assign_parent_"+row.id;
                    // var present_val= $(idd).val();
                    // if (present_val == row.id) {
                    //     $("#assign_parent_"+row.id).prop( "checked", true );
                    // }

                    // for (var i = 0; i < submenu_length; i++) {
                    //     //console.log(response.store_submenu_id[i]);

                    //     var s_id = ".SelectedSub_"+row.id
                    //     var submenu_val =$(s_id).val();
                    //     console.log(submenu_val);

                    //     if (submenu_val==response.store_submenu_id[i]) {
                    //         //console.log(submenu_val);
                    //         //console.log(response.store_submenu_id[i]);
                    //          $(".SelectedSub_"+row.id).prop( "checked", true);
                    //     }
                    // }
                    var idd ="#assign_parent_"+row.id;
                    var present_val= $(idd).val();
                    if (present_val == row.id) {
                        $("#assign_parent_"+row.id).prop( "checked", true );
                        $(".SelectedSub_"+row.id).prop( "checked", true);
                        $(".IsRead_"+row.id).prop( "checked", true);
                        $(".is_edit_"+row.id).prop( "checked", true);
                        $(".is_del_"+row.id).prop( "checked", true);
                        $(".is_res_"+row.id).prop( "checked", true);
                    }

                 });

                },
            });

          }

    });

</script>
</html>