<style>
    div {
        word-wrap: break-word !important;
    }
</style>
<nav class="main-header navbar navbar-expand navbar-white navbar-light " style="background-color:#ee1b22">

  <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #fff"><i class="fas fa-bars"></i></a>
      </li>

      <li>
        <a data-widget="pushmenu" href="#" style="font-size: 32px;color: #fff; margin-left:10px">

          <?php
            $user_type_id = auth()->user()->user_type_id;
            if(is_null($user_type_id)!=1){

              if ($user_type_id==1) {
                echo "Bank Panel";
              }
              else if ($user_type_id==2) {
                  $school_id = auth()->user()->school_id;
                  $school_info = DB::select(DB::raw("SELECT school_name FROM school_infos WHERE id = $school_id"));
                  echo $school_info[0]->school_name;
              }

              else if ($user_type_id==3) {
                echo "Guardian Panel";
              }
              else if ($user_type_id==4) {
                echo "Bank Teller Panel";
              }
              else if ($user_type_id==5) {
                echo "Bank Agent Panel";
              }
              else if ($user_type_id==6) {
                echo "Bank Officer Panel";
              }
              else if ($user_type_id==10) {
                  echo "School Account's Panel";
              }

            }
          ?>

         </a>
      </li>
  </ul>

  <ul class="navbar-nav ml-auto">

      <?php
        $total_notice=0;
        $user_type_id = auth::user()->user_type_id;

       /*Guardian Panel Start*/
       if ($user_type_id==3) {
       $current_user_id = auth::user()->id;
       $sibling_students = DB::select(DB::raw("SELECT * FROM siblings WHERE user_id=$current_user_id"));
       $count_student = count($sibling_students);


       if ($count_student>0) {

        /*store the corresponding guardian students classes in the student_class array*/
        $student_class = array();
        for ($i=0; $i <$count_student ; $i++) {
            $id = (int)($sibling_students[$i]->class_id);
            array_push($student_class,$id);
        }

        /*Get the notices/notification from  all_notifications table of the corresponding class(es)*/
        $all_notice = DB::table('all_notifications')
                     ->whereIn('class_id', $student_class)
                     ->orderBy('id', 'desc')
                     ->limit(6)
                     ->get();

        $total_notice =count($all_notice);
       }

      }
      /*Guardian Panel End*/



      /*School Panel start*/
      if ($user_type_id==2) {
         $current_school_id = auth::user()->school_id;
         $school_all_notice = DB::table('all_notifications')
                     ->where('school_id',$current_school_id)
                     ->orderBy('id', 'desc')
                     ->limit(6)
                     ->get();
        $school_total_notice = count($school_all_notice);
      }
      /*School Panel end*/



      /*Super Admin Panel start*/
      if ($user_type_id==1) {
        $sa_notification = DB::table('all_notifications')
                     ->orderBy('id', 'desc')
                     ->limit(6)
                     ->get();
        $sa_total_notice = count($sa_notification);
      }
      /*Super Admin Panel end*/

      ?>


      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" style="color: #fff;">
          <i class="far fa-bell" color="#fff"></i>
          <span class="badge badge-warning navbar-badge" style="color: #fff;">
           <?php
            if($user_type_id==3){
              echo $total_notice;
            }

            if($user_type_id==2){
              echo $school_total_notice;
            }

            if($user_type_id==1){
              echo $sa_total_notice;
            }

             if($user_type_id==4){
              echo "0";
            }

          ?>
          </span>
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">
            <?php
              if($user_type_id==3){
                echo  $total_notice."-Notifications";
              }

              if($user_type_id==2){
                echo  $school_total_notice."-Notifications";
              }

              if($user_type_id==1){
                echo  $sa_total_notice."-Notifications";
              }

               if($user_type_id==4){
                echo  "No-Notifications";
              }

            ?>
          </span>
          <div class="dropdown-divider"></div>


            <!--
              *Please donot modify any code of below section
             -->


            <!-- Super Admin Panel -->
            @if(isset($sa_notification) && $user_type_id==1)
              @foreach($sa_notification as $superadmin_notification)
                <a href="javascript:void(0)" class="tooltip-button dropdown-item" style="overflow: hidden" onclick="showNotificationDetails({{$superadmin_notification->id}})">
                    <i class="fas fa-envelope mr-2"></i>{{$superadmin_notification->notification_title}}
                </a>
               <div class="dropdown-divider"></div>
              @endforeach
            @endif


            <!-- School Panel -->
            @if(isset($school_all_notice) && $user_type_id==2)
                @foreach($school_all_notice as $schoolnotification)
                  <a href="javascript:void(0)"class="tooltip-button dropdown-item" style="overflow: hidden" onclick="showNotificationDetails({{$schoolnotification->id}})">
                    <i class="fas fa-envelope mr-2"></i>
                    {{$schoolnotification->notification_title}}
                  </a>
              <div class="dropdown-divider"></div>
              @endforeach
            @endif

            <!-- Guardian Panel -->
            @if(isset($all_notice) && $user_type_id==3)
            @foreach($all_notice as $guardianNotice)
                <a href="javascript:void(0)" class="tooltip-button dropdown-item" style="overflow: hidden" onclick="showNotificationDetails({{$guardianNotice->id}})">
                    <i class="fas fa-envelope mr-2"></i>{{$guardianNotice->notification_title}}
                </a>
              <div class="dropdown-divider"></div>
              @endforeach
            @endif


            <!--
              *Please donot modify any code of above section
            -->


          <?php
            $user_id = auth::user()->user_type_id;

            /*If the user type is Guardian Panel then only work this routes*/
            if($user_id==3 && $total_notice>0){
              echo "<a href='/guardian-notice' class='dropdown-item dropdown-footer'>See All Notifications</a>";
            }

             /*If the user type is Guardian Panel then only work this routes*/
            if($user_id==2 && $school_total_notice>0){
              echo "<a href='/school-wise-notice' class='dropdown-item dropdown-footer'>See All Notifications</a>";
            }

            /*If the user type is Super Admin then only work this routes */
            if($user_id==1 && $sa_total_notice>0 ){
               echo "<a href='/all_notification' class='dropdown-item dropdown-footer'>See All Notifications</a>";
            }

          ?>

        </div>
      </li>


      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#" style="color: #fff;">
           <?php
              $user_info = Auth::user();
              $user_id = $user_info->id;

              $user= DB::select( DB::raw("SELECT email,mobile_number  FROM  users WHERE id = $user_id"));
              $user_email = $user[0]->email;
              $user_mobile = $user[0]->mobile_number;

              $user_profile=App\Models\UserProfile::select('user_id','profile_img')->where('user_id',$user_id)->first();
              if ($user_profile != null && $user_profile->profile_img !=null) {
                $img_path = '/storage/profile_img/'. $user_profile->profile_img;
              }
              else{
                $img_path = asset('default_profile_img.png');
              }
           ?>

            <img src="{{asset($img_path)}}" class="img-circle" alt="profile picture" width="30px" height="30px">
            <span style="margin-left:5px">{{$user_info->name}}</span>
            <i class="fa fa-caret-down" aria-hidden="true"></i>
        </a>

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-default-view-profile">
                <i class="fas fa-eye mr-2"></i> View Profile
            </a>
          <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-default-edit-profile">
            <i class="fas fa-user-edit mr-2"></i> Edit Profile
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item" data-toggle="modal" data-target="#modal-default-change-pass">
            <i class="fas fa-lock mr-2"></i> Change Password
          </a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        </div>
      </li>


     <!--  <li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button" style="color: #fff;">
          <i class="fas fa-th-large"></i>
        </a>
      </li> -->
    </ul>
  </nav>

<!-- Notification Details Modal  -->
<div class="modal fade" id="NotificationDetails" data-backdrop="static" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content" style="border-bottom: 4px  solid #ee1b22;min-height: 350px">
      <div class="modal-header ab_bank_modal_background_color">
        <h5 class="modal-title white-color" id="notification_title_text">Notification Title</h5>
      </div>

      <div class="modal-body">
      <div class="row">
        <div class="col-md-12">
          <div class="card-body">
             <div class="form-group">
              <label for="notification_details_text">Notification Details</label>
              <textarea class="form-control" rows="12" id="notification_details_text" readonly="">Notification body text</textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22" data-dismiss="modal">Close</button>
          </div>

        </div>

        </div>
      </div>
    </div>
  </div>
</div>





<!-- View profile popup -->

<div class="modal fade" id="modal-default-view-profile" data-backdrop="static" >
    <div class="modal-dialog">
        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
            <div class="modal-header ab_bank_modal_background_color">
                <h4 class="modal-title white-color"> <i class="fas fa-eye mr-2"></i> View Profile </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="row">

                    <div class="col-md-12">
                        <form method="" action="" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <img src="{{asset($img_path)}}" class="img-circle mx-auto d-block" alt="profile picture" width="80px" height="80px" align="center">

                                </div>

                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control" id="name"  name="name" value="{{$user_info->name}}" maxlength="100" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="mobile_number">Mobile Number</label>
                                    <input type="text" class="form-control" id="mobile_number"  name="mobile_number" value="{{$user_mobile}}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" disabled>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{$user_email}}" maxlength="100" disabled>
                                </div>

                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

  <!-- Edit profile popup -->

<div class="modal fade" id="modal-default-edit-profile" data-backdrop="static" >
      <div class="modal-dialog">
        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color"> <i class="fas fa-user-edit mr-2"></i> Edit Profile </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
          <form method="POST" action="{{route('manageProfile')}}" enctype="multipart/form-data" autocomplete="off">
              @csrf
                <div class="card-body">
                <div class="form-group">
                <img src="{{asset($img_path)}}" class="img-circle mx-auto d-block" alt="profile picture" width="80px" height="80px" align="center">

                </div>

                  <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" class="form-control" id="name"  name="name" value="{{$user_info->name}}" maxlength="100" required="">
                  </div>

                  <div class="form-group">
                    <label for="mobile_number">Mobile Number</label>
                    <input type="text" class="form-control" id="mobile_number"  name="mobile_number" value="{{$user_mobile}}" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" required="">
                  </div>

                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{$user_email}}" maxlength="100" required="">
                  </div>

                  <div class="form-group">
                    <label for="class_name">Profile Picture</label>
                    <input type="hidden" name="hidden_profile_img" id="hidden_profile_img" value="">
                    <input type="file" class="form-control-file" id="profile_img" name="profile_img">
                    <span class="school_logo_in_edit_mode"> <img id="profile_img"></span>
                  </div>

                </div>


                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
               </div>
              </form>

          </div>
          </div>
      </div>
    </div>
  </div>
</div>

<!-- Change password popup -->

<div class="modal fade" id="modal-default-change-pass" data-backdrop="static" >
      <div class="modal-dialog">
        <div class="modal-content" style="border-bottom: 4px  solid red;min-height: 350px">
          <div class="modal-header ab_bank_modal_background_color">
            <h4 class="modal-title white-color"> <i class="fas fa-lock mr-2"></i> Change Password </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
          <div class="row">

          <div class="col-md-12">
          <form method="POST" id="PostForm" action="{{ route('changePassword') }}" enctype="multipart/form-data" autocomplete="off">
              @csrf
                <div class="card-body">
                <div class="form-group">
                    <label for="class_name">Current Password <span style="color:red;">*</span></label>
                    <input type="password" class="form-control" id="current_pass" placeholder="Current Password" name="current_pass" required="">
                  </div>

                  <div class="form-group">
                    <label for="class_name">New Password <span style="color:red;">*</span></label>
                    <input type="password" class="form-control" id="new_pass" placeholder="New Password" name="new_pass" required="">
                  </div>

                  <div class="form-group">
                    <label for="class_name">Confirm Password <span style="color:red;">*</span></label>
                    <input type="password" class="form-control" id="confirm_pass" placeholder="Confirm Password" name="confirm_pass" required="">
                  </div>

                   </div>



                <!-- /.card-body -->

                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" style="background-color: #ee1b22;border-color: #ee1b22">Save</button>
               </div>
              </form>
            <!-- /.card -->

          </div>
      </div>
    </div>
  </div>
</div>
</div>


<script type="text/javascript">

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

  function showNotificationDetails(id) {

    $("#NotificationDetails").modal("show");

    var url = globalURL + "show-notification-details/" + id;
        $.ajax({
              url: url,
              type: "get",
              dataType: 'json',
              success: function(response){
                document.getElementById("notification_title_text").innerText=response[0].notification_title;
                document.getElementById("notification_details_text").innerText=response[0].notification_body;
              },
              error: function(){
                document.getElementById("notification_title_text").innerText=" ";
                document.getElementById("notification_details_text").innerText=" ";
              }
          });

  }

</script>
