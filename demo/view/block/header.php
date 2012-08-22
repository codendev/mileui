<html>
    <head>
        <title>Mile Framework</title>
        <link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
    </head>
    <body>
        <div id="toolbar"><div class="fixed_width">

                <div id="filter_form">



                </div>
                <ul class="menu_bar">
                    <li><?if(!empty($_SESSION["user"])):?><a href="?action=home&event=logout">Logout</a><?else:?><a href="?action=home&event=login">Login</a><?endif;?></li>
                </ul>
                <ul id="last_menu_bar" class="menu_bar">
                    <li><a id="show-feedback" href="/feedback">Feedback</a></li>
                </ul>

            </div>
        </div>
     <div id="content">