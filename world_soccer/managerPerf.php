<!DOCTYPE html>
<html>
  <head>
    <title>Soccer Database Project</title>
    <!-- This means that the browser will render the width of the page at the width of its own screen. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="css/bootstrap-theme.css" rel="stylesheet" media="screen">
    <link href="css/my-styles.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="css/style-responsive.css" />
    <style>
      table {
          width:100%;
      }
      table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
      }
      th, td {
          padding: 5px;
          text-align: center;
      }
      table#t01 tr:nth-child(even) {
          background-color: #eee;
      }
      table#t01 tr:nth-child(odd) {
         background-color:#fff;
      }
      table#t01 th {
          background-color: black;
          color: white;
      }
    </style>
  </head>
  <body>

    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
      <!-- Include a container inside of our navbar so the container will span the same width of the content -->
      <div class="container">

        <!-- navbar-toggle positions the toggle button over to the right side of the navbar in mobile views. -->
        <!-- Data-toggle attribute is a custom data attribute that calls the collapse JS plugin functions -->
        <!-- Data-target attribute id what makes the nav toggle on and off -->
        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        

        <!-- brand will let bootstrap to place it over to the left side of the navigation. text-muted don't let text stand out -->
        <a class="navbar-brand text-muted" href="Final.html">Home</a>
        </div>
        <div class="collapse navbar-collapse">

          <!-- navbar positions the navigation links horizontally and gives them their default color styles. -->
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a data-toggle="dropdown" data-target="player.html">Player<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="player.php">Player Information</a></li>
                <li><a href="playerPerf.php">Player Performance</a></li>
              </ul>
            </li>

            <li class="active dropdown">
              <a data-toggle="dropdown" data-target="manager.html">Manager<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="manager.php">Manager Information</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a data-toggle="dropdown" data-target="club.html">Club<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="club.php">Club Information</a></li>
                <li><a href="clubPerf.php">Club Performance</a></li>
              </ul>
            </li>

            <li class="dropdown">
              <a data-toggle="dropdown" data-target="match.html">Season<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="match.php">Season Statistics</a></li>
              </ul>
            </li>

          </ul>

        </div>
      </div>
    </div>
    <!-- End navbar -->



    <!-- container class inside Bootstrap can center the page content and it also sets the containers max width at every media query break point. -->
    <div class="container documentationbar">

      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-2 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="query">
            <form method= "get" action="">
              <fieldset>
                <legend>Manager Performance:</legend>
                <label for="lmanagername">Managed By (Ex: Pep Guardiola)</label>
                <input type="text" id="managername" name="managername" value="<?php echo htmlspecialchars($_GET['managername']);?>">

                <label for="lcountry">Country (Ex: England)</label>
                <input type="text" id="countryname" name="managercountry" value="<?php echo htmlspecialchars($_GET['managercountry']);?>">

                <label for="lmanagername">Managed which player (Ex: Raheem Sterling)</label>
                <input type="text" id="playername" name="playername" value="<?php echo htmlspecialchars($_GET['playername']);?>">

                <label for="lclubname">Manages for which club (Ex: Manchester City)</label>
                <input type="text" id="clubname" name="clubname" value="<?php echo htmlspecialchars($_GET['clubname']);?>"><hr>

                <label for="lage">For one age value, enter same value:</label>
                <label for="lminage">Minimum Age (>=)</label>
                <input type="text" id="lminage" name="managerminage" value="<?php echo htmlspecialchars($_GET['managerminage']);?>">

                <label for="lmaxage">Maximum Age (<=)</label>
                <input type="text" id="lmax" name="managermaxage" value="<?php echo htmlspecialchars($_GET['managermaxage']);?>">
              
                <input type="submit" value="Search">
              </fieldset>
            </form>
          </div>
        </div><!--/.sidebar-offcanvas-->


        <div class="col-xs-12 col-lg-9">
          
          <div class="row">
            <div class="col-xs-12 col-lg-12">
              <form method="get" action="">
                <h5>Page: </h5>
                <table id="t01">
                  <tr>
                    <th>Manager_id</th>
                    <th>Manager_name</th>
                    <th>Age</th>
                    <th>Country</th>
                  </tr>
                <?php
                include 'inc/connection.php';


                // style="width:100%; border: 1px solid black; border-collapse: collapse;"
                session_start();


                $rec_limit = 15;
                // Getting the input parameter (user):
                $managername = trim($_REQUEST['managername']);
                $managercountry = trim($_REQUEST['managercountry']);
                $playername = trim($_REQUEST['playername']);
                $clubname = trim($_REQUEST['clubname']);
                $managerminage = trim($_REQUEST['managerminage']);
                $managermaxage = trim($_REQUEST['managermaxage']);


                $page=$_GET["page"];
                if ($page=="" || $page=="1") {
                  $page1 = 0;
                } else {
                  $page1 = ($page*15)-15;
                }

                $condition=$_GET["cc"];



                if ($managername) {
                  $condition = "where m.manager_name = '$managername'";
                }

                if ($managercountry) {
                  if ($condition) {
                    $condition = $condition." and m.country = '$managercountry'";
                  } else {
                    $condition = "where m.country = '$managercountry'";
                  }
                }

                if ($playername) {
                  if ($condition) {
                    $condition = $condition." and p.player_name like '%$playername%'";
                  } else {
                    $condition = "where p.player_name like '%$playername%'";
                  }
                }

                if ($clubname) {
                  if ($condition) {
                    $condition = $condition." and c.club_name = '$clubname'";
                  } else {
                    $condition = "where c.club_name = '$clubname'";
                  }
                }

                if ($managerminage) {
                  if ($condition) {
                    $condition = $condition." and m.age >= '$managerminage'";
                  } else {
                    $condition = "where m.age >= '$managerminage'";
                  }
                }

                if ($managermaxage) {
                  if ($condition) {
                    $condition = $condition." and m.age <= '$managermaxage'";
                  } else {
                    $condition = "where m.age <= '$managermaxage'";
                  }
                }


                $query = "SELECT distinct m.manager_id, m.manager_name, m.age, m.country FROM manager m
                          inner join managerplayer mp on m.manager_id = mp.manager_id
                          inner join player p on p.player_id = mp.player_id
                          inner join playerclub pc on p.player_id = pc.player_id 
                          inner join club c on c.club_id = pc.club_id ".$condition." limit $page1, 15";


              
                $result = mysqli_query($dbcon, $query)
                  or die('Query failed: ' . mysqli_error($dbcon));

                $totalItem = 0;
                while ($tuple = mysqli_fetch_assoc($result)) {
                  print '<tr><td>'.$tuple['manager_id'].'</td>
                  <td>'.$tuple['manager_name'].'</td>
                  <td>'.$tuple['age'].'</td>
                  <td>'.$tuple['country'].'</td></tr>';
                  $totalItem += 1;
                }

                if ($totalItem < 1) {
                  print "<div class='jumbotron'><h4>Search Results: Not Found. Please modify your condition!</h4></div>";
                  //"<p>Search Results: Not Found. Please modify your condition!</p>";
                }
                

                $query1 = "SELECT distinct m.manager_id, m.manager_name, m.age, m.country FROM manager m
                          inner join managerplayer mp on m.manager_id = mp.manager_id
                          inner join player p on p.player_id = mp.player_id
                          inner join playerclub pc on p.player_id = pc.player_id 
                          inner join club c on c.club_id = pc.club_id ".$condition;
              
                $result1 = mysqli_query($dbcon, $query1)
                  or die('Query failed: ' . mysqli_error($dbcon));



                $total = 0;
                while ($tuple = mysqli_fetch_assoc($result1)) {
                  $total += 1;
                }

                $p = $total/15;
                $p = ceil($p);

                for ($b=1;$b<=$p;$b++) {
                  ?><a href="manager.php?page=<?php echo $b; ?>&cc=<?php echo $condition; ?>"><?php echo $b." "; ?></a><?php
                }
                echo "<br>";

                ?>
                </table>
                <br>
                <br>
              </form>
              
            </div><!--/.col-xs-6.col-lg-4-->
          
          </div><!--/row-->

        </div><!--/.col-xs-12.col-sm-9-->

      </div><!--/row-->
    </div>






    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>