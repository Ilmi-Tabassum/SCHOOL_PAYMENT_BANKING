<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color:#ee1b22">

    <!-- brand logo -->
    <a href="{{url('/')}}" class="brand-link" style="background-color: #fff">
        <img src="{{asset('dist/img/logo.png')}}" alt="AB Bank logo" class="brand-image img-circle">
        <span class="brand-text font-weight-light" style="color:#000000;font-weight: bold;">AB Bank (EMS)</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="background-color:#ee1b22;margin-top:-1px">

    <?php
    $user_type = auth()->user()->user_type_id;
    ?>

    @if($user_type==1 || $user_type==5 || $user_type==6)
        <!-- Sidebar Menu -->
            <nav class="mt-2" style="background-color:#ee1b22">

                <?php
                $user_type_id = auth()->user()->user_type_id;
                if(is_null($user_type_id)!=1){
                    if ($user_type_id==2) {
                        $school_id = auth()->user()->school_id;
                        $school_logo = App\Models\SchoolInfo::find($school_id)->first()->school_logo;
                        /*if the school has logo in database school_infos table then shows that logo,otherwise shows default logo*/
                        if(is_null($school_logo)!=1){
                            $school_logo_img_path = '/storage/school_logo/'. $school_logo;
                            echo "<div style='background-color: #fff;text-align: center;border-radius: 4%;padding: 5px 0px 5px 0px'>
                       <img src='$school_logo_img_path' style='max-width: 100%;max-height: 150px'>
                    </div>" ;
                        }
                        else{
                            $school_logo_default_path = asset('default_school_logo.png');
                            echo "<div style='background-color: #fff;text-align: center;border-radius: 4%;padding: 5px 0px 5px 0px'>
                       <img src='$school_logo_default_path' style='max-width: 100%;max-height: 150px'>
                    </div>" ;
                        }
                    }
                }
                ?>

                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

                    <?php
                    $user_type_id = auth::user()->user_type_id;
                    /*Get the parrent menu name from the permissions table --- start*/
                    $find_u_id = DB::select(DB::raw("SELECT id,utype_id FROM permissions WHERE utype_id='".$user_type_id."'"));
                    if (count($find_u_id)>0){
                        // $assigned_menus = DB::select(DB::raw("SELECT utype_id,id_menu FROM permissions WHERE utype_id=$user_type_id"));
                        $assigned_menus = DB::select(DB::raw("SELECT utype_id,id_menu FROM `permissions` WHERE utype_id='".$user_type_id."'"));
                        $query_output = unserialize($assigned_menus[0]->id_menu);
                        $size = count($query_output);
                        //echo $size;//size of parent_menu
                        $main_menu = array();
                        for ($i=0; $i <$size ; $i++) {
                            $main_menu_id = (int) $query_output[$i]['main_menu'];
                            array_push($main_menu,$main_menu_id);
                        }
                        $left_parent_menu_items = DB::table("menu_setups")->whereIn('id', $main_menu)->get();
                        //var_dump($left_parent_menu_items);
                        /*Get the parrent menu name from the permissions table --- end*/
                        $menu_options = "";
                        $counter = 0;
                        foreach ($left_parent_menu_items as $key => $value) {
                            $menu_options .= "<li class='nav-item'>
                        <a href='#' class='nav-link ' style='color: #fff'>
                          <i class='nav-icon $value->menu_icon'></i>
                          <p>
                           $value->menu_title
                            <i class='fas fa-angle-left right'></i>
                          </p>
                        </a>";
                            $submenu_size = count($query_output[$counter]['sub_menu']);
                            //echo $submenu_size."<br>";
                            $menu_options .= "<ul class='nav nav-treeview'>";
                            for ($j=0; $j <$submenu_size ; $j++) {
                                $sub_menu_id =(int)$query_output[$counter]['sub_menu'][$j]['sub_menu'];
                                //echo $sub_menu_id ." ";
                                $left_child_menu_items = DB::table("menu_setups")
                                    ->select('id','sub_id','menu_name','menu_title','menu_url','menu_icon')
                                    ->where('sub_id', '=',$value->id)
                                    ->where('id','=',$sub_menu_id)
                                    ->get();
                                //var_dump($left_child_menu_items);
                                $menu_options .= "<li class='nav-item'>";
                                if(isset($left_child_menu_items[0])){
                                    if($left_child_menu_items[0]->menu_url != ''){
                                        $menu_options .= "<a href='". URL::to($left_child_menu_items[0]->menu_url) ."' class='nav-link ' style='color: #fff'>";
                                    }else{
                                        $menu_options .= "<a href='". URL::to('/') ."' class='nav-link ' style='color: #fff'>";
                                    }
                                    $menu_options .= "<i class='fa fa-arrow-circle-right nav-icon'></i>
                                    <p>" . $left_child_menu_items[0]->menu_title ."</p>
                                  </a>
                                </li>";
                                }
                            } //end submenu loop
                            $menu_options .= " </ul>
                          </li>";
                            $counter++;
                        } //end parent menu loop
                        echo $menu_options;
                    } //end count check if
                    ?>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
    @endif

    @if($user_type==2)
        <!-- Sidebar Menu -->
            <nav class="mt-2" style="background-color:#ee1b22">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                    <?php

                    $user_type_id = auth()->user()->user_type_id;

                    if(is_null($user_type_id)!=1){

                        if ($user_type_id==2) {
                            $school_id = auth()->user()->school_id;
                            //echo  $school_id;
                            /*$school_logo = App\Models\SchoolInfo::find(15)->first()->school_logo;*/
                            $school_logo = App\Models\SchoolInfo::where('id',$school_id)->first()->school_logo;
                            //echo  $school_logo;
                            /*if the school has logo in database school_infos table then shows that logo,otherwise shows default logo*/
                            if(is_null($school_logo)!=1){

                                $school_logo_img_path = '/storage/school_logo/'. $school_logo;
                                //echo "$school_logo_img_path";
                                echo "<div style='background-color: #fff;text-align: center;border-radius: 4%;padding: 5px 0px 5px 0px'>
                       <img src='$school_logo_img_path' style='max-width: 100%;max-height: 150px'>
                    </div>" ;
                            }
                            else{
                                $school_logo_default_path = asset('default_school_logo.png');
                                echo "<div style='background-color: #fff;text-align: center;border-radius: 4%;padding: 5px 0px 5px 0px'>
                       <img src='$school_logo_default_path' style='max-width: 100%;max-height: 150px'>
                    </div>" ;
                            }
                        }
                    }
                    ?>


                    <li class="nav-item">
                        <a href="#" class="nav-link" style='color: #fff'>
                            <i class="nav-icon fa fa-graduation-cap"></i>
                            <p>
                                Admission
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/students/create/form')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Add New Student</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/schoolpanel/upload-mystudents')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Upload Bulk Student</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/students')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Student List</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="#" class="nav-link" style='color: #fff'>
                            <i class="nav-icon fa fa-cog"></i>
                            <p>
                                Configuration
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/class-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Classes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/shift-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Shift</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/section-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Section</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/group-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Group</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/session-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Session</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/late_fee_setup')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Late Fine</p>
                                </a>
                            </li>


                            <!--  <li class="nav-item">
                                <a href="#" class="nav-link" style='color: #fff'>
                                  <i class="fa fa-arrow-circle-right nav-icon"></i>
                                  <p>Manage Fees</p>
                                </a>
                              </li> -->
                        </ul>
                    </li>









                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-calculator"></i>
                            <p>
                                Accounts
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{url('/class-wise-fees')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Assign Classwise Fees</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/feeshead')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Fees Particulars</p>
                                </a>
                            </li>

                        <!--  <li class="nav-item">
                <a href="{{url('/subhead')}}" class="nav-link " style='color: #fff'>
                  <i class="fa fa-arrow-circle-right nav-icon"></i>
                  <p>Add Fees Subhead</p>
                </a>
              </li> -->

                            <li class="nav-item">
                                <a href="{{url('/fees-waiver')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Waiver</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/view-waivers')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>View Waiver</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{url('/manage-fine')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Fine</p>
                                </a>
                            </li>

                            <!--   <li class="nav-item">
                               <a href="#" class="nav-link " style='color: #fff'>
                                 <i class="fa fa-arrow-circle-right nav-icon"></i>
                                 <p>Manage Dues</p>
                               </a>
                             </li> -->

                            <li class="nav-item">
                                <a href="{{url('/generate-invoices')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Generate Invoices</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/view-invoices/Unverified')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Unverified Invoice</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{url('/view-invoices')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>View Invoices</p>
                                </a>
                            </li>

{{--                            <li class="nav-item">
                                <a href="{{url('/fees-collection')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Tuition Fee Collections</p>
                                </a>
                            </li>--}}
                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-bell"></i>
                            <p>
                                Notification / Notice
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/notification-index')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Add Notification</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                     <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-users"></i>
                            <p>Manage Users<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/create-user-panel')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Add Users</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                <!--       <li class="nav-item">
            <a href="#" class="nav-link " style='color: #fff'>
              <i class="nav-icon fa fa-users"></i>
              <p>
                Manage Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/create-user')}}" class="nav-link " style='color: #fff'>
                  <i class="fa fa-arrow-circle-right nav-icon"></i>
                  <p>Create User</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{url('/permission-setting')}}" class="nav-link " style='color: #fff'>
                  <i class="fa fa-arrow-circle-right nav-icon"></i>
                  <p>Assign User Privileges</p>
                </a>
              </li>
            </ul>
          </li> -->


                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Reports
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/transactions')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Search Transactions</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/school_accounts_panel/income_statement')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Income Statement</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/student_ledger')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Student Ledger</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/dues-report-sp')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Dues Report</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/settlement/list')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Settlements</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/withdraw/list')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Withdraw list</p>
                                </a>
                            </li>

                        </ul>
                    </li>



                </ul>
            </nav>
    @endif

    @if($user_type==10)
        <!-- Sidebar Menu -->
            <nav class="mt-2" style="background-color:#ee1b22">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                    <?php

                    $user_type_id = auth()->user()->user_type_id;
                    if(is_null($user_type_id)!=1){
                        if ($user_type_id==2) {
                            $school_id = auth()->user()->school_id;
                            $school_logo = App\Models\SchoolInfo::find($school_id)->first()->school_logo;
                            /*if the school has logo in database school_infos table then shows that logo,otherwise shows default logo*/
                            if(is_null($school_logo)!=1){
                                $school_logo_img_path = '/storage/school_logo/'. $school_logo;
                                echo "<div style='background-color: #fff;text-align: center;border-radius: 4%;padding: 5px 0px 5px 0px'>
                       <img src='$school_logo_img_path' style='max-width: 100%;max-height: 150px'>
                    </div>" ;
                            }
                            else{
                                $school_logo_default_path = asset('default_school_logo.png');
                                echo "<div style='background-color: #fff;text-align: center;border-radius: 4%;padding: 5px 0px 5px 0px'>
                       <img src='$school_logo_default_path' style='max-width: 100%;max-height: 150px'>
                    </div>" ;
                            }
                        }
                    }
                    ?>


                    <li class="nav-item">
                        <a href="#" class="nav-link" style='color: #fff'>
                            <i class="nav-icon fa fa-graduation-cap"></i>
                            <p>
                                Admission
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/students/create/form')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Add New Student</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/schoolpanel/upload-mystudents')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Upload Bulk Student</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/students')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Student List</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="#" class="nav-link" style='color: #fff'>
                            <i class="nav-icon fa fa-cog"></i>
                            <p>
                                Configuration
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/class-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Classes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/shift-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Shift</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/section-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Section</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/group-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Group</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/session-info')}}" class="nav-link" style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Session</p>
                                </a>
                            </li>

                            <!--  <li class="nav-item">
                                <a href="#" class="nav-link" style='color: #fff'>
                                  <i class="fa fa-arrow-circle-right nav-icon"></i>
                                  <p>Manage Fees</p>
                                </a>
                              </li> -->
                        </ul>
                    </li>




                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-graduation-cap"></i>
                            <p>
                                Admission
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/students/create/form')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Add New Student</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/schoolpanel/upload-mystudents')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Upload Bulk Student</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/students')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Student List</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-cog"></i>
                            <p>
                                Configuration
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/class-info')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Classes</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/shift-info')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Shift</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('/section-info')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Section</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/group-info')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Group</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/session-info')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Session</p>
                                </a>
                            </li>

                            <!--  <li class="nav-item">
                                <a href="#" class="nav-link " style='color: #fff'>
                                  <i class="fa fa-arrow-circle-right nav-icon"></i>
                                  <p>Manage Fees</p>
                                </a>
                              </li> -->
                        </ul>
                    </li>



                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-calculator"></i>
                            <p>
                                Accounts
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/feeshead')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Fees Particulars</p>
                                </a>
                            </li>

                        <!--  <li class="nav-item">
                <a href="{{url('/subhead')}}" class="nav-link " style='color: #fff'>
                  <i class="fa fa-arrow-circle-right nav-icon"></i>
                  <p>Add Fees Subhead</p>
                </a>
              </li> -->

                            <li class="nav-item">
                                <a href="{{url('/fees-waiver')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Waiver</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/view-waivers')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>View Waiver</p>
                                </a>
                            </li>


                            <li class="nav-item">
                                <a href="{{url('/manage-fine')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Manage Fine</p>
                                </a>
                            </li>

                            <!--   <li class="nav-item">
                               <a href="#" class="nav-link " style='color: #fff'>
                                 <i class="fa fa-arrow-circle-right nav-icon"></i>
                                 <p>Manage Dues</p>
                               </a>
                             </li> -->

                            <li class="nav-item">
                                <a href="{{url('/generate-invoices')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Generate Invoices</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/view-invoices')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>View Invoices</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/fees-collection')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Tuition Fee Collections</p>
                                </a>
                            </li>
                        </ul>
                    </li>


                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-bell"></i>
                            <p>
                                Notification / Notice
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/notification-index')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Add Notification</p>
                                </a>
                            </li>

                        </ul>
                    </li>


                <!--       <li class="nav-item">
            <a href="#" class="nav-link " style='color: #fff'>
              <i class="nav-icon fa fa-users"></i>
              <p>
                Manage Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{url('/create-user')}}" class="nav-link " style='color: #fff'>
                  <i class="fa fa-arrow-circle-right nav-icon"></i>
                  <p>Create User</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="{{url('/permission-setting')}}" class="nav-link " style='color: #fff'>
                  <i class="fa fa-arrow-circle-right nav-icon"></i>
                  <p>Assign User Privileges</p>
                </a>
              </li>
            </ul>
          </li> -->


                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Reports
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/transactions')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Search Transactions</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/school_accounts_panel/income_statement')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Income Statement</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/student_ledger')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Student Ledger</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/dues-report-sp')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Dues Report</p>
                                </a>
                            </li>


                        </ul>
                    </li>



                </ul>
            </nav>
        @endif


        @if($user_type==3)
            <nav class="mt-2" style="background-color:#ee1b22">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

                    <li class="nav-item">
                        <a href="{{url('/student-list')}}" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-users"></i>
                            <p>Student List</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-calculator"></i>
                            <p>
                                Accounts
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/dues-list')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Dues List</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/payment-list')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Payment List</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/pay-online')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Pay Online</p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fa fa-bell"></i>
                            <p>
                                Notification / Notice
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{url('/guardian-notice')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Notification List</p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link " style='color: #fff'>
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Reports
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>

                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{url('/student-ledger')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Student Ledger</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{url('/dues-list')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Dues Report</p>
                                </a>
                            </li>

                          {{--  <li class="nav-item">
                                <a href="{{url('/waiver-report')}}" class="nav-link " style='color: #fff'>
                                    <i class="fa fa-arrow-circle-right nav-icon"></i>
                                    <p>Waiver Report</p>
                                </a>
                            </li>--}}
                        </ul>
                    </li>

                </ul>
            </nav>
        @endif



        @if($user_type==4)
            <nav class="mt-2" style="background-color:#ee1b22">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">

                    <li class="nav-item">
                        <a href="{{url('/tellerpanel')}}" class="nav-link  " style='color: #fff'>
                            <i class="nav-icon fa fa-shopping-cart"></i>
                            <p>Cash Collection</p>
                        </a>
                    </li>

{{--                    <li class="nav-item">
                        <a href="{{url('/student_ledger')}}" class="nav-link  " style='color: #fff'>
                            <i class="nav-icon fa fa-users"></i>
                            <p>Student Legder</p>
                        </a>
                    </li>--}}
                </ul>
            </nav>
        @endif




    </div>
    <!-- /.sidebar -->


</aside>

</aside>
