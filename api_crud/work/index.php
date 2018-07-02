<!DOCTYPE html>
<html lang="en">
<head>
  <title>CRU functionality usin PHP API</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>CRUD functionality usin PHP API</h2>
  <p><button type="button" class="btn btn-info btn-lg" id="add_button">Add Data</button></p>           
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Firstname</th>
        <th>Lastname</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
      
    </tbody>
  </table>

  <!-- Modal -->
  <div class="modal fade" id="apicrudModel" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="" method="POST" id="api_crud_form">
          
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"></h4>
            </div>

            <div class="modal-body">
              <div class="form-group">
                <label>Enter First Name</label>
                <input type="text" name="first_name" id="first_name" class="form-control">
              </div>

              <div class="form-group">
                <label>Enter Last Name</label>
                <input type="text" name="last_name" id="last_name" class="form-control">
              </div>
            </div>

            <div class="modal-footer">
              <input type="hidden" name="hidden_id" id="hidden_id" />
              <input type="hidden" name="action" id="action" value="" />

              <input type="submit" name="button_action" id="button_action" class="btn btn-primary" value="">

              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

        </form>
      </div>
    </div>
  </div>
  <!-- model end -->
</div>

<script>
  $(document).ready(function(){
    fetch_data();

    function fetch_data()
    {
      $.ajax({
          url:'fetch.php',
          success:function(data)
                {
                  $('tbody').html(data);
                }

      })
    }

    $('#add_button').click(function(){
      $('.modal-title').text('Add Data');
      $('#action').val('insert');
      $('#button_action').val('Insert');
      $('#apicrudModel').modal('show'); 
    });

    $('#api_crud_form').on('submit', function(event){
        event.preventDefault();
        if($('#first_name').val()=='')
        {
          alert('Enter First Name');
          $('#first_name').focus();
        }else if($('#last_name').val()==''){
          alert('Enter Last Name');
          $('#last_name').focus();
        }else{
          var form_data = $(this).serialize();
          //alert(form_data);
          $.ajax({
            url:'action.php',
            method:'POST',
            data:form_data,
            success:function(data){
                      fetch_data();
                      $('#api_crud_form')[0].reset();
                      $('#apicrudModel').modal('hide');
                      //alert(data);
                      if(data=='insert')
                      {
                        alert('Data inserted using PHP API');
                      }

                      if(data=='update')
                      {
                        alert('Data update using PHP API');
                      }
                    }
          });
        }
    });

    $(document).on('click', '.edit', function(){
      var id = $(this).attr('id');
      var action = 'fetch_single';
   
      $.ajax({
              url:'action.php',
              method:'POST',
              data:{id:id, action:action},
              dataType:"json",
              success:function(data)
              {
                $('#hidden_id').val(id);
                $('#first_name').val(data.first_name);
                $('#last_name').val(data.last_name);
                $('#button_action').val('Update');
                $('#action').val('update');
                $('.modal-title').text('Edit Data');
                $('#apicrudModel').modal('show');
              }
      });
    });

    $(document).on('click','.delete', function(){
        var id = $(this).attr("id");
        var action = 'delete';
        if(confirm("Are you sure you want to delete data?"))
        {
          $.ajax({
            url:'action.php',
            method:"POST",
            data:{id:id, action:action},
            success:function()
            {
              fetch_data();
              alert('Data deleted by Using PHP API.');
            }
          })
        }

    });

  });

</script>
</body>
</html>
