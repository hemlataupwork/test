<?php
require_once('../../config.php');
require_login();
$homeurl = new moodle_url('/my');
require_login();
if (!is_siteadmin()) {
  redirect($homeurl, "This feature is only available for site administrators.", 5);
}



global $USER;
global $DB;
global $CFG;
$id = $_GET['id'];

$data = $DB->get_record_sql("SELECT * FROM {user} where id=$id");
$email2_field_id = $DB->get_field("user_info_field", 'id', ['shortname'=>'email2']);
$student_email2 = $DB->get_record_sql("SELECT uiv.* FROM {user_info_data} uiv WHERE uiv.userid =$id AND uiv.fieldid =$email2_field_id");


if ($student_email2) {
    $student_email2_data = $student_email2->data;
}
else
{
    $student_email2_data = '';
}

$PAGE->set_heading('Editing page');


$PAGE->set_pagelayout('standard');
echo $OUTPUT->header();

echo '



<div class="container">
  <header class="heading">
    <div class="green-bar"></div>
    <h1 id="title" class="main-heading">Editing page</h1>
   
    <hr>

  </header>
  
 
    
    <form action=""  method="post" id="survey-form" class="survey-form">
      <div class="margin-bottom" >
        <label  for="moodleid"> <strong>Moodle Id :</strong> </label>
        <input type="text" id="moodleid" class="form-control" name="moodleid" value="' . $data->id . '" readonly>
        <input type="hidden" class="form-control" name="email2id" value="' . $student_email2->id . '" >
      </div>
      
      <div class="margin-bottom">
        <label for="firstname"> <strong>First Name :</strong> </label>
        <input type="text" id="firstname" class="form-control"  name="firstname" value="' . $data->firstname . '" readonly>
      </div>
      
      <div class="margin-bottom">
        <label for="othername"><strong>Other Name :</strong>  </label>
        <input type="text" id="othername" class="form-control"  name="othername" value="' . $data->alternatename . '" readonly>
      </div>
      
      <div class="margin-bottom" >
        <label for="lastname"> <strong> Last Name :</strong> </label>
        <input type="text" id="lastname" class="form-control"  name="lastname" value="' . $data->lastname . '" readonly>
      </div>
      
      <div class="margin-bottom" >
        <label for="emailid"><strong>Email Id :</strong>  </label>
        <input type="email" id="emailid" class="form-control"  name="emailid" value="' . $data->email . '">
      </div>
      
      <div class="margin-bottom" >
        <label for="emailid2"> <strong>Email Id 2 :</strong>  </label>
        <input type="email" id="emailid2" class="form-control"  name="emailid2" value="'.$student_email2_data.'">
      </div>
      
    <div class="btn-area">
        <input type="submit" value="Update" class="update update_btn">
        <button class="update Cancel_btn"><a href=' . $CFG->wwwroot . "/local/student_information/index.php" . ' class="">Cancel</a></button>
      </div>
      </form>
    
 
    

 


    
  
  
  

</div>

<style>

  .update_btn{
    background-color: #268a9a;
    color: white;
  }
  .update_btn:hover{
    box-shadow: 0 0 10px rgb(0, 113, 83);
  }
  .Cancel_btn{
    background-color: #409cd4;
    color: white !important;
  }
  .Cancel_btn:hover{
    box-shadow: 0 0 10px  #409cd4;
  }
  .Cancel_btn a{
    color: white;
    text-decoration: none;
  }
  
  .main-heading {
    margin-bottom: 1rem !important;
}
header.heading {
    /* display: none; */
    padding: 33px;
}
section#region-main {
    background-image: linear-gradient(107deg,rgba(206, 255, 8, 0.048) 0%,rgba(255, 255, 255, 0.49) 35%);
}
  .form-control:disabled, .form-control[readonly] {
    background-color: white;
    opacity: 1;
    margin: 0;
}
  .btn-area {
    color: black;
    display: flex;
    align-items: center;
    justify-content: space-around;
    /* margin: auto; */
    /* margin: 10px auto; */
}
  .update {
    width: 200px;
    border: none;
    font-size: 17px;
    border-radius: 16px;
    padding: 12px;
    transition: 0.3s;
}
  .form-control{
    border: 1px solid #dbdbdb;
  }
    @import url("https://fonts.googleapis.com/css2?family=Lato:wght@100;400;700&display=swap");


    form div label{
      font-size: 18px;
    }
* {
   margin: 0;
   padding: 0;
   box-sizing: border-box;
}



section#region-main {
   font-family: "Lat", sans-serif;
   font-size: 1.6rem;

   color: #222;
   padding: 0 5px;
}

.container {
   min-width: 20rem;
   max-width: 65rem;
   
   margin: 4rem auto;
}
.form-control:disabled, .form-control[readonly] {
    background-color: white;
    opacity: 1;
}
.heading,
.survey-form {
/* From https://css.glass */
background: rgba(255, 255, 255, 0.74);
border-radius: 16px;
box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
backdrop-filter: blur(5.1px);
-webkit-backdrop-filter: blur(5.1px);
border: 1px solid rgba(255, 255, 255, 0.3);
   padding: 3rem;
   border-radius: 1rem;
   display: grid;
    grid-template-columns: 1fr 1fr;
    column-gap: 39px;
   margin-bottom: 2rem;
   box-shadow: 0 0 20px 5px rgba(0, 0, 0, 0.15);
}

.heading {
   position: relative;
}

.survey-form {
   font-size: 1.8rem;
}

.green-bar {
   background-color: #008080;
   height: 1rem;
   width: 100%;
   position: absolute;
   top: 0;
   left: 0;
   border-top-left-radius: 1rem;
   border-top-right-radius: 1rem;
}

.main-heading {

   margin-bottom: 2rem;
}

.main-description {
   margin-bottom: 2rem;
}

.instructions {
   font-size: 1.5rem;
   margin-top: 1rem;
}

.required {
   font-size: 1.6rem;
   color: #d61212;
}
.margin-bottom {
    margin-bottom: 27px;
}
label {
   display: block;
   font-size: 1.8rem;
   margin: 4px 0;
}

input,
select,
textarea {
   display: block;
   width: 100%;
   margin: 2rem 0;
   font-size: 1.6rem;
}

input[type="radio"],
input[type="checkbox"] {
   display: inline-block;
   width: unset;
   margin: unset;
   margin-bottom: 1rem;
   margin-right: .5rem;
}

.name,
.email,
.age{
   min-height: 2rem;
   padding: 1rem 0;
   border: none;
   border-bottom: 1px solid #bcb9b9;
}

.dropdown {
   min-height: 2rem;
   padding: 1rem 0;
   background-color: transparent;
   border: 1px solid #bcb9b9;
   color: #767676;
}

option {
   font-size: 1.6rem;
   color: #222;
}

.radio-btn-description,
.checkbox-description {
   margin: 2rem 0;
}

.radio-btn-label,
.checkbox-label {
   margin: unset;
}

textarea {
   font-size: 1.8rem;
   font-family: "Lato", sans-serif;
   border: 1px solid #bcb9b9;
}

.submit {
   font-size: 1.7rem;
   font-weight: 600;
   text-transform: uppercase;
   letter-spacing: 1px;
   color: #f4f4f4;
   background-color: #008080;
   border: 3px solid #008080;
   border-radius: 1rem;
   width: 15rem;
   padding: 1rem 2rem;
   margin: 4rem auto 2rem auto;
   cursor: pointer;
   transition: all .3s;
}

.submit:hover {
   background-color: transparent;
   color: #222;
}

footer {
   text-align: center;
   margin-top: 4rem;
}

/* a:link,
a:visited {
   color: #008080;
} */
  .custom-moodle label {
  width: 130px;
}
.gap-1{
  gap: 10px;
}
</style>
';
if ($_POST['emailid']) {
  $email = $_POST['emailid'];
  $email2_data = $_POST['emailid2'];
  $email2data_id = $_POST['email2id'];

  $record_exist = $DB->get_record_sql("SELECT * from {user} where email='$email' And id != $id");
  if ($record_exist) {
    redirect("$CFG->wwwroot/local/student_information/edit_form.php?id=$id", "This email id already exist");
  }



  $update = $DB->execute("UPDATE {user} SET email='$email' where id=$id");
  if ($email2data_id) {
    $DB->execute("UPDATE {user_info_data} SET data='$email2_data' where id=$email2data_id");
  }
  else {
    $email2_field_id = $DB->get_field("user_info_field", 'id', ['shortname'=>'email2']);
    $user_info_data = new stdClass();
    $user_info_data->userid =$id; 
    $user_info_data->fieldid =$email2_field_id; 
    $user_info_data->data =$email2_data; 
    $DB->insert_record("user_info_data", $user_info_data, $returnid=true, $bulk=false);
  }

  redirect("$CFG->wwwroot/local/student_information/index.php", "Saved Successfully");
}




echo $OUTPUT->footer();
