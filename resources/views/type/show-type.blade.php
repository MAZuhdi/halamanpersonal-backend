<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">  
    <title>Types</title>
</head>

<body>
    <div class="container">
      <h1 class="text-center">Show All Type</h1>
      <a href="types/create" class="btn-sm btn-primary">Create Type</a>
      <table id="typestable" class="table table-hover text-wrap display">
        <thead>
          <?php $i=1; ?>
          <tr>
            <th style="width: 20px">No</th>
            <th>Name</th>
            <th>Headline</th>
            <th>Desc</th>
            <th>Icon</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="tabledashboard" class="">
          <?php $i=1; ?>
          @foreach ($types as $t)
          <tr>
              <td>{{$i++}}</td>
              <td>{{$t->name}}</td>
              <td>{{$t->headline}}</td>
              <td>{{$t->desc}}</td>
              <td>{{$t->icon}}</td>
              <td>
                  <a href="/types/{{$t->id}}/edit" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                  <a href="{{ route('type.delete',$t->id) }}" class="btn btn-sm btn-data btn-danger" style="font-size: 0.8em;" id="deleteType" data-id="{{ $t->id }}">
                    <i class="bi bi-trash-fill"></i>
                 </a>
              </td>
          </tr>
          @endforeach
        </tbody>
      </table>

      
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    {{-- Jquery --}}
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
    -->

    <script>
      $(document).ready(function () {
      $("body").on("click","#deleteType",function(e){
        if(!confirm("Are you sure want to delete this?")) {
            return false;
          }

        e.preventDefault();
        var id = $(this).data("id");
        // var id = $(this).attr('data-id');
        var token = $("meta[name='csrf-token']").attr("content");
        var url = e.target;
        $.ajax(
            {
              url: url.href,
              type: 'DELETE',
              data: {
                _token: token,
                    id: id
            },
            success: function (response){

                $("#success").html(response.message)

                Swal.fire(
                  'Success',
                  'Types deleted successfully',
                  'success'
                )
                
                location.reload();
            }
          });
          return false;
        });
        

      });
    </script>
</body>

</html>