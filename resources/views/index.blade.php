<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />

    <title>HalamanPersonal Backend</title>
  </head>
  <body>

    <h1>List of API of HalamanPersonal.my.id</h1>

    <ul>
        <li><a href="{{url('')}}api/users">{{url('')}}/api/users</a>
            <ul>
                <li>GET</li>
                <li>POST</li>
                <li>DELETE</li>
            </ul>
        </li>


        <li><a href="{{url('')}}api/user/{username}">{{url('')}}/api/user/{username}</a>**Going to be updated as users/{username} to follow RESTful API rule
            <ul>
                <li>GET</li>
            </ul>
        </li>
        <li><a href="{{url('')}}api/user/{username}/socmed">{{url('')}}/api/user/{username}/socmed</a>**Going to be updated as users/{username}/socmed to follow RESTful API rule
            <ul>
                <li>GET</li>
            </ul>
        </li>
        <li><a href="{{url('')}}api/types">{{url('')}}/api/types</a>
            <ul>
                <li>GET</li>
            </ul>
        </li>
        
        <li><a href="{{url('')}}api/types/{username}">{{url('')}}/api/types/{username}</a>
            <ul>
                <li>GET</li>
            </ul>
        </li>
        
        <li><a href="{{url('')}}api/types/{username}">{{url('')}}/api/types/{username}</a>
            <ul>
                <li>GET</li>
            </ul>
        </li>
        
        <li><a href="{{url('')}}api/contents">{{url('')}}/api/contents</a>
            <ul>
                <li>GET</li>
            </ul>
        </li>

        <li><a href="{{url('')}}api/contents/{username}">{{url('')}}/api/contents/{username}</a>
            <ul>
                <li>GET</li>
            </ul>
        </li>

        <li><a href="{{url('')}}api/contents/{username}/{type}">{{url('')}}/api/contents/{username}/{type}</a>
            <ul>
                <li>GET</li>
            </ul>
        </li>
        
        <li><a href="{{url('')}}api/contents/{username}/{type}/{slug}">{{url('')}}/api/contents/{username}/{type}/{slug}</a>
            <ul>
                <li>GET</li>
            </ul>
        </li>
       

    </ul>
    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"
    ></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>
</html>
