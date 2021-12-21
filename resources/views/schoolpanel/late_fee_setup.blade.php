@extends('master')

@section('title','View Invoices')

@section('page_specific_css')
  <!-- page specific script will be here -->
@endsection


@section('content')

 <section class="content-header" style="margin-right: 1%;height: 50px">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h3 style="font-size: 25px;font-weight: bolder;margin-left: -15px">Late Fee</h3>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item active">Late Fee</li>
          </ol>
        </div>
      </div>
    </div>
  </section>


<div style="clear:both; height:10px;"></div>

<div class="card"  style="margin-right:1%">

  <div class="card-header">

    <form method="post" action="{{route('setupFeeStore')}}">
      @csrf
       <div class="row">
          <div class="col">
            <div>
               <select class="form-control select2" name="date_number"  id="date_number" required="" oninvalid="this.setCustomValidity('Please select a date in the list')" oninput="setCustomValidity('')">
                    <option value="">Select Date</option>
                    <option value="01">01</option>
                    <option value="02">02</option>
                    <option value="03">03</option>
                    <option value="04">04</option>
                    <option value="05">05</option>
                    <option value="06">06</option>
                    <option value="07">07</option>
                    <option value="08">08</option>
                    <option value="09">09</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                   <option value="14">14</option>
                   <option value="15">15</option>
                   <option value="16">16</option>
                   <option value="17">17</option>
                   <option value="18">18</option>
                   <option value="19">19</option>
                   <option value="20">20</option>
                   <option value="21">21</option>
                   <option value="22">22</option>
                   <option value="23">23</option>
                   <option value="24">24</option>
                   <option value="25">25</option>
                   <option value="26">26</option>
                   <option value="27">27</option>
                   <option value="28">28</option>
                   <option value="29">29</option>
                   <option value="30">30</option>
                   <option value="31">31</option>

              </select>
             </div>
          </div>

           <div class="col">
               <div>
                   <input type="text"  class="form-control" name="Amount"  id="Amount"  oninput="this.value = this.value.replace(/[^0-9 .]/g, '').replace(/(\..*)\./g, '$1');" placeholder="Amount" required="">

               </div>
           </div>

        <div class="col-sm">
         <button type="submit" class="btn btn-primary" required="" style="background-color:#ee1b22;border-color:#ee1b22;">
          <span style="margin-left:5px">Save</span>
         </button>
        </div>

       </div>
    </form>

      <div style="clear:both; height:10px;"></div>
  </div>





</div>




<aside class="control-sidebar control-sidebar-dark"></aside>
@endsection


@section('page_specific_script')

<script type="text/javascript">


        function hideShow() {
        let y = document.getElementById("approve");
        if (y.style.display === "none") {
        y.style.display = "block";
    }
        else {
            y.style.display = "none";
        }
    }

     $("#approve").click(function(){
          var url = globalURL + "view-invoices/Approve_all";

          $.ajax({
                url: url,
                type: "get",
                dataType: 'json',
                success: function(response){
                    console.log("hello")
                }
            });
         window.location.reload();
    });

</script>

@endsection
