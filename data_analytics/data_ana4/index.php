<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php'); // Moodle configuration file
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../css/style.css">';
require_login(); // make sure the user is logged in
$homeurl = new moodle_url('/my');
if (user_has_role_assignment($USER->id,5)) {
    redirect($homeurl, "This feature is only available for site administrators.", 5);
}

$PAGE->set_context(context_system::instance()); // set the page context
$PAGE->set_url('/local/data_analytics/data_ana4/index.php'); // set the page URL
$PAGE->set_heading('Percentage of students completing an assessment by the due date'); // set the page heading
$PAGE->set_pagelayout('standard');
$PAGE->navbar->add("Data Analytics", new moodle_url('/local/data_analytics/index.php'));

$PAGE->navbar->add("Percentage of students completing an assessment by the due date");
echo $OUTPUT->header(); // output the page header
echo '<h4 style="font-size: 22px; color: #2e3573; margin: 0px 0px 25px 0px;">Select Course</h4>';

// Display the category tree
function display_category_tree($parent_id = 0, $depth = 0) {
    global $CFG;
    // Fetch categories with the given parent ID
    $categories = $GLOBALS['DB']->get_records_select('course_categories', "parent = ?", array($parent_id));
    if ($categories) {
        echo '<ul class="dropdown dropdown-bg">';
        foreach ($categories as $category) {
            echo '<li data-category-id="'.$category->id.'">';
            echo '<i class="fa fa-arrow-right" aria-hidden="true"></i><a href="#">'.$category->name.'</a>';
            if ($category->id != $parent_id) { // Check if this is not the root category
                if (!$GLOBALS['DB']->count_records('course_categories', array('parent' => $category->id))) { // Check if this category has any children
                    echo '<script>';
                    echo 'document.querySelector("li[data-category-id=\''.$category->id.'\']").addEventListener("click", function(event) {';
                    echo 'event.preventDefault();';
                    echo 'window.location.href = "' . $CFG->wwwroot . '/local/data_analytics/data_ana4/table.php?category_id=' . $category->id . '";';
                    echo '});';
                    echo '</script>';
                } else {
                    display_category_tree($category->id, $depth+1); // Recursively display subcategories
                }
            }
            echo '</li>';
        }
        echo '</ul>';
    }
}

// Load the top-level categories initially
display_category_tree();

// Load the subcategories of a category on click
echo '<script>';
// echo 'document.getElementById('foo').onclick = function(){
//     prompt('Hello world');
// }'
echo 'var categoryList = document.querySelectorAll("li[data-category-id]");';
echo 'categoryList.forEach(function(category) {';
echo 'category.addEventListener("click", function(event) {';
echo 'event.preventDefault();';
echo 'var categoryId = category.getAttribute("data-category-id");';
echo 'var subcategoryList = category.querySelector("ul");';
echo 'if (subcategoryList.innerHTML === "") {';
// echo 'var url = "' . $CFG->wwwroot . '/local/zeroparentscat/get_subcategories.php";';
echo 'var data = new FormData();';
echo 'data.append("category_id", categoryId);';
echo 'var request = new XMLHttpRequest();';
echo 'request.open("POST", url, true);';
echo 'request.onload = function() {';
echo 'if (request.status === 200) {';
echo 'subcategoryList.innerHTML = request.responseText;';
echo '}';
echo '};';
echo 'request.send(data);';
echo '}';
echo 'subcategoryList.classList.toggle("active");';
echo '});';
echo '});';
echo '</script>';


echo'<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>';
// echo'<script>';
// echo'$("ul li ul").hide();';
// echo'$(".dropdown li a").click(function(){';
// echo'$(this).next(".dropdown").slideToggle();';
// echo'})';
// echo'</script>';
echo'
<script>
    $("ul li ul").hide();
    $(".dropdown li a").click(function(){
        $(this).next(".dropdown").slideToggle();
    });

    $(".dropdown.dropdown-bg").parents("li").addClass("customdropdown");
    $(".dropdown.dropdown-bg").prevAll("a").addClass("arrow").first();


</script>
';
echo $OUTPUT->footer(); // output the page footer