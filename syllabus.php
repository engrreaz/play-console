<?php
include 'inc.php'; echo $usr;
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />

    <!-- Bootstrap CSS v5.2.1 -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="css.css?v=a" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
    />
  </head>

  <body style="background: var(--lighter); width:100%; max-width:100%; overflow:auto;">
    <div class="containerx" style="width: 100%">
      <div style="text-align: center; margin-top: 25px">
        <svg
                      xmlns="http://www.w3.org/2000/svg"
                      width="40"
                      height="40"
                      fill="var(--darker)"
                      class="bi bi-book-half"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M8.5 2.687c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"
                      />
                    </svg>
        <div style="margin-bottom:12px; font-weight:bold;">Syllabus</div>

        <style>
          .box {
            color: gray;
            font-weight: bold;
            text-align: left;
            padding: 7px 30px;
            border: 1px solid #ccc;
          }
          .box small {
            font-weight: 400;
            font-size: 10px;
            color: var(--normal);
            padding-left: 30px;
            font-style:italic;
          }
          .icon {
            padding-right: 5px;
          }
        </style>
        
        <div class="box">
          <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
              <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z"/>
            </svg>
          <?php echo $usr; ?>
          <br /><small>Email Address</small>
        </div>
        
        
      </div>
    </div>
    
    



  </body>
</html>
