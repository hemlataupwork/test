<?php
require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php'); // Moodle configuration file
echo '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../css/style.css">';
echo "
<style>
    
</style>
";

require_login(); // make sure the user is logged in
$homeurl = new moodle_url('/my');
if (user_has_role_assignment($USER->id,5)) {
    redirect($homeurl, "This feature is only available for site administrators.", 5);
}
$PAGE->set_context(context_system::instance()); // set the page context
$PAGE->set_url('/local/data_analytics/data_ana1/index.php'); // set the page URL
$PAGE->set_heading('Average enrolled student numbers per moodle class'); // set the page heading
$PAGE->navbar->ignore_active();

$PAGE->set_pagelayout('standard');
$PAGE->navbar->add("Data Analytics", new moodle_url('/local/data_analytics/index.php'));
$PAGE->navbar->add("Average enrolled student numbers per moodle class");

echo $OUTPUT->header(); // output the page header
echo '<h4 style="font-size: 24px;
color: #2e3573;
margin: 0 0 20px;
font-weight: 600;">Select Course  </h4>';
// Display the category tree
function display_category_tree($parent_id = 0, $depth = 0) {
    global $CFG;
    // Fetch categories with the given parent ID
    $categories = $GLOBALS['DB']->get_records_select('course_categories', "parent = ?", array($parent_id));
    if ($categories) {
        echo '<ul class="dropdown dropdown-bg">';
        foreach ($categories as $category) {
            echo '<li data-category-id="'.$category->id.'">';
            echo '<a href="#">'.$category->name.'</a>';
            if ($category->id != $parent_id) { // Check if this is not the root category
                if (!$GLOBALS['DB']->count_records('course_categories', array('parent' => $category->id))) { // Check if this category has any children
                    echo '<script>';
                    echo 'document.querySelector("li[data-category-id=\''.$category->id.'\']").addEventListener("click", function(event) {';
                    echo 'event.preventDefault();';
                    echo 'window.location.href = "' . $CFG->wwwroot . '/local/data_analytics/data_ana1/avg-graph.php?category_id=' . $category->id . '";';
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

echo '<script>
    var categoryList = document.querySelectorAll("li[data-category-id]");
    categoryList.forEach(function(category) {
        category.addEventListener("click", function(event) {
            event.preventDefault();
            var categoryId = category.getAttribute("data-category-id");
            var subcategoryList = category.querySelector("ul");
            if (subcategoryList.innerHTML === "") {
                var url = "' . $CFG->wwwroot . '/local/data_analytics/data_ana1/avg-graph.php";
                var data = new FormData();
                data.append("category_id", categoryId);
                var request = new XMLHttpRequest();
                request.open("POST", url, true);
                request.onload = function() {
                    if (request.status === 200) {
                        subcategoryList.innerHTML = request.responseText;
                    }
                };
                request.send(data);
            }
            subcategoryList.classList.toggle("active");
        });
    });
</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script>
    $("ul li ul").hide();
    $(".dropdown li a").click(function(){
        $(this).next(".dropdown").slideToggle();
    });

    $(".dropdown.dropdown-bg").parents("li").addClass("customdropdown");
    $(".dropdown.dropdown-bg").prevAll("a").addClass("arrow").first();


</script>';

echo $OUTPUT->footer(); // output the page footer

