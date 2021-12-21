<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('dist/js/adminlte.js')}}"></script>
{{--<script src="{{asset('dist/js/bootstrap-datepicker.min.js')}}"></script>--}}
<!-- ChartJS -->
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
{{--<script src="{{asset('dist/js/bootstrap-datepicker.min.js')}}"></script>--}}
<!-- AdminLTE for demo purposes -->
<script src="{{asset('dist/js/demo.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('dist/js/pages/dashboard3.js')}}"></script>

<!-- jQuery UI 1.11.4 -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>


<!-- Sparkline -->
<script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- custom js -->
<script src="{{ asset('/dist/js/functions.js') }}"></script>
 <script src="{{asset('custom/js/bootstrap-multiselect.js')}}"></script>


<!-- Select2 -->
<script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>


<!-- <script src="{{asset('plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script> -->
<!-- Call Modal -->
 <script type="text/javascript">
    // Payonline

  $(".totalAmount").on('click', function(){
      // id =  document.querySelector('.totalAmount').id;
      //   alert(id);
      alert();
  });

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // $(function () {
  //   $("#example1").DataTable({
  //     "responsive": true, "lengthChange": false, "autoWidth": false,
  //     "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
  //   }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
  //   $('#example2').DataTable({
  //     "paging": true,
  //     "lengthChange": false,
  //     "searching": true,
  //     "ordering": true,
  //     "info": true,
  //     "autoWidth": false,
  //     "responsive": true,
  //   });
  // });
  //Initialize Select2 Elements
  $('.select2').select2();

  //Initialize Select2 Elements
  $('.select2').select2({
      theme: 'bootstrap4'
  });

  $('#multiple-checkboxes').multiselect({
          includeSelectAllOption: true,
   });

  // Call a modal
  $(".CallModal").on('click', function(){

    var title=$(this).data('title');
    $(".modal-title").html(title);
    var post_url=$(this).data('url');
    var btnSubmit=$(this).data('submit');
    $(".btnSubmit").html(btnSubmit);

    $.post(post_url,
      {
        data: "",
      },
      function(data, status){
        $(".modal-body").html(data);
    });

  });

//Submit a form
$(".btnSubmit").on('click', function(e){
   e.preventDefault();
   var myurl=$("#PostForm").attr("action");
   var btn_txt=$(this).html();

   $(this).html('Sending..');
  $.ajax({
          data: $('#PostForm').serialize(),
          url: myurl,
          type: "POST",
          dataType: 'json',
          success: function (data) {

              $('#PostForm').trigger("reset");
              $('#modal-custom').modal('hide');
             location.reload();
          },
          error: function (data) {
            //debugger;
              console.log('Error:', data);
              $('.btnSubmit').html(btn_txt);
          }
      });
});

//View Details
$(".ViewDetails").on('click', function(e){
   e.preventDefault();
   var title=$(this).data('title');
    $(".modal-title").html(title);
   var id=$(this).data("id");
   var myurl=$(this).data('url');

  $.ajax({
         // data: {id:id},
          url: myurl,
          type: "get",
          dataType: 'text',
           headers: {
             'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
            },
          success: function (data) {
            $(".modal-body").html(data);
          },
          error: function (data) {
            $(".modal-body").html('Sorry Data Not Found!');
          }
      });
});


  $(".CallGlobalModal").on('click', function(){
    var title = $(this).data('title');
    $(".modal-title").html(title);
    var url = $(this).data('url');

    $.ajax({
            url: url,
            type: "get",
            dataType: 'json',
            headers: {
             'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
            },
            success: function(response){
                $(".modal-body").html(response);
            },
            error: function(){
                alert('We are sorry. Please try again.');
            }
        });

  });





</script>
{{--<script type="text/javascript">
 $('#sdate').daterangepicker({
  locale: {
            format: 'YYYY-MM-DD',
            separator: '  to  '
        }
      });

</script>--}}

