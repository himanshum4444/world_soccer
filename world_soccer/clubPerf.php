<?php
        include 'inc/connection.php';
        session_start();
?>  
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
        <a class="navbar-brand text-muted" href="index.php">Home</a>
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

            <li class="dropdown">
              <a data-toggle="dropdown" data-target="manager.html">Manager<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="manager.php">Manager Information</a></li>
              </ul>
            </li>

            <li class="active dropdown">
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
                <legend>Club Performance:</legend>
                <label for="lclubname">Club Name (Ex: Barcelona)</label>
                <input type="text" id="clubname" name="clubname" value="<?php if(isset($_GET['clubname'])) echo htmlspecialchars($_GET['clubname']); else echo '';?>">


                <label for="season">Season Year</label>
                <select id="season" name="season">
                  <option value=""></option>
                  <option value="2016">2016</option>
                  <option value="2015">2015</option>
                  <option value="2014">2014</option>
                  <option value="2013">2013</option>
                  <option value="2012">2012</option>
                  <option value="2011">2011</option>
                  <option value="2010">2010</option>
                  <option value="2009">2009</option>
                  <option value="2008">2008</option>
                  <option value="2007">2007</option>
                  <option value="2006">2006</option>
                  <option value="2005">2005</option>
                </select>

              
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
                    <th>Season_Year</th>
                    <th>Club_name</th>
                    <th>Total_win</th>
                    <th>Total_draw</th>
                    <th>Total_defeat</th>
                  </tr>
                <?php
                include 'inc/connection.php';

                $rec_limit = 50;
                // Getting the input parameter (user):

                $clubname = isset($_REQUEST['clubname'])?trim($_REQUEST['clubname']):'';
                $season = isset($_REQUEST['season'])?trim($_REQUEST['season']):'';
                


                $page=isset($_GET["page"])?$_GET["page"]:"";
                if ($page=="" || $page=="1") {
                  $page1 = 0;
                } else {
                  $page1 = ($page*50)-50;
                }


                $condition=isset($_GET["cc"])?$_GET["cc"]:"";

                if ($clubname) {
                  $condition = "where c.club_name like '%$clubname%'";
                }

                if ($season) {
                  if ($condition) {
                    $condition = $condition." and s.season_year = '$season' group by c.club_name";
                  } else {
                    $condition = "where s.season_year = '$season' group by c.club_name";
                  }
                } else {
                  $condition = $condition." group by s.season_year";
                }

                if ($co) {
                  $condition = $co;
                } else if (!$clubname && !$season) {
                  $condition = "";
                }


                $query = "SELECT s.season_year, c.club_name, (sc.home_win+sc.away_win) as total_win, 
                          (sc.home_draw+sc.away_draw) as total_draw, (sc.home_defeat+sc.away_defeat) as total_defeat
                          from seasonclub sc 
                          inner join season s
                          on sc.season_id = s.season_id
                          inner join club c
                          on sc.club_id = c.club_id ".$condition." limit $page1, 50";

              
                $result = mysqli_query($dbcon, $query)
                  or die('Query failed bbbbbb: ' . mysqli_error($dbcon));

                $totalItem = 0;
                while ($tuple = mysqli_fetch_assoc($result)) {
                  print '<tr><td>'.$tuple['season_year'].'</td>
                  <td>'.$tuple['club_name'].'</td>
                  <td>'.$tuple['total_win'].'</td>
                  <td>'.$tuple['total_draw'].'</td>
                  <td>'.$tuple['total_defeat'].'</td></tr>';
                  $totalItem += 1;
                }

                if ($totalItem < 1) {
                  print "<div class='jumbotron'><h4>Search Results: Not Found. Please modify your condition!</h4></div>";
                }


                $query1 = "SELECT s.season_year, c.club_name, (sc.home_win+sc.away_win) as total_win, 
                          (sc.home_draw+sc.away_draw) as total_draw, (sc.home_defeat+sc.away_defeat) as total_defeat
                          from seasonclub sc 
                          inner join season s
                          on sc.season_id = s.season_id
                          inner join club c
                          on sc.club_id = c.club_id ".$condition;

                
              
                $result1 = mysqli_query($dbcon, $query1)
                  or die('Query failed: ' . mysqli_error($dbcon));

                $total = 0;
                while ($tuple = mysqli_fetch_assoc($result1)) {
                  $total += 1;
                }

                $p = $total/50;
                $p = ceil($p);

                for ($b=1;$b<=$p;$b++) {
                  ?><a href="clubPerf.php?page=<?php echo $b; ?>&cc=<?php echo $condition; ?>"><?php echo $b." "; ?></a><?php
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