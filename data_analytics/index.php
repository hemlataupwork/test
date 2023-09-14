<?php

require_once(dirname(dirname(dirname(__FILE__))).'/config.php'); // Moodle configuration file
require_login(); // make sure the user is logged in
$homeurl = new moodle_url('/my');
if (user_has_role_assignment($USER->id,5)) {
    redirect($homeurl, "This feature is only available for site administrators.", 5);
}

global $PAGE,$OUTPUT,$DB;
$PAGE->set_context(context_system::instance()); // set the page context
$PAGE->set_url('/local/data_analytics/index.php'); // set the page URL
$PAGE->set_title('Data Analytics Categories'); // set the page title
$PAGE->set_heading('Data Analytics Categories'); // set the page heading
$PAGE->set_pagelayout('standard');
echo $OUTPUT->header(); // output the page header
// $get_nav=$DB->get_record_sql("SELECT value FROM {config} WHERE name='custommenuitems'");
// $get_nav .= "Data Analytics|/local/data_analytics";
// $set_nav=$DB->("INSERT INTO {config} (name, value) VALUES ('custommenuitems', '{$get_nav}')");
if ($PAGE->theme->name == 'boost') {
    echo "
        <style>
        #page-local-data_analytics-index #page-header .d-flex.align-items-center {
            padding: 8px 50px;
        }
        #page-local-data_analytics-index .card-body{
            padding: 3rem 1.25rem !important;
        }
      
        </style>
    ";
}
if ($PAGE->theme->name == 'classic') {
    echo "
        <style>
        #page-header-headings h1{
            font-size: 22px !important;
        }
        #page-footer
        {
            display : none !important;
        }
        #page-local-data_analytics-index .card-body{
            padding: 3rem 1.25rem !important;
        }
        </style>
    ";
}
echo '<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Document</title>
    <style>
  .first-card{
            background: linear-gradient(to top, #09203f 0%, #537895 100%) !important;
        }
        .second-card{
            background-image: linear-gradient(to left top, #15a085, #24b094, #31c0a3, #3dd0b2, #49e0c2);
        }
        .third-card{
            background-image: linear-gradient(to left top, #f39c13, #f6a731, #f9b247, #fcbd5b, #ffc76f);
        }
        .four-card{
            background-image: linear-gradient(to left top, #2a80b9, #398bc3, #4697cd, #53a2d7, #60aee1);
        }
        .five-card{
            background-image: linear-gradient(to left top, #e74c3d, #ef6455, #f67b6d, #fb9185, #ffa69d);
        }
        .six-card{
            background-image: linear-gradient(to left top, #8e44ad, #a155c1, #b567d5, #c979ea, #dd8bff);
        }
        .hover-item {
            
  transition: 0.3s;
}
.hover-item:hover {
 
  transform: translate(0, -10px);
}
.container{
  
    height: 100vh;
}
   section#region-main {
    background-image: linear-gradient(107deg,rgba(206, 255, 8, 0.048) 0%,rgba(255, 255, 255, 0.49) 35%);
           
        }
        
        .cards{
         
            padding-bottom: 42px;
            
          
background: rgb(255, 255, 255);
border-radius: 16px;
box-shadow: 2px 3px 15px 1px rgba(60, 156, 215, .3);

            border-radius: 20px;
            border-bottom: 6px solid #ffd03a;
            
        }
      
        .card-top {
    width: 100%;
    height: 75px;
    background-color: #2e76e1;
    border-radius: 20px;
    position: relative;
    box-shadow: 0 5px 10px rgba(154,160,185,.05), 0 15px 40px rgba(166,173,201,.2);
}
        .card-box{
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
           gap: 40px;
         
        }
  
        .card-img-box {
    position: absolute;
    top: 82%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    
    background-color: #2869ca;
    padding: 18px;
    box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
    border-radius: 23px;
}
p.card-text {
    width: 90%;
    font-weight: bold;
    text-align: center;
    margin: 44px auto;
}
button.link-btn{
   
    border: none;
    margin: 20px auto;
    width: 90%;
    background: linear-gradient(90deg, rgba(2,0,36,1) 0%, rgba(255,208,58,1) 0%, rgba(251,160,0,1) 0%, rgba(255,208,48,1) 100%);
    color: white;
    padding: 8px;
    border-radius: 10px;
    box-shadow: rgba(100, 100, 111, 0.2) 0px 7px 29px 0px;
    margin-top: 32px;
}
.btn-box{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cards {
 cursor: pointer;
  transition: 0.3s;
}
.cards:hover {
  transform: translate(0, -10px);
}
h2.title-data {
 
    padding: 24px 0;
    margin-bottom: 22px;
    font-weight: bold;
}
.card-box>a:hover .card-img {
    rotate: 360deg;
    transition: all 0.9s;
}
.container .card-box a{
    color: black;
}
@media only screen and (max-width: 600px) {
    .card-box{
            display: grid;
            grid-template-columns: 1fr;
           gap: 40px;
         
        }
}
    </style>
</head>

<body>
    <div class="container">
        <h2 class="title-data" >Data Analytics Reports  </h2>
        <div class="card-box">
            <a class=" text-decoration-none" href="' . $CFG->wwwroot . '/local/data_analytics/data_ana1">
            <div class="cards">
                <div class="card-top">
                    <div class="card-img-box">
                        <img class="card-img" src="img/user.png" alt="Card image">
                    </div>

                </div>
                <div class="">
                    <p class="card-text">Average enrolled student numbers per moodle class</p>  
                </div>
                <!-- <div class="btn-box">
                <button class="link-btn" >Continue</button>
            </div> -->

            </div>
        </a>
        <a class=" text-decoration-none" href="' . $CFG->wwwroot . '/local/data_analytics/data_ana2">
            <div class="cards">
                <div class="card-top">
                    <div class="card-img-box">
                        <img class="card-img" src="img/class-student.png" alt="Card image">
                    </div>

                </div>
                <div class="">
                    <p class="card-text">Percentage of students who never log in from any class</p>
                </div>
                <!-- <div class="btn-box">
                <button class="link-btn" >Continue</button>
            </div> -->

            </div>
        </a>
        <a class=" text-decoration-none" href="' . $CFG->wwwroot . '/local/data_analytics/data_ana3">
            <div class="cards">
                <div class="card-top">
                    <div class="card-img-box">
                        <img class="card-img" src="img/watch.png" alt="Card image">
                    </div>

                </div>
                <div class="">
                    <p class="card-text">Hours per week online on moodle by each teacher</p>
                </div>
                <!-- <div class="btn-box">
                <button class="link-btn" >Continue</button>
            </div> -->

            </div>
        </a>
        <a class=" text-decoration-none" href="' . $CFG->wwwroot . '/local/data_analytics/data_ana4">
            <div class="cards">
                <div class="card-top">
                    <div class="card-img-box">
                        <img class="card-img" src="img/user-details.png" alt="Card image">
                    </div>

                </div>
                <div class="">
                    <p class="card-text">Percentage of students completing an assessment by the due date</p>
                </div>
                <!-- <div class="btn-box">
                <button class="link-btn" >Continue</button>
            </div> -->

            </div>
        </a>
        <a class=" text-decoration-none" href="' . $CFG->wwwroot . '/local/data_analytics/data_ana5">

            <div class="cards">
                <div class="card-top">
                    <div class="card-img-box">
                        <img class="card-img" src="img/bar-graph.png" alt="Card image">
                    </div>

                </div>
                <div class="">
                    <p class="card-text">Percentage of students receiving less than 50% for an assessment</p>
                </div>
                <!-- <div class="btn-box">
                <button class="link-btn" >Continue</button>
            </div> -->

            </div>
            </a>
            <a class="text-decoration-none" href="' . $CFG->wwwroot . '/local/data_analytics/data_ana6" >
            <div class="cards">
                <div class="card-top">
                    <div class="card-img-box">
                        <img class="card-img" src="img/search.png" alt="Card image">
                    </div>

                </div>
                <div class="">
                    <p class="card-text">How many logins to moodle per week, per teacher</p>
                </div>
                <!-- <div class="btn-box">
                <button class="link-btn" >Continue</button>
            </div> -->

            </div>
            </a>


        </div>

     


       
    </div>
</body>

</html>';

echo $OUTPUT->footer(); // output the page footer
?>
