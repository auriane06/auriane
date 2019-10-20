<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Laravel 5.7 First Ajax CRUD Application - Tutsmake.com</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/lodash@4.17.15/lodash.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-range/4.0.2/moment-range.js"></script>

<script>
  window['moment-range'].extendMoment(moment);
</script>
 
 <style>
   .container{
    padding: 0.5%;
   } 
  .odd{background-color:#DCEDC8;} 
  </style>

</head>
<body>

<div class="container text-center">
  <h3 class="display-4">Events Calendar</h3>
  <br>
</div>

<div class="container">
  <div class="row">
      <div class="col">
          <div class="container">
              <div class="form-group row add">
                <div class="col-md-6">
                  <h6>Name your event:</h6>
                  <input type="hidden" name="event_id" id="event_id">
                  <input type="text" class="form-control" id="name" name="name"
                  placeholder="Enter event name" required>
                </div>
              </div>
              <div class="form-group row add">
                <div class="col-md-6">
                  <h6>Choose date range:</h6>
                  <input type="text" name="daterange" class="form-control" value="" onkeydown="return false" />
                </div>
              </div>
              
              <h6>Select working days:</h6>
              <div class="form-check form-check-inline">
                <input class="form-check-input" name="selected_days" type="checkbox" id="inlineCheckbox1" value="1">
                <label class="form-check-label" for="inlineCheckbox1">Mon</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" name="selected_days" type="checkbox" id="inlineCheckbox2" value="2">
                <label class="form-check-label" for="inlineCheckbox2">Tue</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" name="selected_days" type="checkbox" id="inlineCheckbox3" value="3">
                <label class="form-check-label" for="inlineCheckbox3">Wed</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" name="selected_days" type="checkbox" id="inlineCheckbox3" value="4">
                <label class="form-check-label" for="inlineCheckbox3">Thu</label>
                </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" name="selected_days" type="checkbox" id="inlineCheckbox3" value="4">
                <label class="form-check-label" for="inlineCheckbox3">Fri</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" name="selected_days" type="checkbox" id="inlineCheckbox3" value="6">
                <label class="form-check-label" for="inlineCheckbox3">Sat</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" name="selected_days" type="checkbox" id="inlineCheckbox3" value="0">
                <label class="form-check-label" for="inlineCheckbox3">Sun</label>
              </div>
        
              <div class="form-group row add">
                <div class="col-md-6">
                  <button class="btn btn-primary" type="submit" id="add" enabled>Save</button>
                </div>
              </div>
        
          </div>
        
      </div>
      <div class="col">
          <div class="container">
              <div class="MyTable">
                <table class="table" id="events">
                </table>
              </div>
            </div>
      </div>
    </div>

  </div>

  <script>  

  $selected_days = new Array();

  //Config Date Range Picker initial start and end dates
   $('input[name="daterange"]').daterangepicker({
    "startDate": "10/15/2019",
    "endDate": "10/31/2019"
    }, function(start, end, label) {
    console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
    });

    //Date Range Picker 
    $(function() {
    $('input[name="daterange"]').daterangepicker({
    opens: 'left'
    }, function(start, end, label) {
    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    $startDate = moment(start).format('YYYY-MM-DD');
    $endDate =moment(end).format('YYYY-MM-DD');
 

   });
    });

   //Disable Save button if input field is empty
   //function success() {
	 //if(document.getElementById("name").value==="") { 
            //document.getElementById('add').disabled = true; 
        //} else { 
           // document.getElementById('add').disabled = false;
        //}
   // }

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
      $("#add").click(function() {
        $("input:checkbox[name=selected_days]:checked").each(function() {
        $selected_days.push($(this).val());
  });
  $( "#add" ).prop( "disabled", true ); //disable when ajax call starts

          $.ajax({
              type: 'post',
              url: '/ajax-crud',
              data: {
                  'event_id': $('input[name=_event_id]').val(),
                  'name': $('input[name=name]').val(),
                  'start_date': $startDate,
                  'end_date' : $endDate,
                  'days' : $selected_days            
              },             
              success: function(data) {
                  $("#events tr").remove();
                  
                  if ((data.errors)){
                    $('.error').removeClass('hidden');
                      $('.error').text(data.errors.name);
                      console.log("asd");
                  }
                  else {
                    document.getElementById('add').disabled = false; 
                      $('.error').addClass('hidden');
                          //Get dates range
                          var range = moment().range($startDate, $endDate);
                          var diff = range.diff('days');
                          var array = Array.from(range.by('days'));                       
                          $.each(array, function(i, e) {
                                var day = moment(e).format("ddd D");
                                var test = Array.from(moment(e).format("d"));
                                var days = Array.from(data.days);
                                var duplicate = _.intersection(test, days);
                                $.each(duplicate, function(i, e){
                                $('#events').append("<tr class='odd'><td>" + day + "</td><td>" + data.name +"</td>")   
                                  
                                });
                                });
                  }
              },
  
          });
          $('#name').val();
      });
  </script>
 
</body>
</html>